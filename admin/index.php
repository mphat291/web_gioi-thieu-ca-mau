<?php
$page_title = "Dashboard";
require_once 'admin_layout.php';

// Khởi tạo các classes
require_once '../classes/Article.php';
require_once '../classes/Category.php';
require_once '../classes/User.php';
require_once '../classes/Comment.php';

$article_obj = new Article($pdo);
$category_obj = new Category($pdo);
$user_obj = new User($pdo);
$comment_obj = new Comment($pdo);

// Lấy thống kê
$total_articles = $article_obj->count();
$total_categories = $category_obj->count();
$total_users = $user_obj->count();
$total_admins = $user_obj->countAdmins();
$total_comments = $comment_obj->count();
$pending_comments = $comment_obj->countPending();

// Lấy bài viết mới nhất
$latest_articles = $article_obj->getAll(5, 0);
?>

<!-- Thống Kê Chính -->
<div class="row g-4 mb-5">
    <!-- Bài Viết -->
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted">📰 Tổng Bài Viết</h6>
                <div class="stat-number"><?= $total_articles ?></div>
                <small class="text-muted"><a href="articles.php">Xem tất cả →</a></small>
            </div>
        </div>
    </div>

    <!-- Danh Mục -->
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted">📁 Danh Mục</h6>
                <div class="stat-number"><?= $total_categories ?></div>
                <small class="text-muted"><a href="categories.php">Quản lý →</a></small>
            </div>
        </div>
    </div>

    <!-- Bình Luận -->
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card border-0 shadow-sm warning">
            <div class="card-body">
                <h6 class="card-title text-muted">💬 Bình Luận Chờ Duyệt</h6>
                <div class="stat-number"><?= $pending_comments ?></div>
                <small class="text-muted"><a href="comments.php">Duyệt ngay →</a></small>
            </div>
        </div>
    </div>

    <!-- Người Dùng -->
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted">👥 Người Dùng</h6>
                <div class="stat-number"><?= $total_users ?></div>
                <small class="text-muted"><a href="users.php">Quản lý →</a></small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Bài Viết Mới Nhất -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">📰 Bài Viết Mới Nhất</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th>Tiêu Đề</th>
                            <th>Danh Mục</th>
                            <th>Lượt Xem</th>
                            <th>Ngày Tạo</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($latest_articles)): ?>
                            <?php foreach ($latest_articles as $article): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars(mb_substr($article['title'], 0, 40)) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($article['category_name'] ?? 'Chung') ?>
                                        </span>
                                    </td>
                                    <td><?= $article['views'] ?? 0 ?></td>
                                    <td><?= formatDate($article['created_at'], 'd/m/Y') ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-warning">✏️</a>
                                            <a href="articles.php?delete=<?= $article['id'] ?>" class="btn btn-danger" 
                                               onclick="return confirmDelete('Xóa bài viết này?')">🗑️</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Chưa có bài viết nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Thông Tin Nhanh -->
    <div class="col-lg-4">
        <!-- Thống Kê Tổng Hợp -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">📊 Thống Kê Tổng Hợp</h6>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span>Tổng Bài Viết:</span>
                        <strong><?= $total_articles ?></strong>
                    </div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span>Tổng Bình Luận:</span>
                        <strong><?= $total_comments ?></strong>
                    </div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span>Chờ Duyệt:</span>
                        <strong class="text-warning"><?= $pending_comments ?></strong>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Người Dùng:</span>
                    <strong><?= $total_users ?></strong>
                </div>
            </div>
        </div>

        <!-- Liên Kết Nhanh -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">⚡ Liên Kết Nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="articles.php" class="btn btn-outline-primary">➕ Thêm Bài Viết</a>
                    <a href="categories.php" class="btn btn-outline-secondary">➕ Thêm Danh Mục</a>
                    <a href="comments.php" class="btn btn-outline-warning">🔔 Duyệt Bình Luận</a>
                    <a href="users.php" class="btn btn-outline-info">👥 Quản Lý Người Dùng</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin_layout_end.php'; ?>
