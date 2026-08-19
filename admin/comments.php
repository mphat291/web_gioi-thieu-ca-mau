<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/Comment.php';

// Xử lý hành động Duyệt hoặc Xóa bình luận
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'approve') {
        Comment::updateStatus($id, 'approved');
        header('Location: comments.php?msg=approved');
        exit;
    } elseif ($_GET['action'] === 'delete') {
        Comment::delete($id);
        header('Location: comments.php?msg=deleted');
        exit;
    }
}

// Lấy tất cả bình luận
$comments = Comment::getAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bình Luận - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 15px; border-radius: 4px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 sidebar p-3">
            <h4 class="text-white mb-4"><i class="fa-solid fa-gear me-2"></i>Admin Panel</h4>
            <ul class="nav flex-column gap-1">
                <li class="nav-item"><a href="index.php"><i class="fa-solid fa-chart-line me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="articles.php"><i class="fa-solid fa-newspaper me-2"></i>Bài Viết</a></li>
                <li class="nav-item"><a href="categories.php"><i class="fa-solid fa-folder me-2"></i>Danh Mục</a></li>
                <li class="nav-item"><a href="comments.php" class="active"><i class="fa-solid fa-comments me-2"></i>Bình Luận</a></li>
                <li class="nav-item"><a href="users.php"><i class="fa-solid fa-users me-2"></i>Người Dùng</a></li>
                <li class="nav-item mt-3"><a href="../index.php" class="text-info"><i class="fa-solid fa-globe me-2"></i>Xem Website</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="fa-solid fa-comments me-2 text-primary"></i>Quản Lý Bình Luận</h2>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'approved'): ?>
                <div class="alert alert-success alert-dismissible fade show">Duyệt bình luận thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
                <div class="alert alert-warning alert-dismissible fade show">Đã xóa bình luận!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Nội dung</th>
                                    <th>Bài viết</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($comments)): ?>
                                    <?php foreach ($comments as $c): ?>
                                        <tr>
                                            <td><?= $c['id'] ?></td>
                                            <td><strong><?= htmlspecialchars($c['user_name'] ?? 'Khách') ?></strong></td>
                                            <td style="max-width: 250px;"><?= htmlspecialchars($c['content']) ?></td>
                                            <td><small class="text-muted"><?= htmlspecialchars($c['article_title'] ?? 'Bài viết #' . $c['article_id']) ?></small></td>
                                            <td>
                                                <?php if (($c['status'] ?? '') === 'approved'): ?>
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small></td>
                                            <td class="text-center">
                                                <?php if (($c['status'] ?? '') !== 'approved'): ?>
                                                    <a href="comments.php?action=approve&id=<?= $c['id'] ?>" class="btn btn-sm btn-success me-1"><i class="fa-solid fa-check"></i> Duyệt</a>
                                                <?php endif; ?>
                                                <a href="comments.php?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ní có chắc muốn xóa bình luận này?')"><i class="fa-solid fa-trash"></i> Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có bình luận nào!</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>