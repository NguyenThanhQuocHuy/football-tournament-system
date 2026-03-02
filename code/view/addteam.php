<?php
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) die('Thiếu id giải');
$idTourna = (int)$_GET['id'];

include_once(__DIR__.'/../model/modeltournateam.php');
include_once(__DIR__.'/../control/controlteam.php');
include_once(__DIR__.'/../control/controlteammember.php'); 
$mTT = new mtournateam();
$cTeam = new cTeam(); // class controlteam của bạn có getAllTeams(), getTeamByName()
$cMember = new cteamMember(); 
// 

$viewTeamId   = (isset($_GET['view_team']) && ctype_digit($_GET['view_team']))
                ? (int)$_GET['view_team'] : 0;
$modalMembers = [];
$modalTeamName = '';
if ($viewTeamId > 0) {
    require_once __DIR__.'/../control/controltournateam.php';
    $cTT     = new cTournateam();
    $regInfo = $cTT->getTeamRegInfo($idTourna, $viewTeamId);
    // Lấy thành viên đội
    $tbl = $cMember->get01TeamMember($viewTeamId); // dùng hàm có sẵn
    if ($tbl instanceof mysqli_result && $tbl->num_rows > 0) {
        while ($row = $tbl->fetch_assoc()) {
            $modalMembers[] = $row;
        }
    }

    // Lấy tên đội để hiển thị trên title
    $teamRes = $cTeam->get01Team($viewTeamId);
    if ($teamRes instanceof mysqli_result && $teamRes->num_rows > 0) {
        $trow = $teamRes->fetch_assoc();
        $modalTeamName = $trow['teamName'] ?? '';
    }
}
// --- XỬ LÝ FORM SUBMIT ---
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $keyword = trim($_POST['team_name'] ?? '');
        if ($keyword === '') {
            $flash = 'Tên đội không được rỗng';
        } else {
            $res = $cTeam->getTeamByName($keyword);
            if ($res instanceof mysqli_result && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $ok  = $mTT->register($idTourna, (int)$row['id_team']);
                $flash = $ok ? 'Đã thêm vào danh sách chờ duyệt'
                             : 'Đội đã tồn tại trong giải hoặc lỗi CSDL';
            } elseif ($res === -1) {
                $flash = 'Không tìm thấy đội phù hợp';
            } else {
                $flash = 'Lỗi kết nối CSDL';
            }
        }

    } elseif ($action === 'quick_approve') {
        require_once __DIR__ . '/../control/controltournateam.php';
        $cTT = new cTournaTeam();
        $ok  = $cTT->approve((int)$_POST['id_tournateam'], (int)$_SESSION['ID_user']);
        $flash = $ok ? 'Đã duyệt đội' : 'Duyệt thất bại';

    } elseif ($action === 'quick_reject') {
        require_once __DIR__ . '/../control/controltournateam.php';
        $cTT = new cTournaTeam();
        $ok  = $cTT->reject((int)$_POST['id_tournateam'], (int)$_SESSION['ID_user']);
        $flash = $ok ? 'Đã từ chối đội' : 'Từ chối thất bại';

    } elseif ($action === 'setstatus') {
        $ok = $mTT->updateStatus((int)$_POST['id_tournateam'], $_POST['status'] ?? 'pending');
        $flash = $ok ? 'Cập nhật trạng thái thành công' : 'Cập nhật thất bại';
    }      elseif ($action === 'bulk_status') {
        $updated = 0;
        if (!empty($_POST['status']) && is_array($_POST['status'])) {
            foreach ($_POST['status'] as $ttId => $st) {
                $ttId = (int)$ttId;
                $st   = $st ?? 'pending';
                if ($ttId > 0 && $mTT->updateStatus($ttId, $st)) {
                    $updated++;
                }
            }
        }
        $flash = $updated > 0
            ? "Đã cập nhật trạng thái cho {$updated} đội."
            : "Không có trạng thái nào được cập nhật.";

      }
}

      

