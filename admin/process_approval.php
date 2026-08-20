<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Kiểm tra quyền Admin
if (!isLoggedIn() || ($_SESSION['role'] ?? $_SESSION['user']['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if ($id > 0 && in_array($action, ['approve', 'reject'])) {
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    
    $stmt = $pdo->prepare("UPDATE articles SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    $_SESSION['flash_msg'] = ($action === 'approve') 
        ? "✅ Đã duyệt bài viết thành công!" 
        : "❌ Đã từ chối bài viết!";
}

header("Location: approve.php");
exit();