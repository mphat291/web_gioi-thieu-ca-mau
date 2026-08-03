<?php
require_once '../config/db.php';

// Thống kê số lượng
$total_articles = $conn->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$total_categories = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_comments = $conn->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$total_contacts = $conn->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị - Cà Mau Văn Hóa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Menu Admin -->
        <div class="bg-dark text-white p-3 min-vh-100" style="width: 250px;">
            <h4 class="text-center text-warning">Admin Cà Mau</h4>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item"><a href="index.php" class="nav-link text-white active">📊 Dashboard</a></li>
                <li><a href="categories.php" class="nav-link text-white">📁 Quản lý Danh mục</a></li>
                <li><a href="articles.php" class="nav-link text-white">📝 Quản lý Bài viết</a></li>
                <li><a href="comments.php" class="nav-link text-white">💬 Kiểm duyệt Bình luận</a></li>
                <li><a href="contacts.php" class="nav-link text-white">📬 Quản lý Liên hệ</a></li>
                <li><a href="users.php" class="nav-link text-white">👤 Quản lý Tài khoản</a></li>
                <li class="mt-4"><a href="../index.php" class="nav-link text-info">🌐 Xem Trang chủ Web</a></li>
            </ul>
        </div>

        <!-- Nội dung chính Dashboard -->
        <div class="p-4 flex-grow-1">
            <h2>Bảng Điều Khiển Quản Trị</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Bài viết</h5>
                            <p class="card-text fs-3"><?= $total_articles ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Danh mục</h5>
                            <p class="card-text fs-3"><?= $total_categories ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Bình luận</h5>
                            <p class="card-text fs-3"><?= $total_comments ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Liên hệ</h5>
                            <p class="card-text fs-3"><?= $total_contacts ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>