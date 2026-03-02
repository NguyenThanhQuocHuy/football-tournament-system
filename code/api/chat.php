<?php
// api/chat.php
require_once __DIR__ . '/ai.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
mb_internal_encoding('UTF-8');

/** dùng lại kết nối trong project của bạn */
require_once __DIR__ . '/../model/modelconnect.php';
$p = new mConnect();
$conn = $p->moKetNoi();
if (!$conn) { http_response_code(500); echo json_encode(['ok'=>false,'answer'=>'Không kết nối được DB']); exit; }

/** helpers */
function q($s){ global $conn; return mysqli_real_escape_string($conn, $s); }
function firstRow($res){ return ($res && $res->num_rows) ? $res->fetch_assoc() : null; }
function rows($res){ $out=[]; if($res){ while($r=$res->fetch_assoc()) $out[]=$r; } return $out; }
/* ===== URL builders (đổi nếu route của bạn khác) ===== */
function url_tourna($id){   return "view/tourna_detail.php?id=".$id; }
function url_schedule($id){ return url_tourna($id)."#lich"; }   // lịch/kết quả
function url_bxh($id){      return url_tourna($id)."#bxh"; }    // BXH/thống kê
function url_rules($id){    return url_tourna($id)."#rules"; }  // điều lệ
function url_team($id){     return "view/team_detail.php?id=".$id; }   



/** nhận input */
$input = json_decode(file_get_contents('php://input'), true);
$msg      = trim($input['message'] ?? '');
$tournaId = isset($input['tourna_id']) ? (int)$input['tourna_id'] : 0;
$teamId   = isset($input['team_id']) ? (int)$input['team_id'] : 0;
if ($msg===''){ echo json_encode(['ok'=>false,'answer'=>'Bạn hãy nhập câu hỏi nhé.']); exit; }

