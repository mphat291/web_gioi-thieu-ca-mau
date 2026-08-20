<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';
require_once 'classes/Category.php';

$article_obj = new Article($pdo);

// Xử lý nút xóa lịch sử
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['reading_history']);
    header('Location: history.php');
    exit;
}

$history_ids = $_SESSION['reading_history'] ?? [];
$articles = [];

if (!empty($history_ids)) {
    // Lấy thông tin các bài viết có ID trong mảng lịch sử
    // Giữ nguyên thứ tự gần nhất
    foreach ($history_ids as $id) {
        $art = $article_obj->getById($id);
        if ($art) {
            $articles[] = $art;
        }
    }
}

// Hàm hỗ trợ xử lý đường dẫn ảnh thông minh
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
    <title>Lịch Sử Đã Đọc - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📖 Lịch Sử Bài Viết Đã Đọc</h2>
        <?php if (!empty($articles)): ?>
            <a href="history.php?action=clear" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa toàn bộ lịch sử đọc?')">
                🗑️ Xóa Lịch Sử
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($articles)): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($articles as $article): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="<?= getArticleImage($article['image'] ?? '') ?>"
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($article['title']) ?>"
                             style="height: 200px; object-fit: cover;"
                             onerror="this.src='assets/img/bacbaphi.jpg';">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-success mb-2 align-self-start">
                                <?= htmlspecialchars($article['category_name'] ?? 'Chung') ?>
                            </span>
                            <h5 class="card-title">
                                <a href="detail.php?id=<?= $article['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-4">
                                👁️ Lượt xem: <?= $article['views'] ?? 0 ?>
                            </p>
                            <div class="mt-auto">
                                <a href="detail.php?id=<?= $article['id'] ?>" class="btn btn-outline-success btn-sm w-100">
                                    📖 Đọc Tiếp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-5">
            <h4>Bạn chưa đọc bài viết nào cả!</h4>
            <p class="text-muted">Hãy khám phá các bài viết thú vị trên trang chủ nhé.</p>
            <a href="index.php" class="btn btn-success mt-2">🏠 Về Trang Chủ</a>
        </div>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>