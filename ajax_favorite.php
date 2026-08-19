<?php
session_start();
header('Content-Type: application/json');

require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu bài viết!']);
    exit();
}

$article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;
$user_id = $_SESSION['user_id'] ?? 0;

if (!$article_id || !$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!']);
    exit();
}

$article_obj = new Article($pdo);

// Toggle trạng thái lưu / bỏ lưu bài viết
if ($article_obj->isFavoritedByUser($article_id, $user_id)) {
    $result = $article_obj->removeFavoriteByUser($article_id, $user_id);
    if ($result) {
        echo json_encode(['status' => 'success', 'action' => 'unfavorited', 'message' => 'Đã bỏ lưu bài viết']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi bỏ lưu!']);
    }
} else {
    $result = $article_obj->addFavoriteByUser($article_id, $user_id);
    if ($result) {
        echo json_encode(['status' => 'success', 'action' => 'favorited', 'message' => 'Đã lưu bài viết thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu bài viết!']);
    }
}