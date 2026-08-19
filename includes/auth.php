<?php
/**
 * File xử lý xác thực người dùng (Authentication)
 */

if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(dirname(__FILE__)));
}

// Yêu cầu các file cần thiết
require_once BASEPATH . '/config/db.php';
require_once BASEPATH . '/includes/functions.php';

/**
 * Kiểm tra quyền truy cập Admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        redirect('../index.php');
    }
}

/**
 * Kiểm tra người dùng đã đăng nhập
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

/**
 * Xử lý đăng nhập
 */
function login($username, $password, $conn) {
    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Vui lòng nhập tên đăng nhập và mật khẩu'];
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Tên đăng nhập không tồn tại'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không chính xác'];
        }

        // Đặt session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        // Redirect theo role
        if ($user['role'] === 'admin') {
            return ['success' => true, 'redirect' => 'admin/index.php'];
        } else {
            return ['success' => true, 'redirect' => 'index.php'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
    }
}

/**
 * Xử lý đăng ký
 */
function register($username, $email, $password, $confirm_password, $conn) {
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'];
    }

    if (!isValidEmail($email)) {
        return ['success' => false, 'message' => 'Email không hợp lệ'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
    }

    if ($password !== $confirm_password) {
        return ['success' => false, 'message' => 'Mật khẩu không trùng khớp'];
    }

    try {
        // Kiểm tra username đã tồn tại
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại'];
        }

        // Kiểm tra email đã tồn tại
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email đã được đăng ký'];
        }

        // Tạo tài khoản mới
        $hashed_password = hashPassword($password);
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role, created_at) 
             VALUES (?, ?, ?, 'user', NOW())"
        );
        $stmt->execute([$username, $email, $hashed_password]);

        $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
        return ['success' => true, 'redirect' => 'login.php'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
    }
}

/**
 * Xử lý đăng xuất
 */
function logout() {
    session_destroy();
    redirect('index.php');
}
?>
