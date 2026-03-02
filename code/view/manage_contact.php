<?php
error_reporting(0);
include_once("control/controlcontact.php");
$p = new cContact();

// Xử lý cập nhật trạng thái
if(isset($_POST["update_status"])) {
    $id = $_POST["id_contact"];
    $status = $_POST["status"];
    $p->updateContactStatus($id, $status);
    echo "<script>
            alert('Cập nhật trạng thái thành công!');
            window.location.href='admin.php?page=manage_contact';
          </script>";
    exit;
}

$contacts = $p->getAllContact();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý liên hệ</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #eee; }
        a.btn { padding: 6px 12px; background: #28a745; color:#fff; text-decoration:none; border-radius:4px; }
        a.btn:hover { background:#218838; }
        /* Modal */
        .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; top: 0; 
        width: 100%; height: 100%; 
        overflow: auto; 
        background-color: rgba(0,0,0,0.5); 
        }
        .modal-content {
        background-color: #fefefe;
        margin: 10% auto; 
        padding: 20px;
        border: 1px solid #888;
        width: 50%; 
        border-radius: 8px;
        }
        .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        }
        .close:hover { color: black; }
    </style>
</head>
<body>

<h2>Danh sách liên hệ chờ phản hồi</h2>

<?php
$dem = 1;
if($contacts == -1){
    echo "<p>Chưa có phản hồi nào.</p>";
} else if($contacts == -2){
    echo "<p>Lỗi kết nối dữ liệu.</p>";
} else {
?>
    <table>
        <tr>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>Ngày gửi</th>
            <th>Mã người dùng</th>
            <th>Trạng thái</th>
        </tr>

        <?php while($row = $contacts->fetch_assoc()){ ?>
        <tr>
            <td><?= $dem++ ?></td>
            <td><?= $row["fullname"] ?></td>
            <td><?= $row["email"] ?></td>
            <td><?= $row["phone"] ?></td>
            <td><?= $row["title"] ?></td>
            <td>
                <?php
                    $words = explode(' ', strip_tags($row["content"]));
                    if(count($words) > 20){
                        $short_content = implode(' ', array_slice($words, 0, 20)) . '...';
                    } else {
                        $short_content = $row["content"];
                    }
                ?>
                <span id="short_<?= $row['id_contact'] ?>"><?= nl2br(htmlspecialchars($short_content)) ?></span>
                <?php if(count($words) > 20){ ?>
                    <a href="javascript:void(0);" onclick="openModal('modal_<?= $row['id_contact'] ?>')">Xem thêm</a>

                    <!-- Modal -->
                    <div id="modal_<?= $row['id_contact'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('modal_<?= $row['id_contact'] ?>')">&times;</span>
                            <h3>Nội dung chi tiết</h3>
                            <p><?= nl2br(htmlspecialchars($row["content"])) ?></p>
                        </div>
                    </div>
                <?php } ?>
            </td>
            <td><?= date("d-m-Y", strtotime($row["created_at"])) ?></td>
            <td><?= $row["id_user"] ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="id_contact" value="<?= $row['id_contact'] ?>">

                    <select name="status" onchange="toggleSaveButton(this, 'btn_<?= $row['id_contact'] ?>')">
                        <option value="Chờ phản hồi" <?= $row["status"] == "Chờ phản hồi" ? "selected" : "" ?>>Chờ phản hồi</option>
                        <option value="Đã xử lý" <?= $row["status"] == "Đã xử lý" ? "selected" : "" ?>>Đã xử lý</option>
                    </select>

                    <button type="submit" name="update_status" id="btn_<?= $row['id_contact'] ?>" style="display: <?= $row['status'] == "Đã xử lý" ? "inline-block" : "none" ?>;">Lưu</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

<?php } ?>
<script>
function openModal(id){
    document.getElementById(id).style.display = "block";
}
function closeModal(id){
    document.getElementById(id).style.display = "none";
}
// Click ngoài modal để đóng
window.onclick = function(event) {
    if(event.target.className === 'modal'){
        event.target.style.display = "none";
    }
}
function toggleSaveButton(selectEl, btnId) {
    var btn = document.getElementById(btnId);
    if(selectEl.value === "Đã xử lý") {
        btn.style.display = "inline-block";
    } else {
        btn.style.display = "none";
    }
}
</script>
</body>
</html>