<?php
include_once('control/controlplayer.php');
include_once("control/controluser.php");
include_once(__DIR__ . '/../control/controlteammember.php');
$p = new cPlayer();

$id_user = $_SESSION['id_user'] ?? 0;
$msg = "";
if(isset($_REQUEST["btn-upload-ava"])){
            $p = new cUser();
            $avatar = $row['avatar'];
            $fileavatar = $_FILES["favatar"];
            $kq = $p->uploadImageAva($id_user, $fileavatar, $avatar);
            if($kq){
                echo "<script>alert('Cập nhật thành công!'); window.location='dashboard.php?page=player_profile';</script>";
                exit();
            }else{
                echo "<script>alert('Cập nhật thất bại!')</script>";
            }
        }
// 🟢 Khi người dùng submit form cập nhật
if (isset($_POST['btnUpdate'])) {
    $fullname = $_POST['FullName'];
    $position = $_POST['position'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $placeOfBirth = $_POST['placeOfBirth'];
    $height = $_POST['height'];
    $jersey_number = $_POST['jersey_number'];
    // Tính tuổi tự động
    $today = new DateTime();
    $dob = new DateTime($dateOfBirth);
    $age = $today->diff($dob)->y;

    $ok = $p->updatePlayerProfile($id_user, $fullname, $position, $age, $dateOfBirth, $placeOfBirth, $height, $jersey_number);
    $msg = $ok ? "✅ Cập nhật thành công!" : "❌ Lỗi khi cập nhật!";
}

$tbl = $p->getPlayerProfile($id_user);
// Xử lý khi cầu thủ rời đội
if (isset($_POST['btnLeaveTeam'])) {
    $id_member = $_POST['id_member'] ?? 0;
    $cTeamMember = new cteamMember();
    $result = $cTeamMember->close01Member($id_member);
    
    if ($result === 1) {
    echo "<script>alert('Bạn đã rời đội thành công!'); window.location='dashboard.php?page=player_profile';</script>";
    exit();
} else {
    echo "<script>alert('Rời đội thất bại!');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cầu thủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #f5f6fa;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #1e40af;
    margin: 20px;
}

/* 🟢 Container chia 2 phần trái-phải */
.container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 40px;
    padding: 20px 40px;
}

/* 🟢 Cột bên trái: thông tin cầu thủ */
.profile-box {
    flex: 2;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 25px 30px;
    position: relative;
}

/* 🟢 Cột bên phải: avatar */
.account-avatar {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 25px;
    text-align: center;
}

.account-avatar h2 {
    color: #2563eb;
    margin-bottom: 10px;
}

.avatar-img {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #2563eb;
    margin-bottom: 15px;
}

.avatar-input {
    display: block;
    width: 100%;
    margin-bottom: 10px;
}

.btn-save {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-save:hover {
    background: #1e3a8a;
}

/* 🟢 Các section thông tin */
.section h2 {
    color: #2563eb;
    font-size: 18px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 6px;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 6px;
    border-bottom: 1px solid #f1f5f9;
}

td:first-child {
    font-weight: 600;
    color: #334155;
    width: 45%;
}

/* 🟢 Trạng thái */
.status {
    font-weight: bold;
    padding: 3px 7px;
    border-radius: 6px;
}

.active { background: #22c55e; color: #fff; }
.free { background: #f97316; color: #fff; }
.team { color: #2563eb; font-weight: bold; }

/* 🟢 Biểu tượng chỉnh sửa */
.edit-icon {
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
    font-size: 22px;
    color: #2563eb;
    transition: 0.3s;
}

.edit-icon:hover {
    color: #1e3a8a;
    transform: scale(1.1);
}

/* 🟢 Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-box {
    background: #fff;
    border-radius: 12px;
    padding: 25px 30px;
    width: 400px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    position: relative;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
}

.modal-box h2 {
    color: #1e40af;
    text-align: center;
    margin-bottom: 15px;
}

.modal-box input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
}

.modal-box .btn {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    background: #2563eb;
    color: #fff;
    cursor: pointer;
    width: 100%;
}

.modal-box .btn:hover {
    background: #1e3a8a;
}

.close-btn {
    position: absolute;
    top: 8px;
    right: 12px;
    font-size: 20px;
    color: #64748b;
    cursor: pointer;
}

.close-btn:hover {
    color: #000;
}

.msg {
    text-align: center;
    font-weight: bold;
    margin: 10px;
}

    </style>
</head>
<body>

<h1>Hồ sơ cầu thủ</h1>

<div class="container">
    <div class="profile-box">

        <?php
        if ($msg != "") echo "<p class='msg'>$msg</p>";

        if ($tbl === -2) {
            echo "<p>Lỗi kết nối CSDL!</p>";
        } elseif ($tbl === -1) {
            echo "<p>Không tìm thấy thông tin cầu thủ!</p>";
        } else {
            $row = $tbl->fetch_assoc();//
            $canLeave = false;
            if (!empty($row['id_member'])) {
                $tmCtrl = new cteamMember();
                $canLeave = $tmCtrl->canLeaveTeam($row['id_member']);
            }//
            $statusClass = ($row['status'] == 'Đang tham gia') ? "active" : "free";
            ?>

            <div class='section'>
              <!-- Bên phải: ảnh đại diện -->
            <div class="account-avatar">
                <h2>Ảnh đại diện</h2>
                <img src="<?= !empty($row['avatar']) ? 'img/avatar/' . htmlspecialchars($row['avatar']) : 'img/avatar/default_avaplayer.jpg' ?>" 
                    alt="Avatar người dùng"
                    class="avatar-img">

                <form method="post" enctype="multipart/form-data">
                    <input type="file" id="favatar" name="favatar" accept="image/*" class="avatar-input">

                    <!-- NÚT LƯU ban đầu bị vô hiệu hóa -->
                    <button type="submit" id="btnAva" name="btn-upload-ava" class="btn-save" disabled>
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                </form>
            </div>
            <script>
                const fileInput = document.getElementById("favatar");
                const btnAva = document.getElementById("btnAva");

                fileInput.addEventListener("change", function() {
                    if (fileInput.files.length > 0) {
                        btnAva.disabled = false;     // bật nút khi có file mới
                    } else {
                        btnAva.disabled = true;      // tắt nút nếu chưa chọn file
                    }
                });
            </script>
            <div class="info-header">
                <h2>Thông tin cá nhân</h2>
                <span class="edit-icon" title="Cập nhật" onclick="openModal()">✏️</span>
                <table>
                    <tr><td>Họ và tên</td><td><?= htmlspecialchars($row['FullName']) ?></td></tr>
                    <tr><td>Vị trí chơi</td><td><?= htmlspecialchars($row['position']) ?></td></tr>
                    <tr><td>Tuổi</td><td><?= htmlspecialchars($row['age']) ?></td></tr>
                    <tr><td>Ngày sinh</td><td><?= htmlspecialchars($row['dateOfBirth']) ?></td></tr>
                    <tr><td>Nơi sinh</td><td><?= htmlspecialchars($row['placeOfBirth']) ?></td></tr>
                    <tr><td>Chiều cao</td><td><?= htmlspecialchars($row['height']) ?> m</td></tr>
                    <tr><td>Số áo</td><td><?= htmlspecialchars($row['jersey_number']) ?></td></tr>
                </table>
            </div>

            <div class='section'>
                <h2>Thông tin đội bóng</h2>
                <table>
                    <tr><td>Tình trạng</td><td><span class='status <?= $statusClass ?>'><?= htmlspecialchars($row['status']) ?></span></td></tr>
                    <tr>
                        <td>Đội hiện tại</td><td class='team'><?= (!empty($row['teamName']) ? htmlspecialchars($row['teamName']) : "Chưa tham gia đội nào") ?></td>
                        <?php if (!empty($row['teamName']) && $row['status'] == 'Đang tham gia' && !empty($row['id_member'])): ?>

    <?php if ($canLeave): ?>
        <!-- ĐỦ 24H → HIỆN NÚT -->
        <form method="post" style="display:inline-block; margin-left:10px;">
            <input type="hidden" name="id_member" value="<?= $row['id_member'] ?>">
            <button type="submit" name="btnLeaveTeam"
                style="border:none; background:none; cursor:pointer;"
                title="Rời đội">
                <i class="fa-solid fa-door-open" style="color:#f97316;"></i>
            </button>
        </form>
    <?php else: ?>
        <!-- CHƯA ĐỦ 24H → ICON MỜ + TOOLTIP -->
        <i class="fa-solid fa-door-closed"
           style="color:#94a3b8; margin-left:10px;"
           title="Bạn chỉ có thể rời đội sau 24 giờ kể từ khi gia nhập"></i>
    <?php endif; ?>

<?php endif; ?>
                </tr>
                </table>
            </div>
            <div class='section'>
                <h2>Sự nghiệp</h2>
                <?php
                $career = $p->getCareerHistory($id_user);
                if ($career === -2) {
                    echo "<p>Lỗi kết nối CSDL!</p>";
                } elseif ($career === -1) {
                    echo "<p>Chưa có dữ liệu sự nghiệp.</p>";
                } else {
                    echo "<table>
                            <tr>
                                <td><b>Đội bóng</b></td>
                                <td><b>Thời gian gia nhập</b></td>
                                <td><b>Thời gian rời đi</b></td>
                                <td><b>Trạng thái</b></td>
                            </tr>";
                    while ($c = $career->fetch_assoc()) {
                        echo "<tr>
                                <td><img src='img/doibong/" . htmlspecialchars($c['logo']) . "' width='30' height='30' style='vertical-align:middle;border-radius:50%;margin-right:5px;'> " . htmlspecialchars($c['teamName']) . "</td>
                                <td>" . date('d/m/Y', strtotime($c['joinTime'])) . "</td>
                                <td>" . (!empty($c['leaveTime']) ? date('d/m/Y', strtotime($c['leaveTime'])) : '-') . "</td>
                                <td>" . htmlspecialchars($c['memberStatus']) . "</td>
                            </tr>";
                    }
                    echo "</table>";
                }
                ?>
            </div>

            <div class='section'>
                <h2>Thành tích</h2>
                <?php
                $achieve = $p->getPlayerAchievements($id_user);
                if ($achieve === -2) {
                    echo "<p>Lỗi kết nối CSDL!</p>";
                } elseif ($achieve === -1) {
                    echo "<p>Chưa có thành tích nào được ghi nhận.</p>";
                } else {
                    echo "<table>
                            <tr>
                                <td><b>Giải đấu</b></td>
                                <td><b>Trận đã chơi</b></td>
                                <td><b>Bàn thắng</b></td>
                                <td><b>Thắng</b></td>
                                <td><b>Hòa</b></td>
                                <td><b>Thua</b></td>
                            </tr>";
                    while ($a = $achieve->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($a['tournaName']) . "</td>
                                <td>" . htmlspecialchars($a['matches_played']) . "</td>
                                <td>" . htmlspecialchars($a['goals_scored']) . "</td>
                                <td>" . htmlspecialchars($a['wins']) . "</td>
                                <td>" . htmlspecialchars($a['draws']) . "</td>
                                <td>" . htmlspecialchars($a['losses']) . "</td>
                            </tr>";
                    }
                    echo "</table>";
                }
                ?>
            </div>

           <!-- 🟢 Modal cập nhật -->
            <div class="modal-overlay" id="updateModal">
                <div class="modal-box">
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                    <h2>Cập nhật thông tin</h2>
                    <form method="post">
                        <label for="FullName">Họ và tên:</label>
                        <input type="text" id="FullName" name="FullName" value="<?= htmlspecialchars($row['FullName']) ?>" required>

                        <label for="position">Vị trí chơi:</label>
                        <input type="text" id="position" name="position" value="<?= htmlspecialchars($row['position']) ?>" required>

                        <label for="dateOfBirth">Ngày sinh:</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?= htmlspecialchars($row['dateOfBirth']) ?>" required>

                        <label for="placeOfBirth">Nơi sinh:</label>
                        <input type="text" id="placeOfBirth" name="placeOfBirth" value="<?= htmlspecialchars($row['placeOfBirth']) ?>" required>

                        <label for="height">Chiều cao (m):</label>
                        <input type="number" step="0.01" id="height" name="height" value="<?= htmlspecialchars($row['height']) ?>" required>

                        <label for="jersey_number">Số áo:</label>
                        <input type="number" id="jersey_number" name="jersey_number" value="<?= htmlspecialchars($row['jersey_number']) ?>" required>

                        <button class="btn" name="btnUpdate">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
function openModal() {
    document.getElementById('updateModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}
</script>

</body>
</html>