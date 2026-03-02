<?php
include_once(__DIR__ . "/../control/controltourna.php");
include_once(__DIR__ . "/../model/modelschedule.php");

$id = $_GET['id'] ?? $_GET['id_tourna'] ?? null;
if ($id <= 0) { echo "<p>Thiếu tham số id</p>"; exit; }

$c   = new cTourna();
$tbl = $c->getTournamentFullDetails($id);
if (!($tbl instanceof mysqli_result) || $tbl->num_rows === 0) {
  echo "<p>Không tìm thấy giải đấu</p>"; exit;
}
$row = $tbl->fetch_assoc();
$ruleType = strtolower($row['ruletype'] ?? $row['rule_type'] ?? '');

/* ---------- Base path & helpers ---------- */
$BASE = rtrim(dirname($_SERVER['PHP_SELF']), '/');
$ROOT = rtrim(dirname($BASE), '/');                  // /KltN
function resolvePath(string $raw = null, string $fallback = '') {
  $raw = trim((string)$raw);
  if ($raw === '') return $fallback;
  if (preg_match('~^(https?://|/|\.{1,2}/)~i', $raw)) return $raw;
  global $BASE; return $BASE . '/' . ltrim($raw, '/');
}

/* ---------- ĐIỀU LỆ & LỆ PHÍ ---------- */
$feeType   = $row['fee_type'] ?? 'FREE';
$feeAmount = $row['fee_amount'] ?? null;
$regOpen  = $row['regis_open_at']  ?? $row['reg_open']  ?? null;
$regClose = $row['regis_close_at'] ?? $row['reg_close'] ?? null;
$summary   = $row['regulation_summary'] ?? null;
// Chuẩn bị hạn chót đăng ký
$regCloseTs = $regClose ? strtotime($regClose) : null;
$deadlineText = $regCloseTs ? date('d-m-Y H:i', $regCloseTs) : '';

/* ---------- Slot & điều kiện đăng ký ---------- */
$teamCnt  = $row['team_count'] ?? ($row['teamCount'] ?? '');
$approved = $c->countApprovedTeams($id);
$slotLeft = is_numeric($teamCnt) ? max(0, (int)$teamCnt - (int)$approved) : null;

$now = date('Y-m-d H:i:s');
$withinWindow =
  (empty($regOpen)  || $now >= $regOpen) &&
  (empty($regClose) || $now <= $regClose);
$canRegister = ($slotLeft === null || $slotLeft > 0) && $withinWindow;

/* ---------- User/role & link nút đăng ký ---------- */
if (session_status() === PHP_SESSION_NONE) session_start();

// Chuẩn hoá key về chữ thường để tránh lệch hoa/thường (ID_role → id_role)
$S = array_change_key_case($_SESSION, CASE_LOWER);

// Lấy userId
$userId = (int)($S['id_user'] ?? $S['iduser'] ?? $S['user_id'] ?? 0);

// Lấy role (3 = Quản lý đội)
$role   = (int)($S['id_role'] ?? $S['idrole'] ?? $S['role'] ?? 0);

// (tùy chọn) debug
if (isset($_GET['debug'])) {
    echo "<pre>SESSION="; print_r($_SESSION); echo "</pre>";
    echo "<pre>userId={$userId}, role={$role}</pre>";
}

$regLink    = "javascript:void(0)";
$regTitle   = $canRegister ? "Đăng ký tham gia giải" : ( !$withinWindow ? "Ngoài thời gian đăng ký" : "Hết slot" );
$btnEnabled = $canRegister;

if ($canRegister) {
  if ($userId <= 0) {
    $regLink = $ROOT . "/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']);
    $regTitle   = "Đăng nhập tài khoản Quản lý đội để đăng ký";
    $btnEnabled = true; // luôn cho bấm
  } elseif ($role !== 3) {
    // ⭐ Thay vì khóa nút, cho click để hiện cảnh báo
    $regLink    = "javascript:alert('Chỉ tài khoản Quản lý đội mới được đăng ký. Vui lòng đăng xuất và đăng nhập lại tài khoản Quản lý đội.');";
    $regTitle   = "Bạn không có quyền đăng ký";
    $btnEnabled = true; // <— cho bấm để hiện alert
  } else {
    $regLink = $ROOT . "/index.php?page=register_tourna&id=" . (int)$id;
    $regTitle   = "Đăng ký tham gia giải";
    $btnEnabled = true;
  }
}