// Lấy datalist (toàn bộ đội) & danh sách đã đăng ký
$allTeams = $cTeam->getAllTeams();           // mysqli_result | -1 | -2
$registered = $mTT->listByTournament($idTourna); // mysqli_result | false
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Đội tham gia giải</title>
<style>
body{font-family:Arial,Helvetica,sans-serif}
.wrap{max-width:1100px;margin:16px auto}
.nav{display:flex;gap:10px;padding:8px;background:#f3f3f3;border:1px solid #ddd}
.nav a{text-decoration:none;color:#333;padding:6px 10px;border:1px solid #ccc;border-radius:4px}
.nav a.active{background:#2563eb;color:#fff;border-color:#2563eb}
h2{margin:8px 0 12px}
.flash{padding:10px;margin:10px 0;border:1px solid #ccc;background:#ffffe0}
.form-inline{display:flex;gap:8px;margin:10px 0}
input[type=text]{padding:6px 8px;min-width:360px}
button{padding:6px 12px;cursor:pointer}
.table{width:100%;border-collapse:collapse;margin-top:12px}
.table th,.table td{border:1px solid #ddd;padding:8px}
.badge{padding:2px 8px;border-radius:12px;border:1px solid #ccc;font-size:12px}
.badge.pending{background:#eef5ff;border-color:#8bb4ff}
.badge.approved{background:#eaffea;border-color:#7ac77a}
.badge.rejected{background:#ffecec;border-color:#ff9f9f}
.btn-view-members{
  margin-left:8px;
  padding:3px 10px;
  font-size:12px;
  border-radius:999px;
  border:1px solid #2563eb;
  background:#eef2ff;
  color:#1d4ed8;
}
.btn-view-members:hover{
  background:#dbeafe;
}

/* Modal overlay */
.modal-overlay{
  position:fixed;
  inset:0;
  background:rgba(15,23,42,0.45);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:999;
}
.modal-overlay.show{
  display:flex;
}
.modal-box{
  background:#fff;
  border-radius:10px;
  max-width:720px;
  width:90%;
  max-height:80vh;
  overflow:auto;
  box-shadow:0 20px 50px rgba(15,23,42,0.3);
  padding:16px 18px;
}
.modal-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:8px;
}
.modal-header h3{
  margin:0;
  font-size:18px;
}
.modal-close{
  border:none;
  background:transparent;
  font-size:24px;
  line-height:1;
  cursor:pointer;
}
.modal-table{
  width:100%;
  border-collapse:collapse;
  margin-top:8px;
  font-size:14px;
}
.modal-table th,
.modal-table td{
  border:1px solid #e5e7eb;
  padding:6px 8px;
  text-align:left;
}
.modal-table th{
  background:#f3f4f6;
}
.muted{
  color:#6b7280;
  font-size:14px;
}

</style>
</head>
<body>
<?php $id = $idTourna; 
            $teamCount = isset($teamCount) ? (int)$teamCount
           : (isset($tourna['team_count']) ? (int)$tourna['team_count'] : 0);
// dùng chung biến cho nav ?>

<!-- <div class="nav">
  <a href="?page=update_tourna&id=<?= $id ?>">Cấu hình</a>
  <a class="active" href="?page=addteam&id=<?= $id ?>">Đội tham gia</a>
  <a href="dashboard.php?page=draw&id_tourna=<?= $id_tourna ?>&team_count=<?= $teamCount ?>">Kết quả bốc thăm</a>
  <a href="?page=schedule&id=<?= $id ?>">Lịch thi đấu</a>
  <a href="?page=rank&id_tourna=<?= $id_tourna ?>">Thống kê - xếp hạng</a>
</div> -->
<div class="nav">
  <a href="?page=update_tourna&id=<?= $id ?>">Cấu hình</a>
  <a href="?page=regulation&id_tourna=<?php echo $id; ?>">Điều lệ</a>
  <a class="active" href="?page=addteam&id=<?= $id ?>">Đội tham gia</a>
  <a href="dashboard.php?page=draw&id_tourna=<?= $id ?>">Kết quả bốc thăm</a>
  <a href="?page=schedule&id=<?= $id ?>">Lịch thi đấu</a>
  <a href="?page=rank&id_tourna=<?= $id ?>">Thống kê - xếp hạng</a>
</div>
<div class="wrap">
  <h2>DANH SÁCH ĐỘI THAM GIA GIẢI</h2>

  <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

  <!-- Form đăng ký đội: luôn hiển thị, không JS -->
  <form method="post" class="form-inline">
    <input type="hidden" name="action" value="register">
    <input list="teamlist" name="team_name" placeholder="Nhập tên đội (có gợi ý từ danh sách)...">
    <datalist id="teamlist">
      <?php if ($allTeams instanceof mysqli_result && $allTeams->num_rows>0): ?>
        <?php while($t = $allTeams->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($t['teamName']) ?>"></option>
        <?php endwhile; ?>
      <?php endif; ?>
    </datalist>
    <button type="submit">Đăng ký đội tham gia</button>
  </form>

  <!-- Bảng danh sách đã đăng ký -->
<form method="post">
  <input type="hidden" name="action" value="bulk_status">
  <table class="table">
    <thead>
      <tr>
        <th>Đội</th>
        <th>Xem thành viên đội</th>
        <th>Nguồn đăng ký</th>
        <th>Đăng ký lúc</th>
        <th>Trạng thái hiện tại</th>
        <th>Chọn trạng thái mới</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($registered instanceof mysqli_result && $registered->num_rows>0): ?>
      <?php while($r = $registered->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($r['teamName']) ?></td>
          <td>
            <a href="dashboard.php?page=addteam&id=<?= $idTourna ?>&view_team=<?= (int)$r['id_team'] ?>"
               class="btn-view-members">Xem</a>
          </td>
          <td>
            <?php if ($r['reg_source']==='online'): ?>
              <span class="badge pending">Online</span>
            <?php else: ?>
              <span class="badge">BTC</span>
            <?php endif; ?> 
          </td>
          <td >
            <?php
              $regAt = $r['registered_at'] ?? '';
              echo $regAt ? date('d/m/Y H:i', strtotime($regAt)) : '-';
            ?>
          </td>
          <td>
            <?php if ($r['reg_status']==='pending'): ?>
              <span class="badge pending">Chờ duyệt</span>
            <?php elseif ($r['reg_status']==='approved'): ?>
              <span class="badge approved">Đã duyệt</span>
            <?php else: ?>
              <span class="badge rejected">Từ chối</span>
            <?php endif; ?>
          </td>
          <td>
            <select name="status[<?= (int)$r['id_tournateam'] ?>]">
              <option value="pending"  <?= $r['reg_status']==='pending'  ? 'selected' : '' ?>>Đang duyệt</option>
              <option value="approved" <?= $r['reg_status']==='approved' ? 'selected' : '' ?>>Chấp nhận</option>
              <option value="rejected" <?= $r['reg_status']==='rejected' ? 'selected' : '' ?>>Từ chối</option>
            </select>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">Chưa có đội nào đăng ký.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>

  <div class="bulk-actions">
    <button type="submit">Lưu tất cả</button>
  </div>

</form>
</div>
<!-- Modal xem thành viên đội -->
 <div id="member-modal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <h3>Thành viên đội: <?= htmlspecialchars($modalTeamName ?? '') ?></h3>
      <button type="button" class="modal-close">&times;</button>
    </div>

    <div class="modal-body">
      <?php
        // đảm bảo biến tồn tại để tránh notice
        $regInfo = $regInfo ?? [];
        $viewTeamId = (int)($viewTeamId ?? 0);
      ?>

      <?php if ($viewTeamId <= 0): ?>
        <p class="muted">Chọn một đội để xem danh sách thành viên.</p>

      <?php else: ?>
        <!-- INFO NHANH: luôn hiển thị nếu có dữ liệu -->
        <?php if (!empty($regInfo)): ?>
          <div class="mb-3 p-3 border rounded-3 bg-light">
            <div class="d-flex align-items-center gap-3">
<?php
$logo = $regInfo['logo'] ?? '';
// Base URL của project (vd: "/Kltn")
$projBase = '/' . explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))[0];

if ($logo && preg_match('~^https?://~i', $logo)) {
  $logoUrl = $logo;
} else {

  $logo = ltrim($logo, '/');

  $candidates = [
    "img/doibong/$logo",
    $logo, 
  ];

  $logoUrl = '';
  foreach ($candidates as $rel) {
    $fs = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $projBase . '/' . $rel;
    if (is_file($fs)) {
      $logoUrl = $projBase . '/' . $rel; // URL hiển thị
      break;
    }
  }

  if ($logoUrl === '') {
    $logoUrl = $projBase . '/img/default-team.png'; // tạo 1 ảnh mặc định nếu cần
  }
}
?>
<?php if ($logoUrl): ?>
  <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo đội"
       style="width:64px;height:64px;object-fit:cover;border-radius:8px">
<?php endif; ?>


              <div>
                <div class="fw-bold"><?= htmlspecialchars($regInfo['teamName'] ?? '') ?></div>
                <div class="small text-muted">
                  Quản lý: <?= htmlspecialchars($regInfo['manager_name'] ?? '—') ?>
                  <?php if (!empty($regInfo['email'])): ?> · <?= htmlspecialchars($regInfo['email']) ?><?php endif; ?>
                  <?php if (!empty($regInfo['phone'])): ?> · <?= htmlspecialchars($regInfo['phone']) ?><?php endif; ?>
                </div>
                <div class="small">
                  Đăng ký lúc:
                  <?php
                    $regAt = $regInfo['registered_at'] ?? '';
                    echo $regAt ? date('d/m/Y H:i', strtotime($regAt)) : '—';
                  ?>
                  <?php if (!empty($regInfo['reg_status'])): ?>
                    · Trạng thái: <?= htmlspecialchars($regInfo['reg_status']) ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- DANH SÁCH THÀNH VIÊN -->
        <?php if (empty($modalMembers)): ?>
          <p class="muted">Đội này chưa có thành viên.</p>
        <?php else: ?>
          <table class="modal-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Họ tên</th>
                <th>Vị trí</th>
                <th>Tuổi</th>
                <th>SĐT</th>
                <th>Vai trò</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; foreach ($modalMembers as $m): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($m['FullName'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['position'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['age'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['phone'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['roleInTeam'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('member-modal');
  const closeBtn = modal.querySelector('.modal-close');

  // Nếu có ?view_team=... thì mở modal ngay
  <?php if ($viewTeamId > 0): ?>
  modal.classList.add('show');
  <?php endif; ?>

  function closeModal() {
    modal.classList.remove('show');
    // Xoá view_team khỏi URL cho sạch (không bắt buộc)
    if (window.history && window.history.replaceState) {
      const url = new URL(window.location.href);
      url.searchParams.delete('view_team');
      window.history.replaceState({}, '', url.toString());
    }
  }

  closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', function(e){
    if (e.target === modal) closeModal();
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeModal();
  });
});
</script>

</body>
</html>
