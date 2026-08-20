<?php
session_start();
require_once '../config/db.php'; // <--- Thêm dòng này để khởi tạo biến $pdo cho các Class

$page_title = "Dashboard";
require_once 'admin_layout.php';

require_once '../classes/Article.php';
require_once '../classes/Category.php';
require_once '../classes/User.php';

$article_obj = new Article($pdo);
$category_obj = new Category($pdo);
$user_obj = new User($pdo);

$total_articles = $article_obj->count();
$total_categories = $category_obj->count();
$total_users = $user_obj->count();
$total_admins = $user_obj->countAdmins();

$latest_articles = $article_obj->getAll(5, 0);
?>

<style>
    .stat-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .bg-blue-light { background: #e0f2fe; color: #0284c7; }
    .bg-emerald-light { background: #d1fae5; color: #059669; }
    .bg-purple-light { background: #f3e8ff; color: #7c3aed; }
    
    .content-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .badge-category {
        background-color: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        border-radius: 20px;
        padding: 0.35rem 0.8rem;
    }
</style>

<div class="row g-4 mb-4 mt-1">
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase">Tổng Bài Viết</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $total_articles ?></h2>
                    <a href="articles.php" class="text-primary text-decoration-none small fw-semibold">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div class="icon-box bg-blue-light">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase">Danh Mục</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $total_categories ?></h2>
                    <a href="categories.php" class="text-success text-decoration-none small fw-semibold">Quản lý <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div class="icon-box bg-emerald-light">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase">Người Dùng</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $total_users ?></h2>
                    <a href="users.php" class="text-purple text-decoration-none small fw-semibold" style="color: #7c3aed;">Chi tiết <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div class="icon-box bg-purple-light">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card content-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-fire text-danger me-2"></i>Bài Viết Mới Nhất</h5>
                <a href="articles.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Tất cả</a>
            </div>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted small text-uppercase">Tiêu Đề</th>
                            <th class="text-muted small text-uppercase">Danh Mục</th>
                            <th class="text-muted small text-uppercase">Lượt Xem</th>
                            <th class="text-muted small text-uppercase">Ngày Tạo</th>
                            <th class="text-end text-muted small text-uppercase">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($latest_articles)): ?>
                            <?php foreach ($latest_articles as $article): ?>
                                <tr>
                                    <td class="fw-semibold" style="max-width: 200px;">
                                        <div class="text-truncate" title="<?= htmlspecialchars($article['title']) ?>">
                                            <?= htmlspecialchars($article['title']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-category">
                                            <?= htmlspecialchars($article['category_name'] ?? 'Chung') ?>
                                        </span>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><i class="fa-regular fa-eye me-1"></i><?= $article['views'] ?? 0 ?></span></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-light text-warning" title="Sửa"><i class="fa-solid fa-pen"></i></a>
                                            <a href="articles.php?delete=<?= $article['id'] ?>" class="btn btn-light text-danger" onclick="return confirm('Xóa bài viết này?')" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có bài viết nào!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card content-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-line text-primary me-2"></i>Thống Kê Nhanh</h5>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                    <span class="text-muted"><i class="fa-solid fa-newspaper me-2 text-primary"></i>Tổng Bài Viết:</span>
                    <span class="fw-bold"><?= $total_articles ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                    <span class="text-muted"><i class="fa-solid fa-user-shield me-2 text-success"></i>Quản trị viên:</span>
                    <span class="fw-bold"><?= $total_admins ?></span>
                </div>
            </div>
        </div>

        <div class="card content-card p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i>Liên Kết Nhanh</h5>
            <div class="d-grid gap-2">
                <a href="articles.php" class="btn btn-primary rounded-3 text-start py-2">
                    <i class="fa-solid fa-plus me-2"></i> Thêm Bài Viết
                </a>
                <a href="categories.php" class="btn btn-outline-secondary rounded-3 text-start py-2">
                    <i class="fa-solid fa-folder-plus me-2"></i> Thêm Danh Mục
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin_layout_end.php'; ?>