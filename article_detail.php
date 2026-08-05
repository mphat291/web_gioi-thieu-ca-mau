<?php
session_start();
require_once 'config/db.php';

$article_id = (int)($_GET['id'] ?? 0);

// Lấy thông tin bài viết
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    die("Bài viết không tồn tại.");
}

// Cập nhật Lịch sử đọc nếu đã đăng nhập
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    
    // Xóa bản ghi cũ nếu có để đưa lần đọc mới nhất lên đầu
    $pdo->prepare("DELETE FROM reading_history WHERE user_id = ? AND article_id = ?")->execute([$user_id, $article_id]);
    // Chèn bản ghi mới
    $pdo->prepare("INSERT INTO reading_history (user_id, article_id, read_at) VALUES (?, ?, NOW())")->execute([$user_id, $article_id]);
}

// Tăng lượt xem của bài viết
$pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article_id]);
?>

<!-- HTML Chi tiết Bài viết -->
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title><?= htmlspecialchars($article['title']) ?></title></head>
<body>
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
</body>
</html>