<?php
// admin/admin_layout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kích hoạt hiển thị lỗi nếu có
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel' ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Sidebar Responsive */
        #sidebar {
            min-width: 240px;
            max-width: 240px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        #sidebar.active {
            margin-left: -240px;
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -240px;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top px-3">
    <div class="container-fluid p-0">
        <!-- Logo & Nút Toggle Sidebar -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand fw-bold me-3" href="index.php">
                ⚙️ Admin Panel
            </a>
            <!-- Nút 3 gạch đã được bật tính năng -->
            <button class="btn btn-outline-light btn-sm" id="sidebarToggle" type="button" title="Ẩn/Hiện Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Các nút thao tác nhanh góc phải Navbar -->
        <div class="d-flex align-items-center gap-2">
            <!-- Nút Quay về Trang Chủ User -->
            <a href="../index.php" class="btn btn-primary btn-sm rounded-pill px-3" target="_blank" title="Xem trang chủ khách">
                <i class="fa-solid fa-house me-1"></i> Xem Website
            </a>
            <!-- Nút Đăng xuất -->
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
            </a>
        </div>
    </div>
</nav>

<div class="d-flex">
    <!-- SIDEBAR LEFT -->
    <div class="bg-white border-end p-3" id="sidebar">
        <div class="text-uppercase text-muted fw-bold small mb-2">Chính</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= ($page_title == 'Dashboard') ? 'active' : 'text-dark' ?>">
                    📊 Dashboard
                </a>
            </li>
        </ul>

        <div class="text-uppercase text-muted fw-bold small mb-2">Nội dung</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li><a href="articles.php" class="nav-link text-dark">📝 Bài Viết</a></li>
            <li><a href="categories.php" class="nav-link text-dark">📁 Danh Mục</a></li>
            <li><a href="comments.php" class="nav-link text-dark">💬 Bình Luận</a></li>
        </ul>

        <div class="text-uppercase text-muted fw-bold small mb-2">Quản lý</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li><a href="contacts.php" class="nav-link text-dark">🎯 Liên Hệ</a></li>
            <li><a href="users.php" class="nav-link text-dark">👥 Người Dùng</a></li>
        </ul>

        <div class="text-uppercase text-muted fw-bold small mb-2">Hệ thống</div>
        <ul class="nav nav-pills flex-column">
            <li><a href="../index.php" class="nav-link text-primary" target="_blank">🌐 Xem Website</a></li>
            <li><a href="logout.php" class="nav-link text-danger">🚪 Đăng Xuất</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 p-4" id="main-content">