<?php
include_once("control/controlteammember.php");
$p = new cteamMember();

if (isset($_REQUEST["id"])) {
    $tblMember = $p->get01TeamMember($_REQUEST["id"]);
} else {
    $tblMember = $p->getAllTeamMember();
}

$dem = 1;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta chartblMemberset="UTF-8">
    <title>Danh sách thành viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        h1 {
            text-align: center;
            color: #0078ff;
            margin: 20px 0;
            font-family: "Segoe UI", Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            font-family: "Segoe UI", Arial, sans-serif;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: center;
        }

        th {
            background-color: #0078ff;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e8f0ff;
        }

        a {
            color: #0078ff;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }
        .nav{display:flex;gap:10px;padding:8px;background:#f3f3f3;border:1px solid #ddd}
        .nav a{text-decoration:none;color:#333;padding:6px 10px;border:1px solid #ccc;border-radius:4px}
        .nav a.active{background:#2563eb;color:#fff;border-color:#2563eb}
    </style>
</head>
<body>
    <?php
// Giả sử bạn lấy idteam từ GET hoặc từ DB
$idteam = isset($_GET['id']) ? $_GET['id'] : 0;  
$teamCount = isset($teamCount) ? $teamCount : 0; // nếu có giá trị trước đó
?>
    <div class="nav">
        <a href="dashboard.php?page=update_team&id=<?php echo $idteam;?>">Cấu hình</a>
        <a href="dashboard.php?page=dash_team_member&id=<?php echo $idteam;?>">Thành viên</a>
    </div>
<?php
echo '<a href="dashboard.php?page=add_member&id_team=' . $idteam . '">+ Thêm thành viên</a>';
if ($tblMember === -2) {
    echo "Không thể kết nối!";
} elseif ($tblMember === -1) {
    echo "Không có dữ liệu!";
} else {
    echo "<h1>Danh sách thành viên</h1>";
    echo "<table>
        <tr>
            <th>STT</th>
            <th>Tên cầu thủ</th>
            <th>Vị trí chơi</th>
            <th>Tuổi</th>
            <th>Số điện thoại</th>
            <th>Ngày gia nhập</th>
            <th>Chức vụ</th>
            <th>Hồ sơ</th>
            <th>Thao tác</th>
        </tr>";

    while ($row = $tblMember->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $dem . "</td>";
        echo "<td>" . htmlspecialchars($row['FullName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['position']) . "</td>";
        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
        echo "<td>" . date("Y-m-d", strtotime($row['joinTime'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['roleInTeam']) . "</td>";
        $id_user = isset($row['id_user']) ? $row['id_user'] : 0;
echo '<td>';
if($id_user){
    echo '<a href="dashboard.php?page=player_profile_team&id_user=' . $id_user . '" 
          style="color:#2563eb; text-decoration:none;">
          Xem hồ sơ
          </a>';
} else {
    echo '-';
}
echo '</td>';
        echo '
        <td>
            <a href="dashboard.php?page=edit_member&id=' . $row['id_member'] . '" 
            title="Cập nhật" 
            style="color: #0d6efd; margin-right: 10px; font-size: 18px;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <a href="dashboard.php?page=delete_member&id=' . $row['id_member'] . '" 
            title="Loại khỏi đội" 
            style="color: #dc3545; font-size: 18px;"
            onclick="return confirm(\'Bạn có chắc muốn loại thành viên này không?\');">
                <i class="fa-solid fa-user-slash"></i>
            </a>
        </td>';
        echo "</tr>";
        $dem++;
    }
    echo "</table>";
}
?>
</body>
</html>