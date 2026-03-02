<?php
require_once __DIR__ . '/../model/modelschedule.php';
require_once __DIR__ . '/../model/modeltourna.php';
require_once __DIR__ . '/../model/modelgroup.php';
require_once __DIR__ . '/../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType; 

class cSchedule {
  public function screen(int $idTourna){
    $m  = new mSchedule();
    $mt = new mTourna();

    // Lấy thông tin giải (để lấy location mặc định nếu cần lưu kèm)
    $tourna = $mt->getById($idTourna); // nên trả về ít nhất: id_tourna, (location_id hoặc id_local)

    // --- Sinh cặp đấu từ kết quả bốc thăm
    // if (isset($_GET['generate']) && $_GET['generate'] === 'auto') {
    //   // Lấy rule từ tournament (mTourna->getDetail đã trả về ruletype, rr_rounds)
    //   $tourna = $mt->getDetail($idTourna);

    //   $type = strtolower($tourna['ruletype'] ?? '');
    //   if ($type === 'knockout') {
    //     $m->generateKnockout($idTourna);
    //     $m->advanceByes($idTourna);
    //   } elseif ($type === 'roundrobin') {
    //     $double = ((int)($tourna['rr_rounds'] ?? 1) >= 2);
    //     $m->generateRoundRobin($idTourna, $double); // <-- HÀM MỚI ở modelschedule
    //   } else {
    //     $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&conflict=1&msg='.rawurlencode('Chưa cấu hình thể thức.'));
    //   }

    //   $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&genok=1');
    // }
    if (isset($_GET['generate']) && $_GET['generate'] === 'auto') {
    // Ưu tiên sinh theo bảng (nếu có bảng)
    $mg = new mGroup();
    $hasGroups = method_exists($mg,'listGroups') && count($mg->listGroups($idTourna)) > 0;

    if ($hasGroups) {
        $m->generateGroupsAndPlayoff($idTourna);   // ✅ sinh vòng bảng + playoff
    } else {
        // fallback: KO hoặc RR không bảng
        $tourna = $mt->getDetail($idTourna);
        $type = strtolower($tourna['ruletype'] ?? '');
        if ($type === 'knockout') {
            $m->generateKnockout($idTourna);
            $m->advanceByes($idTourna);
        } else {
            $double = ((int)($tourna['rr_rounds'] ?? 1) >= 2);
            $m->generateRoundRobin($idTourna, $double);
        }
    }
    $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&genok=1');
}
    if (isset($_GET['resolve']) && $_GET['resolve']==='groups') {
    $rs = $m->resolvePlayoffFromStandings($idTourna);
    $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&scoreok=1&msg='.rawurlencode($rs['msg'] ?? ''));
}
    
        // --- Gợi ý phân lịch tự động ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto_suggest'])) {
        $gap    = (int)($_POST['gap_minutes']   ?? 0);
        $fields = (int)($_POST['field_count']   ?? 1);
        $time   =       $_POST['day_start_time'] ?? '';

        if ($gap <= 0 || $fields <= 0 || !$time) {
            $msg = rawurlencode('Vui lòng nhập đầy đủ khoảng cách thời gian, số sân và giờ bắt đầu.');
            $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&autosuggest_err=1&msg='.$msg);
        }

        $rs = $m->autoSuggestSchedule($idTourna, $gap, $fields, $time);

        if (empty($rs['ok'])) {
            $msg = rawurlencode($rs['msg'] ?? 'Không thể gợi ý lịch.');
            $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&autosuggest_err=1&msg='.$msg);
        } else {
            $msg = rawurlencode($rs['msg'] ?? 'Đã gợi ý và lưu lịch thi đấu.');
            $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&autosuggest=1&msg='.$msg);
        }
    }

    // --- Cập nhật phân lịch (Ngày / Giờ / Sân + ghi chú)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_kickoff'])) {
      $mid   = (int)($_POST['id_match'] ?? 0);
      $date  = $_POST['kickoff_date'] ?: null;
      $time  = $_POST['kickoff_time'] ?: null;

