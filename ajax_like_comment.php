<?php
session_start();
header('Content-Type: application/json');

require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thả tim!']);
    exit();
}

$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
$user_id = $_SESSION['user_id'] ?? 0;

if (!$comment_id || !$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!']);
    exit();
}

// Kiểm tra user đã thả tim chưa
$stmt = $pdo->prepare("SELECT id FROM comment_likes WHERE comment_id = ? AND user_id = ?");
$stmt->execute([$comment_id, $user_id]);
$is_liked = $stmt->fetch();

if ($is_liked) {
    // Nếu đã tim -> Bỏ tim
    $stmt = $pdo->prepare("DELETE FROM comment_likes WHERE comment_id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    $action = 'unliked';
} else {
    // Chưa tim -> Thêm tim
    $stmt = $pdo->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
    $stmt->execute([$comment_id, $user_id]);
    $action = 'liked';
}

// Đếm lại tổng số tim của bình luận này
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM comment_likes WHERE comment_id = ?");
$stmt->execute([$comment_id]);
$total_likes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

echo json_encode([
    'status' => 'success',
    'action' => $action,
    'total_likes' => $total_likes
]);