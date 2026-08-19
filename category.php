<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';
require_once 'classes/Category.php';

$article_obj = new Article($pdo);
$category_obj = new Category($pdo);

// Lấy ID danh mục (hoặc từ URL parameter)
$category_id = $_GET['id'] ?? $_GET['cat'] ?? 0;
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
} elseif ($search) {
    $articles = $article_obj->search($search, 12, 0);
    $page_title = 'Kết quả tìm kiếm: ' . htmlspecialchars($search);
} else {
    $articles = $article_obj->getAll(12, 0);
}

$categories = $category_obj->getAll();
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">🔍 Bộ Lọc</h6>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary" type="submit">🔍</button>
                        </div>
                    </form>

                    <!-- Categories -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">📁 Danh Mục</h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="category.php" class="text-decoration-none <?= !$category_id ? 'fw-bold text-primary' : '' ?>">
                                📌 Tất Cả
                            </a>
                            <?php foreach ($categories as $cat): ?>
                                <a href="category.php?id=<?= $cat['id'] ?>" 
                                   class="text-decoration-none <?= $category_id == $cat['id'] ? 'fw-bold text-primary' : '' ?>">
                                    📌 <?= htmlspecialchars($cat['category_name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card shadow-sm border-0 bg-warning">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">💡 Mẹo</h6>
                    <p class="small mb-0">
                        Sử dụng bộ lọc bên cạnh để tìm kiếm bài viết theo danh mục hoặc từ khóa.
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-lg-9">
            <?php if (!empty($articles)): ?>
                <div class="row g-4">
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift" style="min-height: 100%; display: flex; flex-direction: column;">
                                <div class="position-relative">
                                    <img src="assets/images/<?= !empty($article['image']) ? htmlspecialchars($article['image']) : 'default.jpg' ?>" 
                                         class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>"
                                         style="height: 200px; object-fit: cover; display: block;"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 200%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22%3ECà Mau%3C/text%3E%3C/svg%3E';">
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
                                        <span>❤️ <?= $article['likes'] ?? 0 ?></span>
                                        <span><?= formatDate($article['created_at'], 'd/m') ?></span>
                                    </div>
                                    <a href="detail.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-primary">
                                        Đọc Tiếp →
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Thêm pagination logic ở đây nếu cần -->
                    </ul>
                </nav>
            <?php else: ?>
                <div class="alert alert-info text-center py-5">
                    <h5>📭 Không Tìm Thấy Bài Viết</h5>
                    <p class="mb-0">Hiện tại chưa có bài viết nào trong danh mục này. Hãy quay lại sau!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>