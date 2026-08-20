<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    echo "<script>
        alert('Ní cần đăng nhập để thực hiện tố cáo!');
        window.history.back();
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    $reason = trim($_POST['reason'] ?? '');
    $detail = trim($_POST['detail'] ?? '');
    $reporter_id = $_SESSION['user_id'] ?? 0;

    if ($comment_id > 0 && !empty($reason)) {
        try {
            // Gộp Lý do + Chi tiết
            $full_reason = $reason;
            if (!empty($detail)) {
                $full_reason .= " (Chi tiết: " . $detail . ")";
            }

            // Sửa tên bảng lưu thành comment_reports cho khớp CSDL
            $stmt = $pdo->prepare("INSERT INTO comment_reports (comment_id, user_id, reason, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$comment_id, $reporter_id, $full_reason]);

            echo "<script>
                alert('Báo cáo của bạn đã được gửi thành công đến Admin!');
                window.location.href = '" . ($_SERVER['HTTP_REFERER'] ?? 'index.php') . "';
            </script>";
            exit();

        } catch (PDOException $e) {
            echo "<script>
                alert('Lỗi lưu dữ liệu: " . addslashes($e->getMessage()) . "');
                window.history.back();
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Thông tin tố cáo không hợp lệ!');
            window.history.back();
        </script>";
        exit();
    }
}