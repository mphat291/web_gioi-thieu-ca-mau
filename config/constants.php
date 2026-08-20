<?php
/**
 * File constants.php - Khai báo các hằng số cấu hình
 * Sử dụng cho toàn ứng dụng
 */

// ===== URL & PATH =====
define('BASE_URL', 'http://localhost/web_gioi-thieu-ca-mau');
define('SITE_NAME', 'Khám Phá Cà Mau');
define('BASEPATH', dirname(dirname(__FILE__)));
define('UPLOAD_PATH', BASEPATH . '/assets/img/');

// ===== PAGINATION =====
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// ===== FILE UPLOAD =====
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// ===== DATE FORMAT =====
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i');
define('DB_DATE_FORMAT', 'Y-m-d H:i:s');

// ===== ROLES =====
define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'user');

// ===== COMMENT STATUS =====
define('COMMENT_PENDING', 'pending');
define('COMMENT_APPROVED', 'approved');
define('COMMENT_REJECTED', 'rejected');

// ===== APP SETTINGS =====
define('ITEMS_PER_PAGE_FRONTEND', 12);
define('ARTICLES_FEATURED', 3);
define('COMMENTS_PER_PAGE', 20);
define('SEARCH_MIN_LENGTH', 2);

// ===== CONTACT SETTINGS =====
define('CONTACT_EMAIL', 'info@camau.com');
define('CONTACT_PHONE', '+84 123 456 789');

// ===== EMAIL SETTINGS =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_password');
define('SMTP_FROM', 'noreply@camau.com');

// ===== TIMEZONE =====
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ===== SESSION =====
define('SESSION_TIMEOUT', 3600); // 1 giờ
define('SESSION_NAME', 'CAMAU_SESSION');

// ===== FUNCTION: Lấy URL đầy đủ cho ảnh =====
if (!function_exists('getImageUrl')) {
    function getImageUrl($filename) {
        if (empty($filename)) {
            return BASE_URL . '/assets/img/default.jpg';
        }
        return BASE_URL . '/assets/img/' . $filename;
    }
}

// ===== FUNCTION: Lấy URL đầy đủ =====
if (!function_exists('getFullUrl')) {
    function getFullUrl($path) {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// ===== FUNCTION: Lấy tên site =====
if (!function_exists('getSiteName')) {
    function getSiteName() {
        return SITE_NAME;
    }
}
?>
