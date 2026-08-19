# 📁 Cấu Trúc Dự Án - Khám Phá Cà Mau

## Hướng Dẫn Tổ Chức Dự Án

Dự án đã được tổ chức lại theo cấu trúc MVC (Model-View-Controller) để dễ bảo trì và mở rộng.

## 📂 Cấu Trúc Thư Mục

```
web_gioi-thieu-ca-mau/
├── config/
│   └── db.php              # Cấu hình kết nối CSDL
├── classes/                # Model classes
│   ├── Article.php         # Model cho bài viết
│   ├── Category.php        # Model cho danh mục
│   ├── User.php            # Model cho người dùng
│   └── Comment.php         # Model cho bình luận
├── includes/               # Include files & components
│   ├── header.php          # Header chung
│   ├── footer.php          # Footer chung
│   ├── sidebar.php         # Sidebar (nếu cần)
│   ├── functions.php       # Các hàm utility
│   └── auth.php            # Xử lý authentication
├── css/
│   ├── style.css           # CSS chính (toàn ứng dụng)
│   ├── admin.css           # CSS riêng cho admin panel
│   └── responsive.css      # CSS responsive (nếu cần)
├── assets/
│   ├── images/             # Thư mục lưu ảnh
│   └── js/                 # JavaScript files
├── admin/                  # Admin panel
│   ├── index.php           # Dashboard
│   ├── articles.php        # Quản lý bài viết
│   ├── categories.php      # Quản lý danh mục
│   ├── comments.php        # Quản lý bình luận
│   ├── contacts.php        # Quản lý liên hệ
│   └── users.php           # Quản lý tài khoản
├── index.php               # Trang chủ
├── detail.php              # Chi tiết bài viết
├── category.php            # Danh sách theo danh mục
├── login.php               # Đăng nhập
├── register.php            # Đăng ký
├── logout.php              # Đăng xuất
├── contact.php             # Liên hệ
├── post_comment.php        # Xử lý bình luận
├── favorite.php            # Bài viết yêu thích
├── history.php             # Lịch sử xem
└── README.md               # File hướng dẫn này
```

## 🎯 Cách Sử Dụng

### 1. **Sử dụng Models (Classes)**

#### Lấy tất cả bài viết:
```php
<?php
require_once 'config/db.php';
require_once 'classes/Article.php';

$article = new Article($conn);
$articles = $article->getAll(10, 0); // Lấy 10 bài viết

foreach ($articles as $item) {
    echo $item['title'];
}
?>
```

#### Lấy bài viết theo ID:
```php
<?php
$article = new Article($conn);
$post = $article->getById(1);
echo $post['title'];
?>
```

#### Tìm kiếm bài viết:
```php
<?php
$article = new Article($conn);
$results = $article->search('du lịch', 10, 0);
?>
```

### 2. **Sử dụng Functions**

#### Kiểm tra người dùng đã đăng nhập:
```php
<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    echo "Người dùng đã đăng nhập";
}

if (isAdmin()) {
    echo "Người dùng là Admin";
}
?>
```

#### Upload ảnh:
```php
<?php
try {
    $filename = uploadImage($_FILES['image'], '../assets/images/');
    echo "Ảnh đã tải lên: " . $filename;
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
```

#### Cắt text:
```php
<?php
$text = "Đây là một bài viết rất dài...";
$short = truncateText($text, 50);
echo $short; // "Đây là một bài viết rất dài..."
?>
```

### 3. **Sử dụng Authentication**

#### Đăng nhập:
```php
<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = login($_POST['username'], $_POST['password'], $conn);
    if ($result['success']) {
        redirect($result['redirect']);
    } else {
        showError($result['message']);
    }
}
?>
```

#### Kiểm tra quyền Admin:
```php
<?php
require_once 'includes/auth.php';

// Redirect nếu không phải admin
requireAdmin();
?>
```

### 4. **Sử dụng Layout (Header & Footer)**

Thêm vào đầu trang:
```php
<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang của bạn</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Nội dung chính ở đây -->

<?php require_once 'includes/footer.php'; ?>

</body>
</html>
```

### 5. **CSS - Các Class Chính**

#### Cards:
```html
<div class="card">
    <img src="image.jpg" alt="">
    <div class="card-body">
        <span class="card-category">Du Lịch</span>
        <h4 class="card-title">Tiêu đề bài viết</h4>
        <p class="card-text">Mô tả ngắn...</p>
        <div class="card-meta">
            <span>Lượt xem: 100</span>
            <span>Thích: 50</span>
        </div>
    </div>
</div>
```

#### Grid Layout:
```html
<div class="grid">
    <div class="card">...</div>
    <div class="card">...</div>
    <div class="card">...</div>
</div>
```

#### Buttons:
```html
<button class="btn btn-primary">Nút chính</button>
<button class="btn btn-secondary">Nút phụ</button>
<button class="btn btn-danger">Nút xóa</button>
<button class="btn btn-warning">Nút cảnh báo</button>
```

#### Alerts:
```html
<div class="alert alert-success">Thành công!</div>
<div class="alert alert-danger">Lỗi!</div>
<div class="alert alert-warning">Cảnh báo!</div>
<div class="alert alert-info">Thông tin</div>
```

#### Forms:
```html
<form>
    <div class="form-group">
        <label>Tên:</label>
        <input type="text" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Email:</label>
        <input type="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Gửi</button>
</form>
```

## 🔒 Security Best Practices

✅ **Đã áp dụng:**
- Prepared statements (chống SQL Injection)
- Password hashing với BCRYPT
- Session management
- Input sanitization
- File upload validation

✅ **Khuyến nghị:**
- Luôn sử dụng `htmlspecialchars()` khi hiển thị dữ liệu user
- Kiểm tra quyền trước khi thực hiện hành động
- Validate tất cả input từ user
- Sử dụng HTTPS trong production

## 📝 Lưu Ý Khi Phát Triển

1. **Tách biệt Logic & View:** Sử dụng Models để xử lý logic, View để hiển thị
2. **Tái sử dụng Code:** Dùng Functions & Classes để tránh lặp code
3. **Naming Convention:** 
   - Models: `ClassName.php` (PascalCase)
   - Functions: `functionName()` (camelCase)
   - Database: `table_name` (snake_case)
4. **Comments:** Viết comment cho các function phức tạp
5. **Error Handling:** Luôn dùng try-catch cho database queries

## 🚀 Phát Triển Tiếp

### Các tính năng có thể thêm:
- [ ] Pagination cho danh sách bài viết
- [ ] Full-text search
- [ ] Categories filter
- [ ] User profile page
- [ ] Advanced admin dashboard
- [ ] Analytics
- [ ] API endpoints (JSON)
- [ ] Email notifications
- [ ] Social sharing
- [ ] Rating & Review system

---

**Cập nhật lần cuối:** 2024
**Phiên bản:** 1.0
