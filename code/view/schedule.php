<?php
$id_tourna = isset($id_tourna) ? (int)$id_tourna : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
?>
<style>
  :root{
    --border:#e5e7eb; --muted:#6b7280; --text:#111827; --bg:#f8fafc; --card:#ffffff;
    --primary:#111827; --primary-600:#0b1220;
  }

  .nav{
    display:flex; gap:10px; padding:10px; background:#f7f7f9;
    border:1px solid var(--border); border-radius:10px; margin-bottom:16px
  }
  .nav a{
    text-decoration:none; color:#374151; padding:8px 12px; background:#fff;
    border:1px solid var(--border); border-radius:8px
  }
    .nav a.active{
        background:#2563eb;color:#fff;border-color:#2563eb
    }

  .page-card{
    background:var(--card); border:1px solid var(--border); border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,.05); padding:16px
  }
  .page-title{ margin:0 0 12px 0; font-size:22px; color:var(--text) }

  .hstack{ display:flex; gap:10px; align-items:center; margin:10px 0 16px }
  .btn{
    padding:8px 12px; border:1px solid var(--border); border-radius:10px;
    background:#fff; cursor:pointer
  }
  .btn.primary{ background:var(--primary); color:#fff; border-color:var(--primary) }
  .btn.primary:hover{ background:var(--primary-600) }

  .alert{ padding:8px 10px; border-radius:10px; display:inline-block }
  .alert.ok{ background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0 }
  .alert.info{ background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe }
  .badge-err{ padding:8px 10px; border-radius:10px; background:#FEF2F2; color:#991B1B; border:1px solid #FECACA }
    .auto-panel{
    margin-left:12px;
  }
  .auto-panel summary{
    list-style:none;
  }
  .auto-panel-body{
    margin-top:8px;
  }
  .auto-form{
    display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; margin-top:6px;
  }
  .auto-form label{
    font-size:0.85rem; color:#4b5563; display:flex; flex-direction:column; gap:4px;
  }
  .auto-form input[type="number"],
  .auto-form input[type="time"]{
    padding:6px 8px; border:1px solid var(--border); border-radius:8px; min-width:90px;
  }


  .round-card{ margin-top:14px; border:1px solid var(--border); border-radius:12px; overflow:hidden }
  .round-header{ padding:10px 14px; background:#22c55e; color:#fff; font-weight:600 }
  .table-wrap{ overflow-x:auto }
  .table{ width:100%; border-collapse:separate; border-spacing:0 }
  .table th,.table td{ padding:12px 14px; border-bottom:1px solid #eef2f7; vertical-align:middle }
  .table thead th{ background:#f9fafb; text-align:left; color:#374151; font-weight:600 }
  .table tbody tr:nth-child(odd){ background:#fcfdff }
  .table tbody tr:hover{ background:#f5f7fb }

  .muted{ color:var(--muted) }

  .kickoff-form, .score-form{ display:flex; gap:8px; align-items:center; flex-wrap:wrap }
  .kickoff-form input[type="date"],
  .kickoff-form input[type="time"],
  .kickoff-form select,
  .kickoff-form input[type="text"]{
    padding:8px 10px; border:1px solid var(--border); border-radius:8px; background:#fff
  }
  .kickoff-form input[type="date"]{ width:150px }
  .kickoff-form input[type="time"]{ width:110px }
  .kickoff-form select{ min-width:160px }
  .kickoff-form input[name="pitch_label"]{ width:130px }
  .kickoff-form input[name="venue"]{ width:170px }
  .kickoff-form .btn{ padding:8px 12px }

  .score-form input{
    width:70px; text-align:center; padding:8px 10px; border:1px solid var(--border); border-radius:8px
  }
  .btn {
    background: green; color: #fff; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;
  }
  .btnpoint {
    display:inline-block; padding:6px 10px; background:#2563eb; color:#fff;
    border-radius:8px; text-decoration:none
  }
    .btnpoint.disabled{
    background:#9ca3af;
    cursor:not-allowed;
    opacity:0.7;
    pointer-events:none; 
  }
.status-pill{
  display:inline-flex; align-items:center; gap:8px;
  padding:6px 14px; border-radius:9999px; font-weight:600;
  position:relative; border:1px solid transparent;
  line-height:1; user-select:none;
}
.status-pill::after{
  content:""; position:absolute; right:4px; top:50%;
  width:16px; height:16px; border-radius:50%;
  background:#fff; transform:translateY(-50%);
  box-shadow:0 0 0 1px rgba(0,0,0,.06);
}

/* trạng thái */
.status-played   { background:#22c55e; color:#fff; border-color:#16a34a; }
.status-scheduled{ background:#ef4444; color:#fff; border-color:#dc2626; }
.status-canceled { background:#9ca3af; color:#fff; border-color:#6b7280; }
/* Status pills */
.status-pill{
  display:inline-flex; align-items:center; gap:8px;
  padding:6px 12px; border-radius:9999px; font-weight:200;
  position:relative; border:1px solid transparent;
  line-height:1; user-select:none; font-size:14px
}
.status-pill::after{
  content:""; position:absolute; right:4px; top:50%;
  width:16px; height:16px; border-radius:50%;
  background:#fff; transform:translateY(-50%);
  box-shadow:0 0 0 1px rgba(0,0,0,.06);
}

/* trạng thái */
.status-played   { background:#22c55e; color:#fff; border-color:#16a34a; }
.status-scheduled{ background:#ef4444; color:#fff; border-color:#dc2626; }
.status-canceled { background:#9ca3af; color:#fff; border-color:#6b7280; }

.score-pill{
  display:inline-block; min-width:64px; text-align:center;
  padding:6px 10px; border-radius:9999px;
  background:#f3f4f6; border:1px solid #e5e7eb; font-weight:600;
}
.btn.lock {
  background:#111827; color:#fff; border:1px solid #0b1220;
  padding:10px 14px; border-radius:10px; font-weight:600;
  box-shadow:0 2px 6px rgba(0,0,0,.06);
}
.btn.lock:hover { background:#0b1220 }
.lock-wrap{ display:flex; justify-content:flex-end; margin:12px 0 4px }
</style>


<div class="nav">
  <a href="dashboard.php?page=update_tourna&id=<?= $id_tourna ?>">Cấu hình</a>
  <a href="dashboard.php?page=regulation&id_tourna=<?= $id_tourna ?>">Điều lệ</a>
  <a href="dashboard.php?page=addteam&id=<?= $id_tourna ?>">Đội tham gia</a>
  <a href="dashboard.php?page=draw&id_tourna=<?= $id_tourna ?>">Kết quả bốc thăm</a>
  <a class="active" href="dashboard.php?page=schedule&id=<?= $id_tourna ?>">Lịch thi đấu</a>
  <a href="dashboard.php?page=rank&id_tourna=<?= $id_tourna ?>">Thống kê - xếp hạng</a>
</div>
<div class="page-card">
  <h2 class="page-title">Lịch thi đấu</h2>


<div class="hstack">
  <a class="btn primary"
     href="dashboard.php?page=schedule&id=<?= $id_tourna ?>&generate=auto">
     + Sinh cặp thi đấu
  </a>
    <details class="auto-panel">
    <summary class="btn">✨ Gợi ý phân lịch</summary>
    <div class="auto-panel-body">
      <form method="post" class="auto-form">
        <input type="hidden" name="auto_suggest" value="1">

        <label>
          Khoảng cách giữa 2 trận (phút)
          <input type="number" name="gap_minutes" min="1" value="120">
        </label>

        <label>
          Số sân thi đấu
          <input type="number" name="field_count" min="1" value="1">
        </label>

        <label>
          Giờ bắt đầu trận đầu tiên
          <input type="time" name="day_start_time" value="08:00">
        </label>

        <button class="btn primary" type="submit">Tự động phân lịch</button>
      </form>

      <?php if(isset($tourna['startdate']) && isset($tourna['enddate'])): ?>
        <p class="muted" style="margin-top:6px;">
          Khoảng thời gian giải:
          <?= date('d/m/Y', strtotime($tourna['startdate'])) ?>
          –
          <?= date('d/m/Y', strtotime($tourna['enddate'])) ?>
        </p>
      <?php endif; ?>
    </div>
  </details>


  <?php if(isset($_GET['genok'])): ?>
    <span class="alert ok">Đã sinh cặp đấu theo thể thức</span>
  <?php endif; ?>

  <?php if(isset($_GET['saved'])): ?>
    <span class="alert info">Đã lưu phân lịch.</span>
  <?php endif; ?>

  <?php if(isset($_GET['conflict'])): ?>
    <span class="badge-err">Khung giờ và sân bị trùng! Vui lòng chọn thời điểm khác.</span>
  <?php endif; ?>
    <?php if(isset($_GET['autosuggest'])): ?>
    <span class="alert ok">
      <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Đã gợi ý và lưu lịch thi đấu.' ?>
    </span>
  <?php endif; ?>

  <?php if(isset($_GET['autosuggest_err'])): ?>
    <span class="badge-err">
      <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Không thể gợi ý lịch thi đấu.' ?>
    </span>
  <?php endif; ?>
</div>


  <?php if(empty($rounds)): ?>
    <p class="muted">Chưa có trận nào. Hãy bấm “Sinh cặp đấu”.</p>
  <?php else: ?>
<div class="d-flex justify-content-end mb-3">
  <a class="btn btn-success"
     href="control/controlschedule.php?action=export&id=<?= (int)$id_tourna ?>">
    <i class="fa fa-file-excel"></i> Tải lịch (.xlsx)
  </a>
</div>
  <?php
// Tìm vòng bảng cuối cùng (vòng có id_group > 0)
$lastGroupRound = null;
foreach ($rounds as $rNo => $list) {
  foreach ($list as $row) {
    $gid = isset($row['_gid']) ? (int)$row['_gid']
                               : (isset($row['id_group']) ? (int)$row['id_group'] : 0);
    if ($gid > 0) { $lastGroupRound = $rNo; }
  }
}
?>
<?php foreach ($rounds as $roundNo => $matches): ?>
  <div class="round-card">
    <div class="round-header">
      <h3 class="round-title">
        <?= htmlspecialchars($roundTitles[$roundNo] ?? ('Vòng '.$roundNo)) ?>
      </h3>
    </div>
    
        <div class="table-wrap">
          <table class="table">
            <thead>
  <tr>
    <th style="width:70px">Mã</th>
    <th style="width:110px">Ngày</th>
    <th style="width:90px">Giờ</th>
    <th>Chủ nhà</th>
    <th class="muted" style="width:60px">Tỉ số</th>
    <th>Khách</th>
    <th style="width:120px">Sân</th>
    <th style="width:120px">Trạng thái</th>
    <th style="width:520px">Phân lịch</th>
    <th style="width:160px">Hành động</th>
  </tr>
            </thead>
<tbody>
<?php
// fallback nếu chưa truyền helper từ controller
if (!isset($prettyPlaceholder) || !is_callable($prettyPlaceholder)) {
  $prettyPlaceholder = function($s){ return $s; };
}
?>

<?php foreach ($matches as $m): ?>
  <tr>
    <!-- Mã (STT theo vòng) -->
    <td><?= isset($m['_seq']) ? (int)$m['_seq'] : '' ?></td>

    <!-- Ngày -->
    <td><?= $m['kickoff_date'] ? date('d/m/Y', strtotime($m['kickoff_date'])) : '' ?></td>

    <!-- Giờ -->
    <td><?= $m['kickoff_time'] ? substr($m['kickoff_time'], 0, 5) : '' ?></td>

    <!-- Chủ nhà -->
    <td class="home">
      <?php
        if (!empty($m['home_name'])) {
          echo htmlspecialchars($m['home_name']);
        } else {
          echo htmlspecialchars($prettyPlaceholder($m['home_placeholder'] ?? ''));
        }
      ?>
    </td>

    <!-- Tỉ số -->
    <td class="muted" style="width:60px">
      <?php
        $hasScore = ($m['status'] === 'played') ||
                    ($m['home_score'] !== null && $m['away_score'] !== null);

        if ($hasScore) {
          $hs = (int)$m['home_score'];
          $as = (int)$m['away_score'];
          echo '<span class="score-pill">'.$hs.' : '.$as.'</span>';
        } else {
          echo '<span class="muted">vs</span>';
        }
      ?>
    </td>

    <!-- Khách -->
    <td class="away">
      <?php
        if (!empty($m['away_name'])) {
          echo htmlspecialchars($m['away_name']);
        } else {
          echo htmlspecialchars($prettyPlaceholder($m['away_placeholder'] ?? ''));
        }
      ?>
    </td>

    <!-- Sân -->
    <td><?= htmlspecialchars($m['pitch_label'] ?: ($m['venue'] ?? '')) ?></td>

    <!-- Trạng thái -->
    <td>
      <?php $st = $m['status'] ?? 'scheduled'; ?>
      <span class="status-pill
                  <?= $st==='played'    ? 'status-played'    : '' ?>
                  <?= $st==='scheduled' ? 'status-scheduled' : '' ?>
                  <?= $st==='canceled'  ? 'status-canceled'  : '' ?>">
        <?= $st==='played' ? 'Đã đá' : ($st==='canceled' ? 'Hủy' : 'Chưa đá') ?>
      </span>
    </td>

    <!-- Phân lịch -->
    <td>
      <form method="post" class="kickoff-form">
        <input type="hidden" name="id_match" value="<?= (int)$m['id_match'] ?>">
        <input type="date" name="kickoff_date" required value="<?= $m['kickoff_date'] ?? '' ?>">
        <input type="time" name="kickoff_time" required value="<?= $m['kickoff_time'] ?? '' ?>">
        <input type="hidden" name="location_id"
               value="<?= isset($tourna['location_id']) ? (int)$tourna['location_id'] : (int)($m['location_id'] ?? 0) ?>">
        <input type="text" name="pitch_label" required placeholder="Sân (vd: Sân 1)" value="<?= htmlspecialchars($m['pitch_label'] ?? '') ?>">
        <input type="text" name="venue" placeholder="Ghi chú" value="<?= htmlspecialchars($m['venue'] ?? '') ?>">
        <button class="btn" name="update_kickoff" value="1">Lưu</button>
      </form>
    </td>

    <!-- Hành động -->
    <td>
      <?php
        // Điều kiện "có lịch" 
        $hasSchedule =
          !empty($m['kickoff_date']) &&
          !empty($m['kickoff_time']) &&
          !empty($m['pitch_label']);
      ?>

      <?php if ($hasSchedule): ?>
        <a class="btnpoint"
           href="dashboard.php?page=match_stats&id_match=<?= (int)$m['id_match'] ?>&id=<?= $id_tourna ?>">
          Nhập kết quả
        </a>
      <?php else: ?>
        <span class="btnpoint disabled"
              title="Hãy phân lịch (ngày, giờ, sân) trước khi nhập kết quả">
          Nhập kết quả
        </span>
      <?php endif; ?>
    </td>

  </tr>
<?php endforeach; ?>
</tbody>

          </table>
        </div>
      </div>
      <?php if ($lastGroupRound !== null && $roundNo === $lastGroupRound): ?>
  <div class="lock-wrap">
    <a class="btn lock"
       href="dashboard.php?page=schedule&id=<?= $id_tourna ?>&resolve=groups">
      🔒 Khóa kết quả vòng bảng
    </a>
  </div>
<?php endif; ?>

    <?php endforeach; ?>
  <?php endif; ?>
</div>