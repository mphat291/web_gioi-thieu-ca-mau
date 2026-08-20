<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel' ?> | Admin Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        #sidebar { min-width: 250px; background: #212529; min-height: 100vh; transition: all 0.3s; }
        .nav-link { color: #adb5bd !important; padding: 12px 20px; transition: 0.2s; }
        .nav-link:hover { background: #343a40; color: #fff !important; }
        .nav-link.active { background: #0d6efd !important; color: #fff !important; }
        .sidebar-heading { color: #6c757d; font-size: 0.75rem; text-transform: uppercase; padding: 15px 20px 5px; font-weight: bold; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="p-3 text-white fw-bold fs-5 border-bottom border-secondary">
            <i class="fa-solid fa-gear me-2"></i> Admin Panel
        </div>
        
        <div class="sidebar-heading">Chính</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-line me-2"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Nội dung</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="articles.php" class="nav-link <?= (in_array($current_page, ['articles.php', 'edit_article.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-newspaper me-2"></i> Bài Viết
                </a>
            </li>
            <li class="nav-item">
                <a href="categories.php" class="nav-link <?= (in_array($current_page, ['categories.php', 'edit_category.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-folder me-2"></i> Danh Mục
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link <?= ($current_page == 'reports.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-flag me-2"></i> Báo Cáo Vi Phạm
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Quản lý</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="contacts.php" class="nav-link <?= ($current_page == 'contacts.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-envelope me-2"></i> Liên Hệ
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link <?= (in_array($current_page, ['users.php', 'edit_user.php'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-users me-2"></i> Người Dùng
                </a>
            </li>
        </ul>

        <div class="sidebar-heading">Hệ thống</div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="../index.php" class="nav-link text-info" target="_blank"><i class="fa-solid fa-globe me-2"></i> Xem Website</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng Xuất</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
            <span class="navbar-brand mb-0 h1"><?= $page_title ?? 'Dashboard' ?></span>
        </nav>
        <div class="p-4">