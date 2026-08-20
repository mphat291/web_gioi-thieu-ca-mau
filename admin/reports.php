<?php
$page_title = "Quản Lý Báo Cáo Vi Phạm";
require_once __DIR__ . '/../config/db.php';

// Xử lý các thao tác của Admin
if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    $action = $_GET['action'];

    if ($action === 'delete') {
        if ($type === 'comment' && isset($_GET['target_id'])) {
            $comment_id = (int)$_GET['target_id'];
            // Xóa bình luận và các báo cáo liên quan
            $pdo->prepare("DELETE FROM comments WHERE id = ?")->execute([$comment_id]);
            $pdo->prepare("DELETE FROM comment_reports WHERE comment_id = ?")->execute([$comment_id]);
        } elseif ($type === 'user' && isset($_GET['target_id'])) {
            $user_id = (int)$_GET['target_id'];
            // Xóa tài khoản và các báo cáo liên quan
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM user_reports WHERE reported_user_id = ?")->execute([$user_id]);
        }
    } elseif ($action === 'dismiss') {
        // Chỉ bỏ qua / xóa bản ghi báo cáo
        $table = ($type === 'comment') ? 'comment_reports' : 'user_reports';
        $pdo->prepare("DELETE FROM $table WHERE id = ?")->execute([$id]);
    }
    header("Location: reports.php");
    exit;
}

require_once 'admin_layout.php';

// Gộp báo cáo Comment và Báo cáo User
$sql = "
    SELECT cr.id, 'comment' AS type, u.username AS reporter, c.user_name AS target, c.id AS target_id, c.content AS detail, cr.reason, cr.created_at
    FROM comment_reports cr
    LEFT JOIN users u ON cr.user_id = u.id
    LEFT JOIN comments c ON cr.comment_id = c.id
    
    UNION ALL
    
    SELECT ur.id, 'user' AS type, u1.username AS reporter, u2.username AS target, u2.id AS target_id, NULL AS detail, ur.reason, ur.created_at
    FROM user_reports ur
    LEFT JOIN users u1 ON ur.reporter_id = u1.id
    LEFT JOIN users u2 ON ur.reported_user_id = u2.id
    
    ORDER BY created_at DESC
";

$reports = $pdo->query($sql)->fetchAll();
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-danger"><i class="fa-solid fa-flag me-2"></i> Danh Sách Báo Cáo Vi Phạm</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Loại báo cáo</th>
                        <th>Người tố cáo</th>
                        <th>Đối tượng bị tố cáo</th>
                        <th>Lý do</th>
                        <th>Ngày gửi</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Không có báo cáo nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td>
                                    <?php if ($r['type'] === 'comment'): ?>
                                        <span class="badge bg-info text-dark">Bình luận</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Người dùng</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($r['reporter'] ?? 'Ẩn danh') ?></strong></td>
                                <td>
                                    <?php if ($r['target']): ?>
                                        <strong><?= htmlspecialchars($r['target']) ?></strong>
                                        <?php if (!empty($r['detail'])): ?>
                                            <br><small class="text-muted">"<?= htmlspecialchars($r['detail']) ?>"</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <em class="text-muted">(Đã bị xóa)</em>
                                    <?php endif; ?>
                                </td>
                                <td><span class="text-danger"><?= htmlspecialchars($r['reason']) ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                <td>
                                    <?php if ($r['target']): ?>
                                        <a href="reports.php?action=delete&id=<?= $r['id'] ?>&type=<?= $r['type'] ?>&target_id=<?= $r['target_id'] ?>" 
                                           class="btn btn-sm btn-danger me-1" 
                                           onclick="return confirm('Bạn có chắc muốn xóa <?= $r['type'] === 'comment' ? 'bình luận' : 'tài khoản' ?> này?')">
                                            <i class="fa-solid fa-trash me-1"></i> Xóa
                                        </a>
                                    <?php endif; ?>
                                    <a href="reports.php?action=dismiss&id=<?= $r['id'] ?>&type=<?= $r['type'] ?>" 
                                       class="btn btn-sm btn-secondary" 
                                       onclick="return confirm('Bỏ qua báo cáo này?')">
                                        <i class="fa-solid fa-xmark me-1"></i> Bỏ qua
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'admin_layout_end.php'; ?>