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
    $comment_id = $_POST['comment_id'] ?? 0;
    $reason = $_POST['reason'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($comment_id > 0 && !empty($reason)) {
        try {
            // Câu lệnh INSERT khớp chính xác với cấu trúc bảng hiện tại của ní
            $stmt = $pdo->prepare("INSERT INTO comment_reports (comment_id, user_id, reason) VALUES (?, ?, ?)");
            $stmt->execute([$comment_id, $user_id, $reason]);
            
            echo "<script>alert('Cảm ơn ní, báo cáo của ní đã được gửi đến Admin!'); window.history.back();</script>";
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Vui lòng chọn lý do tố cáo!'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
}
?>