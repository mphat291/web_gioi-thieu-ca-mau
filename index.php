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

// 1. Lấy 3 bài viết nổi bật (join chính xác với bảng categories qua cột category_name)
$featured_stmt = $pdo->query("SELECT a.*, c.category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.views DESC LIMIT 3");
$featured_articles = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Cấu hình phân trang cho "Tất Cả Bài Viết"
$limit = 6; // 6 bài viết mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Đếm tổng số bài viết để tính số trang
$total_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$total_pages = ceil($total_articles / $limit);

// Lấy danh sách bài viết theo phân trang
$articles = $article->getAll($limit, $offset);
$categories = $category->getAll();

// Hàm hỗ trợ xử lý đường dẫn ảnh thông minh cho assets/img/
function getArticleImage($imageName) {
    if (empty($imageName)) {
        return 'assets/img/bacbaphi.jpg';
    }
    
    $imageName = str_replace('assets/images/', 'assets/img/', $imageName);

    if (strpos($imageName, 'assets/img/') === 0) {
        return htmlspecialchars($imageName);
    }
    
    return 'assets/img/' . htmlspecialchars($imageName);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Cà Mau - Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section text-white text-center position-relative py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/img/Cà-mau.jpg'); background-size: cover; background-position: center; min-height: 450px; display: flex; align-items: center;">
    <div class="container-lg">
        <h1 class="display-4 fw-bold mb-3 text-white">Chào Mừng Đến Với Cà Mau</h1>
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
            <?php if (!empty($featured_articles) && count($featured_articles) > 0): ?>
                <?php foreach ($featured_articles as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                            <div class="position-relative">
                                <img src="<?= getArticleImage($item['image'] ?? '') ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     style="height: 200px; object-fit: cover; display: block;"
                                     onerror="this.src='assets/img/bacbaphi.jpg';">
                                <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Chung') ?>
                                </span>
                                <a href="save_post.php?id=<?= $item['id'] ?>" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm fw-bold d-flex align-items-center gap-1 px-2 py-1" style="background: rgba(255, 255, 255, 0.95); font-size: 0.75rem; border-radius: 20px;" title="Lưu đọc sau">
                                    <i class="fa-solid fa-bookmark text-warning"></i> Lưu
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                <h5 class="card-title fw-bold text-dark" style="line-height: 1.3; height: 2.6em; overflow: hidden; white-space: normal;"><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="card-text text-muted" style="height: 4.5em; line-height: 1.5; overflow: hidden; white-space: normal;"><?= strip_tags($item['content']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-secondary">
                                        👁️ <?= $item['views'] ?? 0 ?>
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
                    Chưa có bài viết nổi bật nào.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <hr class="my-5">

    <!-- Tất Cả Bài Viết -->
    <section id="articles">
        <div class="mb-4">
            <h2 class="display-6 fw-bold">Tất Cả Bài Viết</h2>
            <div class="border-bottom border-warning" style="width: 100px;"></div>
        </div>

        <div class="row g-4">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $item): ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                            <div class="position-relative">
                                <img src="<?= getArticleImage($item['image'] ?? '') ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     style="height: 180px; object-fit: cover; display: block;"
                                     onerror="this.src='assets/img/bacbaphi.jpg';">
                                <span class="badge bg-info position-absolute top-0 start-0 m-2">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Chung') ?>
                                </span>
                                <a href="save_post.php?id=<?= $item['id'] ?>" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm fw-bold d-flex align-items-center gap-1 px-2 py-1" style="background: rgba(255, 255, 255, 0.95); font-size: 0.75rem; border-radius: 20px;" title="Lưu đọc sau">
                                    <i class="fa-solid fa-bookmark text-warning"></i> Lưu
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                <h6 class="card-title fw-bold text-dark" style="line-height: 1.3; height: 2.6em; overflow: hidden; white-space: normal;"><?= htmlspecialchars($item['title']) ?></h6>
                                <p class="card-text text-muted small" style="height: 3em; line-height: 1.5; overflow: hidden; white-space: normal;"><?= strip_tags($item['content']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2 small text-secondary">
                                    <span>👁️ <?= $item['views'] ?? 0 ?></span>
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

        <!-- Phân Trang cho Tất Cả Bài Viết -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>#articles"><i class="fa-solid fa-angle-left"></i> Trang trước</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>#articles"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>#articles">Trang sau <i class="fa-solid fa-angle-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </section>

    <!-- Call To Action -->
    <section class="mt-5 p-5 bg-warning rounded-3 text-dark text-center">
        <h3 class="fw-bold mb-3">📸 Bạn Có Câu Chuyện Hay?</h3>
        <p class="mb-4">Chia sẻ kinh nghiệm du lịch, khám phá văn hóa, và ẩm thực Cà Mau của bạn với chúng tôi!</p>
        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
            <a href="submit_article.php" class="btn btn-primary btn-lg">📝 Viết Bài Viết</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary btn-lg">🔑 Đăng Nhập Để Viết Bài</a>
        <?php endif; ?>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>