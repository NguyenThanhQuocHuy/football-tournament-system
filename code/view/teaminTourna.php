<?php /* $tournas, $teams */

$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // => /Kltn
$PUBLIC   = $APP_BASE . '/index.php';
$DASH     = $APP_BASE . '/dashboard.php';
?> 
<style>
  .my-wrap{max-width:1100px;margin:24px auto;padding:0 16px;color:#111827}
  .my-title{font-size:22px;font-weight:700;margin:0 0 14px}
  .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;box-shadow:0 2px 6px rgba(0,0,0,.04)}
  .card h5{font-size:16px;margin:0 0 6px;line-height:1.35}
  .muted{color:#6b7280;font-size:12px;margin-bottom:10px}
  .btn-row{display:flex;flex-wrap:wrap;gap:8px}
  .btn{display:inline-block;font-size:13px;padding:6px 10px;border-radius:10px;border:1px solid #d1d5db;text-decoration:none}
  .btn.primary{border-color:#2563eb}
  .btn:hover{background:#f3f4f6}
  .chipbar{display:flex;flex-wrap:wrap;gap:8px}
  .chip{font-size:12px;border:1px dashed #d1d5db;padding:6px 10px;border-radius:999px;text-decoration:none;color:#111827}
  .chip:hover{background:#f9fafb}
  .section-h6{font-size:13px;margin:18px 0 8px;color:#374151;font-weight:600;text-transform:uppercase;letter-spacing:.04em}
  .divider{height:1px;background:#e5e7eb;margin:18px 0}
</style>

<section class="my-wrap">
  <h2 class="my-title">Giải đấu đang tham gia</h2>

  <?php if(empty($tournas)): ?>
    <div class="card">Chưa có giải nào được duyệt.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach($tournas as $g): ?>
        <div class="card">
          <h5><?=htmlspecialchars($g['tournaName'])?></h5>
          <div class="muted">
            <?= $g['startdate'] ? date('d/m/Y', strtotime($g['startdate'])) : '?' ?>
            – <?= $g['enddate'] ? date('d/m/Y', strtotime($g['enddate'])) : '?' ?>
          </div>

          <div class="btn-row">
            <!-- sang trang chi tiết public của giải -->
            <a class="btn primary"
               href="<?=$PUBLIC?>?page=detail_tourna&id=<?= (int)$g['idtourna'] ?>">
              Xem chi tiết (public)
            </a>

            <!-- Link nhanh xem lịch từng đội (không dùng dropdown để tránh phụ thuộc JS) -->
            <?php foreach($teams as $tm): ?>
              <a class="btn"
                 href="<?=$DASH?>?page=team.schedule&team_id=<?=$tm['id_team']?>&tourna_id=<?=$g['idtourna']?>">
                Lịch <?=$tm['teamName']?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="divider"></div>
  <h3 class="section-h6">Lối tắt</h3>
  <div class="chipbar">
    <?php foreach($teams as $tm): ?>
      <a class="chip"
         href="<?=$DASH?>?page=team.schedule&team_id=<?=$tm['id_team']?>&status=upcoming">
        Lịch sắp đá – <?=$tm['teamName']?>
      </a>
    <?php endforeach; ?>
  </div>
</section>