      // Chuẩn hoá sân: trim và gộp khoảng trắng
      $pitch = isset($_POST['pitch_label']) ? preg_replace('/\s+/', ' ', trim($_POST['pitch_label'])) : '';
      $ven   = $_POST['venue'] ?: null;

      // Validate: bắt buộc đủ Ngày, Giờ, Sân để UNIQUE làm việc chuẩn
      if ($pitch === '' || !$date || !$time) {
        $msg = rawurlencode('Vui lòng nhập đầy đủ: Ngày, Giờ và Sân (ví dụ: "Sân 1").');
        $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&conflict=1&msg='.$msg);
      }

      // Địa điểm mặc định của giải 
      $loc = null;
      if (isset($tourna['location_id'])) $loc = (int)$tourna['location_id'];
      elseif (isset($tourna['id_local']))  $loc = (int)$tourna['id_local'];

      // Lưu; nếu vi phạm UNIQUE (id_tourna, pitch_label, kickoff_date, kickoff_time) -> trả về 'conflict'
      $rs = $m->updateKickoffFull($mid, $date, $time, $loc, $pitch, $ven);
      if (!$rs['ok']) {
  if (($rs['error'] ?? '') === 'conflict') {
    $msg = rawurlencode('Khung giờ bị trùng trên cùng sân. Chọn thời điểm khác hoặc đổi sân.');
  } else {
    $msg = rawurlencode('Có lỗi khi lưu lịch. Vui lòng thử lại.');
  }
  $this->redir('dashboard.php?page=schedule&id='.$idTourna.'&conflict=1&msg='.$msg);
}

// OK
$this->redir('dashboard.php?page=schedule&id='.$idTourna.'&saved=1');
    }

    // Tải lịch để hiển thị
    $rounds = $m->loadSchedule($idTourna);
    // --- Gắn STT cho từng trận theo từng vòng & tạo map id_match -> STT
$idToSeq = [];              // [id_match] => seq toàn cục
$seq     = 1;
ksort($rounds);             // đảm bảo vòng tăng dần
foreach ($rounds as $rnd => &$list) {
    foreach ($list as &$row) {
        $row['_seq'] = $seq;                 // STT hiển thị
        $idToSeq[(int)$row['id_match']] = $seq;
        $seq++;
    }
}
unset($list, $row); // tránh reference leak
// Tạo tiêu đề vòng đẹp (Vòng bảng vs KO)
// --- Nhận diện ruletype + ngưỡng vòng bảng (nếu có)
$detail        = $mt->getDetail($idTourna);                 // có ruletype, rr_rounds...
$ruleType      = strtolower($detail['ruletype'] ?? '');
$mg            = new mGroup();
$maxGroupRound = method_exists($mg,'maxGroupRoundNo') ? (int)$mg->maxGroupRoundNo($idTourna) : 0;

$roundTitles = [];

// Nhãn KO theo SỐ TRẬN của vòng (matches in round)
$koLabelByMatches = function(int $matches): string {
    return match ($matches) {
        1   => 'Chung kết',
        2   => 'Bán kết',
        4   => 'Tứ kết',
        8   => 'Vòng 1/8',
        16  => 'Vòng 1/16',
        32  => 'Vòng 1/32',
        default => 'Play-off',
    };
};

foreach ($rounds as $rnd => $list) {
    // có trận thuộc bảng?
    $hasGroup = false;
    foreach ($list as $row) {
        $gid = isset($row['_gid']) ? (int)$row['_gid']
                                   : (isset($row['id_group']) ? (int)$row['id_group'] : 0);
        if ($gid > 0) { $hasGroup = true; break; }
    }

    if ($hasGroup) {                        // Vòng bảng
        $roundTitles[$rnd] = 'Vòng '.$rnd;
        continue;
    }

    // Không có id_group:
    // - RR không bảng  -> 'Vòng n'
    // - KO thuần hoặc playoff sau vòng bảng -> nhãn KO theo số trận
    if ($ruleType === 'roundrobin' && $maxGroupRound === 0) {
        $roundTitles[$rnd] = 'Vòng '.$rnd;                 // RR (không bảng)
    } else {
        $roundTitles[$rnd] = $koLabelByMatches(count($list)); // KO
    }
}

