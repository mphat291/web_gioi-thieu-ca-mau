<?php
require_once '../config/db.php';

// Xử lý Thay đổi quyền (Role) hoặc Xóa tài khoản
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle_role') {
        $current_role = $_GET['role'] ?? 'user';
        $new_role = ($current_role === 'admin') ? 'user' : 'admin';
        // Lưu ý: Nếu config/db.php của ní dùng biến $conn, hãy đổi $pdo thành $conn ở 2 dòng dưới nhé
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

// Gọi Layout chung
$page_title = "Quản Lý Người Dùng";
require_once 'admin_layout.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-1">
    <h2 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Quản Lý Người Dùng</h2>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'role_updated'): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa-solid fa-check-circle me-1"></i> Cập nhật quyền thành viên thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fa-solid fa-triangle-exclamation me-1"></i> Đã xóa người dùng khỏi hệ thống!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Vai trò (Role)</th>
                        <th>Ngày tham gia</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="px-3"><?= $u['id'] ?></td>
                                <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                                <td><?= htmlspecialchars($u['email'] ?? 'Chưa cập nhật') ?></td>
                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge bg-danger px-2 py-1"><i class="fa-solid fa-crown me-1"></i>Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-2 py-1"><i class="fa-solid fa-user me-1"></i>User</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></small></td>
                                <td class="text-center pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="users.php?action=toggle_role&id=<?= $u['id'] ?>&role=<?= $u['role'] ?>" class="btn btn-outline-primary" title="Đổi quyền">
                                            <i class="fa-solid fa-user-shield"></i> <?= $u['role'] === 'admin' ? 'Hạ cấp' : 'Thăng cấp' ?>
                                        </a>
                                        <a href="users.php?action=delete&id=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('Ní chắc chắn muốn xóa tài khoản này?')" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
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

<?php require_once 'admin_layout_end.php'; ?>