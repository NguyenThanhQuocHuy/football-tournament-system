<?php
include_once(__DIR__ . '/../model/modelplayer.php');

class cPlayer {
    public function getPlayerProfile($id_user) {
        $m = new mPlayer();
        $tbl = $m->selectPlayerProfile($id_user);

        if ($tbl == false) {
            return -2; // lỗi kết nối
        } else {
            if ($tbl->num_rows > 0) {
                return $tbl;
            } else {
                return -1; // không tìm thấy
            }
        }
    }
    // Gọi model để cập nhật
    public function updatePlayerProfile($id_user, $fullname, $position, $age, $dateOfBirth, $placeOfBirth, $height, $jersey_number) {
        $m = new mPlayer();
        $result = $m->updatePlayerProfile($id_user, $fullname, $position, $age, $dateOfBirth, $placeOfBirth, $height, $jersey_number);
        return $result ? 1 : 0;
    }
public function getCareerHistory($id_user) {
    $m = new mPlayer();
    $tbl = $m->getCareerHistory($id_user);
    if ($tbl == false) return -2;
    elseif ($tbl->num_rows > 0) return $tbl;
    else return -1;
}

public function getPlayerAchievements($id_user) {
    $m = new mPlayer();
    $tbl = $m->getPlayerAchievements($id_user);
    if ($tbl == false) return -2;
    elseif ($tbl->num_rows > 0) return $tbl;
    else return -1;
}
public function cgetPlayerProfileByIdPlayer($id_player) {
        $p = new mPlayer();
        return $p->selectPlayerProfileByIdPlayer($id_player);
    }
}
?>