<?php
// manage_ranktourna.php
error_reporting(E_ALL);
include_once(__DIR__ . '/../model/modelrank.php');
include_once(__DIR__ . '/../model/modeltourna.php');

$tournaId = isset($_GET['id_tourna']) ? (int)$_GET['id_tourna'] : 0;
if ($tournaId <= 0) { echo "<p>Thiếu ID giải.</p>"; return; }

/* Tương thích link cũ */
$id_tourna = $tournaId;



/* -------- Lấy cấu hình giải -------- */
$mt  = new mTourna();
$td  = $mt->getDetail($tournaId);            // ruletype, rr_rounds, ...

$lockMsg = null;
// Xử lý nút Đóng giải
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lock_tourna'])) {
    $mtLock = new mTourna();
    $current = $mtLock->getTournamentById($tournaId);

    if ($current && ($current['status'] ?? '') === 3) {
        $lockMsg = 'Giải đã được khóa trước đó.';
    } else {
        $ok = $mtLock->updateStatus($tournaId, 3);
        if ($ok) {
            $lockMsg = 'Đã đóng / lưu trữ giải. Từ giờ không thể xóa giải này.';
            // Cập nhật lại $td để dùng phía dưới
            if ($td) $td['status'] = 3;
        } else {
            $lockMsg = 'Khóa giải thất bại. Vui lòng thử lại.';
        }
    }
}
$ruleType = strtolower($td['ruletype'] ?? '');
$isRR     = ($ruleType === 'roundrobin');
$isKO     = ($ruleType === 'knockout');
$isHybrid = ($ruleType === 'hybrid');

/* -------- KPI tổng quan (giữ nguyên) -------- */
$mr       = new mRank();
$overview = $mr->getOverviewByTournament($tournaId);

/* -------- Phát hiện dữ liệu vòng bảng -------- */
$hasGroup = $mr->hasGroupMatches($tournaId); // true nếu có trận dạng bảng/rr

/* -------- Dựng nhánh KO (chỉ khi KHÔNG phải RR-only) -------- */
$koStage = null;
$bracket = [];
$hasKO   = false;

if (!$isRR) {
  // nếu DB có stage/bracket_node thì ưu tiên
  $koStage = $mr->getKnockoutStage($tournaId);
  if ($koStage) {
    $bracket = $mr->getBracketNodes($koStage['id_stage']);
  }
  // luôn có fallback đọc từ bảng match (đã loại trận vòng bảng trong model)
  $fallback = $mr->getBracketFromMatchesSimple($tournaId);
  if (empty($bracket) || count($bracket) < count($fallback)) {
    $bracket = $fallback;
  }
  $hasKO = !empty($bracket);
}

/* -------- Xác định chế độ hiển thị --------
   Ưu tiên theo ruletype để tránh RR-only bị dựng KO nhầm */
if ($isRR) {
  $mode = 'rr';                         // BXH tổng
} elseif ($isHybrid && $hasGroup && $hasKO) {
  $mode = 'hybrid';                     // KO + BXH bảng
} elseif ($isKO && $hasKO) {
  $mode = 'ko';                         // KO only
} elseif ($hasGroup) {
  $mode = 'rr_group';                   // có bảng nhưng ruletype không đặt roundrobin (fallback)
} else {
  $mode = 'empty';
}

/* -------- Chuẩn bị dữ liệu hiển thị KO --------
   - Đánh "mã trận" tuần tự (1..N) theo thứ tự round tăng dần
   - Map id_match -> mã trận để thay placeholder "Thắng trận <idmatch>" */
$idToCode = [];      // [id_match] => mã trận
if ($hasKO) {
  ksort($bracket);   // round_no tăng dần
  $code = 0;
  foreach ($bracket as $rNo => &$nodes) {
    foreach ($nodes as &$n) {
      $n['_code'] = ++$code;                   // mã trận hiển thị
      if (!empty($n['id_match'])) {
        $idToCode[(int)$n['id_match']] = (int)$n['_code'];
      }
    }
    unset($n);
  }
  unset($nodes);
}

/* Đổi "Thắng trận <idmatch>" -> "Thắng trận <mã trận>" */
$prettyPH = function (?string $label) use ($idToCode) {
  if (!$label) return '—';
  return preg_replace_callback('/Thắng trận\s+(\d+)/u', function($m) use ($idToCode){
    $id = (int)$m[1];
    $code = $idToCode[$id] ?? null;
    return 'Thắng trận ' . ($code ?? $id);
  }, $label);
};

