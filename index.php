<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Nhập file cần thiết
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/Article.php';
require_once __DIR__ . '/classes/Category.php';

// Khởi tạo các object
$article = new Article($pdo);
$category = new Category($pdo);

// Lấy danh sách bài viết mới nhất và danh mục
$articles = $article->getAll(12, 0);
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Cà Mau - Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thêm FontAwesome để hiển thị icon bookmark -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<!-- Hero Section dạng ảnh nền full-width -->
<section class="hero-section text-white text-center position-relative py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/Cà-mau.jpg'); background-size: cover; background-position: center; min-height: 450px; display: flex; align-items: center;">
    <div class="container-lg">
        <h1 class="display-4 fw-bold mb-3 text-white"> Chào Mừng Đến Với Cà Mau</h1>
        <p class="lead mb-4 text-light">Khám phá vẻ đẹp thiên nhiên, văn hóa, và ẩm thực độc đáo của mảnh đất phía Nam</p>
        <a href="#articles" class="btn btn-warning btn-lg fw-bold px-4 py-3 shadow">📖 Bắt Đầu Đọc Ngay</a>
    </div>
</section>

<main class="container-lg py-5">
    <!-- Bài Viết Nổi Bật -->
    <section class="mb-5" id="featured">
        <div class="mb-4">
            <h2 class="display-6 fw-bold">🔥 Bài Viết Nổi Bật</h2>
            <div class="border-bottom border-warning" style="width: 100px;"></div>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($articles) && count($articles) > 0): ?>
                <?php foreach (array_slice($articles, 0, 3) as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                            <div class="position-relative">
                                <img src="assets/images/<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'default.jpg' ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>"
                                     style="height: 200px; object-fit: cover; display: block;"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 200%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22%3ECà Mau%3C/text%3E%3C/svg%3E';">
                                <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Chung') ?>
                                </span>
                                <!-- Nút lưu nhanh góc trên bên phải -->
                                <a href="save_post.php?id=<?= $item['id'] ?>" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm fw-bold d-flex align-items-center gap-1 px-2 py-1" style="background: rgba(255, 255, 255, 0.95); font-size: 0.75rem; border-radius: 20px;" title="Lưu đọc sau">
                                    <i class="fa-solid fa-bookmark text-warning"></i> Lưu
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                <h5 class="card-title fw-bold text-dark" style="line-height: 1.3; height: 2.6em; overflow: hidden; white-space: normal;"><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="card-text text-muted" style="height: 4.5em; line-height: 1.5; overflow: hidden; white-space: normal;"><?= strip_tags($item['content']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-secondary">
                                        👁️ <?= $item['views'] ?? 0 ?> | ❤️ <?= $item['real_likes'] ?? 0 ?>
                                    </small>
                                </div>
                                <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary mt-3">
                                    Đọc Tiếp →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                     Chưa có bài viết nào.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <hr class="my-5">

    <!-- Tất Cả Bài Viết (Full chiều ngang, không có sidebar) -->
    <section id="articles">
        <div class="mb-4">
            <h2 class="display-6 fw-bold"> Tất Cả Bài Viết</h2>
            <div class="border-bottom border-warning" style="width: 100px;"></div>
        </div>

        <div class="row g-4">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $item): ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                            <div class="position-relative">
                                <img src="assets/images/<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'default.jpg' ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>"
                                     style="height: 180px; object-fit: cover; display: block;"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 180%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22180%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22%3ECà Mau%3C/text%3E%3C/svg%3E';" >
                                <span class="badge bg-info position-absolute top-0 start-0 m-2">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Chung') ?>
                                </span>
                                <!-- Nút lưu nhanh góc trên bên phải -->
                                <a href="save_post.php?id=<?= $item['id'] ?>" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm fw-bold d-flex align-items-center gap-1 px-2 py-1" style="background: rgba(255, 255, 255, 0.95); font-size: 0.75rem; border-radius: 20px;" title="Lưu đọc sau">
                                    <i class="fa-solid fa-bookmark text-warning"></i> Lưu
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                <h6 class="card-title fw-bold text-dark" style="line-height: 1.3; height: 2.6em; overflow: hidden; white-space: normal;"><?= htmlspecialchars($item['title']) ?></h6>
                                <p class="card-text text-muted small" style="height: 3em; line-height: 1.5; overflow: hidden; white-space: normal;"><?= strip_tags($item['content']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2 small text-secondary">
                                    <span>👁️ <?= $item['views'] ?? 0 ?></span>
                                    <span>❤️ <?= $item['real_likes'] ?? 0 ?></span>
                                </div>
                                <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary mt-3">
                                    Xem Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        📭 Chưa có bài viết nào.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Call To Action -->
    <section class="mt-5 p-5 bg-warning rounded-3 text-dark text-center">
        <h3 class="fw-bold mb-3">📸 Bạn Có Câu Chuyện Hay?</h3>
        <p class="mb-4">Chia sẻ kinh nghiệm du lịch, khám phá văn hóa, và ẩm thực Cà Mau của bạn với chúng tôi!</p>
        <?php if (isLoggedIn()): ?>
            <a href="submit_article.php" class="btn btn-primary btn-lg">📝 Viết Bài Viết</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary btn-lg">🔑 Đăng Nhập Để Viết Bài</a>
        <?php endif; ?>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>