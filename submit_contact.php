<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    if (!empty($fullname) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contacts (fullname, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$fullname, $email, $message]);
        $_SESSION['contact_success'] = "Gửi thông tin liên hệ thành công!";
    } else {
        $_SESSION['contact_error'] = "Vui lòng nhập đầy đủ thông tin hợp lệ.";
    }

    header("Location: contact.php");
    exit;
}
?>