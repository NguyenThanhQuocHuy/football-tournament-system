<style>
  .about-page {
    background: #f5f5f5;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
    color: #222;
  }

  .about-inner {
    max-width: 1120px;
    margin: 40px auto 80px;
    padding: 0 16px;
  }

  /* Tiêu đề */
  .about-header {
    text-align: center;
    margin-bottom: 28px;
  }
  .about-header h1 {
    font-size: 26px;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
    margin: 0 0 10px;
  }
  .about-header .underline {
    width: 70px;
    height: 3px;
    background: #f4c300;
    margin: 0 auto;
  }

  /* Đoạn giới thiệu */
  .about-intro {
    background: #fff;
    padding: 20px 24px;
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 28px;
  }
  .about-intro p { margin: 0 0 10px; }
  .about-intro strong { font-weight: 600; }

  /* 2 cột nội dung chính */
  .about-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.1fr);
    gap: 24px;
    margin-bottom: 32px;
  }
  .about-box {
    background: #fff;
    padding: 20px 24px;
    font-size: 15px;
    line-height: 1.7;
  }
  .about-box h2 {
    color : #111;
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px;
  }
  .about-box h3 {
    font-size: 15px;
    font-weight: 600;
    margin: 16px 0 4px;
  }
  .about-box p { margin: 0 0 8px; }
  .about-box ul {

    margin: 0 0 8px 18px;
    padding: 0;
  }
  .about-box li { margin-bottom: 4px; color: black ; }
  .about-box strong { font-weight: 600; color: black ; }

  /* Đội ngũ phát triển */
  .about-team {
    background: #fff;
    padding: 20px 24px 24px;
    font-size: 14px;
    line-height: 1.7;
  }
  .about-team h2 {
    color : #111;
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 12px;
  }
  .about-team-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px;
  }
  .about-member h3 {
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 4px;
  }
  .about-member p { margin: 0 0 6px; }

  /* Mobile */
  @media (max-width: 900px) {
    .about-inner {
      margin: 24px auto 56px;
      padding: 0 12px;
    }
    .about-grid {
      grid-template-columns: 1fr;
    }
    .about-team-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<section class="about-page">
  <div class="about-inner">

    <!-- Tiêu đề -->
    <header class="about-header">
      <h1>VỀ CHÚNG TÔI</h1>
      <div class="underline"></div>
    </header>

    <!-- Giới thiệu TOURNAPRO -->
    <div class="about-intro">
      <p><strong>TOURNAPRO</strong> là nền tảng quản lý giải đấu thể thao hướng tới mục tiêu
        <strong>tự động hóa – minh bạch – hiệu quả</strong>. Hệ thống giúp Ban tổ chức, đội bóng và khán giả
        theo dõi trọn vẹn hành trình của một giải đấu từ khâu đăng ký, chia bảng, sắp lịch cho đến cập nhật kết quả,
        thống kê và báo cáo.</p>

      <p>Thay vì quản lý bằng Excel rời rạc hoặc giấy tờ dễ thất lạc, TOURNAPRO gom toàn bộ dữ liệu về giải đấu
        vào một nơi, giúp bạn dễ xem – dễ tìm – dễ tổng hợp.</p>
    </div>

    <!-- 2 cột: Sứ mệnh / TOURNAPRO giúp gì -->
    <div class="about-grid">

      <!-- Cột 1 -->
      <div class="about-box">
        <h2>Sứ mệnh</h2> <br> <br>
        <p>Trở thành công cụ quen thuộc cho các giải bóng đá phong trào, giải sinh viên, giải nội bộ công ty…</p>

        <h3>Giá trị cốt lõi</h3>
        <ul>
          <li><strong>Minh bạch:</strong> lịch, kết quả, BXH rõ ràng, hạn chế tranh cãi.</li>
          <li><strong>Tiện lợi:</strong> duyệt đội, chia bảng, nhập tỷ số trên một giao diện.</li>
          <li><strong>Chuyên nghiệp:</strong> giao diện người xem rõ ràng, dễ theo dõi.</li>
        </ul>
      </div>

      <!-- Cột 2 -->
      <div class="about-box">
        <h2>TOURNAPRO giúp gì?</h2>
  <br>
        <h3>Cho Ban tổ chức</h3>
        <ul>
          <li>Quản lý đăng ký đội, cầu thủ, lệ phí trên một hệ thống.</li>
          <li>Tự động tạo lịch, tính BXH theo luật giải đã cấu hình.</li>
        </ul>

        <h3>Cho đội bóng & khán giả</h3>
        <ul>
          <li>Xem nhanh lịch thi đấu, kết quả, thứ hạng của đội.</li>
          <li>Theo dõi hành trình của đội xuyên suốt giải đấu.</li>
        </ul>
      </div>

    </div>

    <!-- Đội ngũ phát triển -->
    <section class="about-team">
      <h2>Đội ngũ phát triển</h2>
      <br> <br>
      <div class="about-team-grid">

        <div class="about-member">
          <h3>Nguyễn Công Bằng</h3>
          <p>Sinh viên ngành Công nghệ Thông tin – Trường Đại học Công nghiệp TP.HCM.
          Phụ trách phân tích nghiệp vụ, thiết kế hệ thống, xây dựng cơ sở dữ liệu
             và lập trình các chức năng chính của TOURNAPRO.</p>
        </div>

        <div class="about-member">
          <h3>Nguyễn Thanh Quốc Huy</h3>
          <p>Sinh viên ngành Công nghệ Thông tin – Trường Đại học Công nghiệp TP.HCM.
          Phụ trách thiết kế giao diện, tối ưu trải nghiệm người dùng và hỗ trợ triển khai
             các chức năng quản lý giải đấu, đội bóng và người dùng.</p>
        </div>

      </div>
    </section>

  </div>
</section>