// --- Helper thay "Thắng trận {id_match}" -> "Thắng trận {STT}"
$prettyPlaceholder = function (?string $ph) use ($idToSeq) {
    if (!$ph) return null;
    return preg_replace_callback('/Thắng trận\s+(\d+)/u', function($m) use ($idToSeq) {
        $mid = (int)$m[1];
        $seq = $idToSeq[$mid] ?? $mid; // fallback nếu chưa có
        return 'Thắng trận '.$seq;
    }, $ph);
};
    // View không còn dùng dropdown địa điểm
    $locations = [];

    include __DIR__ . '/../view/schedule.php';
  }

  private function redir(string $url): void {
    if (!headers_sent()) {
      header('Location: '.$url);
      exit;
    }
    echo '<script>location.href="'.htmlspecialchars($url, ENT_QUOTES).'";</script>';
    exit;
  }
  // Vòng bảng
    // Sinh lịch vòng bảng cho cả giải
    public function generateGroupStage(int $idTourna): array {
        $mg = new mGroup();
        $ms = new mSchedule();
        $mt = new mTourna();

        // Lấy default location của giải (nếu có để set sẵn)
        $t = $mt->getTournamentById($idTourna);
        $defaultLoc = isset($t['id_local']) ? (int)$t['id_local'] : null;

        // Xoá lịch vòng bảng cũ để sinh lại
        $ms->deleteGroupStage($idTourna);

        $groups = $mg->listGroups($idTourna);
        if (empty($groups)) return ['ok'=>false, 'msg'=>'Chưa có bảng nào.'];

        foreach ($groups as $g) {
            $teams = $mg->listTeamsInGroup((int)$g['id_group']); // theo slot
            // Lấy danh sách id_team theo slot
            $order = [];
            foreach ($teams as $row) {
                if (!empty($row['id_team'])) $order[] = (int)$row['id_team'];
            }
            // Nếu số đội lẻ -> thêm BYE (0)
            $n = count($order);
            $hasBye = false;
            if ($n % 2 === 1) { $order[] = 0; $n++; $hasBye = true; }

            if ($n < 2) continue;

            // Thuật toán "circle method"
            $half = $n / 2;
            $arr  = $order;
            $round = 1;

            $roundsToGenerate = 1; // 1 lượt (home/away 1)
            // Nếu muốn 2 lượt trong bảng, đổi =2 và lặp thêm lượt đảo sân.

            for ($r=0; $r<$roundsToGenerate; $r++) {
                $A = $arr;
                for ($i=0; $i<$n-1; $i++) {
                    for ($j=0; $j<$half; $j++) {
                        $home = $A[$j];
                        $away = $A[$n-1-$j];
                        if ($home==0 || $away==0) continue; // BYE

                        // Đảo sân đơn giản cho cân bằng (tuỳ chọn)
                        if ($j % 2 == 1) { $tmp = $home; $home = $away; $away = $tmp; }

                        $ms->insertMatch([
                            'id_tourna'     => $idTourna,
                            'id_group'      => (int)$g['id_group'],
                            'round_no'      => $round,
                            'home_team_id'  => $home,
                            'away_team_id'  => $away,
                            'kickoff_date'  => null,        // để BTC tự xếp ngày/giờ sau
                            'location_id'   => $defaultLoc, // set sẵn sân mặc định nếu có
                            'pitch_label'   => null
                        ]);
                    }
                    $round++;

                    // Rotate (giữ nguyên phần tử 0)
                    $fixed = $A[0];
                    $tail  = array_slice($A, 1);
                    array_unshift($tail, array_pop($tail));
                    $A = array_merge([$fixed], $tail);
                }

                // lượt 2 (nếu cần): đảo sân các cặp vừa sinh
                // có thể sinh bằng cách lặp lại toàn bộ và hoán vị home/away.
            }
        }

        return ['ok'=>true, 'msg'=>'Đã sinh lịch vòng bảng'];
    }
    public function genGroupSchedule(int $idTourna): array {
    $ms = new mSchedule();
    return $ms->generateGroupsAndPlayoff($idTourna);
  }
  // Tải lịch đấu
  public function index() {
    $id_tourna = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Nếu bấm export
    if (isset($_GET['export']) && $_GET['export'] === 'xlsx') {
      $this->exportXlsx($id_tourna);
      return; // dừng ở đây
    }

    // ... phần còn lại render view lịch như cũ
  }

    public function exportXlsx(int $tournaId): void
    {
        if ($tournaId <= 0) { http_response_code(400); echo "Thiếu ID giải"; return; }
        $m = new mSchedule();
        $tournaName = trim((string)$m->getTournaName($tournaId));
        if ($tournaName === '') $tournaName = 'Giai ' . $tournaId;

        $rows = $m->getScheduleExport($tournaId); 
        // Tạo file Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Lịch thi đấu');

        // Header cột
        $headers = [
            'A1' => 'STT',
            'B1' => 'Vòng',
            'C1' => 'Ngày',
            'D1' => 'Giờ',
            'E1' => 'Chủ nhà',
            'F1' => 'Tỉ số/VS',
            'G1' => 'Khách',
            'H1' => 'Sân',
        ];
        foreach ($headers as $cell => $text) $sheet->setCellValue($cell, $text);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Dữ liệu từng trận
        $idToStt = [];
        $tmp = 1;
        foreach ($rows as $rr) {
            if (isset($rr['match_code'])) $idToStt[(int)$rr['match_code']] = $tmp++;
        }
        
        
        $r = 2; $stt = 1;
        foreach ($rows as $row) {
            $roundNo  = $row['round_no'] ?? '';
            $dateStr  = $row['match_date'] ?? '';
            $timeStr  = $row['match_time'] ?? '';
            $homeName = $row['home_name'] ?? '';
            $awayName = $row['away_name'] ?? '';

            // "Thắng trận 99" -> "Thắng trận 1" (STT)
            $homeName = preg_replace_callback('~Thắng trận\s+(\d+)~u', function($m) use ($idToStt) {
                $id = (int)$m[1]; return 'Thắng trận ' . ($idToStt[$id] ?? $id);
            }, $homeName);
            $awayName = preg_replace_callback('~Thắng trận\s+(\d+)~u', function($m) use ($idToStt) {
                $id = (int)$m[1]; return 'Thắng trận ' . ($idToStt[$id] ?? $id);
            }, $awayName);

            $homeSc   = $row['home_score']; 
            $awaySc   = $row['away_score'];
            $scoreTxt = (is_numeric($homeSc) && is_numeric($awaySc)) ? ($homeSc . ' - ' . $awaySc) : 'vs';
            $pitch    = $row['pitch_label'] ?? '';

            $sheet->setCellValue("A$r", $stt);
            $sheet->setCellValue("B$r", $roundNo);
            $sheet->setCellValueExplicit("C$r", (string)$dateStr, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D$r", (string)$timeStr, DataType::TYPE_STRING);
            $sheet->setCellValue("E$r", $homeName);
            $sheet->setCellValue("F$r", $scoreTxt);
            $sheet->setCellValue("G$r", $awayName);
            $sheet->setCellValue("H$r", $pitch);

            $r++; $stt++;
        }

        foreach (range('A', 'H') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->freezePane('A2');

        // Xuất file — tên dùng UTF-8 (hiển thị đúng tiếng Việt ở trình duyệt hiện đại)
        if (ob_get_length()) { @ob_end_clean(); }
        $filenameUtf8  = 'lichthidau ' . $tournaName . '.xlsx'; // ↓ fallback ASCII ngắn gọn
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="lichthidau.xlsx"; filename*=UTF-8\'\'' . rawurlencode($filenameUtf8));
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
if (($_GET['action'] ?? '') === 'export') {
    (new cSchedule())->exportXlsx((int)($_GET['id'] ?? 0));
}
