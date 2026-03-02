<?php
include_once("modelconnect.php");

class mPlayer {
    // Lấy thông tin cầu thủ theo id_user (dựa trên người dùng đăng nhập)
    public function selectPlayerProfile($id_user) {
        $p = new mConnect();
        $conn = $p->moketnoi();

        if ($conn) {
            $query = "
                SELECT 
                    p.id_player,
                    p.position,
                    p.age,
                    p.dateOfBirth,
                    p.placeOfBirth,
                    p.height,
                    p.jersey_number,
                    p.status,
                    u.FullName,
                    u.avatar,
                    t.teamName,
                    tm.id_member
                FROM player p
                JOIN users u ON p.id_user = u.id_user
                LEFT JOIN team_member tm ON tm.id_player = p.id_player AND tm.status = 1
                LEFT JOIN team t ON tm.id_team = t.id_team
                WHERE p.id_user = ?
                LIMIT 1
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();

            $p->dongketnoi($conn);
            return $result;
        } else {
            return false;
        }
    }
    // Cập nhật thông tin cầu thủ
    public function updatePlayerProfile($id_user, $fullname, $position, $age, $dateOfBirth, $placeOfBirth, $height, $jersey_number) {
        $p = new mConnect();
        $conn = $p->moketnoi();
        if ($conn) {
            $query1 = "UPDATE users SET FullName=? WHERE id_user=?";
            $stmt1 = $conn->prepare($query1);
            $stmt1->bind_param("si", $fullname, $id_user);
            $stmt1->execute();

            $query2 = "
                UPDATE player 
                SET position=?, age=?, dateOfBirth=?, placeOfBirth=?, height=?, jersey_number=? 
                WHERE id_user=?
            ";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("sissdii", $position, $age, $dateOfBirth, $placeOfBirth, $height, $jersey_number, $id_user);
            $ok = $stmt2->execute();

            $p->dongketnoi($conn);
            return $ok;
        }
        return false;
    }
// ========================== SỰ NGHIỆP ==========================
public function getCareerHistory($id_user) {
    $p = new mConnect();
    $conn = $p->moketnoi();
    if ($conn) {
        $query = "
            SELECT 
                t.teamName, 
                t.logo,
                tm.joinTime, 
                tm.leaveTime,
                CASE 
                    WHEN tm.status = 1 THEN 'Đang tham gia'
                    ELSE 'Đã rời đội'
                END AS memberStatus
            FROM team_member tm
            JOIN player p ON tm.id_player = p.id_player
            JOIN team t ON tm.id_team = t.id_team
            WHERE p.id_user = ? AND tm.status = 0
            ORDER BY tm.joinTime DESC
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $p->dongketnoi($conn);
        return $result;
    }
    return false;
}

// ========================== THÀNH TÍCH ==========================
public function getPlayerAchievements($id_user) {
    $p = new mConnect();
    $conn = $p->moketnoi();
    if ($conn) {
        $query = "
            SELECT 
                tr.tournaName,
                COUNT(DISTINCT ma.id_match) AS matches_played,
                SUM(CASE WHEN me.event_type = 'goal' THEN 1 ELSE 0 END) AS goals_scored,
                SUM(CASE WHEN (ma.home_team_id = t.id_team AND ma.home_score > ma.away_score)
                          OR (ma.away_team_id = t.id_team AND ma.away_score > ma.home_score)
                    THEN 1 ELSE 0 END) AS wins,
                SUM(CASE WHEN ma.home_score = ma.away_score THEN 1 ELSE 0 END) AS draws,
                SUM(CASE WHEN (ma.home_team_id = t.id_team AND ma.home_score < ma.away_score)
                          OR (ma.away_team_id = t.id_team AND ma.away_score < ma.home_score)
                    THEN 1 ELSE 0 END) AS losses
            FROM player p
            JOIN team_member tm ON p.id_player = tm.id_player
            JOIN team t ON tm.id_team = t.id_team
            JOIN match_event me ON tm.id_member = me.id_member
            JOIN `match` ma ON me.id_match = ma.id_match
            JOIN tournament tr ON ma.id_tourna = tr.idtourna
            WHERE p.id_user = ?
            GROUP BY tr.idtourna, tr.tournaName
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $p->dongketnoi($conn);
        return $result;
    }
    return false;
}
public function selectPlayerProfileByIdPlayer($id_player) {
    $p = new mConnect();
    $conn = $p->moketnoi();

    if ($conn) {
        $query = "
            SELECT 
                p.id_player,
                p.position,
                p.age,
                p.dateOfBirth,
                p.placeOfBirth,
                p.height,
                p.jersey_number,
                p.status,
                u.FullName,
                u.avatar,
                t.teamName,
                tm.id_member
            FROM player p
            JOIN users u ON p.id_user = u.id_user
            LEFT JOIN team_member tm ON tm.id_player = p.id_player AND tm.status = 1
            LEFT JOIN team t ON tm.id_team = t.id_team
            WHERE p.id_player = ?
            LIMIT 1
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_player);
        $stmt->execute();
        $result = $stmt->get_result();

        $p->dongketnoi($conn);
        return $result;
    }
    return false;
}
}
?>