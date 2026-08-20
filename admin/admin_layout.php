<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

// 1. Đếm tổng số báo cáo vi phạm chưa xử lý
$total_reports = 0;
try {
    $count_cr = $pdo->query("SELECT COUNT(*) FROM comment_reports")->fetchColumn();
    $count_ur = $pdo->query("SELECT COUNT(*) FROM user_reports")->fetchColumn();
    $total_reports = $count_cr + $count_ur;
} catch (PDOException $e) {
    // Bỏ qua nếu chưa có bảng
}

// 2. Đếm số lượng bài viết đang chờ duyệt
$total_pending_articles = 0;
try {
    $total_pending_articles = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'pending' OR status = '0'")->fetchColumn();
} catch (PDOException $e) {
    // Bỏ qua nếu chưa có bảng
}

// 3. Đếm số lượng tin nhắn liên hệ
$total_contacts = 0;
try {
    $total_contacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
} catch (PDOException $e) {
    // Bỏ qua nếu chưa có bảng
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #1e1e2d; color: #a2a3b7; }
        .sidebar-brand { padding: 1.5rem; font-weight: bold; font-size: 1.25rem; color: #fff; display: flex; align-items: center; gap: 10px; }
        .sidebar-heading { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #4c4e6f; padding: 1rem 1.5rem 0.5rem; font-weight: 700; }
        .sidebar .nav-link { color: #a2a3b7; padding: 0.75rem 1.5rem; font-weight: 500; display: flex; align-items: center; justify-content: space-between; text-decoration: none; transition: all 0.2s; }
        .sidebar .nav-link:hover { color: #ffffff; background-color: rgba(255,255,255,0.05); }
        .sidebar .nav-link.active { color: #ffffff; background: linear-gradient(90deg, #6f42c1 0%, #8540f5 100%); border-radius: 0 25px 25px 0; margin-right: 15px; }
        .sidebar .nav-link i { width: 25px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
            <div class="position-sticky">
                <div class="sidebar-brand">
                    <i class="fa-solid fa-gear text-primary"></i> Admin Panel
                </div>
                
                <div class="sidebar-heading">CHÍNH</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-chart-line"></i> Dashboard</div>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">NỘI DUNG</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a href="articles.php" class="nav-link <?= $current_page == 'articles.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-newspaper"></i> Bài Viết</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="approve.php" class="nav-link <?= $current_page == 'approve.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-circle-check"></i> Duyệt Bài Đăng</div>
                            <?php if ($total_pending_articles > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= $total_pending_articles ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="categories.php" class="nav-link <?= $current_page == 'categories.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-folder"></i> Danh Mục</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link <?= $current_page == 'reports.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-flag"></i> Báo Cáo Vi Phạm</div>
                            <?php if ($total_reports > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= $total_reports ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">QUẢN LÝ</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a href="contacts.php" class="nav-link <?= $current_page == 'contacts.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-envelope"></i> Liên Hệ</div>
                            <?php if ($total_contacts > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= $total_contacts ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link <?= $current_page == 'users.php' ? 'active' : '' ?>">
                            <div><i class="fa-solid fa-users"></i> Người Dùng</div>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">HỆ THỐNG</div>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a href="../index.php" target="_blank" class="nav-link">
                            <div><i class="fa-solid fa-globe"></i> Xem Website</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <div><i class="fa-solid fa-right-from-bracket"></i> Đăng Xuất</div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">