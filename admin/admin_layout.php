<?php
/**
 * admin_layout.php - Template chung cho admin panel
 * Sử dụng Bootstrap 5
 * 
 * Cách dùng:
 * <?php 
 * $page_title = "Quản Lý Bài Viết";
 * require_once 'admin_layout.php';
 * ?>
 * <!-- Nội dung trang admin ở đây -->
 * <?php require_once 'admin_layout_end.php'; ?>
 */

session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Kiểm tra quyền admin
requireAdmin();

// Cấu hình mặc định
$page_title = $page_title ?? 'Dashboard';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="bg-light">

<!-- Navigation -->
<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">⚙️ Admin Panel</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-md-block bg-light sidebar offcanvas-md offcanvas-start" tabindex="-1" id="sidebar">
            <div class="position-sticky pt-3">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>📊 CHÍNH</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="index.php">
                            📊 Dashboard
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>📝 NỘI DUNG</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'articles' ? 'active' : '' ?>" href="articles.php">
                            📰 Bài Viết
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'categories' ? 'active' : '' ?>" href="categories.php">
                            📁 Danh Mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'comments' ? 'active' : '' ?>" href="comments.php">
                            💬 Bình Luận
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>⚡ QUẢN LÝ</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'contacts' ? 'active' : '' ?>" href="contacts.php">
                            📧 Liên Hệ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'users' ? 'active' : '' ?>" href="users.php">
                            👥 Người Dùng
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>⚙️ HỆ THỐNG</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank">
                            🌐 Xem Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">
                            🚪 Đăng Xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">📊 Dashboard</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($page_title) ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2"><?= htmlspecialchars($page_title) ?></h1>
            </div>
