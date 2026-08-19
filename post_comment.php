<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;
    $content    = isset($_POST['content']) ? trim($_POST['content']) : '';
    // Lấy parent_id nếu đây là bình luận trả lời
    $parent_id  = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : NULL;
    $user_id    = $_SESSION['user_id'];

    if ($article_id > 0 && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, content, parent_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$article_id, $user_id, $content, $parent_id]);
    }

    // Load lại trang chi tiết bài viết
    header("Location: detail.php?id=" . $article_id);
    exit();
}