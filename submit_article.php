<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/Category.php';

if (!function_exists('isLoggedIn') || !isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$category = new Category($pdo);
$categories = $category->getAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content) || empty($category_id)) {
        $error = 'Vui lòng điền đầy đủ tiêu đề, danh mục và nội dung bài viết!';
    } else {
        $imageName = 'default.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/assets/images/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imageName = $newFileName;
                }
            }
        }

        try {
            // Lưu bài viết với trạng thái chờ duyệt 'pending'
            $stmt = $pdo->prepare("INSERT INTO articles (title, category_id, content, image, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
            $stmt->execute([$title, $category_id, $content, $imageName]);
            
            $success = '🎉 Gửi bài viết thành công! Bài viết của bạn đang chờ Admin kiểm duyệt.';
        } catch (PDOException $e) {
            $error = 'Lỗi cơ sở dữ liệu: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viết Bài Mới - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-pen-to-square"></i> Viết Bài Chia Sẻ Mới</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <div class="mt-3">
                            <a href="index.php" class="btn btn-sm btn-primary">Về Trang Chủ</a>
                            <a href="submit_article.php" class="btn btn-sm btn-outline-secondary">Viết Bài Khác</a>
                        </div>
                    </div>
                <?php else: ?>

                    <form action="submit_article.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Nhập tiêu đề hấp dẫn...">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name'] ?? '') ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Ảnh bìa bài viết</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Chọn định dạng ảnh (jpg, png, webp).</div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fw-bold">Nội dung chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="8" required placeholder="Chia sẻ câu chuyện, trải nghiệm của bạn..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">← Quay Lại</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">🚀 Đăng Bài Viết</button>
                        </div>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>