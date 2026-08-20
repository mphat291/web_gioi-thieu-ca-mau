<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = "Báo Cáo Vi Phạm";
require_once 'admin_layout.php';

// Đã sửa câu lệnh SQL: Lấy trực tiếp c.user_name thay vì c.user_id
$sql = "SELECT r.*, c.content as comment_content, c.user_name as comment_owner, u.username as reporter_name
        FROM comment_reports r
        JOIN comments c ON r.comment_id = c.id
        JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC";
$stmt = $pdo->query($sql);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="fw-bold mb-4">🚨 Quản Lý Báo Cáo Vi Phạm Bình Luận</h2>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Người Tố Cáo</th>
                        <th>Nội Dung Bị Tố Cáo</th>
                        <th>Tác Giả Bình Luận</th>
                        <th>Lý Do Vi Phạm</th>
                        <th>Thời Gian</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $rep): ?>
                            <tr>
                                <td><?= $rep['id'] ?></td>
                                <td><span class="fw-semibold text-primary"><?= htmlspecialchars($rep['reporter_name']) ?></span></td>
                                <td><div class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($rep['comment_content']) ?>"><?= htmlspecialchars($rep['comment_content']) ?></div></td>
                                <td><?= htmlspecialchars($rep['comment_owner']) ?></td>
                                <td><span class="badge bg-danger"><?= htmlspecialchars($rep['reason']) ?></span></td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($rep['created_at'])) ?></small></td>
                                <td>
                                    <a href="delete_comment.php?id=<?= $rep['comment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ní có chắc muốn xóa bình luận vi phạm này không?')">
                                        <i class="fa-solid fa-trash"></i> Xóa Bình Luận
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có báo cáo vi phạm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div></div></div>
</body>
</html>