/* ---------- Thông tin hiển thị chung ---------- */
$title    = $row['tournaName'] ?? ($row['name'] ?? 'Giải đấu');
$start    = !empty($row['startdate']) ? date('d-m-Y', strtotime($row['startdate'])) : '';
$end      = !empty($row['enddate'])   ? date('d-m-Y', strtotime($row['enddate']))   : '';
$status   = $row['status']   ?? '';
$location = $row['location'] ;
$address  = $row['address'] ;
$desc     = $row['description'] ?? ($row['note'] ?? '');

$rawBanner = trim($row['banner'] ?? '');
$rawLogo   = trim($row['logo']   ?? '');
$bannerSrc = resolvePath($rawBanner, $BASE . '/../img/giaidau/banner_macdinh.jpg');
$logoSrc   = resolvePath($rawLogo,   $BASE . '/../img/giaidau/logo_macdinh.png');
$dateText  = $start ? ('Từ ' . $start . ($end ? ' đến ' . $end : '')) : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root { --radius: 20px; }
    *{ box-sizing:border-box }
    body{ margin:0; font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial; color:#222; background:#f5f7fb }
    .hero-header{
      position:relative; min-height:320px; display:flex; align-items:flex-end; padding:24px;
      background:#111 url('<?= htmlspecialchars($bannerSrc) ?>') center/cover no-repeat;
      border-bottom-left-radius: var(--radius); border-bottom-right-radius: var(--radius);
      overflow:hidden;
    }
    .hero-header::after{ content:""; position:absolute; inset:0; background:linear-gradient(180deg,rgba(0,0,0,.25),rgba(0,0,0,.65)); }
    .hero-inner{ position:relative; z-index:2; display:flex; gap:16px; align-items:center; width:100%; }
    .logo img{ width:90px; height:90px; object-fit:cover; border-radius:50%; border:3px solid rgba(255,255,255,.8); background:#fff }
    .title-wrap{ color:#fff; flex:1 }
    .title-wrap h1{ margin:0 0 6px; font-size:28px; letter-spacing:.2px }
    .title-wrap .meta{ opacity:.95; font-size:14px; display:flex; gap:12px; flex-wrap:wrap }
    .badge{ display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.12); color:#fff; padding:6px 10px; border-radius:999px; border:1px solid rgba(255,255,255,.25); }
    .page-actions{ display:flex; gap:10px }
    .btn{ display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:none; cursor:pointer; background:#ffd400; color:#222; font-weight:600; text-decoration:none; }
    .btn.secondary{ background:#ffffffd9 }
    .container{ max-width:1100px; margin:28px auto; padding:0 16px }

    /* ====== Phẳng hoá giao diện các section ====== */
    .card{ background:transparent; border:none; border-radius:0; box-shadow:none; padding:0; margin:18px 0; }
    .section-title{ margin:0 0 10px; font-size:20px; padding-bottom:6px; border-bottom:1px solid #e5e7eb }
    .info-list{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:6px 16px }
    .info-item{ display:flex; gap:10px; align-items:flex-start; padding:8px 0; border-bottom:1px dashed #e9edf3; border-radius:0; background:transparent }
    .info-item i{ opacity:.75; margin-top:2px }
    .desc{ white-space:pre-line; line-height:1.6; color:#444 }
    .kv{margin:0; padding:0; list-style:none; line-height:1.75}
    .kv li{padding:6px 0; border-bottom:1px dashed #edf0f5}
    .kv li:last-child{border-bottom:none}
    .kv b{display:inline-block; min-width:150px}
    .muted{opacity:.8; margin-left:10px; background:rgba(255,255,255,.28); padding:4px 8px; border-radius:8px}

    /* Bracket tối giản */
    .bracket { display:grid; grid-auto-flow:column; gap:16px; overflow:auto; }
    .br-col { display:flex; flex-direction:column; gap:10px; min-width:240px; }
    .br-match{ border:1px solid #e5e7eb; padding:10px 12px; background:#fff }
    .br-title{ font-weight:600; margin-bottom:4px; opacity:.85 }
    .br-kick{ font-size:12px; color:#6b7280; margin-bottom:4px }
    .br-team { display:flex; justify-content:space-between; }
    @media (max-width: 768px){ .info-list{ grid-template-columns:1fr } .hero-header{ min-height:260px } .title-wrap h1{ font-size:22px } }
  </style>
</head>
<body>

<header class="hero-header">
  <div class="hero-inner">
    <div class="logo">
      <img src="<?= htmlspecialchars($logoSrc) ?>" alt="logo"
           onerror="this.onerror=null;this.src='<?= $BASE ?>/img/giaidau/logo_macdinh.png';">
    </div>
    <div class="title-wrap">
      <h1><?= htmlspecialchars(mb_strtoupper($title)) ?></h1>
      <div class="meta">
        <?php if ($dateText): ?><span class="badge"><i class="fa fa-calendar"></i> <?= htmlspecialchars($dateText) ?></span><?php endif; ?>
        <?php if ($status):   ?><span class="badge"><i class="fa fa-circle-dot"></i> <?= htmlspecialchars($status) ?></span><?php endif; ?>
        <?php if ($teamCnt!==''): ?><span class="badge"><i class="fa fa-users"></i> <?= (int)$teamCnt ?> đội</span><?php endif; ?>
        <?php if ($location): ?><span class="badge"><i class="fa fa-location-dot"></i> <?= htmlspecialchars($location) ?></span><?php endif; ?>
      </div>
    </div>
    <?php if ($regCloseTs && time() <= $regCloseTs): ?>
      <span class="muted">Còn lại: <span id="reg-countdown">đang tính…</span></span>
    <?php endif; ?>
    <div class="page-actions">
      <a class="btn<?= $btnEnabled ? '' : ' disabled' ?>"
         href="<?= htmlspecialchars($regLink) ?>"
         title="<?= htmlspecialchars($regTitle) ?>"
         <?= $btnEnabled ? '' : 'style="pointer-events:none;opacity:.6;"' ?>>
         <i class="fa fa-plus"></i> ĐĂNG KÝ ĐỘI
      </a>
      <a class="btn secondary"
         href="<?= $ROOT ?>/index.php?page=tourna"
         onclick="if (document.referrer) { history.back(); return false; }">
        <i class="fa fa-arrow-left"></i> QUAY LẠI
      </a>
    </div>
  </div>
</header>

<main class="container">

<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Điều lệ / Giới thiệu giải</h2>
  <div class="desc">
    <?php if ($summary): ?>
      <?= nl2br(htmlspecialchars($summary)) ?>
    <?php elseif (!empty($row['description']) || !empty($row['note'])): ?>
      <?= nl2br(htmlspecialchars($row['description'] ?? $row['note'])) ?>
    <?php else: ?>
      Chưa có điều lệ/mô tả cho giải đấu này.
    <?php endif; ?>
  </div>
</section>

<?php $regFiles = $c->getRegulationFiles($id); if (!empty($regFiles)): ?>
<section class="card" style="grid-column: 1 / -1; margin-top:12px;">
  <h2 class="section-title">Tệp điều lệ (PDF/Word)</h2>
  <ul style="margin:0; padding-left:18px;">
    <?php foreach ($regFiles as $f): ?>
      <li>
        <a href="<?= htmlspecialchars($f['file_path']) ?>" target="_blank" rel="noopener">
          <?= htmlspecialchars($f['file_name']) ?>
        </a>
        <span class="muted">
          (v<?= (int)$f['version_no'] ?> • <?= date('d-m-Y H:i', strtotime($f['uploaded_at'])) ?>)
        </span>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>
<?php
  $rrRounds = $row['rr_rounds'] ?? $row['rrRounds'] ?? null;
  $ptWin    = $row['pointwin']  ?? $row['point_win'] ?? null;
  $ptDraw   = $row['pointdraw'] ?? $row['point_draw'] ?? null;
  $ptLoss   = $row['pointloss'] ?? $row['point_loss'] ?? null;
  $tieRule  = $row['tiebreak_rule'] ?? null;
  $totalMatch = $row['total_matches'] ?? $row['match_count'] ?? null;

  function info_item($icon, $label, $value) {
    if ($value === null || $value === '' ) return '';
    return '<div class="info-item"><i class="fa '.$icon.'"></i><div><strong>'.$label.
           '</strong><br>'.$value.'</div></div>';
  }
?>
<section class="card" style="grid-column: 1 / -1;">
  <h2 class="section-title">Thông tin & luật thi đấu</h2>
  <div class="info-list">
    <?= info_item('fa-calendar-days', 'Thời gian', $dateText ?: null) ?>
    <?= info_item('fa-calendar-check', 'Hạn chót đăng ký',$deadlineText ?: null); ?>
    <?= info_item('fa-location-dot', 'Địa điểm', htmlspecialchars($location ?: '')) ?>
    <?= info_item('fa-location-dot', 'Địa chỉ', htmlspecialchars($address ?: '')) ?>
    <?= info_item('fa-flag-checkered', 'Trạng thái', htmlspecialchars($status ?: '')) ?>

    <?= info_item('fa-people-group', 'Số đội dự kiến',
        ($teamCnt!=='' && $teamCnt!==null) ? ((int)$teamCnt.' đội') : '') ?>

    <?= info_item('fa-user-check', 'Số đội đã tham gia',
        ($teamCnt!=='' && $teamCnt!==null) ? ($approved.' / '.(int)$teamCnt.' đội') : ($approved.' đội')) ?>

    <?= info_item('fa-money-bill', 'Lệ phí',
        ($feeType==='PAID') ? (number_format((int)$feeAmount, 0, ',', '.').' đ') : 'Miễn phí') ?>

    <?php
      $formatMap = ['roundrobin' => 'Vòng tròn', 'knockout' => 'Loại trực tiếp' ,'hybrid' => 'Hỗn hợp (vòng bảng + loại trực tiếp)'];
      $formatText = $ruleType ? ($formatMap[$ruleType] ?? $ruleType) : null;
      if ($formatText && $rrRounds) $formatText .= ' ('.$rrRounds.' lượt)';
      echo info_item('fa-chess', 'Thể thức', $formatText);
      echo info_item('fa-trophy', 'Điểm thắng', ($ptWin!==null) ? ((int)$ptWin.' điểm') : null);
      echo info_item('fa-scale-balanced', 'Điểm hoà', ($ptDraw!==null) ? ((int)$ptDraw.' điểm') : null);
      echo info_item('fa-circle-xmark', 'Điểm thua', ($ptLoss!==null) ? ((int)$ptLoss.' điểm') : null);
      echo info_item('fa-list-ol', 'Tiêu chí xếp hạng', $tieRule ? htmlspecialchars($tieRule) : null);
      echo info_item('fa-list-check', 'Tổng số trận', ($totalMatch ? (int)$totalMatch : null));
    ?>
  </div>
</section>
<?php
// Bản đồ địa điểm thi đấu
$lat  = isset($row['lat']) ? (float)$row['lat'] : null;
$lng  = isset($row['lng']) ? (float)$row['lng'] : null;
$addr = $row['formatted_address'] ?? ($row['Address'] ?? null);
$name = $row['display_name']     ?? ($row['LocalName'] ?? 'Địa điểm thi đấu');
?>
<div class="card" style="overflow:hidden;">
  <div class="section-title">Địa điểm thi đấu</div>
  <div>
    <div><i class="fa fa-location-dot"></i> <?= htmlspecialchars($addr ?: 'Chưa cập nhật') ?></div>
    <div id="tournaMap" style="height:320px;border:1px solid #e5e7eb;border-radius:8px;margin-top:8px;"></div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  (function(){
    const map = L.map('tournaMap').setView([<?= $lat ? (float)$lat : 10.8231 ?>, <?= $lng ? (float)$lng : 106.6297 ?>], <?= ($lat && $lng) ? 16 : 12 ?>);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19}).addTo(map);
    <?php if ($lat && $lng): ?>
      L.marker([<?= (float)$lat ?>, <?= (float)$lng ?>]).addTo(map)
        .bindPopup(`<b><?= htmlspecialchars($name) ?></b><br><?= htmlspecialchars($addr) ?>`).openPopup();
    <?php endif; ?>
  })();
</script>


<?php
  $quick    = $c->getQuickStats((int)$id);
  $upcoming = $c->getMatches((int)$id, 'upcoming', 6);
  $played   = $c->getMatches((int)$id, 'played',   6);

  // ===== Lấy BRACKET gốc =====
  $bracket = [];
  if ($ruleType === 'knockout') {
      $bracket = $c->getBracketKOOnly((int)$id);
  } elseif ($ruleType === 'hybrid') {
      $bracket = $c->getBracketKOFromHybrid((int)$id);
  }

  // ===== Chuẩn hoá ROUND + tạo MÃ TRẬN tuần tự + map id_match -> mã_trận =====
  $idToCode = [];          // [id_match|match_id] => mã trận
  $prevRoundMap = [];      // [$roundIdx][$n] => mã trận ở vòng trước vị trí n
  if (!empty($bracket)) {
    // Normalize round index 1..K (KO của hybrid có thể bắt đầu 4/5/6)
    $rks = array_keys($bracket); sort($rks);
    $first = $rks[0]; $norm = [];
    foreach ($rks as $rk) $norm[$rk - $first + 1] = $bracket[$rk];
    $bracket = $norm;

    // Gán mã trận global + map id_match -> code
    $global = 0;
    foreach ($bracket as &$nodes) {
      foreach ($nodes as &$m) {
        $m['_code'] = ++$global;
        if (!empty($m['id_match']))  $idToCode[(int)$m['id_match']]  = (int)$m['_code'];
        if (!empty($m['match_id']))  $idToCode[(int)$m['match_id']]  = (int)$m['_code'];
      }
      unset($m);
    }
    unset($nodes);

    // Lập map vị trí ở vòng trước -> mã trận tương ứng
    $prev = null;
    foreach ($bracket as $rNo => $nodes) {
      if ($prev !== null) {
        $arr = []; $i = 0;
        foreach ($prev as $pm) $arr[++$i] = (int)($pm['_code'] ?? $i);
        $prevRoundMap[$rNo] = $arr;
      }
      $prev = $nodes;
    }
  }

  // ===== Hàm hiển thị tên đội với placeholder "Thắng trận <mã>" đúng MÃ TRẬN =====
  function prettyKoLabel(array $m, string $side, int $roundNo, array $idToCode, array $prevRoundMap): string {
    $name = trim($m[$side . '_name'] ?? '');
    if ($name !== '') return $name;

    $ph = trim($m[$side . '_placeholder'] ?? $m[$side . '_label'] ?? '');
    if ($ph === '') return '—';

    // Đổi "Thắng trận <số>" theo id_match -> mã trận, hoặc theo STT vòng trước
    return preg_replace_callback('/Thắng trận\s+(\d+)/u', function($mm) use ($idToCode,$prevRoundMap,$roundNo){
      $x = (int)$mm[1];
      if (isset($idToCode[$x]))                 return 'Thắng trận ' . $idToCode[$x];
      if (isset($prevRoundMap[$roundNo][$x]))   return 'Thắng trận ' . $prevRoundMap[$roundNo][$x];
      return 'Thắng trận ' . $x;
    }, $ph);
  }

  function fmt_dt($s) { return $s ? date('d-m-Y H:i', strtotime($s)) : ''; }
?>

<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Thống kê nhanh</h2>
  <div class="info-list" style="grid-template-columns: repeat(4, minmax(0,1fr));">
    <?= info_item('fa-hashtag','Tổng trận',        (int)($quick['matches_total']   ?? 0)) ?>
    <?= info_item('fa-check','Đã diễn ra',         (int)($quick['matches_played']  ?? 0)) ?>
    <?= info_item('fa-forward','Sắp diễn ra',      (int)($quick['matches_upcoming']?? 0)) ?>
    <?= info_item('fa-futbol','Tổng số bàn',       (int)($quick['goals_total']     ?? 0)) ?>
    <?= !empty($quick['champion'])  ? info_item('fa-trophy','Vô địch', htmlspecialchars($quick['champion'])) : '' ?>
    <?= !empty($quick['runner_up']) ? info_item('fa-medal','Á quân',  htmlspecialchars($quick['runner_up'])): '' ?>
  </div>
</section>

<?php if (!empty($upcoming)): ?>
<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Lịch sắp diễn ra (<?= count($upcoming) ?>)</h2>
  <div class="info-list" style="grid-template-columns: repeat(2, minmax(0,1fr));">
    <?php foreach ($upcoming as $m): ?>
      <div class="info-item">
        <i class="fa fa-calendar-check"></i>
        <div>
          <strong><?= htmlspecialchars($m['home_name'] ?? 'Đội A') ?> vs <?= htmlspecialchars($m['away_name'] ?? 'Đội B') ?></strong><br>
          <?= fmt_dt($m['kickoff_date']) ?><?= $m['local_name'] ? ' • '.htmlspecialchars($m['local_name']) : '' ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

    <!-- // Hiển thị lịch RR  -->
<?php
// ===== RR THUẦN: chỉ dùng khi ruletype === 'roundrobin' =====
if ($ruleType === 'roundrobin') {
  $ms = new mSchedule();
  $rrRounds = $ms->getRrRoundsPlain((int)$id);
  $rrData   = [];
  foreach ($rrRounds as $r) $rrData[$r] = $ms->getRoundMatchesRRPlain((int)$id, $r);
}
?>
<?php
// ===== HYBRID: LỊCH VÒNG BẢNG (RR THEO BẢNG) =====
if ($ruleType === 'hybrid') {
  $ms = new mSchedule();
  $rrRounds = $ms->getRrRoundsStrict((int)$id); // chỉ lấy các round có id_group
  $rrData   = [];
  foreach ($rrRounds as $r) $rrData[$r] = $ms->getRoundMatchesRRStrict((int)$id, $r);
}
?>

<?php if ($ruleType === 'hybrid' && !empty($rrRounds)): ?>
<section id="rr-group-schedule" class="card" style="grid-column:1 / -1; margin:24px 0">
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <h3 style="margin:0">Lịch thi đấu (Vòng bảng)</h3>
    <label>Vòng:</label>
    <select id="rrg-round-select" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px">
      <?php foreach($rrRounds as $r): ?>
        <option value="gr<?= (int)$r ?>">Vòng <?= (int)$r ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php foreach($rrData as $rNo => $rows): ?>
    <div class="rr-round" id="gr<?= (int)$rNo ?>" style="<?= ($rNo==reset($rrRounds)?'':'display:none') ?>;margin-top:14px">
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#111827;color:#fff">
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:110px">Ngày</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:80px">Giờ</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb">Trận đấu</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:90px">Bảng</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:160px">Sân</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="5" style="padding:10px;border:1px solid #e5e7eb">Chưa có lịch.</td></tr>
          <?php else: foreach($rows as $m): 
            $day   = $m['kickoff_date'] ? date('d-m-Y', strtotime($m['kickoff_date'])) : '—';
            $time  = $m['kickoff_time'] ?: '—';
            $home  = $m['home_name'] ?: '—';
            $away  = $m['away_name'] ?: '—';
            $hs    = is_numeric($m['home_score']) ? (int)$m['home_score'] : null;
            $as    = is_numeric($m['away_score']) ? (int)$m['away_score'] : null;
            $vsTxt = ($hs !== null || $as !== null) ? ($hs.' - '.$as) : 'vs';
            $venue = $m['pitch_label'] ?: ($m['local_name'] ?: '—');
            $grp   = $m['group_label'] ?: '—';
          ?>
            <tr>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($day) ?></td>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($time) ?></td>
              <td style="padding:10px;border:1px solid #e5e7eb">
                <strong><?= htmlspecialchars($home) ?></strong>
                <span style="color:#6b7280"><?= $vsTxt ?></span>
                <strong><?= htmlspecialchars($away) ?></strong>
              </td>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($grp) ?></td>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($venue) ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</section>

<script>
  (function(){
    var sel = document.getElementById('rrg-round-select');
    if(!sel) return;
    sel.addEventListener('change', function(){
      document.querySelectorAll('#rr-group-schedule .rr-round').forEach(function(el){ el.style.display='none'; });
      var blk = document.getElementById(this.value);
      if(blk) blk.style.display = '';
    });
  })();
</script>
<?php endif; ?>

<?php if ($ruleType === 'roundrobin' && !empty($rrRounds)): ?>
<section id="rr-schedule" class="card" style="grid-column: 1 / -1; margin:24px 0">
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <h3 style="margin:0">Lịch thi đấu</h3>
    <label>Vòng:</label>
    <select id="rr-round-select" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px">
      <?php foreach($rrRounds as $r): ?>
        <option value="r<?= (int)$r ?>">Vòng <?= (int)$r ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php foreach($rrData as $rNo => $rows): ?>
    <div class="rr-round" id="r<?= (int)$rNo ?>" style="<?= ($rNo==reset($rrRounds)?'':'display:none') ?>;margin-top:14px">
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#111827;color:#fff">
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:110px">Ngày</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:80px">Giờ</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb">Trận đấu</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:90px">Bảng</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;width:160px">Sân</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="5" style="padding:10px;border:1px solid #e5e7eb">Chưa có lịch.</td></tr>
          <?php else: foreach($rows as $m): 
            $day   = $m['kickoff_date'] ? date('d-m-Y', strtotime($m['kickoff_date'])) : '—';
            $time  = $m['kickoff_time'] ?: '—';
            $home  = $m['home_name'] ?: '—';
            $away  = $m['away_name'] ?: '—';
            $hs    = is_numeric($m['home_score']) ? (int)$m['home_score'] : null;
            $as    = is_numeric($m['away_score']) ? (int)$m['away_score'] : null;
            $vsTxt = ($hs !== null || $as !== null) ? ($hs.' - '.$as) : 'vs';
            $venue = $m['pitch_label'] ?: ($m['local_name'] ?: '—');
          ?>
            <tr>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($day) ?></td>
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($time) ?></td>
              <td style="padding:10px;border:1px solid #e5e7eb">
                <strong><?= htmlspecialchars($home) ?></strong>
                <span style="color:#6b7280"><?= $vsTxt ?></span>
                <strong><?= htmlspecialchars($away) ?></strong>
              </td>
              <td style="padding:10px;border:1px solid #e5e7eb">—</td> <!-- RR thuần: luôn trống -->
              <td style="padding:10px;border:1px solid #e5e7eb"><?= htmlspecialchars($venue) ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</section>

<script>
  (function(){
    var sel = document.getElementById('rr-round-select');
    if(!sel) return;
    sel.addEventListener('change', function(){
      document.querySelectorAll('.rr-round').forEach(function(el){ el.style.display='none'; });
      var blk = document.getElementById(this.value);
      if(blk) blk.style.display = '';
    });
  })();
</script>
<?php endif; ?>

<?php if (!empty($played)): ?>
<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Kết quả gần đây</h2>
  <div class="info-list" style="grid-template-columns: repeat(2, minmax(0,1fr));">
    <?php foreach ($played as $m): ?>
      <div class="info-item">
        <i class="fa fa-square-poll-vertical"></i>
        <div>
          <strong><?= htmlspecialchars($m['home_name'] ?? 'Đội A') ?>
            <?= is_numeric($m['home_score']) ? (int)$m['home_score'] : '-' ?> :
            <?= is_numeric($m['away_score']) ? (int)$m['away_score'] : '-' ?>
            <?= htmlspecialchars($m['away_name'] ?? 'Đội B') ?>
          </strong><br>
          <?= fmt_dt($m['kickoff_date']) ?><?= $m['local_name'] ? ' • '.htmlspecialchars($m['local_name']) : '' ?>
          <?= ($m['round_no']!==null) ? ' • Vòng '.(int)$m['round_no'] : '' ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($bracket)): ?>
<style>
  /* giữ cùng class, chỉ phẳng hơn một chút (đã khai báo phía trên) */
</style>
<?php
function ko_round_label(int $n): ?string {
  if ($n === 1)  return 'Chung kết';
  if ($n === 2)  return 'Bán kết';
  if ($n === 4)  return 'Tứ kết';
  if ($n === 8)  return 'Vòng 1/8';
  if ($n === 16) return 'Vòng 1/16';
  return null;
}
?>
<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Nhánh thi đấu</h2>
  <div class="bracket">
    <?php foreach ($bracket as $roundNo => $list): 
      $stage = ko_round_label(count($list));
      $title = $stage ?: ('Vòng ' . (int)$roundNo);
    ?>
      <div class="br-col">
        <div class="br-title"><?= htmlspecialchars($title) ?></div>
        <?php foreach ($list as $m): 
          $day  = $m['kickoff_date'] ?? $m['date'] ?? null;
          $time = $m['kickoff_time'] ?? $m['time'] ?? null;
          $dt   = trim(($day ?? '') . ' ' . ($time ?? ''));
        ?>
          <div class="br-match">
            <div class="br-kick">
              <?= $dt ? '<i class="fa fa-clock"></i> '.htmlspecialchars($dt).' · ' : '' ?>
              <?= 'Trận '.(int)($m['_code'] ?? 0) ?>
            </div>
            <div class="br-team">
              <span><?= htmlspecialchars(prettyKoLabel($m,'home',$roundNo,$idToCode,$prevRoundMap)) ?></span>
              <strong><?= is_numeric($m['home_score']) ? (int)$m['home_score'] : '-' ?></strong>
            </div>
            <div class="br-team">
              <span><?= htmlspecialchars(prettyKoLabel($m,'away',$roundNo,$idToCode,$prevRoundMap)) ?></span>
              <strong><?= is_numeric($m['away_score']) ? (int)$m['away_score'] : '-' ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php endif; ?>

<?php if ($ruleType === 'roundrobin'): ?>
<?php
  require_once __DIR__ . '/../model/modelrank.php';
  $mr = new mRank();
  $standings = $mr->getStandingsLive((int)$id);
?>
<section class="card" style="grid-column: 1 / -1; margin-top:16px;">
  <h2 class="section-title">Bảng xếp hạng</h2>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #eee;">#</th>
        <th style="text-align:left;border-bottom:1px solid #eee;">Đội</th>
        <th style="border-bottom:1px solid #eee;">Tr</th>
        <th style="border-bottom:1px solid #eee;">T</th>
        <th style="border-bottom:1px solid #eee;">H</th>
        <th style="border-bottom:1px solid #eee;">B</th>
        <th style="border-bottom:1px solid #eee;">GF</th>
        <th style="border-bottom:1px solid #eee;">GA</th>
        <th style="border-bottom:1px solid #eee;">GD</th>
        <th style="border-bottom:1px solid #eee;">Điểm</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($standings as $i => $r): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($r['team_name']) ?></td>
        <td style="text-align:center"><?= $r['p'] ?></td>
        <td style="text-align:center"><?= $r['w'] ?></td>
        <td style="text-align:center"><?= $r['d'] ?></td>
        <td style="text-align:center"><?= $r['l'] ?></td>
        <td style="text-align:center"><?= $r['gf'] ?></td>
        <td style="text-align:center"><?= $r['ga'] ?></td>
        <td style="text-align:center"><?= $r['gd'] ?></td>
        <td style="text-align:center;font-weight:700;"><?= $r['pts'] ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($standings)): ?>
        <tr><td colspan="10" style="padding:10px;">Chưa có trận nào được ghi nhận.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php endif; ?>

</main>
<?php if ($regCloseTs && time() <= $regCloseTs): ?>
<script>
(function(){
  var end = <?= json_encode($regCloseTs * 1000) ?>;
  var el  = document.getElementById('reg-countdown');
  if(!el) return;
  function fmt(n){ return n<10 ? '0'+n : ''+n; }
  function tick(){
    var now = Date.now(), diff = end - now;
    if (diff <= 0){ el.textContent = 'hết hạn'; clearInterval(t); return; }
    var d = Math.floor(diff/86400000);
    var h = Math.floor((diff%86400000)/3600000);
    var m = Math.floor((diff%3600000)/60000);
    var s = Math.floor((diff%60000)/1000);
    el.textContent = (d>0? d+' ngày ' : '') + fmt(h)+':'+fmt(m)+':'+fmt(s);
  }
  tick();
  var t = setInterval(tick, 1000);
})();
</script>
<?php endif; ?>

</body>
</html>
  