<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu bài viết!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$article_id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;

if ($article_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID bài viết không hợp lệ!']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$user_id, $article_id]);
    $favorited = $stmt->fetch();

    if ($favorited) {
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$user_id, $article_id]);
        $action = 'unfavorited';
    } else {
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, article_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $article_id]);
        $action = 'favorited';
    }

    echo json_encode(['status' => 'success', 'action' => $action]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>