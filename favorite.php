<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách bài viết đã THÍCH của user từ bảng likes kết nối với articles
try {
    $stmt = $pdo->prepare("
        SELECT a.*, l.created_at as liked_at, c.category_name,
        (SELECT COUNT(*) FROM likes l_sub WHERE l_sub.article_id = a.id) as real_likes 
        FROM likes l 
        JOIN articles a ON l.article_id = a.id 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE l.user_id = ? 
        ORDER BY l.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $favorites = [];
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
    <title>Bài Viết Đã Thích - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container py-5">
    <div class="mb-4">
        <h2 class="display-6 fw-bold">❤️ Danh Sách Bài Viết Đã Thích</h2>
        <div class="border-bottom border-warning" style="width: 100px;"></div>
    </div>

    <?php if (empty($favorites)): ?>
        <div class="alert alert-info text-center py-4">
            <p class="mb-2">Bạn chưa thích bài viết nào. Hãy bấm nút <strong>Thích</strong> ở những bài viết bạn đã đọc nhé!</p>
            <a href="index.php" class="btn btn-success btn-sm mt-2">Khám phá ngay bài viết</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($favorites as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden hover-lift">
                        <div class="position-relative">
                            <img src="<?= getArticleImage($item['image'] ?? '') ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" 
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='assets/img/bacbaphi.jpg';">
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                <?= htmlspecialchars($item['category_name'] ?? 'Chung') ?>
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">
                                <a href="detail.php?id=<?= $item['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= mb_substr(strip_tags($item['content']), 0, 100) ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-muted small">❤️ <?= $item['real_likes'] ?? 0 ?> lượt thích</span>
                                <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-outline-success btn-sm">Đọc tiếp</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>