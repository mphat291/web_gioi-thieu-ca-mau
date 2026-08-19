<?php
// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Ép kiểu header trả về bắt buộc là JSON thuần túy
header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thích bài viết!']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
$article_id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;

if ($article_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID bài viết không hợp lệ!']);
    exit;
}

try {
    // Kiểm tra xem user đã thích bài viết này chưa
    $stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$user_id, $article_id]);
    $liked = $stmt->fetch();

    if ($liked) {
        // Nếu đã thích -> Hủy thích (Chỉ xóa khỏi bảng likes)
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$user_id, $article_id]);
        $action = 'unliked';
    } else {
        // Nếu chưa thích -> Thêm vào bảng likes
        $stmt = $pdo->prepare("INSERT IGNORE INTO likes (user_id, article_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $article_id]);
        $action = 'liked';
    }

    // Đếm lại tổng số lượt thích của bài viết từ database
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM likes WHERE article_id = ?");
    $stmt->execute([$article_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $newLikeCount = isset($result['total']) ? intval($result['total']) : 0;

    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'likeCount' => $newLikeCount
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    exit;
}
?>