<?php
include_once("control/controluser.php");
include_once("control/controlcontact.php");

// Nếu đã đăng nhập thì lấy thông tin user
$userInfo = null;
if (isset($_SESSION['id_user'])) {
    $cuser = new cUser();
    $result = $cuser->get01User($_SESSION['id_user']);

    if ($result instanceof mysqli_result && $result->num_rows > 0) {
    $userInfo = $result->fetch_assoc();
}

}
if(isset($_POST["btnContact"])) {
  // Kiểm tra người dùng đã đăng nhập chưa
    if(!isset($_SESSION['id_user'])) {
        echo "<script>
                alert('Vui lòng đăng nhập trước khi gửi phản hồi!');
                window.location.href = 'index.php?page=login';
              </script>";
        exit; // dừng thực hiện tiếp
    }
    $cContact = new cContact();

    // Lấy dữ liệu từ form, xử lý chống XSS
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $email    = htmlspecialchars($_POST['email'] ?? '');
    $phone    = htmlspecialchars($_POST['phone'] ?? '');
    $title    = htmlspecialchars($_POST['subject'] ?? '');
    $content  = htmlspecialchars($_POST['message'] ?? '');
    $id_user  = $_SESSION['id_user'] ?? null; // Nếu user đã đăng nhập

    // Gọi hàm thêm contact
    $kq = $cContact->addContact($fullname, $email, $phone, $title, $content, $id_user);

    if($kq) {
        echo "<script>
                alert('Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gửi liên hệ thất bại. Vui lòng thử lại!');
              </script>";
    }
}
?>
<article class="contact-article" aria-label="Liên hệ">
  <style>
    /* ====== SCOPED: chỉ áp dụng trong .contact-article ====== */
    .contact-article { isolation:isolate; position:relative; z-index:1; padding:32px 0 56px; background:#f5f6fb; }
    .contact-article * { box-sizing:border-box; font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial; }

    .ct-wrap { max-width:1100px; margin:0 auto; padding:0 16px; }

    .ct-title {
      margin:0 0 18px; font-size:28px; line-height:1.25; font-weight:800; color:#111;
      text-transform:uppercase; letter-spacing:.2px;
      background:#fff; padding:14px 18px; border-radius:16px; box-shadow:0 6px 18px rgba(14,30,37,.06);
      align-items: center;
    }
    .ct-title .bar { display:block; width:80px; height:4px; margin-top:8px; background:#ffd400; border-radius:2px; }

    .ct-grid {
      display:grid; grid-template-columns: 1fr 1.2fr; gap:16px;
    }
    @media (max-width: 900px){ .ct-grid{ grid-template-columns:1fr; } }

    .ct-card {
      background:#fff; border-radius:16px; padding:20px 18px;
      box-shadow:0 6px 18px rgba(14,30,37,.06);
    }

    .ct-info h3 { margin:0 0 8px; font-size:18px; color:#111; }
    .ct-info p { margin:0 0 10px; color:#444; font-size:15px; line-height:1.7; }
    .ct-list { margin:8px 0 0; padding:0; list-style:none; }
    .ct-list li { padding:10px 0; border-bottom:1px dashed #e9edf3; color:#333; font-size:15px; }
    .ct-list li:last-child { border-bottom:none; }
    .ct-list small { display:block; color:#777; }

    /* Form dạng "bảng" 2 cột đẹp & đều */
    .ct-form-table { width:100%; border-collapse:separate; border-spacing:0 10px; }
    .ct-form-table th, .ct-form-table td { vertical-align:top; }
    .ct-form-table th {
      width:180px; text-align:right; padding:10px 12px 10px 0; color:#333; font-weight:600;
    }
    .ct-form-table td { padding:10px 0; }

    .ct-input, .ct-textarea, .ct-select {
      width:100%; border:1px solid #e6e8ee; border-radius:12px; padding:11px 12px;
      outline:none; font-size:15px; background:#fafbff; transition:border-color .2s ease, box-shadow .2s ease;
    }
    .ct-input:focus, .ct-textarea:focus, .ct-select:focus {
      border-color:#cfd6ff; box-shadow:0 0 0 4px rgba(79,109,255,.08);
      background:#fff;
    }
    .ct-textarea { resize:vertical; min-height:120px; }

    .ct-actions { padding-top:6px; display:flex; gap:10px; }
    .ct-btn {
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      border:none; border-radius:12px; cursor:pointer; padding:11px 16px; font-weight:700; font-size:15px;
      transition:transform .06s ease, box-shadow .15s ease, opacity .2s ease;
    }
    .ct-btn:active { transform:translateY(1px); }
    .ct-btn.primary { background:#ffd400; color:#1a1a1a; box-shadow:0 4px 10px rgba(255,212,0,.25); }
    .ct-btn.secondary { background:#eef1ff; color:#2b2f55; }
    .ct-btn[disabled]{ opacity:.6; pointer-events:none; }

    .ct-note { color:#777; font-size:13px; margin-top:4px; }

    /* Alert */
    .ct-alert { padding:12px 14px; border-radius:12px; margin-bottom:14px; font-size:14px; }
    .ct-alert.success { background:#edfce9; color:#205e1a; border:1px solid #c8efbf; }
    .ct-alert.error   { background:#fff0f0; color:#8b1a1a; border:1px solid #f3c2c2; }

    /* Mobile: label trên, input dưới cho dễ nhập tay */
    @media (max-width: 680px){
      .ct-form-table th { width:auto; text-align:left; padding:0 0 6px 0; display:block; }
      .ct-form-table td { padding:0 0 12px 0; display:block; }
    }
  </style>

  <div class="ct-wrap">

    <div class="ct-grid">
      <!-- Thông tin liên hệ -->
      <div class="ct-card ct-info">
        <h3>Thông tin</h3>
        <p>Nếu bạn cần hỗ trợ nhanh, vui lòng để lại thông tin hoặc liên hệ chúng tôi qua:</p>
        <ul class="ct-list">
          <li><strong>Email:</strong> congbang180703@gmail.com<br><small>Phản hồi trong giờ hành chính</small></li>
          <li><strong>Hotline:</strong> 0376583553<br><small>8:00 – 18:00 (T2 – T6)</small></li>
          <li><strong>Địa chỉ:</strong> 12 Đường Nguyễn Văn Bảo, Phường 1, Quận Gò Vấp, TP.HCM</li>
        </ul>
      </div>

      <!-- Form liên hệ -->
      <div class="ct-card">
        <form method="post">
          <!-- Honeypot chống bot (ẩn) -->
          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

          <table class="ct-form-table" role="presentation">
            <tr>
              <th><label for="fullname">Họ và tên <span style="color:#e22">*</span></label></th>
              <td>
                <input class="ct-input" type="text" id="fullname" name="fullname" required 
                value="<?= htmlspecialchars($userInfo['FullName'] ?? ($_POST['fullname'] ?? '')) ?>" />
              </td>
            </tr>
            <tr>
              <th><label for="email">Email <span style="color:#e22">*</span></label></th>
              <td>
                <input class="ct-input" type="email" id="email" name="email" required
                  value="<?= htmlspecialchars($userInfo['email'] ?? ($_POST['email'] ?? '')) ?>" />
                <div class="ct-note">Chúng tôi sẽ dùng email này để phản hồi.</div>
              </td>
            </tr>
            <tr>
              <th><label for="phone">Số điện thoại</label></th>
              <td>
                <input class="ct-input" type="tel" id="phone" name="phone"
              value="<?= htmlspecialchars($userInfo['phone'] ?? ($_POST['phone'] ?? '')) ?>" />
              </td>
            </tr>
            <tr>
              <th><label for="subject">Chủ đề</label></th>
              <td>
                <input class="ct-input" type="text" id="subject" name="subject"
                       placeholder="VD: Hỗ trợ cài đặt" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" />
              </td>
            </tr>
            <tr>
              <th><label for="message">Nội dung <span style="color:#e22">*</span></label></th>
              <td>
                <textarea class="ct-textarea" id="message" name="message" required
                          placeholder="Mô tả chi tiết nhu cầu/vấn đề của bạn..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </td>
            </tr>
            <tr>
              <th></th>
              <td class="ct-actions">
                <button class="ct-btn primary" type="submit" name="btnContact">Gửi liên hệ</button>
                <button class="ct-btn secondary" type="reset">Làm mới</button>
            </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</article>
