<?php
require_once '../config/db.php';

// Xử lý Xóa tin nhắn liên hệ
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: contacts.php?msg=deleted');
    exit;
}

// Lấy danh sách liên hệ
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY id DESC");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gọi Layout chung
$page_title = "Quản Lý Liên Hệ";
require_once 'admin_layout.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-1">
    <h2 class="mb-0"><i class="fa-solid fa-envelope me-2 text-primary"></i>Quản Lý Liên Hệ</h2>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fa-solid fa-trash-can me-1"></i> Đã xóa tin nhắn liên hệ!
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
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contacts)): ?>
                        <?php foreach ($contacts as $c): ?>
                            <tr>
                                <td class="px-3"><?= $c['id'] ?></td>
                                <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['subject'] ?? 'Không có') ?></td>
                                <td><small class="text-muted"><?= htmlspecialchars(substr($c['message'], 0, 50)) ?>...</small></td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small></td>
                                <td class="text-center pe-3">
                                    <a href="contacts.php?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Chắc chắn xóa liên hệ này?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có liên hệ nào mới!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'admin_layout_end.php'; ?>