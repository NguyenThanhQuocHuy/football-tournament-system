<?php
// scripts/send_match_reminders.php
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/../api/config.local.php';
require_once __DIR__ . '/../src/mail.php';
require_once __DIR__ . '/../model/modelconnect.php';

// Kết nối DB theo model hiện tại của bạn
$p = new mConnect();
$con = $p->moKetNoi();
if (!$con) { die("DB connect error"); }

// Cửa sổ chạy cron mỗi 5 phút -> gửi những trận có KO sau ~30 phút
$now    = new DateTime('now');
$from   = (clone $now)->modify('+30 minutes'); // bắt đầu cửa sổ
$to     = (clone $now)->modify('+35 minutes'); // kết thúc cửa sổ

$koFrom = $from->format('Y-m-d H:i:s');
$koTo   = $to->format('Y-m-d H:i:s');

/*
  Lưu ý schema:
  - match có kickoff_date + kickoff_time (tách), status='scheduled'
  - follow_tournament (id_user, idtourna, is_active=1)
  -> Lấy người theo dõi giải để gửi nhắc. (Bạn có thể bổ sung theo dõi đội sau)
*/
$sql = "
SELECT DISTINCT
  u.id_user, u.email, u.FullName,
  m.id_match,
  t.tournaName,
  th.teamName AS home_name,
  ta.teamName AS away_name,
  TIMESTAMP(m.kickoff_date, m.kickoff_time) AS ko_dt
FROM `match` m
JOIN tournament t ON t.idtourna = m.id_tourna
LEFT JOIN team th ON th.id_team = m.home_team_id
LEFT JOIN team ta ON ta.id_team = m.away_team_id
JOIN follow_tournament ft ON ft.idtourna = m.id_tourna AND ft.is_active = 1
JOIN users u ON u.id_user = ft.id_user
WHERE m.status = 'scheduled'
  AND TIMESTAMP(m.kickoff_date, m.kickoff_time) >= ?
  AND TIMESTAMP(m.kickoff_date, m.kickoff_time) < ?
  AND NOT EXISTS (
    SELECT 1 FROM email_notification_log l
    WHERE l.user_id = u.id_user AND l.match_id = m.id_match AND l.type='MATCH_REMINDER'
  )
";
$stmt = $con->prepare($sql);
$stmt->bind_param('ss', $koFrom, $koTo);
$stmt->execute();
$res = $stmt->get_result();

$sent = 0;
while ($row = $res->fetch_assoc()) {
  $minsLeft = max(1, (int) round((strtotime($row['ko_dt']) - time()) / 60));
  $subject  = "[{$row['tournaName']}] Nhắc lịch: {$row['home_name']} vs {$row['away_name']} (còn ~{$minsLeft} phút)";
  $url      = BASE_URL . "/tourna_detail.php?id=" . (int)$row['idtourna'];

  $body = "
    <p>Xin chào {$row['FullName']},</p>
    <p>Trận <b>{$row['home_name']} vs {$row['away_name']}</b> sẽ bắt đầu khoảng <b>{$minsLeft} phút</b>.</p>
    <p>Thời gian: <b>".date('H:i d/m/Y', strtotime($row['ko_dt']))."</b></p>
    <p>Xem chi tiết: <a href='{$url}'>Tại đây</a></p>
  ";

  if ($row['email'] && mail_send_html($row['email'], $subject, $body)) {
    $ins = $con->prepare("INSERT INTO email_notification_log(user_id, match_id, type, sent_at)
                          VALUES (?, ?, 'MATCH_REMINDER', NOW())");
    $ins->bind_param('ii', $row['id_user'], $row['id_match']);
    $ins->execute();
    $sent++;
  }
}
echo "Sent: {$sent}\n";
