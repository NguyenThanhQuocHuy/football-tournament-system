<?php
require_once __DIR__ . '/../src/mail.php';

$ok = mail_send_html(
  'diachi_nhan@domain.com',               // địa chỉ nhận để bạn kiểm tra
  'Test PHPMailer - TOUNAPRO',
  '<p>Xin chào! Đây là email test từ PHPMailer.</p>'
);
echo $ok ? 'OK' : 'FAILED';
