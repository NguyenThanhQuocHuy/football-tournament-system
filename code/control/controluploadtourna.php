<?php
class cUploadTourna
{
    // Base URL của app (đặt đúng theo thư mục dự án của bạn)
    private const APP_BASE = '/Kltn';

    private static function normalizeWebPath(string $path): string {
        // Nếu đã là http/https hoặc bắt đầu bằng / => giữ nguyên
        if (preg_match('~^(https?://|/)~i', $path)) return $path;
        // Nếu kiểu ../img/... hoặc ./img/... => bỏ dấu chấm
        $path = preg_replace('~^\.+/~', '', $path);
        // Nếu path bắt đầu "img/" => gắn base
        if (str_starts_with($path, 'img/')) return self::APP_BASE . '/' . $path;
        // Mặc định: trả về theo base
        return self::APP_BASE . '/' . ltrim($path, '/');
    }

    public static function saveUploadOrDefault(string $fileKey, string $defaultWebPath, string $subFolder = 'tournaments'): string
    {
        // Không có file hoặc lỗi -> trả default (nhưng được chuẩn hoá path)
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return self::normalizeWebPath($defaultWebPath);
        }

        $f    = $_FILES[$fileKey];
        $ext  = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $allow = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allow)) return self::normalizeWebPath($defaultWebPath);
        if ($f['size'] > 5 * 1024 * 1024) return self::normalizeWebPath($defaultWebPath);

        // Thư mục lưu file (filesystem)
        $baseFsDir = __DIR__ . '/../uploads';
        if (!is_dir($baseFsDir)) @mkdir($baseFsDir, 0777, true);

        $targetDir = rtrim($baseFsDir, '/\\') . '/' . trim($subFolder, '/');
        if (!is_dir($targetDir)) @mkdir($targetDir, 0777, true);

        // Tên file an toàn
        $rand     = bin2hex(random_bytes(4));
        $filename = date('Ymd_His') . "_{$rand}." . $ext;

        // Lưu file
        $fsPath = $targetDir . '/' . $filename;
        if (!move_uploaded_file($f['tmp_name'], $fsPath)) {
            return self::normalizeWebPath($defaultWebPath);
        }

        // Trả web path tuyệt đối
        return self::APP_BASE . '/uploads/' . trim($subFolder, '/') . '/' . $filename;
    }
public static function saveDoc(string $fileKey, string $subFolder = 'regulations'): ?array
{
    // Không có file hoặc upload lỗi -> bỏ qua
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $f   = $_FILES[$fileKey];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

    // Chỉ cho phép PDF/Word
    $allow = ['pdf', 'doc', 'docx'];
    if (!in_array($ext, $allow)) {
        return null;
    }

    // Giới hạn dung lượng, ví dụ 20MB
    if ($f['size'] > 20 * 1024 * 1024) {
        return null;
    }

    // Thư mục lưu trên ổ đĩa
    $baseFsDir = __DIR__ . '/../uploads';
    if (!is_dir($baseFsDir)) {
        @mkdir($baseFsDir, 0777, true);
    }

    $targetDir = rtrim($baseFsDir, '/\\') . '/' . trim($subFolder, '/');
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0777, true);
    }

    // Tạo tên file an toàn
    $rand     = bin2hex(random_bytes(4));
    $filename = date('Ymd_His') . "_{$rand}." . $ext;

    $fsPath = $targetDir . '/' . $filename;
    if (!move_uploaded_file($f['tmp_name'], $fsPath)) {
        return null;
    }

    // Web path để lưu DB
    $webPath = self::APP_BASE . '/uploads/' . trim($subFolder, '/') . '/' . $filename;

    // MIME type
    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($fsPath) ?: 'application/octet-stream';
    } else {
        $mime = $f['type'] ?? 'application/octet-stream';
    }

    return [
        'file_name' => $f['name'],      // tên gốc để hiển thị
        'file_path' => $webPath,        // đường dẫn dùng trong <a href="">
        'mime_type' => $mime,
        'file_size' => (int)$f['size'],
    ];
}

}
