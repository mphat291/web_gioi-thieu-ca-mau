<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$error = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '', $pdo);
    
    if ($result['success']) {
        redirect($result['redirect']);
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<div class="container-sm py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #006633 0%, #004d24 100%) !important;">
                    <h3 class="mb-0 text-center fw-bold">🔓 Đăng Nhập</h3>
                </div>
                
                <div class="card-body p-4">
                    <!-- Thông báo thành công -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✅ <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Thông báo lỗi -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ❌ <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">👤 Tên Đăng Nhập</label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="username"
                                name="username" 
                                placeholder="Nhập tên đăng nhập" 
                                required>
                            <div class="invalid-feedback">
                                Vui lòng nhập tên đăng nhập.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">🔐 Mật Khẩu</label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg" 
                                id="password"
                                name="password" 
                                placeholder="Nhập mật khẩu" 
                                required>
                            <div class="invalid-feedback">
                                Vui lòng nhập mật khẩu.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ghi nhớ tôi
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            🔓 Đăng Nhập
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center text-muted mb-0">
                        Chưa có tài khoản? 
                        <a href="register.php" class="fw-bold text-decoration-none">
                            📝 Đăng Ký Ngay
                        </a>
                    </p>
                </div>
            </div>

            <!-- Hỗ trợ -->
            <div class="mt-4 text-center text-muted small">
                <p>
                    <a href="contact.php" class="text-decoration-none">📧 Cần hỗ trợ?</a> | 
                    <a href="index.php" class="text-decoration-none">🏠 Về Trang Chủ</a>
                </p>
            </div>
        </div>
    </div>
</div>

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