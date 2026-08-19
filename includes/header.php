<?php
/**
 * Header - Navigation chung cho toàn ứng dụng
 * Sử dụng Bootstrap 5
 * 
 * Cách dùng: 
 * require_once 'includes/header.php';
 */

if (!isset($_SESSION)) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient" style="background: linear-gradient(135deg, #006633 0%, #004d24 100%) !important;">
    <div class="container-lg">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            🏖️ Khám Phá Cà Mau
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">📰 Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="category.php">📁 Danh Mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="favorite.php">❤️ Yêu Thích</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="history.php">📖 Lịch Sử đọc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">📧 Liên Hệ</a>
                </li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">⚙️ Admin Panel</a>
                    </li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Vị trí cuối cùng: Tên tài khoản & Nút đăng xuất thu nhỏ bên dưới -->
                    <li class="nav-item ms-lg-2">
                        <div class="d-flex flex-column align-items-lg-end align-items-start py-1">
                            <span class="text-white fw-bold mb-1" style="font-size: 0.9rem;">
                                👤 <?= htmlspecialchars($_SESSION['username'] ?? 'Người dùng') ?>
                            </span>
                            <a href="logout.php" class="btn btn-outline-light btn-sm py-0 px-2" style="font-size: 0.75rem;">
                                🚪 Đăng Xuất
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">🔓 Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">📝 Đăng Ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>