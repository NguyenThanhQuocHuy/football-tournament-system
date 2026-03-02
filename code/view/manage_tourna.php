<?php
// ========== PATH HELPER (đặt ở đầu file) ==========
$APP_BASE = '/Kltn'; // đổi nếu app không nằm trong /Kltn

function asset_url(?string $p, string $fallback): string {
  $p = trim($p ?? '');
  if ($p === '') return $fallback;                        // rỗng -> fallback
  if (preg_match('~^(https?://|/)~i', $p)) return $p;     // http(s) hoặc /... -> giữ nguyên
  $p = preg_replace('~^\.+/~', '', $p);                   // bỏ ../ hoặc ./
  return $GLOBALS['APP_BASE'] . '/' . ltrim($p, '/');     // ghép base /Kltn/...
}
?>
<style>
.list-header{
  display:flex;align-items:center;justify-content:space-between;margin:0 0 16px;
}
.btn-add{
  display:inline-flex;align-items:center;gap:8px;
  background:#2d6cdf;color:#fff;padding:10px 14px;border-radius:10px;
  text-decoration:none;font-weight:700;border:none;cursor:pointer;font-size:16px;
}
.btn-add:hover{opacity:.92}

/* Lưới cards */
.cards{
  display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
  gap:22px;
}
.card{
  background:#fff;border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,.06);
  overflow:hidden; transition:transform .15s ease, box-shadow .15s ease;
}
.card:hover{ transform:translateY(-2px); box-shadow:0 14px 30px rgba(0,0,0,.10); }

.card-top{padding:24px 18px 14px;text-align:center;}
.card-logo{
  width:180px;height:180px;object-fit:contain;background:#fafafa;border-radius:50%;
  display:block;margin:0 auto 12px;border:6px solid #f3f4f6;
}
.card-meta{color:#6b7280;font-size:14px;line-height:1.4;margin:6px 0 2px;}
.card-name{margin-top:6px;font-size:18px;font-weight:700;color:#111827}

/* Footer hành động – 3 dải màu */
.card-actions{
  display:flex; gap:10px; justify-content:flex-end; align-items:center;
  padding:12px; background:linear-gradient(90deg,#83c66b 0,#3db0c6 50%,#c44b44 100%);
}
.card-actions a{
  background:#fff;border:none;border-radius:10px;padding:8px 10px;
  text-decoration:none;color:#111;font-weight:600
}
.card-actions a:hover{filter:brightness(0.95)}
/* màu riêng từng nút nếu muốn */
.btn-edit{ }
.btn-award{ }
.btn-del{ color:#b91c1c }
</style>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once('control/controltourna.php');
$ctl  = new cTourna();
$orgId = (int)($_SESSION['id_org'] ?? 0);
if ($orgId === 0 && !empty($_SESSION['id_user'])) $orgId = (int)$_SESSION['id_user'];
$list = $ctl->getByUser($orgId);
?>

<h2 class="list-header">
  <span>Danh sách giải đấu</span>
  <a class="btn-add" href="?page=create_tourna">＋ Thêm mới</a>
</h2>

<?php
$author = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Người tạo';
?>
<section class="cards">
<?php if (empty($list)): ?>
  <p style="color:#6b7280;">Chưa có giải nào. Bấm “Thêm mới” để tạo.</p>
<?php else: foreach ($list as $r):
$logoSrc   = asset_url($r['logo']   ?? '', $APP_BASE . '/img/giaidau/logo_macdinh.png');

?>
  <article class="card">
    <div class="card-top">
<img class="card-logo"
     src="<?= htmlspecialchars($logoSrc) ?>"
     alt="logo"
     onerror="this.onerror=null;this.src='img/giaidau/logo_macdinh.png';">
      <div class="card-meta">📅 
      <?= 
      (!empty($r['startdate']) && !empty($r['enddate']))
        ? date('d-m-Y', strtotime($r['startdate'])) . ' - ' . date('d-m-Y', strtotime($r['enddate']))
        : (!empty($r['startdate']) ? date('d-m-Y', strtotime($r['startdate'])) 
                                   : (!empty($r['enddate']) ? date('d-m-Y', strtotime($r['enddate'])) : ''))
?>
    </div>
      <div class="card-meta">👤 <?= htmlspecialchars($author) ?></div>
      <div class="card-name"><?= htmlspecialchars($r['TournaName']) ?></div>
    </div>
    <div class="card-actions">
      <a class="btn-edit" href="?page=edit_tourna&id=<?= (int)$r['idtourna'] ?>">✏️ Sửa</a>
      <a class="btn-update" href="?page=update_tourna&id=<?= (int)$r['idtourna'] ?>">🏆 Cấu hình</a>
      <a class="btn-del" href="?page=delete_tourna&id=<?= (int)$r['idtourna'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa giải này?');">🗑️ Xóa</a>
    </div>
  </article>
<?php endforeach; endif; ?>
</section>

