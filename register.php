<?php
session_start();
require_once 'config/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) $errors[] = "Chưa nhập tên tài khoản.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
    if (strlen($password) < 6) $errors[] = "Mật khẩu phải từ 6 ký tự trở lên.";

    if (empty($errors)) {
        // Kiểm tra trùng username/email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Tên đăng nhập hoặc Email đã tồn tại.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$username, $email, $hashedPassword]);
            $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập.";
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!-- Form mẫu Đăng ký -->
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Đăng ký</title></head>
<body>
    <h2>Đăng ký tài khoản</h2>
    <?php if ($errors): ?>
        <ul style="color:red;"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mật khẩu" required><br>
        <button type="submit">Đăng ký</button>
    </form>
</body>
</html>