/** ————— Chuẩn hoá + fuzzy ————— */
function vn_strip_accents($str){
  $acc = ['à'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
  'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a','è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
  'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i','ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
  'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o','ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
  'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y','đ'=>'d',
  'À'=>'A','Á'=>'A','Ả'=>'A','Ã'=>'A','Ạ'=>'A','Ă'=>'A','Ằ'=>'A','Ắ'=>'A','Ẳ'=>'A','Ẵ'=>'A','Ặ'=>'A',
  'Â'=>'A','Ầ'=>'A','Ấ'=>'A','Ẩ'=>'A','Ẫ'=>'A','Ậ'=>'A','È'=>'E','É'=>'E','Ẻ'=>'E','Ẽ'=>'E','Ẹ'=>'E','Ê'=>'E','Ề'=>'E','Ế'=>'E','Ể'=>'E','Ễ'=>'E','Ệ'=>'E',
  'Ì'=>'I','Í'=>'I','Ỉ'=>'I','Ĩ'=>'I','Ị'=>'I','Ò'=>'O','Ó'=>'O','Ỏ'=>'O','Õ'=>'O','Ọ'=>'O','Ô'=>'O','Ồ'=>'O','Ố'=>'O','Ổ'=>'O','Ỗ'=>'O','Ộ'=>'O',
  'Ơ'=>'O','Ờ'=>'O','Ớ'=>'O','Ở'=>'O','Ỡ'=>'O','Ợ'=>'O','Ù'=>'U','Ú'=>'U','Ủ'=>'U','Ũ'=>'U','Ụ'=>'U','Ư'=>'U','Ừ'=>'U','Ứ'=>'U','Ử'=>'U','Ữ'=>'U','Ự'=>'U',
  'Ỳ'=>'Y','Ý'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y','Ỵ'=>'Y','Đ'=>'D'];
  return strtr($str,$acc);
}
function normalize($s){
  $s = mb_strtolower(trim($s));
  $s = vn_strip_accents($s);
  $s = preg_replace('/[^a-z0-9\s]/u',' ',$s);
  $s = preg_replace('/\s+/',' ',$s);
  return $s;
}
function fuzzy_has($text, array $kws, $threshold=0.75){
  $t = normalize($text);
  foreach($kws as $kw){
    $k = normalize($kw);

    // 1) nếu keyword ngắn (<=2 ký tự) thì phải match đúng từ (word-boundary)
    if (mb_strlen($k) <= 2) {
      if (preg_match('/\b'.preg_quote($k,'/').'\b/u', $t)) return true;
      continue;
    }
    // 2) bình thường: ưu tiên từ đầy đủ
    if (preg_match('/\b'.preg_quote($k,'/').'\b/u', $t)) return true;

    // 3) fuzzy nhẹ
    similar_text($t,$k,$perc);
    if (($perc/100) >= $threshold) return true;
  }
  return false;
}


/** ————— Từ khoá intent ————— */
$KW_SCHEDULE = ['lich','lich thi dau','lich giai','lich doi','tran sau','tiep theo','next match'];
$KW_OPPONENT = ['doi thu','gap ai','vs ai','gap doi nao','doi nao'];
$KW_RESULT   = ['ti so','score','tran truoc','gan nhat','result'];
$KW_TABLE    = ['bxh','bang xep hang','xep hang','ranking','diem so','standings','ketqua'];
$KW_RULES    = ['dieu le','luat','the le','rule','quy dinh','the thuc'];
$KW_TOURNA   = ['giai','tournament','thong tin giai'];
$KW_TOURNA_IN_MONTH = ['co bao nhieu giai trong thang','so giai trong thang']; 
$KW_TEAM     = ['doi','thong tin doi','doi hinh','doi co bao nhieu','doi thuoc giai'];
$KW_PLAYER   = ['cau thu','thong tin cau thu','vi tri','age','ban thang','top scorer','vua pha luoi'];
$KW_HELLO = ['xin chao','chao','hello','chao ban','hey','hi'];
$KW_BYE   = ['tam biet','bye','goodbye','hen gap','see you'];
$KW_THANK = ['cam on','thanks','thank you','tks','tnx'];
$KW_HELP  = ['huong dan','giup','help','lam gi duoc','hoi cai gi'];
$KW_PARTICIPANTS = ['doi tham gia','danh sach doi','cac doi tham gia','bao nhieu doi','doi nao tham gia'];

// ---- Detect intent ----
$intent='unknown';
if (fuzzy_has($msg,$KW_HELLO))              $intent='hello';
elseif (fuzzy_has($msg,$KW_BYE))             $intent='bye';
elseif (fuzzy_has($msg,$KW_THANK))           $intent='thanks';
elseif (fuzzy_has($msg,$KW_HELP))            $intent='help';
elseif (fuzzy_has($msg,$KW_SCHEDULE)) {
  // phân biệt: có chữ "giai" -> lịch giải, có "doi" -> lịch đội, mặc định -> lịch trận kế tiếp của đội
  if (preg_match('/\bgiai\b/u', $norm))      $intent='schedule_tourna';
  elseif (preg_match('/\bdoi\b/u', $norm))   $intent='schedule_team';
  else                                       $intent='schedule_next';
}
elseif (fuzzy_has($msg,$KW_OPPONENT))        $intent='opponent_next';
elseif (fuzzy_has($msg,$KW_RESULT))          $intent='result_last';
elseif (fuzzy_has($msg,$KW_TABLE))           $intent='standings';
elseif (fuzzy_has($msg,$KW_RULES))           $intent='rules';
elseif (fuzzy_has($msg,$KW_TOURNA))          $intent='tourna_info';
elseif (fuzzy_has($msg,$KW_PARTICIPANTS))    $intent='tourna_teams';
elseif (fuzzy_has($msg,$KW_TOURNA_IN_MONTH)) $intent='count_tourna_in_month';
elseif (fuzzy_has($msg,$KW_TEAM))            $intent='team_info';
elseif (fuzzy_has($msg,$KW_PLAYER))          $intent='player_info';
/** ————— Entity find theo schema của bạn ————— */
function findTournaIdByName($name){
  global $conn;
  $sql="SELECT idtourna FROM tournament WHERE tournaName LIKE '%".q($name)."%' ORDER BY idtourna DESC LIMIT 1";
  $r=mysqli_query($conn,$sql); $row=firstRow($r); return $row? (int)$row['idtourna'] : 0;
}
function findTeamIdByName($name,$tournaId=0){
  global $conn;
  if ($tournaId>0){
    $sql="SELECT t.id_team FROM team t
          JOIN tournament_team tt ON tt.id_team=t.id_team AND tt.id_tourna=$tournaId AND tt.reg_status='approved'
          WHERE t.teamName LIKE '%".q($name)."%' ORDER BY t.teamName LIMIT 1";
  } else {
    $sql="SELECT id_team FROM team WHERE teamName LIKE '%".q($name)."%' ORDER BY teamName LIMIT 1";
  }
  $r=mysqli_query($conn,$sql); $row=firstRow($r); return $row? (int)$row['id_team'] : 0;
}
function findPlayerByName($name){
  global $conn;
  $sql="SELECT p.id_player, u.FullName, p.position, p.age
        FROM player p JOIN users u ON u.id_user=p.id_user
        WHERE u.FullName LIKE '%".q($name)."%' LIMIT 1";
  $r=mysqli_query($conn,$sql); return firstRow($r);
}

/** ————— Query chính ————— */
// Lịch của GIẢI
function scheduleOfTourna($tournaId, $limit=10){
  global $conn;
  $sql="SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
        FROM `match` m
        LEFT JOIN team th ON th.id_team=m.home_team_id
        LEFT JOIN team ta ON ta.id_team=m.away_team_id
        WHERE m.id_tourna=$tournaId
        ORDER BY m.kickoff_date, m.kickoff_time
        LIMIT ".(int)$limit;
  return rows(mysqli_query($conn,$sql));
}

// Lịch của ĐỘI (có thể lọc theo giải)
function scheduleOfTeam($teamId,$tournaId=0,$limit=8){
  global $conn;
  $condTour = $tournaId>0 ? " AND m.id_tourna=$tournaId" : "";
  $sql="SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
        FROM `match` m
        LEFT JOIN team th ON th.id_team=m.home_team_id
        LEFT JOIN team ta ON ta.id_team=m.away_team_id
        WHERE (m.home_team_id=$teamId OR m.away_team_id=$teamId) $condTour
        ORDER BY m.kickoff_date, m.kickoff_time
        LIMIT ".(int)$limit;
  return rows(mysqli_query($conn,$sql));
}

// Danh sách đội của một giải
function teamsOfTourna($tournaId){
  global $conn;
  $sql="SELECT t.teamName
        FROM tournament_team tt
        JOIN team t ON t.id_team=tt.id_team
        WHERE tt.id_tourna=$tournaId AND tt.reg_status='approved'
        ORDER BY t.teamName";
  return rows(mysqli_query($conn,$sql));
}

// Parse "tháng 11 [năm 2025]"
function parseMonthYear($norm,&$month,&$year){
  $now = time();
  $year = (int)date('Y', $now);


  if (preg_match('/thang\s*([0-9]{1,2})(?:\s*nam\s*([0-9]{4}))?/u', $norm, $m)) {
    $month = (int)$m[1];
    if (!empty($m[2])) $year = (int)$m[2];
    return true;
  }

  // "tháng này" | "trong tháng"
  if (preg_match('/(thang\s*nay|trong\s*thang)\b/u', $norm)) {
    $month = (int)date('n', $now);
    return true;
  }

  // "tháng sau" | "tháng tới"
  if (preg_match('/thang\s*(sau|toi)\b/u', $norm)) {
    $ts = strtotime('+1 month', $now);
    $month = (int)date('n', $ts);
    $year  = (int)date('Y', $ts);
    return true;
  }

  return false;
}

// Đếm (và lấy danh sách) giải diễn ra trong tháng
function countTournaInMonth($month,$year=null){
  global $conn;
  $y = $year ?: (int)date('Y');
  $start = sprintf('%04d-%02d-01',$y,$month);
  $end   = date('Y-m-t', strtotime($start));
  $sql="SELECT idtourna, tournaName FROM tournament
        WHERE startdate <= '$end' AND enddate >= '$start'
        ORDER BY startdate";
  return rows(mysqli_query($conn,$sql));
}

function nextMatchOfTeam($teamId,$tournaId=0){
  global $conn;
  $condTour = $tournaId>0 ? " AND m.id_tourna=$tournaId" : "";
  $sql="SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
        FROM `match` m
        JOIN team th ON th.id_team=m.home_team_id
        JOIN team ta ON ta.id_team=m.away_team_id
        WHERE (m.home_team_id=$teamId OR m.away_team_id=$teamId)
          $condTour AND (m.status='scheduled' OR m.status IS NULL)
        ORDER BY m.kickoff_date ASC, m.kickoff_time ASC
        LIMIT 1";
  $r=mysqli_query($conn,$sql);            // ← thêm
  return firstRow($r);                    // ← thêm
}

function lastResultOfTeam($teamId,$tournaId=0){
  global $conn;
  $condTour = $tournaId>0 ? " AND m.id_tourna=$tournaId" : "";
  $sql="SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
        FROM `match` m
        JOIN team th ON th.id_team=m.home_team_id
        JOIN team ta ON ta.id_team=m.away_team_id
        WHERE (m.home_team_id=$teamId OR m.away_team_id=$teamId)
          $condTour AND m.status='played'
        ORDER BY m.kickoff_date DESC, m.kickoff_time DESC
        LIMIT 1";
  $r=mysqli_query($conn,$sql); return firstRow($r);
}
function standingsOfTourna($tournaId){
  global $conn;
  $rw = firstRow(mysqli_query($conn,"SELECT r.pointwin, r.pointdraw, r.pointloss
    FROM tournament t LEFT JOIN rule r ON r.id_rule=t.id_rule WHERE t.idtourna=$tournaId"));
  $pW = ($rw && $rw['pointwin']!==null) ? (int)$rw['pointwin'] : 3;
  $pD = ($rw && $rw['pointdraw']!==null) ? (int)$rw['pointdraw'] : 1;
  $pL = ($rw && $rw['pointloss']!==null) ? (int)$rw['pointloss'] : 0;

  $sql="SELECT t.id_team, t.teamName,
           SUM(CASE WHEN m.status='played' AND ((m.home_team_id=t.id_team AND m.home_score>m.away_score) OR (m.away_team_id=t.id_team AND m.away_score>m.home_score)) THEN 1 ELSE 0 END) AS W,
           SUM(CASE WHEN m.status='played' AND (m.home_score=m.away_score) THEN 1 ELSE 0 END) AS D,
           SUM(CASE WHEN m.status='played' AND ((m.home_team_id=t.id_team AND m.home_score<m.away_score) OR (m.away_team_id=t.id_team AND m.away_score<m.home_score)) THEN 1 ELSE 0 END) AS L,
           SUM(CASE WHEN m.status='played' AND m.home_team_id=t.id_team THEN m.home_score
                    WHEN m.status='played' AND m.away_team_id=t.id_team THEN m.away_score ELSE 0 END) AS GF,
           SUM(CASE WHEN m.status='played' AND m.home_team_id=t.id_team THEN m.away_score
                    WHEN m.status='played' AND m.away_team_id=t.id_team THEN m.home_score ELSE 0 END) AS GA
        FROM team t
        JOIN tournament_team tt ON tt.id_team=t.id_team AND tt.id_tourna=$tournaId AND tt.reg_status='approved'
        LEFT JOIN `match` m ON m.id_tourna=$tournaId AND (m.home_team_id=t.id_team OR m.away_team_id=t.id_team)
        GROUP BY t.id_team, t.teamName";
  $res=mysqli_query($conn,$sql);
  $data=[];
  while($r=$res->fetch_assoc()){
    $r['W']=(int)$r['W']; $r['D']=(int)$r['D']; $r['L']=(int)$r['L'];
    $r['GF']=(int)$r['GF']; $r['GA']=(int)$r['GA']; $r['GD']=$r['GF']-$r['GA'];
    $r['Pts']=$r['W']*$pW + $r['D']*$pD + $r['L']*$pL;
    $data[]=$r;
  }
  usort($data,function($a,$b){
    foreach(['Pts','GD','GF'] as $k){ if($a[$k]==$b[$k]) continue; return ($a[$k]<$b[$k])?1:-1; }
    return strcasecmp($a['teamName'],$b['teamName']);
  });
  return $data;
}
function rulesText($tournaId){
  global $conn;
  if ($tournaId>0){
    $r = firstRow(mysqli_query($conn,"SELECT title,content FROM doc_page WHERE tourna_id=$tournaId ORDER BY id DESC LIMIT 1"));
    if ($r) return $r['title'].":\n".$r['content'];
    $t = firstRow(mysqli_query($conn,"SELECT tournaName, regulation_summary FROM tournament WHERE idtourna=$tournaId"));
    if ($t && !empty($t['regulation_summary'])) return $t['tournaName']."\n".$t['regulation_summary'];
  }
  return "Chưa có điều lệ lưu.";
}
function tournaInfo($tournaId){
  global $conn;
  $sql="SELECT t.*, l.LocalName, l.Address, r.rulename, r.ruletype, r.rr_rounds, r.pointwin, r.pointdraw, r.pointloss
        FROM tournament t
        LEFT JOIN location l ON l.id_local=t.id_local
        LEFT JOIN rule r ON r.id_rule=t.id_rule
        WHERE t.idtourna=$tournaId";
  return firstRow(mysqli_query($conn,$sql));
}
function teamInfo($teamId){
  global $conn;
  $team = firstRow(mysqli_query($conn,"SELECT * FROM team WHERE id_team=$teamId"));
  $tours= rows(mysqli_query($conn,"SELECT tt.id_tourna, tr.tournaName
                                   FROM tournament_team tt JOIN tournament tr ON tr.idtourna=tt.id_tourna
                                   WHERE tt.id_team=$teamId AND tt.reg_status='approved'
                                   ORDER BY tr.startdate DESC"));
  $members = rows(mysqli_query($conn,"SELECT tm.id_member, u.FullName, p.position, p.age, tm.roleInTeam
                                      FROM team_member tm
                                      JOIN player p ON p.id_player=tm.id_player
                                      JOIN users u ON u.id_user=p.id_user
                                      WHERE tm.id_team=$teamId
                                      ORDER BY u.FullName ASC"));
  return ['team'=>$team,'tournaments'=>$tours,'members'=>$members];
}
function topScorers($tournaId,$limit=10){
  global $conn;
  $sql="SELECT u.FullName, t.teamName,
              SUM(CASE WHEN me.event_type IN ('goal','penalty_goal') THEN 1 ELSE 0 END) AS goals,
              SUM(CASE WHEN me.event_type='own_goal' THEN 1 ELSE 0 END) AS own_goals
        FROM match_event me
        JOIN `match` m ON m.id_match=me.id_match AND m.id_tourna=$tournaId
        JOIN team_member tm ON tm.id_member=me.id_member
        JOIN team t ON t.id_team=tm.id_team
        JOIN player p ON p.id_player=tm.id_player
        JOIN users u ON u.id_user=p.id_user
        GROUP BY u.FullName, t.teamName
        HAVING goals>0 OR own_goals>0
        ORDER BY goals DESC, own_goals DESC, u.FullName
        LIMIT ".(int)$limit;
  return rows(mysqli_query($conn,$sql));
}
function playerProfile($playerId,$tournaId=0){
  global $conn;
  $core = firstRow(mysqli_query($conn,"SELECT p.id_player, p.position, p.age, u.FullName
                                       FROM player p JOIN users u ON u.id_user=p.id_user
                                       WHERE p.id_player=$playerId"));
  $teams = rows(mysqli_query($conn,"SELECT t.id_team, t.teamName, tm.roleInTeam, tm.joinTime
                                    FROM team_member tm JOIN team t ON t.id_team=tm.id_team
                                    WHERE tm.id_player=$playerId"));
  $condTour = $tournaId>0? " AND m.id_tourna=$tournaId" : "";
  $stats = firstRow(mysqli_query($conn,"SELECT
             SUM(CASE WHEN me.event_type IN ('goal','penalty_goal') THEN 1 ELSE 0 END) AS goals,
             SUM(CASE WHEN me.event_type='own_goal' THEN 1 ELSE 0 END) AS own_goals
           FROM match_event me
           JOIN `match` m ON m.id_match=me.id_match $condTour
           JOIN team_member tm ON tm.id_member=me.id_member
           WHERE tm.id_player=$playerId"));
  return ['core'=>$core,'teams'=>$teams,'stats'=>[
    'goals'=>(int)($stats['goals']??0),
    'own_goals'=>(int)($stats['own_goals']??0)
  ]];
}
/* ruletype của giải → 'knockout' hay 'roundrobin' (tuỳ schema r.ruletype) */
function getRuleType($tournaId){
  global $conn;
  $row = firstRow(mysqli_query($conn,"SELECT r.ruletype FROM tournament t 
    LEFT JOIN rule r ON r.id_rule=t.id_rule WHERE t.idtourna=".$tournaId));
  return $row && !empty($row['ruletype']) ? strtolower($row['ruletype']) : '';
}

/* Lấy Vô địch/Á quân/Hạng 3 cho giải KO.
   Ưu tiên: cột trong bảng tournament (nếu bạn có), sau đó suy từ trận chung kết. */
function knockoutSummary($tournaId){
  global $conn;

  // Lấy danh sách cột hiện có ở bảng tournament
  $hasCols = [];
  $res = mysqli_query(
    $conn,
    "SELECT COLUMN_NAME
     FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME   = 'tournament'
       AND COLUMN_NAME IN ('champion_team_id','runnerup_team_id','third_team_id')"
  );
  if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
      $hasCols[$r['COLUMN_NAME']] = true;
    }
  }

  $out = ['champion'=>null,'runnerup'=>null,'third'=>null];

  // 1) Nếu có các cột champion/runner/third → lấy trực tiếp
  if (!empty($hasCols)) {
    $sel = "SELECT "
         . (!empty($hasCols['champion_team_id']) ? "champion_team_id" : "NULL") . " AS champion_team_id, "
         . (!empty($hasCols['runnerup_team_id']) ? "runnerup_team_id" : "NULL") . " AS runnerup_team_id, "
         . (!empty($hasCols['third_team_id'])    ? "third_team_id"    : "NULL") . " AS third_team_id
            FROM tournament WHERE idtourna=".(int)$tournaId;

    $t = firstRow(mysqli_query($conn, $sel));
    if ($t) {
      $getName = function($id) use ($conn) {
        $id = (int)$id; if ($id<=0) return null;
        $row = firstRow(mysqli_query($conn, "SELECT teamName FROM team WHERE id_team=".$id));
        return $row['teamName'] ?? null;
      };
      $out['champion'] = $getName($t['champion_team_id'] ?? 0);
      $out['runnerup'] = $getName($t['runnerup_team_id'] ?? 0);
      $out['third']    = $getName($t['third_team_id']    ?? 0);
      if ($out['champion'] || $out['runnerup'] || $out['third']) return $out;
    }
  }

  // 2) Suy ra từ trận có round/time lớn nhất ĐÃ ĐÁ (đủ tốt cho demo)
  $candidates = [
    "SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
     FROM `match` m
     JOIN team th ON th.id_team=m.home_team_id
     JOIN team ta ON ta.id_team=m.away_team_id
     WHERE m.id_tourna={$tournaId} AND m.status='played'
     ORDER BY m.round_no DESC, m.kickoff_date DESC, m.kickoff_time DESC
     LIMIT 1",
    "SELECT m.*, th.teamName AS home_name, ta.teamName AS away_name
     FROM `match` m
     JOIN team th ON th.id_team=m.home_team_id
     JOIN team ta ON ta.id_team=m.away_team_id
     WHERE m.id_tourna={$tournaId} AND m.status='played'
     ORDER BY m.kickoff_date DESC, m.kickoff_time DESC
     LIMIT 1"
  ];
  $final = null;
  foreach ($candidates as $q) {
    $row = firstRow(mysqli_query($conn, $q));
    if ($row) { $final = $row; break; }
  }
  if (!$final) return $out; // chưa có trận đã đá

  $hs = (int)$final['home_score']; $as = (int)$final['away_score'];
  if ($hs > $as) { $out['champion'] = $final['home_name']; $out['runnerup'] = $final['away_name']; }
  elseif ($as > $hs) { $out['champion'] = $final['away_name']; $out['runnerup'] = $final['home_name']; }
  // Hạng 3: cần thêm luật riêng (trận tranh 3/4) → sẽ bổ sung nếu bạn có field.

  return $out;
}



/** ————— extract entity từ câu hỏi ————— */
$norm = normalize($msg);
if (!$tournaId && preg_match('/giai\s+([a-z0-9\s]+)/u',$norm,$m)){ $tournaId = findTournaIdByName(trim($m[1])); }
if (!$teamId && preg_match('/\bdoi\s+([a-z0-9\s]+?)(?:\s+lich|\s|$)/u', $norm, $m)) {
  $teamId = findTeamIdByName(trim($m[1]), $tournaId);
}
$playerByName=null;
if (preg_match('/cau thu\s+([a-z0-9\s]+)/u',$norm,$m)){ $playerByName = findPlayerByName(trim($m[1])); }
//
// if (preg_match('/\blich\b.*\bgiai\b/u',$norm) || ($tournaId>0 && preg_match('/\blich\b/u',$norm))) {
//   $intent = 'schedule_tourna';
// } elseif (preg_match('/\blich\b.*\bdoi\b/u',$norm)) {
//   $intent = 'schedule_team';
// } elseif (preg_match('/\b(cac\s+doi|doi\s+nao)\b.*\bgiai\b/u',$norm)) {
//   $intent = 'tourna_teams';
// }
if (preg_match('/\blich\b.*\bgiai\b/u', $norm) || ($tournaId>0 && preg_match('/\blich\b/u',$norm))) {
  $intent = 'schedule_tourna';
} elseif (preg_match('/\b(lich\s+cua\s+doi|lich\s+doi|doi\s+.+\s+lich)\b/u', $norm)) {
  $intent = 'schedule_team';
} elseif (preg_match('/\b(cac\s+doi|doi\s+nao)\b.*\bgiai\b/u', $norm)) {
  $intent = 'tourna_teams';
}
/* === Fallback AI khi không nhận diện được intent === */
if ($intent === 'unknown') {
  // ngữ cảnh rất ngắn cho có “mùi” dữ liệu
  $context = '';
  if ($t = firstRow(mysqli_query($conn, "SELECT idtourna, tournaName FROM tournament ORDER BY idtourna DESC LIMIT 1"))) {
    $context = "Giải gần đây: {$t['tournaName']} (ID {$t['idtourna']}).";
  }

    $ai = call_ai($msg, $context);
  if (!empty($ai['ok'])) {
    echo json_encode(['ok'=>true, 'answer'=>$ai['answer']]); 
    exit;
  }
    echo json_encode(['ok'=>false, 'answer'=>$ai['answer'] ?? 'AI fail']);
    exit;
}
/** ————— trả lời ————— */
try{
  switch($intent){
    case 'hello':
  echo json_encode(['ok'=>true,'answer'=>"Chào bạn 👋 Mình là TournamentBot.\nBạn cần xem lịch/kết quả đội, BXH/điều lệ giải, đội hay cầu thủ?\nHãy thử: \"đội Golden Tigers\", \"Kết quả giải 11111\"."]); 
  break;

case 'bye':
  echo json_encode(['ok'=>true,'answer'=>"Tạm biệt bạn! 👋 Chúc một ngày nhiều năng lượng. Khi cần, gõ mình là có dữ liệu ngay."]);
  break;

case 'thanks':
  echo json_encode(['ok'=>true,'answer'=>"Rất vui được hỗ trợ bạn 🙌 Nếu cần thêm, cứ hỏi mình ngay nhé!"]);
  break;

case 'help':
  $g = "Mẹo nhanh ✨\n• đội <Tên đội> lịch / kết quả\n• BXH/điều lệ giải <Mã giải>\n• đội <Tên đội> \n• vua phá lưới giải <Mã giải> / cầu thủ <Họ tên>";
  echo json_encode(['ok'=>true,'answer'=>$g]); 
  break;

    case 'schedule_next':
    case 'opponent_next':
      if ($teamId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem lịch của đội nào? Ví dụ: đội Golden Tigers lịch']); break; }
      $m = nextMatchOfTeam($teamId,$tournaId);
      if (!$m){ echo json_encode(['ok'=>true,'answer'=>'Chưa thấy trận sắp tới.']); break; }
      $when = ($m['kickoff_date']? date('d-m-Y', strtotime($m['kickoff_date'])):'?') . ($m['kickoff_time']? ' '.substr($m['kickoff_time'],0,5):'');
      $ans  = "Trận sắp tới: {$m['home_name']} vs {$m['away_name']} • $when";
      if (!empty($m['venue'])) $ans.=" • Sân: {$m['venue']}";
      echo json_encode(['ok'=>true,'answer'=>$ans]); break;

    case 'result_last':
      if ($teamId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem kết quả đội nào? Ví dụ: đội Blue Dragon United kết quả']); break; }
      $m = lastResultOfTeam($teamId,$tournaId);
      if (!$m){ echo json_encode(['ok'=>true,'answer'=>'Chưa có kết quả đã đá.']); break; }
      $score = "{$m['home_name']} {$m['home_score']} - {$m['away_score']} {$m['away_name']}";
      $when  = ($m['kickoff_date']? date('d-m-Y', strtotime($m['kickoff_date'])):'');
      echo json_encode(['ok'=>true,'answer'=>"Kết quả gần nhất: $score".($when?" • $when":"")]); break;

case 'standings':
  if ($tournaId<=0){ 
    echo json_encode(['ok'=>true,'answer'=>'Bạn hãy nêu rõ giải (vd: Kết quả giải 11111).']); 
    break; 
  }

  $type = getRuleType($tournaId);
  if ($type==='knockout' || $type==='ko' || $type==='knock-out' || $type==='loai truc tiep'){
    // KO → trả vô địch/á quân
    $sum = knockoutSummary($tournaId);
    // tạo câu thân thiện
    $lines = ["Giải này thi đấu **loại trực tiếp** 🏆"];
    if ($sum['champion'] || $sum['runnerup']){
      if ($sum['champion']) $lines[] = "Vô địch: ".$sum['champion'];
      if ($sum['runnerup']) $lines[] = "Á quân: ".$sum['runnerup'];
      if ($sum['third'])    $lines[] = "Hạng 3: ".$sum['third'];
      $ans = "Kết quả chung cuộc\n".implode("\n", $lines);
    } else {
      $ans = "Giải **loại trực tiếp** chưa xác định nhà vô địch.\n".
             "Có thể giải đang trong giai đoạn bốc thăm/diễn ra. Bạn thử hỏi lịch: \"đội <Tên đội> lịch\".";
    }
    $links = [['label'=>'Xem chi tiết trang giải ','href'=>url_tourna($tournaId)]];
    echo json_encode(['ok'=>true,'answer'=>$ans,'links'=>$links]);
    break;
  }

  // Mặc định: vòng tròn → BXH chuẩn
  $tab = standingsOfTourna($tournaId);
  if (!$tab){ echo json_encode(['ok'=>true,'answer'=>'Chưa có dữ liệu BXH.']); break; }
  $lines=["TOP BXH:"];
  $i=1; foreach($tab as $r){
    $lines[] = sprintf("%d) %s — %dđ (W%s D%s L%s, GD %s)", $i++,$r['teamName'],$r['Pts'],$r['W'],$r['D'],$r['L'],$r['GD']);
    if ($i>6) break;
  }
  $ans = "Bảng xếp hạng (vòng tròn) 📊\n".implode("\n",$lines);
  $links = [['label'=>'Xem BXH đầy đủ','href'=>url_tourna($tournaId)]];
  echo json_encode(['ok'=>true,'answer'=>$ans,'table'=>$tab,'links'=>$links]);
  break;

    case 'rules':
      if ($tournaId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem điều lệ giải nào?']); break; }
      echo json_encode(['ok'=>true,'answer'=>rulesText($tournaId)]); break;

    case 'tourna_info':
      if ($tournaId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem giải nào? Ví dụ: Giải 11111']); break; }
      $t = tournaInfo($tournaId);
      if (!$t){ echo json_encode(['ok'=>true,'answer'=>'Không tìm thấy giải.']); break; }
      $when = ($t['startdate']?date('d-m-Y',strtotime($t['startdate'])):'?').' — '.($t['enddate']?date('d-m-Y',strtotime($t['enddate'])):'?');
      $fee  = ($t['fee_type']==='PAID' && $t['fee_amount']!==null) ? ('Lệ phí: '.number_format($t['fee_amount']).'đ') : 'Miễn phí';
      $loc  = !empty($t['LocalName']) ? ('Địa điểm: '.$t['LocalName']) : '';
      $rule = $t['rulename'] ? ('Thể thức: '.$t['rulename']) : '';
      $ct   = $t['team_count'] ? ('Số đội dự kiến: '.$t['team_count']) : '';
      $ans  = "$t[tournaName]\nThời gian: $when\n$fee\n$loc\n$rule\n$ct";
      echo json_encode(['ok'=>true,'answer'=>trim($ans)]); break;

    case 'team_info':
      if ($teamId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn hãy nêu tên đội. Ví dụ: Đội Golden Tigers']); break; }
      $info = teamInfo($teamId);
      if (!$info['team']){ echo json_encode(['ok'=>true,'answer'=>'Không tìm thấy đội.']); break; }
      $lines=[]; $lines[]="Đội: ".$info['team']['teamName'];
      if (!empty($info['tournaments'])){
        $lines[]="Các giải tham dự:"; foreach($info['tournaments'] as $t){ $lines[]="- ".$t['tournaName']; }
      }
      if (!empty($info['members'])){
        $lines[]="Thành viên (5 người đầu):"; $i=0;
        foreach($info['members'] as $m){ $lines[]="• {$m['FullName']} ({$m['position']}, {$m['roleInTeam']})"; if(++$i>=5) break; }
      }
      echo json_encode(['ok'=>true,'answer'=>implode("\n",$lines),'team'=>$info]); break;

    case 'player_info':
      if (preg_match('/(top\s*scorer|vua\s*pha\s*luoi)/u', normalize($msg))){
        if ($tournaId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem vua phá lưới của giải nào?']); break; }
        $tops = topScorers($tournaId,10);
        if (!$tops){ echo json_encode(['ok'=>true,'answer'=>'Chưa có dữ liệu bàn thắng.']); break; }
        $lines=["Top ghi bàn:"]; foreach($tops as $i=>$r){ $lines[] = ($i+1).") {$r['FullName']} ({$r['teamName']}) — {$r['goals']} bàn".($r['own_goals']?" (+{$r['own_goals']} phản)":""); }
        echo json_encode(['ok'=>true,'answer'=>implode("\n",$lines),'tops'=>$tops]); break;
      }
      if ($playerByName){
        $pp = playerProfile((int)$playerByName['id_player'], $tournaId);
        if (!$pp['core']){ echo json_encode(['ok'=>true,'answer'=>'Không thấy cầu thủ.']); break; }
        $lines=[]; $lines[]="{$pp['core']['FullName']} — {$pp['core']['position']} (tuổi {$pp['core']['age']})";
        if (!empty($pp['teams'])){ $names = array_map(fn($t)=>$t['teamName'],$pp['teams']); $lines[]="Đội: ".implode(', ',$names); }
        $lines[]="Bàn thắng: {$pp['stats']['goals']}".($pp['stats']['own_goals']?(" (phản lưới: {$pp['stats']['own_goals']})"):'');
        echo json_encode(['ok'=>true,'answer'=>implode("\n",$lines),'player'=>$pp]); break;
      }
      echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem cầu thủ nào? Ví dụ: Cầu thủ Nguyễn Xuân Hinh']); break;
    case 'schedule_tourna':
{
  if ($tournaId<=0){
    // cố gắng lấy tên giải từ câu
    if (preg_match('/giai\s+([a-z0-9\s]+)/u',$norm,$mm)) $tournaId = findTournaIdByName(trim($mm[1]));
  }
  if ($tournaId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn đang hỏi lịch giải nào?']); break; }

  $rows = scheduleOfTourna($tournaId, 10);
  if (!$rows){ echo json_encode(['ok'=>true,'answer'=>'Giải này chưa có lịch.']); break; }

  $lines=[];
  foreach($rows as $r){
    $h = $r['home_name'] ?? ($r['home_placeholder'] ?: 'TBD');
    $a = $r['away_name'] ?? ($r['away_placeholder'] ?: 'TBD');
    $d = $r['kickoff_date']? date('d-m-Y',strtotime($r['kickoff_date'])):'Chưa ấn định';
    $t = $r['kickoff_time']? substr($r['kickoff_time'],0,5) : '';
    $lines[] = "• $h vs $a — $d".($t?" $t":"");
  }
  echo json_encode(['ok'=>true,'answer'=>"Lịch giải:\n".implode("\n",$lines),'links'=>[['label'=>'Trang giải','href'=>url_tourna($tournaId)]]]);
  break;
}
case 'schedule_team':
{
  if ($teamId<=0 && preg_match('/doi\s+([a-z0-9\s]+)/u',$norm,$mm)) {
    $teamId = findTeamIdByName(trim($mm[1]), $tournaId);
  }
  // nếu có thêm "giải X" thì lấy luôn
  if ($tournaId<=0 && preg_match('/giai\s+([a-z0-9\s]+)/u',$norm,$mm)) {
    $tournaId = findTournaIdByName(trim($mm[1]));
  }

  if ($teamId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn muốn xem lịch của đội nào?']); break; }

  $rows = scheduleOfTeam($teamId, $tournaId, 8);
  if (!$rows){ echo json_encode(['ok'=>true,'answer'=>'Chưa có lịch nào cho đội này.']); break; }

  $lines=[];
  foreach($rows as $r){
    $h = $r['home_name'] ?? ($r['home_placeholder'] ?: 'TBD');
    $a = $r['away_name'] ?? ($r['away_placeholder'] ?: 'TBD');
    $d = $r['kickoff_date']? date('d-m-Y',strtotime($r['kickoff_date'])):'Chưa ấn định';
    $t = $r['kickoff_time']? substr($r['kickoff_time'],0,5) : '';
    $lines[] = "• $h vs $a — $d".($t?" $t":"");
  }
  echo json_encode(['ok'=>true,'answer'=>"Lịch của đội:\n".implode("\n",$lines)]);
  break;
}
case 'tourna_teams':
{
  if ($tournaId<=0 && preg_match('/giai\s+([a-z0-9\s]+)/u',$norm,$mm)) {
    $tournaId = findTournaIdByName(trim($mm[1]));
  }
  if ($tournaId<=0){ echo json_encode(['ok'=>true,'answer'=>'Bạn cho mình tên/mã giải nhé.']); break; }

  $teams = teamsOfTourna($tournaId);
  if (!$teams){ echo json_encode(['ok'=>true,'answer'=>'Giải này chưa có đội được duyệt.']); break; }
  $names = array_map(fn($r)=>$r['teamName'],$teams);

  echo json_encode(['ok'=>true,
    'answer'=>"Các đội của giải:\n• ".implode("\n• ",$names),
    'links'=>[['label'=>'Trang giải','href'=>url_tourna($tournaId)]]
  ]);
  break;
}
case 'count_tourna_in_month': {
  $month = null; $year = null;
  if (!parseMonthYear($norm,$month,$year)) {
    $month = (int)date('n'); $year = (int)date('Y'); // mặc định tháng này
  }
  $rows = countTournaInMonth($month,$year);
  if (!$rows){
    echo json_encode(['ok'=>true,'answer'=>"Tháng $month/$year không có giải nào."]); break;
  }
  $names = array_map(fn($r)=>$r['tournaName'],$rows);
  $links = array_map(fn($r)=>['label'=>$r['tournaName'],'href'=>url_tourna($r['idtourna'])], $rows);
  echo json_encode(['ok'=>true,
    'answer'=>"Tháng $month/$year có ".count($rows)." giải:\n• ".implode("\n• ",$names),
    'links'=>$links
  ]);
  break;
}

    default:
      // FULLTEXT fallback (nếu đã tạo bảng ở bước 1)
      $txt = q($msg); $cond = $tournaId>0? " AND tourna_id=$tournaId" : "";
      $best=null;
      foreach([
        "SELECT 'faq' AS src, MATCH(question,answer) AGAINST('$txt') AS score, answer AS content
         FROM faq_qa WHERE MATCH(question,answer) AGAINST('$txt' IN NATURAL LANGUAGE MODE) $cond ORDER BY score DESC LIMIT 1",
        "SELECT 'doc' AS src, MATCH(title,content) AGAINST('$txt') AS score, content
         FROM doc_page WHERE MATCH(title,content) AGAINST('$txt' IN NATURAL LANGUAGE MODE) $cond ORDER BY score DESC LIMIT 1"
      ] as $sql){
        $r=mysqli_query($conn,$sql); $row=firstRow($r); if($row && (!$best || $row['score']>$best['score'])) $best=$row;
      }
      if ($best){ echo json_encode(['ok'=>true,'answer'=>$best['content']]); break; }
      echo json_encode(['ok'=>true,'answer'=>'Mình có thể trả lời: lịch/kết quả đội, BXH & điều lệ giải, thông tin giải, thông tin đội, top ghi bàn, hồ sơ cầu thủ… Hãy thử: "đội Golden Tigers ", "Kết quả giải 11111", .']);
  }
} catch(Throwable $e){
  echo json_encode(['ok'=>false,'answer'=>'Có lỗi: '.$e->getMessage()]);
} finally {
  if ($conn) { /* dùng chung connection – không đóng gấp để tránh chạm code khác */ }
}
