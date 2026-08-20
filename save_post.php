<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $article_id = intval($_GET['id']);
    
    // Kiểm tra xem bài viết đã được lưu vào bảng saved_posts chưa để tránh trùng lặp
    $check = $pdo->prepare("SELECT * FROM saved_posts WHERE user_id = ? AND article_id = ?");
    $check->execute([$user_id, $article_id]);
    
    if ($check->rowCount() == 0) {
        // Thêm vào bảng saved_posts riêng biệt
        $stmt = $pdo->prepare("INSERT INTO saved_posts (user_id, article_id, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $article_id]);
    }
}

// Quay lại trang trước đó
$redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $redirectUrl");
exit();