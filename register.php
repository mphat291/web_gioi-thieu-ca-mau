<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$error = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = register(
        trim($_POST['username'] ?? ''),
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? '',
        $_POST['confirm_password'] ?? '',
        $pdo
    );
    
    if ($result['success']) {
        $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
        redirect('login.php');
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
    <title>Đăng Ký - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<div class="container-sm py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #006633 0%, #004d24 100%) !important;">
                    <h3 class="mb-0 text-center fw-bold">📝 Đăng Ký</h3>
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
                                placeholder="Chọn tên đăng nhập" 
                                minlength="3"
                                required>
                            <div class="form-text">Tối thiểu 3 ký tự</div>
                            <div class="invalid-feedback">
                                Vui lòng nhập tên đăng nhập hợp lệ.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">📧 Email</label>
                            <input 
                                type="email" 
                                class="form-control form-control-lg" 
                                id="email"
                                name="email" 
                                placeholder="Nhập email của bạn" 
                                required>
                            <div class="invalid-feedback">
                                Vui lòng nhập email hợp lệ.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">🔐 Mật Khẩu</label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg" 
                                id="password"
                                name="password" 
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" 
                                minlength="6"
                                required>
                            <div class="form-text">Tối thiểu 6 ký tự</div>
                            <div class="invalid-feedback">
                                Vui lòng nhập mật khẩu hợp lệ.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label fw-bold">🔐 Xác Nhận Mật Khẩu</label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg" 
                                id="confirm_password"
                                name="confirm_password" 
                                placeholder="Nhập lại mật khẩu" 
                                minlength="6"
                                required>
                            <div class="invalid-feedback">
                                Mật khẩu không trùng khớp.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                            <label class="form-check-label" for="agree">
                                Tôi đồng ý với 
                                <a href="#" class="text-decoration-none">điều khoản dịch vụ</a>
                            </label>
                            <div class="invalid-feedback">
                                Vui lòng đồng ý với điều khoản.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                            📝 Đăng Ký
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center text-muted mb-0">
                        Đã có tài khoản? 
                        <a href="login.php" class="fw-bold text-decoration-none">
                            🔓 Đăng Nhập
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

    // Check password match
    document.getElementById('confirm_password').addEventListener('change', function() {
        const password = document.getElementById('password').value;
        const confirm = this.value;
        if (password !== confirm) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
</script>

</body>
</html>