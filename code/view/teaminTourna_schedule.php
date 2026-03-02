<?php /* $matches */ 
$teamName = ''; $tournaName = '';
if (!empty($matches)) {
  $teamName   = (int)$_GET['team_id'] == (int)$matches[0]['home_team_id']
                  ? $matches[0]['home_name'] : $matches[0]['away_name'];
  $tournaName = $matches[0]['tournaName'] ?? '';
}

/* Base path để link đúng file (dashboard/index) */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // /Kltn
$DASH     = $APP_BASE . '/dashboard.php';
$PUBLIC   = $APP_BASE . '/index.php';
?>

<style>
  .sched-wrap{max-width:1100px;margin:24px auto;padding:0 16px;color:#111827}
  .crumb{font-size:13px;color:#6b7280;margin-bottom:8px}
  .crumb a{color:#374151;text-decoration:none}
  .crumb a:hover{text-decoration:underline}
  .title-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
  .title{font-size:20px;font-weight:700;margin:0}
  .filter .btn{font-size:12px;padding:6px 10px;border:1px solid #d1d5db;border-radius:10px;text-decoration:none;color:#111827;margin-left:6px}
  .filter .btn:hover{background:#f3f4f6}

  .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 2px 8px rgba(0,0,0,.04)}
  .tbl{width:100%;border-collapse:separate;border-spacing:0;border-radius:14px;overflow:hidden}
  .tbl thead th{background:#f8fafc;font-weight:600;font-size:13px;color:#374151;padding:12px;border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:1}
  .tbl tbody td{padding:12px;border-bottom:1px solid #f1f5f9;font-size:14px}
  .tbl tbody tr:hover{background:#f9fafb}
  .t-center{text-align:center}
  .score-pill{display:inline-block;min-width:64px;padding:4px 10px;border:1px solid #e5e7eb;border-radius:999px;font-weight:600}
  .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600}
  .badge.soon{background:#eef2ff;color:#3730a3}
  .badge.done{background:#ecfdf5;color:#065f46}
  .badge.cancel{background:#fef2f2;color:#b91c1c}
  .muted{color:#6b7280}
  .link{color:#1d4ed8;text-decoration:none}
  .link:hover{text-decoration:underline}
</style>

<section class="sched-wrap">
  <div class="crumb">
    <a href="<?=$DASH?>?page=team.my_tournaments">Dashboard đội</a> / Lịch
  </div>

  <div class="title-row">
    <h2 class="title">
      Lịch của <?=htmlspecialchars($teamName)?>
      <?php if(!empty($_GET['tourna_id'])): ?>
        <span class="muted">trong giải</span> <?=htmlspecialchars($tournaName)?>
      <?php endif; ?>
    </h2>
    <div class="filter">
      <?php
        $base = $DASH.'?page=team.schedule&team_id='.(int)$_GET['team_id'];
        if(isset($_GET['tourna_id'])) $base .= '&tourna_id='.(int)$_GET['tourna_id'];
      ?>
      <a class="btn" href="<?=$base?>">Tất cả</a>
      <a class="btn" href="<?=$base.'&status=upcoming'?>">Sắp đá</a>
      <a class="btn" href="<?=$base.'&status=played'?>">Đã đá</a>
    </div>
  </div>

  <?php if(empty($matches)): ?>
    <div class="card" style="padding:14px">Chưa có trận nào.</div>
  <?php else: ?>
    <div class="card">
      <div class="table-responsive">
        <table class="tbl">
          <thead>
            <tr>
              <th style="width:110px">Ngày</th>
              <th style="width:90px">Giờ</th>
              <th>Giải</th>
              <th>Chủ nhà</th>
              <th class="t-center" style="width:90px">Tỷ số</th>
              <th>Khách</th>
              <th style="width:140px">Sân</th>
              <th style="width:110px">Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($matches as $m): 
              $date = $m['kickoff_date'] ? date('d/m/Y', strtotime($m['kickoff_date'])) : '-';
              $time = $m['kickoff_time'] ? substr($m['kickoff_time'],0,5) : '-';
              $score = ($m['status']==='played') ? ((int)$m['home_score'].' - '.(int)$m['away_score']) : 'vs';
              $badge = ($m['status']==='scheduled') ? 'soon' : (($m['status']==='played') ? 'done' : 'cancel');
              $badgeText = ($m['status']==='scheduled') ? 'Sắp đá' : (($m['status']==='played') ? 'Đã đá' : 'Hủy');
            ?>
              <tr>
                <td><?=$date?></td>
                <td><?=$time?></td>
                <td>
                  <a class="link" href="<?=$PUBLIC?>?page=tourna_detail&id=<?=$m['id_tourna']?>">
                    <?=htmlspecialchars($m['tournaName'])?>
                  </a>
                </td>
                <td><?=htmlspecialchars($m['home_name'])?></td>
                <td class="t-center"><span class="score-pill"><?=$score?></span></td>
                <td><?=htmlspecialchars($m['away_name'])?></td>
                <td><?=htmlspecialchars($m['pitch_label'] ?: ($m['venue'] ?: '-'))?></td>
                <td><span class="badge <?=$badge?>"><?=$badgeText?></span></td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</section>
