<?php
include_once(__DIR__.'/modelconnect.php');

class mtournateam {
    public function listByTournament($idTourna){
        $p = new mConnect(); $con = $p->moKetNoi();
        if($con){
            $sql = "SELECT tt.id_tournateam, tt.reg_status, tt.reg_source,tt.registered_at,
               t.id_team, t.teamName, t.logo 
        FROM tournament_team tt
        JOIN team t ON tt.id_team = t.id_team
        WHERE tt.id_tourna = ?
        ORDER BY t.teamName";
            $stm = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stm, "i", $idTourna);
            mysqli_stmt_execute($stm);
            $res = mysqli_stmt_get_result($stm); 
            mysqli_stmt_close($stm);
            $p->dongKetNoi($con);
            return $res;
        }
        return false;
    }

    public function register($idTourna, $idTeam){
        $p = new mConnect(); $con = $p->moKetNoi();
        if($con){
            $sql = "INSERT INTO tournament_team(id_tourna, id_team, reg_status, reg_source) VALUES(?, ?, 'pending','org')";
            $stm = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stm, "ii", $idTourna, $idTeam);
            $ok  = @mysqli_stmt_execute($stm); // tránh lỗi duplicate entry
            mysqli_stmt_close($stm);
            $p->dongKetNoi($con);
            return $ok;
        }
        return false;
    }

    public function updateStatus($idTournateam, $status){
        $allowed = ['pending','approved','rejected'];
        if(!in_array($status,$allowed,true)) return false;
        $p = new mConnect(); $con = $p->moKetNoi();
        if($con){
            $sql = "UPDATE tournament_team SET reg_status=? WHERE id_tournateam=?";
            $stm = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stm, "si", $status, $idTournateam);
            $ok  = mysqli_stmt_execute($stm);
            mysqli_stmt_close($stm);
            $p->dongKetNoi($con);
            return $ok;
        }
        return false;
    }
    public function approveRegistration(int $ttId, int $adminId, bool $approve = true): bool {
        $status = $approve ? 'approved' : 'rejected';
        $conn = (new mConnect())->moKetNoi();
        $sql  = "UPDATE tournament_team
                SET reg_status = ?, approved_by = ?, approved_at = NOW()
                WHERE id_tournateam = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $status, $adminId, $ttId);
        $ok = $stmt->execute();
        $stmt->close(); $conn->close();
        return $ok;
    }
    // Phân hạt giống
public function setSeed(int $idTourna, int $idTeam, ?int $seed): bool {
    $p = new mConnect(); 
    $c = $p->moKetNoi(); 
    if (!$c) return false;

    $sql = "UPDATE tournament_team SET seed = ? WHERE id_tourna = ? AND id_team = ?";
    $st  = $c->prepare($sql);

    // MySQLi require kiểu ràng buộc → dùng i,i,i; nếu $seed null thì gán NULL bằng set_null sau:
    $seedParam = $seed === null ? null : (int)$seed;
    $st->bind_param('iii', $seedParam, $idTourna, $idTeam);

    $ok = $st->execute();
    $st->close(); 
    $c->close();
    return $ok;
}
  public function getApprovedTournamentsByTeam($teamId) {
    $p = new mConnect(); $con = $p->moKetNoi();
    $sql = "SELECT tt.id_tourna, t.tournaName, t.logo, t.banner, t.startdate, t.enddate, t.status
            FROM tournament_team tt
            JOIN tournament t ON t.idtourna = tt.id_tourna
            WHERE tt.id_team = ? AND tt.reg_status = 'approved'
            ORDER BY t.startdate DESC";
    $stm = $con->prepare($sql);
    $stm->bind_param('i', $teamId);
    $stm->execute();
    $res = $stm->get_result();
    $p->dongketnoi($con);
    return $res;
  }

  // Danh sách giải của "chủ đội" (user quản lý đội) – gom tất cả đội của user
  public function getApprovedTournamentsByOwner($ownerUserId){
    $p = new mConnect(); $con = $p->moKetNoi();
    $sql = "SELECT DISTINCT t.idtourna, t.tournaName, t.logo, t.banner, t.startdate, t.enddate, t.status
            FROM team tm
            JOIN tournament_team tt ON tt.id_team = tm.id_team AND tt.reg_status = 'approved'
            JOIN tournament t ON t.idtourna = tt.id_tourna
            WHERE tm.id_user = ?
            ORDER BY t.startdate DESC";
    $stm = $con->prepare($sql);
    $stm->bind_param('i', $ownerUserId);
    $stm->execute();
    $res = $stm->get_result();
    $p->dongketnoi($con);
    return $res;
  }

  // Lấy các đội của 1 owner (để lọc lịch theo từng đội)
  public function getTeamsByOwner($ownerUserId){
    $p = new mConnect(); $con = $p->moKetNoi();
    $sql = "SELECT id_team, teamName FROM team WHERE id_user = ? ORDER BY teamName";
    $stm = $con->prepare($sql);
    $stm->bind_param('i', $ownerUserId);
    $stm->execute();
    $res = $stm->get_result();
    $p->dongketnoi($con);
    return $res;
  }

public function getTeamRegInfo(int $idTourna, int $idTeam): ?array {
    $p = new mConnect(); $c = $p->moKetNoi(); if (!$c) return null;

    $sql = "SELECT 
              t.id_team, t.teamName, t.logo,
              u.id_user AS manager_id, u.FullName AS manager_name, u.email, u.phone,
              tt.registered_at, tt.reg_status, tt.approved_at
            FROM tournament_team tt
            JOIN team  t ON t.id_team = tt.id_team
            /* ĐỔI INNER -> LEFT để không rơi hàng khi thiếu manager */
            LEFT JOIN users u ON u.id_user = t.id_user
            WHERE tt.id_tourna = ? AND tt.id_team = ?
            LIMIT 1";
    $st  = mysqli_prepare($c, $sql);
    mysqli_stmt_bind_param($st, "ii", $idTourna, $idTeam);
    mysqli_stmt_execute($st);
    $res = mysqli_stmt_get_result($st);
    $row = $res ? $res->fetch_assoc() : null;
    mysqli_stmt_close($st); $p->dongKetNoi($c);
    return $row;
}


}
