<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $content = sanitize($_POST['content'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($content)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường';
    } elseif (!isValidEmail($email)) {
        $error = 'Email không hợp lệ';
    } else {
        try {
            // Lưu vào CSDL
            $stmt = $pdo->prepare(
                "INSERT INTO contacts (name, email, subject, message, created_at) 
                 VALUES (?, ?, ?, ?, NOW())"
            );
            $stmt->execute([$name, $email, $subject, $content]);
            
            $message = '✅ Cảm ơn bạn! Tin nhắn của bạn đã được gửi. Chúng tôi sẽ liên hệ lại sớm.';
        } catch (Exception $e) {
            $error = 'Lỗi: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container-lg py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold">📧 Liên Hệ Với Chúng Tôi</h1>
        <p class="lead text-muted">Có câu hỏi? Chúng tôi rất vui được nghe từ bạn!</p>
    </div>

    <div class="row g-4">
        <!-- Thông tin liên hệ -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📍 Địa Chỉ</h5>
                    <p class="text-muted">
                        Cà Mau<br>
                        Việt Nam
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📞 Điện Thoại</h5>
                    <p class="text-muted">
                        <a href="tel:+84123456789" class="text-decoration-none">
                            +84 (123) 456 789
                        </a>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📧 Email</h5>
                    <p class="text-muted">
                        <a href="mailto:info@camau.com" class="text-decoration-none">
                            info@camau.com
                        </a>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-warning">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">⏰ Giờ Làm Việc</h6>
                    <p class="small mb-0">
                        Thứ 2 - Thứ 7: 8:00 - 17:00<br>
                        Chủ Nhật: Nghỉ
                    </p>
                </div>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #006633 0%, #004d24 100%) !important;">
                    <h5 class="mb-0 fw-bold">📬 Gửi Tin Nhắn</h5>
                </div>
                
                <div class="card-body p-4">
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">👤 Họ Tên</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                   placeholder="Nhập họ tên của bạn" required>
                            <div class="invalid-feedback">Vui lòng nhập họ tên</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">📧 Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                   placeholder="Nhập email của bạn" required>
                            <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">📝 Chủ Đề</label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject" 
                                   placeholder="Chủ đề của tin nhắn" required>
                            <div class="invalid-feedback">Vui lòng nhập chủ đề</div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fw-bold">💬 Nội Dung Tin Nhắn</label>
                            <textarea class="form-control form-control-lg" id="content" name="content" rows="5" 
                                      placeholder="Nhập nội dung tin nhắn của bạn" required></textarea>
                            <div class="invalid-feedback">Vui lòng nhập nội dung tin nhắn</div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            ✉️ Gửi Tin Nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map (nếu muốn thêm) -->
    <div class="mt-5">
        <h3 class="fw-bold mb-4">🗺️ Bản Đồ</h3>
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.897969451215!2d104.75145!3d8.7279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31ea1f5f5f5f5f5f%3A0x5f5f5f5f5f5f5f5f!2sCA%20MAU%2C%20Vietnam!5e0!3m2!1sen!2s!4v1234567890"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

</body>
</html>
