<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = (int)($_POST['article_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user']['id'];

    if ($article_id > 0 && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$article_id, $user_id, $content]);
    }
    
    header("Location: article_detail.php?id=" . $article_id);
    exit;
}
?>