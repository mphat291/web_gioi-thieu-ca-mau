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
        $error = 'Vui lòng điền đầy đủ tất cả các trường!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Định dạng email không hợp lệ!';
    } else {
        try {
            // Lưu vào CSDL bảng contacts
            $stmt = $pdo->prepare(
                "INSERT INTO contacts (name, email, subject, message, created_at) 
                 VALUES (?, ?, ?, ?, NOW())"
            );
            $stmt->execute([$name, $email, $subject, $content]);
            
            $message = '✅ Cảm ơn bạn! Tin nhắn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm.';
        } catch (Exception $e) {
            $error = 'Lỗi hệ thống: ' . $e->getMessage();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php require_once 'includes/header.php'; ?>

<main class="container-lg py-5 flex-grow-1">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold text-success mb-2">📧 Liên Hệ Với Chúng Tôi</h1>
        <p class="lead text-muted">Có câu hỏi hoặc đóng góp? Chúng tôi rất vui được nghe từ bạn!</p>
    </div>

    <div class="row g-4 align-items-stretch">
        <!-- Thông tin liên hệ -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="card shadow-sm border-0 p-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2 text-success"><i class="fa-solid fa-location-dot me-2"></i>Địa Chỉ</h5>
                        <p class="text-muted mb-0">Cà Mau, Việt Nam</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2 text-primary"><i class="fa-solid fa-phone me-2"></i>Điện Thoại</h5>
                        <p class="text-muted mb-0">
                            <a href="tel:+84123456789" class="text-decoration-none text-muted">+84 (09) 423 987 74</a>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2 text-warning"><i class="fa-solid fa-envelope me-2"></i>Email</h5>
                        <p class="text-muted mb-0">
                            <a href="mailto:info@camau.com" class="text-decoration-none text-muted">24210501030@student.bdu.edu.vn</a>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 bg-warning bg-opacity-15 p-3 border-start border-warning border-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2 text-dark"><i class="fa-regular fa-clock me-2 text-warning"></i>Giờ Làm Việc</h6>
                        <p class="small text-secondary mb-0">
                            Thứ 2 - Thứ 7: 8:00 - 17:00<br>
                            Chủ Nhật: Nghỉ
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header text-white py-3 px-4" style="background: linear-gradient(135deg, #006633 0%, #004d24 100%) !important;">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-paper-plane me-2"></i>Gửi Tin Nhắn</h5>
                </div>
                
                <div class="card-body p-4 d-flex flex-column justify-content-between">
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

                    <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column justify-content-between" novalidate>
                        <div>
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">👤 Họ Tên *</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ tên của bạn" required>
                                <div class="invalid-feedback">Vui lòng nhập họ tên</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">📧 Email *</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn" required>
                                <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">📝 Chủ Đề *</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Chủ đề của tin nhắn" required>
                                <div class="invalid-feedback">Vui lòng nhập chủ đề</div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">💬 Nội Dung Tin Nhắn *</label>
                                <textarea class="form-control" id="content" name="content" rows="4" placeholder="Nhập nội dung tin nhắn của bạn..." required></textarea>
                                <div class="invalid-feedback">Vui lòng nhập nội dung tin nhắn</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-2 mt-3">
                            <i class="fa-solid fa-paper-plane me-2"></i> Gửi Tin Nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bản Đồ Google Map -->
    <div class="mt-5">
        <h3 class="fw-bold mb-3 text-success"><i class="fa-solid fa-map-location-dot me-2"></i>Bản Đồ</h3>
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm border">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d251641.57962208154!2d105.008434!3d9.176426!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a149bc2e118933%3A0xb340cf36f2a2491a!2zQ8OgIE1hdSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bootstrap validation script
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