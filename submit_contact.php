<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ các thông tin bắt buộc!";
        header("Location: contact.php");
        exit;
    }

    // Tự động gộp Chủ đề vào Nội dung để không cần tạo cột subject trong Database
    $full_message = "Chủ đề: " . $subject . "\n\n" . $message;

    try {
        // Chỉ lưu vào các cột mặc định có sẵn (name, email, message, created_at)
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $full_message]);

        $_SESSION['success'] = "Gửi liên hệ thành công! Cảm ơn bạn đã phản hồi.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    }
}

header("Location: contact.php");
exit;