/* Nhãn vòng theo tổng số vòng KO sau khi normalize 1..K */
$roundName = function (int $idx, int $total) {
  if ($total === 4) return [1=>'Vòng 1/8', 2=>'Tứ kết', 3=>'Bán kết', 4=>'Chung kết'][$idx] ?? ('Vòng '.$idx);
  if ($total === 3) return [1=>'Tứ kết',   2=>'Bán kết', 3=>'Chung kết'][$idx] ?? ('Vòng '.$idx);
  if ($total === 2) return [1=>'Bán kết',  2=>'Chung kết'][$idx] ?? ('Vòng '.$idx);
  if ($total === 1) return 'Chung kết';
  return 'Vòng '.$idx;
};
?>
<style>
  .nav{display:flex;gap:10px;padding:10px;background:#f7f7f9;border:1px solid #e5e5e5;border-radius:10px;margin-bottom:16px}
  .nav a{ text-decoration:none; color:#374151; padding:8px 12px; background:#fff; border:1px solid #e5e5e5; border-radius:8px }
  .nav a.active{ background:#2563eb;color:#fff;border-color:#2563eb }
  .kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:14px;margin:10px 0}
  .kpi{background:#a0b3ed;border:1px solid #eee;border-radius:12px;padding:14px}
  .kpi .num{font-size:28px;font-weight:700}
  .kpi .label{font-size:13px;color:#777}
  .table{width:100%;border-collapse:collapse;margin-top:12px;table-layout: fixed}
  .table th,.table td{border:1px solid #e5e5e5;padding:8px}
  .table th{background:#fafafa;text-align:left}
  .bracket{display:grid;grid-auto-flow:column;grid-auto-columns:260px;gap:20px;overflow:auto;padding:8px 0}
  .round-col{display:flex;flex-direction:column;gap:14px}
  .node{border:1px solid #e5e5e5;border-radius:10px;padding:10px;background:#fff}
  .node .title{font-size:12px;color:#888;margin-bottom:6px}
  .team{display:flex;justify-content:space-between;padding:6px 8px;border-radius:8px;background:#f9fafb}
  .team + .team{margin-top:6px}
  .team.win{font-weight:600}
</style>

<div class="nav">
  <a href="dashboard.php?page=update_tourna&id=<?= $id_tourna ?>">Cấu hình</a>
  <a href="dashboard.php?page=regulation&id_tourna=<?= $id_tourna ?>">Điều lệ</a>
  <a href="dashboard.php?page=addteam&id_tourna=<?= $id_tourna ?>">Đội tham gia</a>
  <a href="dashboard.php?page=draw&id_tourna=<?= $id_tourna ?>">Kết quả bốc thăm</a>
  <a href="dashboard.php?page=schedule&id=<?= $id_tourna ?>">Lịch thi đấu</a>
  <a class="active" href="dashboard.php?page=rank&id_tourna=<?= $id_tourna ?>">Thống kê - xếp hạng</a>
</div>

<h2>Thống kê & Xếp hạng</h2>

<!-- KPI -->
<section>
  <h3>Tổng quan giải</h3>
  <div class="kpi-grid">
    <div class="kpi"><div class="num"><?= (int)($overview['num_teams'] ?? 0) ?></div><div class="label">Đội tham dự</div></div>
    <div class="kpi"><div class="num"><?= (int)($overview['num_matches_played'] ?? 0) ?></div><div class="label">Trận đã đấu</div></div>
    <div class="kpi"><div class="num"><?= (int)($overview['total_goals'] ?? 0) ?></div><div class="label">Tổng bàn thắng</div></div>
    <div class="kpi"><div class="num"><?= htmlspecialchars($overview['goals_per_match'] ?? '0.00') ?></div><div class="label">Bàn/trận</div></div>
  </div>
</section>

<?php /* ========================= BLOCK THEO CHẾ ĐỘ ========================= */ ?>

<?php if ($mode === 'rr'): ?>
  <!-- RR ONLY: BXH duy nhất -->
  <?php $standings = $mr->getStandingsLive($tournaId); ?>
  <section>
    <h3>BXH (Vòng tròn<?= (int)($td['rr_rounds'] ?? 1) === 2 ? ' · 2 lượt' : '' ?>)</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Hạng</th><th>Đội</th><th>Tr</th><th>T</th><th>H</th><th>B</th>
          <th>GF</th><th>GA</th><th>GD</th><th>Điểm</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($standings)): foreach ($standings as $i => $r): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($r['team_name']) ?></td>
          <td><?= (int)$r['p'] ?></td>
          <td><?= (int)$r['w'] ?></td>
          <td><?= (int)$r['d'] ?></td>
          <td><?= (int)$r['l'] ?></td>
          <td><?= (int)$r['gf'] ?></td>
          <td><?= (int)$r['ga'] ?></td>
          <td><?= (int)$r['gd'] ?></td>
          <td><strong><?= (int)$r['pts'] ?></strong></td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="10" style="padding:10px;">Chưa có dữ liệu.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </section>

<?php elseif ($mode === 'ko' || $mode === 'hybrid'): ?>
  <!-- KO & HYBRID: hiển thị nhánh KO -->
  <?php
    // Normalize round về 1..K để đặt nhãn đúng (1/8, Tứ kết, ...)
    $koRounds = array_keys($bracket);
    sort($koRounds);
    $firstRound  = $koRounds[0] ?? 1;
    $totalRounds = count($koRounds);
    $normalized  = [];
    foreach ($koRounds as $rNo) {
      $normalized[$rNo - $firstRound + 1] = $bracket[$rNo];
    }
    $bracket = $normalized;
  ?>
  <h3>Nhánh thi đấu (Loại trực tiếp)</h3>
  <div class="bracket">
    <?php foreach ($bracket as $idxRound => $nodes): ?>
      <div class="round-col">
        <?php foreach ($nodes as $n): ?>
          <?php
            $title = $roundName($idxRound, $totalRounds) . ' · Trận ' . (int)($n['_code'] ?? 1);
            if (!empty($n['kickoff_date'])) $title .= ' · ' . htmlspecialchars($n['kickoff_date']);
            $homeLbl = $prettyPH($n['home_label'] ?? $n['home_name'] ?? null);
            $awayLbl = $prettyPH($n['away_label'] ?? $n['away_name'] ?? null);
          ?>
          <div class="node">
            <div class="title"><?= htmlspecialchars($title) ?></div>
            <div class="team <?= !empty($n['home_win']) ? 'win' : '' ?>">
              <span><?= htmlspecialchars($homeLbl) ?></span>
              <strong><?= isset($n['home_score']) ? (int)$n['home_score'] : '' ?></strong>
            </div>
            <div class="team <?= !empty($n['away_win']) ? 'win' : '' ?>">
              <span><?= htmlspecialchars($awayLbl) ?></span>
              <strong><?= isset($n['away_score']) ? (int)$n['away_score'] : '' ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($mode === 'hybrid'): ?>
    <!-- HYBRID: kèm BXH vòng bảng -->
    <h3>BXH vòng bảng</h3>
    <?php $all = $mr->getAllGroupStandings($tournaId); ?>
    <?php foreach ($all as $label => $rows): ?>
      <h4>Bảng <?= htmlspecialchars($label) ?></h4>
      <table class="table">
        <thead>
          <tr><th>Hạng</th><th>Đội</th><th>Tr</th><th>T</th><th>H</th><th>B</th>
              <th>GF</th><th>GA</th><th>GD</th><th>Điểm</th></tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $i=>$r): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['teamName']) ?></td>
            <td><?= (int)$r['p'] ?></td><td><?= (int)$r['w'] ?></td><td><?= (int)$r['d'] ?></td><td><?= (int)$r['l'] ?></td>
            <td><?= (int)$r['gf'] ?></td><td><?= (int)$r['ga'] ?></td><td><?= (int)$r['gd'] ?></td>
            <td><strong><?= (int)$r['pts'] ?></strong></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endforeach; ?>
  <?php endif; ?>

<?php else: ?>
  <!-- EMPTY -->
  <p>Chưa có dữ liệu để hiển thị.</p>
<?php endif; 
?>
<?php
  // Xác định giải đã finished hay chưa
  $isFinished = (int)($td['status'] ?? 0) === 3;
?>
<form method="post">
  <div class="lock-box">
    <div class="note">
      <?php if ($isFinished): ?>
        Giải này đã được <strong>lưu trữ</strong>.
      <?php else: ?>

      <?php endif; ?>
    </div>
    <div>
      <button type="submit" name="lock_tourna" value="1" <?= $isFinished ? 'disabled' : '' ?>>
        🔒  Lưu trữ giải
      </button>
    </div>
  </div>
</form>

<?php if (!empty($lockMsg)): ?>
  <div class="lock-msg">
    <?= htmlspecialchars($lockMsg) ?>
  </div>
<?php endif; ?>
