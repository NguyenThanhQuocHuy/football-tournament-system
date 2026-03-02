
<?php
error_reporting(0);
include_once("../control/controlteam.php");
$p = new cteam();
$id = $_REQUEST["id"];
$tblTeam = $p->getTeamDetails($id);

if ($tblTeam == -1 || $tblTeam == -2) {
    echo "<p>Không tìm thấy đội bóng</p>";
    exit;
}

$teamName = "";
$logo = "";
$manager_name = "";
$manager_email = "";
$manager_phone = "";
$members = [];

while ($row = $tblTeam->fetch_assoc()) {
    $teamName = $row["teamName"];
    $logo = $row["logo"];
    $manager_name = $row["manager_name"];
    $manager_email = $row["manager_email"];
    $manager_phone = $row["manager_phone"];

    if (!empty($row["id_player"])) {
        $members[] = [
            "id_player" => $row["id_player"],   
            "name" => $row["player_name"],
            "position" => $row["position"],
            "role" => $row["roleInTeam"],
            "age" => $row["age"],
            "status" => $row["status"],
            "ava" => $row["avatar"] ?: 'default.jpg'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Team Detail</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style_team_detail.css?v=1.0">
</head>
<style>
  /* --- FIX CHỮ TRẮNG TRONG MODAL --- */
.modal-box,
.modal-box table,
.modal-box h2,
.modal-box h3,
.modal-box td,
.modal-box span,
.modal-box p {
    color: #000 !important;     /* Chữ đen */
}

.modal-box table td {
    padding: 6px 4px;
    border-bottom: 1px solid #ddd;
}

.modal-box span {
    color: #000 !important;
}
</style>
<body>
  <!-- HEADER -->
  <header class="hero-header">
    <nav class="navbar">
      <div class="logo d-flex align-items-center gap-2">
        <img src="../img/doibong/<?php echo $logo; ?>" alt="<?php echo $teamName; ?>" 
            style="height:60px; width:60px; border-radius:50%; object-fit:cover;">
    <!--   <span style="font-weight:700; font-size:20px; color:white;"><?php echo strtoupper($teamName); ?></span>-->
        </div>
    <!--  <ul class="nav-links">
        <li><a href="index.php">HOME</a></li>
        <li><a href="#">MATCHES</a></li>
        <li><a href="#">PLAYERS</a></li>
        <li><a href="#">BLOG</a></li>
        <li><a href="#">CONTACT</a></li>
      </ul>-->
    </nav>

    <div class="hero-content">
      <h1><?php echo strtoupper($teamName); ?></h1>
      <div class="buttons">
        <a href="../view/join_team.php?id_team=<?php echo $id; ?>" class="btn btn-primary"
          onclick="return confirm('Bạn có chắc muốn gửi yêu cầu gia nhập đội này?')">
          GIA NHẬP ĐỘI
        </a>
        <a href="../index.php?page=team" class="btn btn-secondary">QUAY LẠI</a>
      </div>
    </div>
  </header>
<!-- THÔNG TIN QUẢN LÝ -->
  <section class="team-info-section">
    <div class="container">
      <div class="info-cards">
        <div class="info-card">
          <i class="fa fa-id-card fa-2x"></i>
          <p>Quản lý: <span class="highlight"><?php echo $manager_name; ?></span></p>
        </div>
        <div class="info-card">
          <i class="fa fa-envelope fa-2x"></i>
          <p>Email: <span class="highlight"><?php echo $manager_email; ?></span></p>
        </div>
        <div class="info-card">
          <i class="fa fa-envelope fa-2x"></i>
          <p>Số điện thoại: <span class="highlight"><?php echo $manager_phone; ?></span></p>
        </div>
      </div>
      <div class="join-btn">
       <a href="../view/join_team.php?id_team=<?php echo $id; ?>" class="btn-join"
          onclick="return confirm('Bạn có chắc muốn gửi yêu cầu gia nhập đội này?')">
          GIA NHẬP ĐỘI
        </a>
      </div>
    </div>
  </section>

<!-- DANH SÁCH CẦU THỦ -->
<section class="member-section">
  <h2>Danh sách thành viên</h2>

  <div class="carousel-container">
    <button class="nav-btn prev-btn">&#10094;</button>
    <div class="member-list" id="memberList">
      <?php foreach ($members as $m) { ?>
<a class="member-card"
   href="#"
   onclick="openPlayerModal(<?php echo $m['id_player']; ?>); return false;">
    <img src="../img/avatar/<?php echo htmlspecialchars($m['ava']); ?>" 
         onerror="this.src='../img/avatar/default_avaplayer.jpg';" 
         alt="Avatar cầu thủ">
    <div class="member-info">
        <h3><?php echo htmlspecialchars($m['name']); ?></h3>
        <p><strong>Vị trí:</strong> <?php echo htmlspecialchars($m['position']); ?></p>
        <p><strong>Tuổi:</strong> <?php echo htmlspecialchars($m['age']); ?></p>
        <p><strong>Vai trò:</strong> <span class="role"><?php echo htmlspecialchars($m['role']); ?></span></p>
    </div>
</a>
<?php } ?>
    </div>
    <button class="nav-btn next-btn">&#10095;</button>
  </div>
</section>
<script>
  const list = document.getElementById('memberList');
  const next = document.querySelector('.next-btn');
  const prev = document.querySelector('.prev-btn');

  let scrollPosition = 0;
  const cardWidth = list.querySelector('.member-card').offsetWidth + 20; // width + margin

  next.addEventListener('click', () => {
    if (scrollPosition < list.scrollWidth - list.clientWidth) {
      scrollPosition += cardWidth * 4;
      list.style.transform = `translateX(-${scrollPosition}px)`;
    }
  });

  prev.addEventListener('click', () => {
    if (scrollPosition > 0) {
      scrollPosition -= cardWidth * 4;
      list.style.transform = `translateX(-${scrollPosition}px)`;
    }
  });
</script>
<!-- PLAYER PROFILE MODAL -->
<div id="playerModal" class="modal-overlay" style="
    display:none; position:fixed; top:0;left:0;width:100%;height:100%;
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center;
    z-index:9999;">
  <div class="modal-box" style="background:white; width:70%; max-height:90%;
       border-radius:12px; overflow:auto; padding:20px; position:relative;">
      
      <button onclick="closePlayerModal()" 
          style="position:absolute; top:10px; right:15px; background:red; color:white;
                 border:none; font-weight:bold; padding:6px 10px; border-radius:6px; cursor:pointer;">
          X
      </button>

      <div id="modalContent">
          <!-- Dữ liệu sẽ được AJAX đổ vào đây -->
          <p style="text-align:center;">Đang tải dữ liệu...</p>
      </div>
  </div>
</div>
<script>
function openPlayerModal(id) {
    const modal = document.getElementById('playerModal');
    const content = document.getElementById('modalContent');

    content.innerHTML = "<p style='text-align:center;'>Đang tải...</p>";
    modal.style.display = "flex";

    fetch("../view/ajax_player_profile.php?id_player=" + id)
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") {
                content.innerHTML = "<p style='text-align:center;color:red;'>" + data.msg + "</p>";
                return;
            }

            const p = data.profile;
            const statusClass = (p.status === "Đang tham gia") ? "active" : "free";

            content.innerHTML = `
                <h2 style="text-align:center; color:#1e40af; margin-bottom:20px;">
                    Hồ sơ cầu thủ
                </h2>

                <div style="display:flex; gap:30px;">
                    <div style="flex:1; text-align:center;">
                        <img src="../img/avatar/${p.avatar || 'default_avaplayer.jpg'}"
                             style="width:160px; height:160px; border-radius:50%; object-fit:cover;
                             border:3px solid #2563eb;">
                        <h3 style="margin-top:10px;">${p.FullName}</h3>
                    </div>

                    <div style="flex:2;">
                        <h3 style="color:#2563eb;">Thông tin cá nhân</h3>
                        <table style="width:100%; border-collapse:collapse;">
                            <tr><td>Vị trí</td><td>${p.position}</td></tr>
                            <tr><td>Tuổi</td><td>${p.age}</td></tr>
                            <tr><td>Ngày sinh</td><td>${p.dateOfBirth}</td></tr>
                            <tr><td>Nơi sinh</td><td>${p.placeOfBirth}</td></tr>
                            <tr><td>Chiều cao</td><td>${p.height} m</td></tr>
                            <tr><td>Số áo</td><td>${p.jersey_number}</td></tr>
                            <tr>
                                <td>Tình trạng</td>
                                <td><span style="
                                    padding:5px 8px; border-radius:6px; color:white;
                                    background:${p.status === 'Đang tham gia' ? '#22c55e' : '#f97316'};">
                                    ${p.status}
                                </span></td>
                            </tr>
                            <tr><td>Đội hiện tại</td><td>${p.teamName ?? 'Chưa tham gia đội nào'}</td></tr>
                        </table>
                    </div>
                </div>
            `;
        });
}

function closePlayerModal() {
    document.getElementById('playerModal').style.display = "none";
}
</script>
</body>
</html>