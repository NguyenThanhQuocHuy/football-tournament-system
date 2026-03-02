<?php
include_once('control/controltourna.php');
$controller = new cTourna();

$idtourna = $_REQUEST["id"] ?? 0;
$idtourna = (int)$idtourna;

$tbl = $controller->getTournaById($idtourna);
if($tbl != null && $tbl != -1 && $tbl != -2){
    $row = $tbl;
    $tournaName = $row['tournaName'] ?? '';
    // Lấy thêm các trường ngày / ảnh
    $startDate  = !empty($row['startdate']) ? substr($row['startdate'], 0, 10) : '';
    $endDate    = !empty($row['enddate'])   ? substr($row['enddate'], 0, 10)   : '';
    $logo       = $row['logo']   ?? '';
    $banner     = $row['banner'] ?? '';
} else {
    echo "<script>alert('Không tìm thấy giải đấu!'); window.location='dashboard.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa giải đấu</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f8f9fa;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin: 40px auto;
            max-width: 700px;
        }

        .form_edittourna {
            width: 100%;
        }

        .form_edittourna h1 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
            color: #333;
        }

        .form_edittourna table {
            width: 100%;
            border-collapse: collapse;
        }

        .form_edittourna td {
            padding: 10px 0;
            vertical-align: top;
        }

        .form_edittourna label {
            font-weight: 600;
            color: #444;
        }

        .form_edittourna input[type="text"],
        .form_edittourna input[type="date"],
        .form_edittourna input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .img-preview {
            display: block;
            max-height: 80px;
            margin-bottom: 8px;
            border-radius: 6px;
            object-fit: cover;
        }

        .hint {
            font-size: 12px;
            color: #777;
            margin-top: 4px;
        }

        .form_edittourna button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .form_edittourna button:hover {
            background-color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- NHỚ có enctype để upload ảnh -->
        <form class="form_edittourna" method="POST" action="" enctype="multipart/form-data">
            <h1>Chỉnh sửa giải đấu</h1>
            <table>
                <tr>
                    <td style="width: 30%;"><label for="tournaName">Tên giải đấu:</label></td>
                    <td>
                        <input type="text" id="tournaName" name="tournaName"
                               value="<?php echo htmlspecialchars($tournaName); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td><label for="startdate">Ngày bắt đầu:</label></td>
                    <td>
                        <input type="date" id="startdate" name="startdate"
                               value="<?php echo htmlspecialchars($startDate); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td><label for="enddate">Ngày kết thúc:</label></td>
                    <td>
                        <input type="date" id="enddate" name="enddate"
                               value="<?php echo htmlspecialchars($endDate); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td><label for="hinhlogo">Logo giải:</label></td>
                    <td>
                        <?php if (!empty($logo)): ?>
                            <img src="<?php echo htmlspecialchars($logo); ?>" alt="Logo hiện tại" class="img-preview">
                        <?php endif; ?>
                        <input type="file" id="hinhlogo" name="hinhlogo" accept="image/*">
                        <div class="hint">Nếu không chọn file mới, hệ thống sẽ giữ nguyên logo hiện tại.</div>
                    </td>
                </tr>

                <tr>
                    <td><label for="hinhbanner">Banner giải:</label></td>
                    <td>
                        <?php if (!empty($banner)): ?>
                            <img src="<?php echo htmlspecialchars($banner); ?>" alt="Banner hiện tại" class="img-preview">
                        <?php endif; ?>
                        <input type="file" id="hinhbanner" name="hinhbanner" accept="image/*">
                        <div class="hint">Nếu không chọn file mới, hệ thống sẽ giữ nguyên banner hiện tại.</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 20px;">
                        <button type="submit">Cập nhật</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

<?php
// Xử lý submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTournaName = trim($_POST['tournaName'] ?? '');
    $newStartDate  = trim($_POST['startdate'] ?? '');
    $newEndDate    = trim($_POST['enddate'] ?? '');

    if ($newTournaName === '' || $newStartDate === '' || $newEndDate === '') {
        echo "<script>alert('Tên giải, ngày bắt đầu và ngày kết thúc không được để trống!');</script>";
    } elseif ($newStartDate > $newEndDate) {
        // Giống logic thêm giải: không cho lưu nếu start > end
        echo "<script>alert('Ngày bắt đầu không được trễ hơn ngày kết thúc!');</script>";
    } else {
        // Default logo / banner nếu DB đang trống
        $defaultLogo   = $row['logo']   ?? '/Kltn/img/giaidau/logo_macdinh.png';
        $defaultBanner = $row['banner'] ?? '/Kltn/img/giaidau/banner_macdinh.jpg';

        // Dùng lại class upload của bạn: nếu không chọn file → giữ đường dẫn cũ
        $logoPath   = cUploadTourna::saveUploadOrDefault('hinhlogo',   $defaultLogo);
        $bannerPath = cUploadTourna::saveUploadOrDefault('hinhbanner', $defaultBanner);

        // Gọi controller để cập nhật tất cả thông tin cơ bản
        $updateResult = $controller->updateTournaBasicInfo(
            $idtourna,
            $newTournaName,
            $newStartDate,
            $newEndDate,
            $logoPath,
            $bannerPath
        );

        if ($updateResult === true) {
            echo "<script>alert('Cập nhật giải đấu thành công!'); window.location='dashboard.php?page=man_tourna';</script>";
            exit();
        } else {
            echo "<script>alert('Cập nhật thất bại! Vui lòng thử lại.');</script>";
        }
    }
}
?>
