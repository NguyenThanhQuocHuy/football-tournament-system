<?php
require_once __DIR__ . '/../model/modeltourna.php';
require_once __DIR__ . '/../control/controltourna.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>alert('Thiếu ID giải'); window.location='dashboard.php?page=man_tourna';</script>";
    exit;
}

$m  = new mTourna();
$row = $m->getTournamentById($id);

if (!$row) {
    echo "<script>alert('Giải không tồn tại'); window.location='dashboard.php?page=man_tourna';</script>";
    exit;
}

// NẾU ĐÃ FINISHED → KHÔNG CHO XÓA
if ((int)($row['status'] ?? 0) === 3) {
    echo "<script>alert('Giải đã được khóa, không thể xóa'); window.location='dashboard.php?page=man_tourna';</script>";
    exit;
}

// Chưa khóa thì cho xóa
$c = new cTourna();
$ok = $c->deleteTourna($id);

if ($ok) {
    echo "<script>alert('Đã xóa giải'); window.location='dashboard.php?page=man_tourna';</script>";
} else {
    echo "<script>alert('Xóa thất bại'); window.location='dashboard.php?page=man_tourna';</script>";
}
