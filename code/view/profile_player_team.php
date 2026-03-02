<?php
include_once('control/controlplayer.php');
$p = new cPlayer();

$id_user = $_GET['id_user'] ?? ($_SESSION['id_user'] ?? 0);

$tbl = $p->getPlayerProfile($id_user);
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
            margin: 0; padding: 0;
        }
        h1 { text-align:center; color:#1e40af; margin:20px; }
        .container { display:flex; justify-content:center; gap:40px; padding:20px 40px; }
        .profile-box, .account-avatar {
            background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:25px; 
        }
        .profile-box { flex:2; }
        .account-avatar { flex:1; text-align:center; }
        .avatar-img { width:180px; height:180px; border-radius:50%; object-fit:cover; border:3px solid #2563eb; margin-bottom:15px; }
        .section h2 { color:#2563eb; font-size:18px; border-bottom:1px solid #e2e8f0; padding-bottom:6px; margin-bottom:10px; }
        table { width:100%; border-collapse:collapse; }
        td { padding:6px; border-bottom:1px solid #f1f5f9; }
        td:first-child { font-weight:600; color:#334155; width:45%; }
        .status { font-weight:bold; padding:3px 7px; border-radius:6px; }
        .active { background:#22c55e; color:#fff; }
        .free { background:#f97316; color:#fff; }
        .team { color:#2563eb; font-weight:bold; }
    </style>
</head>
<body>
<div style="text-align:left; margin: 20px 0 0 40px;">
    <button onclick="history.back()" 
            style="background:#2563eb; color:#fff; border:none; padding:10px 18px; border-radius:8px; cursor:pointer; font-weight:600;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </button>
</div>
<h1>Hồ sơ cầu thủ</h1>

<div class="container">
    <div class="profile-box">

    <?php
    if ($tbl === -2) {
        echo "<p>Lỗi kết nối CSDL!</p>";
    } elseif ($tbl === -1) {
        echo "<p>Không tìm thấy thông tin cầu thủ!</p>";
    } else {
        $row = $tbl->fetch_assoc();
        $statusClass = ($row['status'] == 'Đang tham gia') ? "active" : "free";
    ?>

        <div class="account-avatar">
            <h2>Ảnh đại diện</h2>
            <img src="<?= !empty($row['avatar']) ? 'img/avatar/' . htmlspecialchars($row['avatar']) : 'img/avatar/default_avaplayer.jpg' ?>" 
                 alt="Avatar người dùng" class="avatar-img">
        </div>

        <div class='section'>
            <h2>Thông tin cá nhân</h2>
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
                <tr><td>Đội hiện tại</td><td class='team'><?= !empty($row['teamName']) ? htmlspecialchars($row['teamName']) : "Chưa tham gia đội nào" ?></td></tr>
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

    <?php } ?>
    </div>
</div>

</body>
</html>
