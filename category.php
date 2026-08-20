<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';
require_once 'classes/Category.php';

$article_obj = new Article($pdo);
$category_obj = new Category($pdo);

// Lấy ID danh mục (hoặc từ URL parameter)
$category_id = $_GET['id'] ?? $_GET['category'] ?? 0;
$search = $_GET['search'] ?? '';

$articles = [];
$category = null;
$page_title = 'Tất Cả Bài Viết';

if ($category_id) {
    $category = $category_obj->getById($category_id);
    if (!$category) {
        redirect('index.php');
    }
    $articles = $article_obj->getByCategory($category_id, 12, 0);
    $page_title = htmlspecialchars($category['category_name']);

    // Nếu có thêm từ khóa tìm kiếm trong danh mục này, lọc trực tiếp mảng bài viết
    if (!empty($search)) {
        $articles = array_filter($articles, function($item) use ($search) {
            return mb_stripos($item['title'], $search) !== false || mb_stripos($item['content'], $search) !== false;
        });
        $page_title .= ' - Tìm kiếm: ' . htmlspecialchars($search);
    }
} elseif ($search) {
    $articles = $article_obj->search($search, 12, 0);
    $page_title = 'Kết quả tìm kiếm: ' . htmlspecialchars($search);
} else {
    $articles = $article_obj->getAll(12, 0);
}

$categories = $category_obj->getAll();

// Hàm hỗ trợ xử lý đường dẫn ảnh thông minh cho assets/img/
function getArticleImage($imageName) {
    if (empty($imageName)) {
        return 'assets/img/bacbaphi.jpg';
    }
    
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
    <title><?= $page_title ?> - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container-lg py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">🏠 Trang Chủ</a></li>
            <li class="breadcrumb-item active"><?= $page_title ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-5">
        <h1 class="display-6 fw-bold mb-3"><?= $page_title ?></h1>
        <div class="border-bottom border-warning" style="width: 100px;"></div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <?php require_once 'includes/sidebar.php'; ?>
        </div>

        <!-- Content -->
        <div class="col-lg-9">
            <?php if (!empty($articles)): ?>
                <div class="row g-4">
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                                <div class="position-relative">
                                    <img src="<?= getArticleImage($article['image'] ?? '') ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($article['title']) ?>"
                                         style="height: 200px; object-fit: cover; display: block;"
                                         onerror="this.src='assets/img/bacbaphi.jpg';">
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                        <?= htmlspecialchars($article['category_name'] ?? 'Chung') ?>
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                    <h5 class="card-title fw-bold" style="line-height: 1.3; height: 2.6em; overflow: hidden; white-space: normal;">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </h5>
                                    <p class="card-text text-muted" style="height: 4.5em; line-height: 1.5; overflow: hidden; white-space: normal;">
                                        <?= strip_tags($article['content']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mb-3 small text-secondary">
                                        <span>👁️ <?= $article['views'] ?? 0 ?></span>
                                        <span>❤️ <?= $article['real_likes'] ?? 0 ?></span>
                                        <span><?= formatDate($article['created_at'], 'd/m') ?></span>
                                    </div>
                                    <a href="detail.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-primary mt-auto">
                                        Đọc Tiếp →
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center py-5">
                    <h5>📭 Không Tìm Thấy Bài Viết</h5>
                    <p class="mb-0">Hiện tại chưa có bài viết nào phù hợp với yêu cầu của bạn.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Live Search mượt mà -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        const articleCols = document.querySelectorAll('.col-lg-9 .row.g-4 > .col-md-6');

        articleCols.forEach(col => {
            const titleEl = col.querySelector('.card-title');
            const textEl = col.querySelector('.card-text');
            
            const title = titleEl ? titleEl.textContent.toLowerCase() : '';
            const text = textEl ? textEl.textContent.toLowerCase() : '';

            if (title.includes(keyword) || text.includes(keyword)) {
                col.style.display = ''; // Hiện bài viết khớp
            } else {
                col.style.display = 'none'; // Ẩn bài viết không khớp
            }
        });
    });
});
</script>

</body>
</html>