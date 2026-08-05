<?php
session_start();
require_once 'config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thực hiện hành động này.']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$article_id = (int)($_POST['article_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$article_id) {
    echo json_encode(['status' => 'error', 'message' => 'Bài viết không hợp lệ.']);
    exit;
}

if ($action === 'like') {
    // Kiểm tra xem đã like chưa
    $check = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND article_id = ?");
    $check->execute([$user_id, $article_id]);
    
    if ($check->fetch()) {
        // Bỏ like
        $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND article_id = ?")->execute([$user_id, $article_id]);
        $liked = false;
    } else {
        // Thêm like
        $pdo->prepare("INSERT INTO likes (user_id, article_id) VALUES (?, ?)")->execute([$user_id, $article_id]);
        $liked = true;
    }
    
    // Đếm lại lượt like
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE article_id = ?");
    $countStmt->execute([$article_id]);
    $totalLikes = $countStmt->fetchColumn();

    echo json_encode(['status' => 'success', 'liked' => $liked, 'total_likes' => $totalLikes]);
    exit;
}

if ($action === 'favorite') {
    $check = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND article_id = ?");
    $check->execute([$user_id, $article_id]);
    
    if ($check->fetch()) {
        $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND article_id = ?")->execute([$user_id, $article_id]);
        $favorited = false;
    } else {
        $pdo->prepare("INSERT INTO favorites (user_id, article_id) VALUES (?, ?)")->execute([$user_id, $article_id]);
        $favorited = true;
    }

    echo json_encode(['status' => 'success', 'favorited' => $favorited]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
?>