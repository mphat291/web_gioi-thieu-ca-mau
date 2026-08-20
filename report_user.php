<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra xem đã đăng nhập chưa
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reported_user_id = $_POST['reported_user_id'] ?? 0;
    $reason = trim($_POST['reason'] ?? '');
    $reporter_id = $_SESSION['user_id'];

    if ($reporter_id == $reported_user_id) {
        echo "<script>alert('Bạn không thể tự tố cáo chính mình!'); window.history.back();</script>";
        exit;
    }

    if ($reported_user_id > 0 && !empty($reason)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO user_reports (reporter_id, reported_user_id, reason) VALUES (?, ?, ?)");
            $stmt->execute([$reporter_id, $reported_user_id, $reason]);

            echo "<script>alert('Cảm ơn bạn, báo cáo người dùng đã được gửi đến Admin!'); window.history.back();</script>";
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Vui lòng nhập lý do tố cáo!'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
}
?>