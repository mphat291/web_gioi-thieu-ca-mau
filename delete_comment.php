<?php
session_start();
require_once 'config/db.php';

// Kiểm tra quyền admin
$isAdmin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
} elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $isAdmin = true;
} elseif (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
    $isAdmin = true;
}

if (!$isAdmin) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $comment_id = (int)$_GET['id'];
    
    try {
        // Dùng Transaction để xóa sạch dữ liệu liên quan (lượt thích, bình luận con) trước khi xóa bình luận chính
        $pdo->beginTransaction();

        // 1. Xóa tất cả lượt thích của bình luận này
        $stmt = $pdo->prepare("DELETE FROM comment_likes WHERE comment_id = ?");
        $stmt->execute([$comment_id]);

        // 2. Xóa các bình luận trả lời (replies) nếu có
        $stmt = $pdo->prepare("DELETE FROM comments WHERE parent_id = ?");
        $stmt->execute([$comment_id]);

        // 3. Xóa chính bình luận đó
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

// Quay lại trang trước đó
header("HTTP/1.1 303 See Other");
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
?>