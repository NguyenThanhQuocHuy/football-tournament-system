<?php
require_once __DIR__ . '/../model/modeltournateam.php';

class cTournaTeam {
    public function approve(int $ttId, int $adminId): bool {
        $m = new mtournateam();
        return $m->approveRegistration($ttId, $adminId, true);
    }
    public function reject(int $ttId, int $adminId): bool {
        $m = new mtournateam();
        return $m->approveRegistration($ttId, $adminId, false);
    }
    public function getTeamRegInfo($idTourna, $idTeam) {
    $m = new mtournateam();
    return $m->getTeamRegInfo((int)$idTourna, (int)$idTeam);
}
}

?>