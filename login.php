<?php
session_start();
require_once 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Sai tên đăng nhập/email hoặc mật khẩu.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Đăng nhập</title></head>
<body>
    <h2>Đăng nhập</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username hoặc Email" required><br>
        <input type="password" name="password" placeholder="Mật khẩu" required><br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>