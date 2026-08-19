<?php
/**
 * Footer - Phần chân trang chung cho toàn ứng dụng
 * Sử dụng Bootstrap 5
 * 
 * Cách dùng: 
 * require_once 'includes/footer.php';
 */
?>
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container-lg">
        <div class="row">
            <!-- Cột 1: Giới thiệu -->
            <div class="col-md-3 col-sm-6 mb-4">
                <h5 class="fw-bold text-warning mb-3">🏖️ Về Cà Mau</h5>
                <p class="small">
                    Cà Mau là một tỉnh nằm ở vùng Đồng bằng sông Cửu Long, nổi tiếng với những điểm du lịch sinh thái độc đáo và giá trị văn hóa lâu đời.
                </p>
            </div>

            <!-- Cột 2: Danh mục -->
            <div class="col-md-3 col-sm-6 mb-4">
                <h5 class="fw-bold text-warning mb-3">📁 Danh Mục</h5>
                <ul class="list-unstyled small">
                    <li><a href="category.php?cat=1" class="text-decoration-none text-light">📰 Du Lịch</a></li>
                    <li><a href="category.php?cat=2" class="text-decoration-none text-light">🎭 Văn Hóa</a></li>
                    <li><a href="category.php?cat=3" class="text-decoration-none text-light">🍜 Ẩm Thực</a></li>
                    <li><a href="category.php?cat=4" class="text-decoration-none text-light">🎉 Con người & giai thoại</a></li>
                </ul>
            </div>

            <!-- Cột 3: Liên kết nhanh -->
            <div class="col-md-3 col-sm-6 mb-4">
                <h5 class="fw-bold text-warning mb-3">🔗 Liên Kết</h5>
                <ul class="list-unstyled small">
                    <li><a href="index.php" class="text-decoration-none text-light">🏠 Trang Chủ</a></li>
                    <li><a href="contact.php" class="text-decoration-none text-light">📧 Liên Hệ</a></li>
                    <li><a href="#" class="text-decoration-none text-light">📋 Chính Sách</a></li>
                    <li><a href="#" class="text-decoration-none text-light">⚖️ Điều Khoản</a></li>
                </ul>
            </div>

            <!-- Cột 4: Mạng xã hội -->
            <div class="col-md-3 col-sm-6 mb-4">
                <h5 class="fw-bold text-warning mb-3">📱 Theo Dõi</h5>
                <div class="d-flex gap-3 small">
                    <a href="#" class="text-decoration-none text-light" title="Facebook">📘</a>
                    <a href="#" class="text-decoration-none text-light" title="Twitter">🐦</a>
                    <a href="#" class="text-decoration-none text-light" title="Instagram">📸</a>
                    <a href="#" class="text-decoration-none text-light" title="YouTube">▶️</a>
                </div>
            </div>
        </div>

        <hr class="bg-secondary my-4">

        <!-- Copyright -->
        <div class="text-center small text-secondary">
            <p class="mb-0">&copy; 2026 <strong>Khám Phá Cà Mau</strong>. Bảo lưu mọi quyền. | Thiết kế bởi Team To6</p>
        </div>
    </div>
</footer>
