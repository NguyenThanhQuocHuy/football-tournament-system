<?php
    include_once('modelconnect.php');
    class mUser{
    //     public function mLogin ($id,$pwd){
    //         $p = new mConnect();
    //         $p -> moKetNoi();
    //         $conn = $p ->moKetNoi();
    //             if($conn==true){
    //                 $query = "select * from users where username='$id' and password='$pwd'";
    //                 $result = mysqli_query ($conn,$query);
    //                 $pwd_hashed = md5($pwd);
    //                 $p ->dongKetNoi($conn);
    //                     return $result;}
    //             else{
    //                 return false;
                
    //     }
    // }
    //     public function mRegister($email,$fullname,$username,$password) {
    //         $p = new mConnect();
    //         $conn = $p->moKetNoi();
    //         if ($conn) {
    //             // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    //             $checkQuery = "SELECT * FROM users WHERE username='$username'";
    //             $checkResult = mysqli_query($conn, $checkQuery);
    //             if ($checkResult->num_rows > 0) {
                  
    //                 $p->dongKetNoi($conn);
    //                 return false;
    //             } else {
    //                 // Chèn người dùng mới
    //                 $hashed_password = md5($password);
    //                 $insertQuery = "INSERT INTO users (email,FullName,username, password, ID_role) VALUES ('$email','$fullname', '$username', '$hashed_password', 5)";
    //                 if (mysqli_query($conn, $insertQuery)) {
    //                     // Thành công
    //                     $p->dongKetNoi($conn);
    //                     return true;
    //                 } else {
    //                     // Lỗi chèn 
    //                     $p->dongKetNoi($conn); 
    //                     return false;
    //                 }
    //             }
    //         } else {
    //             return false;
    //         }
    // }
public function mLogin($id, $pwd){
            $p = new mConnect();
            $conn = $p->moKetNoi();
            if($conn){
                // Mã hóa mật khẩu người dùng nhập
                $pwd_hashed = md5($pwd);

                $query = "SELECT * FROM users WHERE username='$id' AND password='$pwd_hashed'";
                $result = mysqli_query($conn, $query);

                $p->dongKetNoi($conn);
                return $result;
            } else {
                return false;
            }
        }

        // public function mRegister($email, $fullname, $username, $password) {
        //     $p = new mConnect();
        //     $conn = $p->moKetNoi();
        //     if ($conn) {
        //         // Kiểm tra trùng username
        //         $checkQuery = "SELECT * FROM users WHERE username='$username'";
        //         $checkResult = mysqli_query($conn, $checkQuery);
        //         if ($checkResult->num_rows > 0) {
        //             $p->dongKetNoi($conn);
        //             return false;
        //         } else {
        //             // Mã hóa mật khẩu trước khi lưu
        //             $hashed_password = md5($password);

        //             $insertQuery = "INSERT INTO users (email, FullName, username, password, ID_role) 
        //                             VALUES ('$email', '$fullname', '$username', '$hashed_password', 5)";
        //             if (mysqli_query($conn, $insertQuery)) {
        //                 $p->dongKetNoi($conn);
        //                 return true;
        //             } else {
        //                 $p->dongKetNoi($conn); 
        //                 return false;
        //             }
        //         }
        //     } else {
        //         return false;
        //     }
        
        // }
        public function mRegister($email, $fullname, $username, $password) {
    $p = new mConnect();
    $conn = $p->moKetNoi();
    if (!$conn) {
        return -2; // lỗi kết nối
    }

    // Escape đơn giản (giữ style hiện có của bạn)
    $email    = mysqli_real_escape_string($conn, $email);
    $fullname = mysqli_real_escape_string($conn, $fullname);
    $username = mysqli_real_escape_string($conn, $username);

    // 1) Check trùng username
    $checkQuery  = "SELECT 1 FROM users WHERE username='$username' LIMIT 1";
    $checkResult = mysqli_query($conn, $checkQuery);
    if ($checkResult && $checkResult->num_rows > 0) {
        $p->dongKetNoi($conn);
        return -1; // username đã tồn tại
    }

    // 2) Tạo user
    $hashed_password = md5($password);
    $insertQuery = "INSERT INTO users (email, FullName, username, password, ID_role)
                    VALUES ('$email', '$fullname', '$username', '$hashed_password', 5)";
    $ok = mysqli_query($conn, $insertQuery);

    $p->dongKetNoi($conn);
    return $ok ? 1 : 0; 
}

        public function selectUserByPhone($sdt){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "SELECT * FROM users where phone LIKE '$sdt'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
public function select01User($id){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "SELECT * FROM users where id_user LIKE '$id'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
    function updateUser($id, $username, $fullname, $email, $phone){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "UPDATE users 
            SET username='$username', 
                FullName='$fullname', 
                email='$email', 
                phone='$phone'
            WHERE id_user='$id'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
    public function updatePassword($id, $newpass) {
        $p = new mConnect();
        $conn = $p->moKetNoi();
        if ($conn) {
            $query = "UPDATE users SET password = ? WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $newpass, $id);
            $result = $stmt->execute();
            $p->dongKetNoi($conn);
            return $result;
        } else {
            return false;
        }
    }
    public function selectAllUser(){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "SELECT * FROM users u join role r on u.id_role = r.id_role";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
public function manageUpdateUser($id, $username, $fullname, $email, $phone, $id_role, $avatar){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "UPDATE users 
            SET username='$username', 
                FullName='$fullname', 
                email='$email', 
                phone='$phone',
                ID_role = '$id_role',
                avatar='$avatar'
            WHERE id_user='$id'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
public function manageInsertUser($username, $password_md5, $fullname, $email, $phone, $idrole, $avatar){
    $p = new mConnect();
    $con = $p->moKetNoi();

    if($con){
        $query = "INSERT INTO users (username, password, FullName, email, phone, ID_role, avatar)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssis", 
            $username, 
            $password_md5, 
            $fullname, 
            $email, 
            $phone, 
            $idrole, 
            $avatar
        );
        $result = $stmt->execute();
        $stmt->close();
        $p->dongketnoi($con);

        return $result;
    } else {
        return false;
    }
}

public function delete01User($id){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "DELETE FROM users where id_user='$id'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
public function selectUserByName($keyword){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "SELECT * FROM users where FullName LIKE '%$keyword%'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
function updateImageAva($id, $avatar){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "UPDATE users 
            SET avatar = '$avatar'
            WHERE id_user='$id'";
            $result = $con->query($query);
            $p->dongketnoi($con);
            return $result;
        }else{
            return false;
        }
    }
public function countuser(){
        $p = new mConnect();
        $con = $p->moKetNoi();
        if($con){
            $query = "SELECT COUNT(*) as total FROM users";
            $result = $con->query($query);
            $row = $result->fetch_assoc();
            $p->dongketnoi($con);
            return $row['total'];
        }else{
            return false;
        }
}

}
    ?>