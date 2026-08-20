<?php
/**
 * File chứa các hàm utility chung cho toàn ứng dụng
 */

/**
 * Kiểm tra người dùng đã đăng nhập hay chưa
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Kiểm tra người dùng là admin hay không
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Lấy thông tin user hiện tại
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return $_SESSION['user'];
    }
    return null;
}

/**
 * Redirect tới URL khác
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Hiển thị thông báo lỗi
 */
function showError($message) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

/**
 * Hiển thị thông báo thành công
 */
function showSuccess($message) {
    echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

/**
 * Sanitize input (chống XSS)
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Cắt text với giới hạn ký tự
 */
function truncateText($text, $limit = 100, $suffix = '...') {
    $text = strip_tags($text);
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . $suffix;
    }
    return $text;
}

/**
 * Upload file ảnh
 */
function uploadImage($file, $target_dir = '../assets/img/') {
    if (!isset($file) || $file['error'] != 0) {
        return null;
    }

    // Tạo thư mục nếu chưa có
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Kiểm tra loại file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Loại file không hợp lệ. Chỉ chấp nhận JPEG, PNG, GIF, WebP');
    }

    // Kiểm tra kích thước (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File quá lớn. Kích thước tối đa là 5MB');
    }

    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $filename;
    }

    throw new Exception('Lỗi khi tải file lên');
}

/**
 * Định dạng ngày tháng theo kiểu Việt Nam
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Kiểm tra email hợp lệ
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash mật khẩu
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Kiểm tra mật khẩu
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Lấy số lượng bài viết theo danh mục
 */
function getArticleCountByCategory($conn, $category_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM articles WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] ?? 0;
}

/**
 * Lấy bài viết nổi bật (lượt xem cao nhất)
 */
function getFeaturedArticles($conn, $limit = 5) {
    $sql = "SELECT a.*, c.category_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            ORDER BY a.views DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Lấy bài viết mới nhất
 */
function getLatestArticles($conn, $limit = 10) {
    $sql = "SELECT a.*, c.category_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            ORDER BY a.created_at DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Tạo slug từ tiêu đề
 */
function createSlug($string) {
    $string = trim($string);
    $string = mb_strtolower($string, 'UTF-8');
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}
?>
