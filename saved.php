<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách bài viết từ bảng saved_posts đúng với cơ sở dữ liệu của bạn
$sql = "SELECT a.*, s.created_at as saved_at, c.category_name 
        FROM saved_posts s 
        JOIN articles a ON s.article_id = a.id 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE s.user_id = ? 
        ORDER BY s.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$saved_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Viết Đã Lưu - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container-lg py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-success mb-0">
            <i class="fa-solid fa-star text-warning me-2"></i>Danh Sách Bài Viết Đã Lưu
        </h3>
        <span class="badge bg-warning text-dark rounded-pill fs-6 px-3 py-2">
            <?= count($saved_articles) ?> bài viết
        </span>
    </div>

    <?php if (!empty($saved_articles)): ?>
        <div class="row g-4">
            <?php foreach ($saved_articles as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <?php if (!empty($item['image'])): ?>
                            <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa-regular fa-image fs-1"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-success"><?= htmlspecialchars($item['category_name'] ?? 'Chung') ?></span>
                            </div>
                            <h5 class="card-title fw-bold">
                                <a href="detail.php?id=<?= $item['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= mb_substr(strip_tags($item['content']), 0, 100) ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i><?= date('d/m/Y', strtotime($item['saved_at'])) ?></small>
                                <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">Đọc bài này</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-regular fa-star fs-1 text-warning mb-3 d-block"></i>
            <h5 class="fw-bold text-secondary">Bạn chưa lưu bài viết nào hết!</h5>
            <p class="text-muted">Bấm vào nút "Lưu" ở cuối bài viết để lưu lại đọc sau nhé.</p>
            <a href="index.php" class="btn btn-success rounded-pill px-4 mt-2">Khám phá bài viết ngay</a>
        </div>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>