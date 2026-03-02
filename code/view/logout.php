<?php
session_start();
$_SESSION = [];
session_destroy();
echo "<script>alert('Đăng xuất thành công!');</script>";
echo "<script>window.location.href = 'index.php';</script>";
exit;


?>
