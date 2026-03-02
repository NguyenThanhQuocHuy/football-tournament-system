<?php
header("Content-Type: application/json");
include_once("../control/controlplayer.php");

if (!isset($_GET["id_player"])) {
    echo json_encode([
        "status" => "error",
        "msg" => "Thiếu id_player"
    ]);
    exit;
}

$id_player = intval($_GET["id_player"]);

$p = new cPlayer();
$res = $p->cgetPlayerProfileByIdPlayer($id_player);

if (!$res || $res->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "msg" => "Không tìm thấy thông tin cầu thủ"
    ]);
    exit;
}

$row = $res->fetch_assoc();

echo json_encode([
    "status" => "success",
    "profile" => $row
]);