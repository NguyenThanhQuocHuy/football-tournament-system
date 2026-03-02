<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo tài khoản</title>
    <style>
 .form-container {
    background-color: #fff;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    width: 480px;
    margin: 60px auto; /* 🟢 Căn giữa ngang */
    animation: fadeIn 0.5s ease-in-out;
}

#tieude {
    text-align: center;
    margin-bottom: 25px;
    color: #1e293b;
    font-size: 26px;
    font-weight: 600;
    letter-spacing: 0.4px;
}

table {
    width: 100%;
}

td {
    padding: 10px 0;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #475569;
    font-weight: 500;
}

input[type="text"], select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    transition: all 0.25s;
    font-size: 15px;
}

input[type="text"]:focus, select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37, 99, 235, 0.25);
    outline: none;
}
input[type="password"], select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    transition: all 0.25s;
    font-size: 15px;
}

input[type="password"]:focus, select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 8px rgba(37, 99, 235, 0.25);
    outline: none;
}

#btnsave, #btnnhaplai {
    padding: 10px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.3s ease;
}

#btnsave {
    background-color: #2563eb;
    color: white;
    margin-right: 10px;
}

#btnsave:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
}

#btnnhaplai {
    background-color: #e2e8f0;
    color: #334155;
}

#btnnhaplai:hover {
    background-color: #cbd5e1;
    transform: translateY(-2px);
}

/* Animation nhẹ */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-15px);}
    to {opacity: 1; transform: translateY(0);}
}

/* Responsive */
@media (max-width: 600px) {
    .form-container {
        width: 90%;
        padding: 25px;
        margin: 30px auto;
    }
    #tieude {
        font-size: 22px;
    }
}#btnback {
    background-color: #f87171;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.3s ease;
}

#btnback:hover {
    background-color: #dc2626;
    transform: translateY(-2px);
}
.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.form-group label {
    width: 140px; /* độ rộng label */
    margin: 0;
    color: #475569;
    font-weight: 500;
}

.form-group input,
.form-group select {
    flex: 1;
}
    </style>
</head>
<body>
    <div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
            <h2 id="tieude">Tạo tài khoản</h2>
            <table>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="fullname"><b>Họ và Tên:</b></label>
                                <input type="text" name="fullname" id="fullname" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="username"><b>Tên tài khoản:</b></label>
                                <input type="text" name="username" id="username" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="password"><b>Mật khẩu:</b></label>
                                <input type="password" name="password" id="password" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>   
                            <div class="form-group">
                                <label for="email"><b>Email:</b></label>
                                <input type="text" name="email" id="email" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="phone"><b>Số điện thoại:</b></label>
                                <input type="text" name="phone" id="phone" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                            <label for="id_role"><b>Vai trò:</b></label>
                            <select name="id_role" id="id_role" required>
                                <?php
                                    include_once("control/controlrole.php");
                                    $p = new crole();
                                    $tblrole = $p->getAllRole();
                                    $dem = 1;
                                    if($tblrole == -2){
                                        echo "Lỗi kết nối!";
                                    }elseif($tblrole == -1){
                                        echo "Không có dữ liệu";
                                    }else{
                                        echo "<option value=''>Chọn vai trò</option>";
                                        while($row = $tblrole->fetch_assoc()){
                                            if($row["ID_role"]==$id_role){
                                                echo "<option value='".$row['ID_role']."' selected>".$row['RoleName']."</option>";
                                            }else{
                                                echo "<option value='".$row['ID_role']."'>".$row['RoleName']."</option>";
                                                $dem++;
                                            }
                                        }
                                    }
                                ?>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="fileavatar"><b>Ảnh đại diện:</b></label>
                            <input type="file" name="fileavatar" id="fileavatar" required>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <button type="button" id="btnback" title="Quay lại" onclick="history.back()">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="reset" id="btnnhaplai" title="Nhập lại">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                            <button type="submit" name="btnsave" id="btnsave" title="Thêm">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </td>
                    </tr>
            </table>
    </form>
</div>
    <?php
        include_once("control/controluser.php");
        $p = new cUser();
        if(isset($_REQUEST["btnsave"])){
            $fullname = $_REQUEST["fullname"];
            $username = $_REQUEST["username"];
            $password = $_REQUEST["password"];
            $email = $_REQUEST["email"];
            $phone = $_REQUEST["phone"];
            $idrole = $_REQUEST["id_role"];
            $fileavatar = $_FILES["fileavatar"];
            $kq = $p->manageAddUser($username, $password, $fullname, $email, $phone, $idrole, $fileavatar);
            if($kq){
                echo "<script>
                        alert('Thêm thành công!');
                        window.location.href='admin.php?page=manage_list_user';
                    </script>";
                exit();
            }else{
                echo "<script>alert('Thêm thất bại!')</script>";
            }
        }
    ?>
</body>
</html>