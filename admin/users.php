<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../config/db.php';

// Xử lý Thay đổi quyền (Role) hoặc Xóa tài khoản
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle_role') {
        $current_role = $_GET['role'] ?? 'user';
        $new_role = ($current_role === 'admin') ? 'user' : 'admin';
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $id]);
        header('Location: users.php?msg=role_updated');
        exit;
    } elseif ($_GET['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: users.php?msg=deleted');
        exit;
    }
}

// Lấy danh sách thành viên
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng - Admin Panel</title>
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
                <li class="nav-item"><a href="comments.php"><i class="fa-solid fa-comments me-2"></i>Bình Luận</a></li>
                <li class="nav-item"><a href="users.php" class="active"><i class="fa-solid fa-users me-2"></i>Người Dùng</a></li>
                <li class="nav-item mt-3"><a href="../index.php" class="text-info"><i class="fa-solid fa-globe me-2"></i>Xem Website</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="fa-solid fa-users me-2 text-primary"></i>Quản Lý Người Dùng</h2>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'role_updated'): ?>
                <div class="alert alert-success alert-dismissible fade show">Cập nhật quyền thành viên thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
                <div class="alert alert-warning alert-dismissible fade show">Đã xóa người dùng khỏi hệ thống!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Vai trò (Role)</th>
                                    <th>Ngày tham gia</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td><?= $u['id'] ?></td>
                                            <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                                            <td><?= htmlspecialchars($u['email'] ?? 'Chưa cập nhật') ?></td>
                                            <td>
                                                <?php if ($u['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">Admin</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">User</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></small></td>
                                            <td class="text-center">
                                                <a href="users.php?action=toggle_role&id=<?= $u['id'] ?>&role=<?= $u['role'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="fa-solid fa-user-shield"></i> Đổi thành <?= $u['role'] === 'admin' ? 'User' : 'Admin' ?>
                                                </a>
                                                <a href="users.php?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ní chắc chắn muốn xóa tài khoản này?')">
                                                    <i class="fa-solid fa-trash"></i> Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có người dùng nào!</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>