<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Kiểm tra quyền Admin
if (!isLoggedIn() || ($_SESSION['role'] ?? $_SESSION['user']['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Lấy danh sách bài viết đang chờ duyệt (status = 'pending')
$stmt = $pdo->prepare("
    SELECT a.*, c.category_name 
    FROM articles a 
    LEFT JOIN categories c ON a.category_id = c.id 
    WHERE a.status = 'pending' 
    ORDER BY a.created_at DESC
");
$stmt->execute();
$pending_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nhúng Header & Sidebar
include 'admin_layout.php';
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark m-0">
            <i class="fa-solid fa-clock-rotate-left text-warning me-2"></i>Duyệt Bài Viết Thành Viên
        </h4>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">
            Chờ duyệt: <?= count($pending_articles) ?>
        </span>
    </div>

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <div class="alert alert-info alert-dismissible fade show rounded-3" role="alert">
            <?= $_SESSION['flash_msg'] ?>
            <?php unset($_SESSION['flash_msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>TIÊU ĐỀ</th>
                    <th>DANH MỤC</th>
                    <th>NGÀY GỬI</th>
                    <th class="text-center">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pending_articles)): ?>
                    <?php foreach ($pending_articles as $art): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($art['title']) ?></div>
                                <small class="text-muted"><?= mb_strimwidth(strip_tags($art['content']), 0, 80, '...') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 rounded-pill">
                                    <?= htmlspecialchars($art['category_name'] ?? 'Chưa phân loại') ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($art['created_at'])) ?></small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="../detail.php?id=<?= $art['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3" title="Xem trước">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="process_approval.php?id=<?= $art['id'] ?>&action=approve" onclick="return confirm('Bạn có chắc muốn duyệt bài viết này?')" class="btn btn-sm btn-success rounded-3 fw-semibold">
                                        <i class="fa-solid fa-check me-1"></i>Duyệt
                                    </a>
                                    <a href="process_approval.php?id=<?= $art['id'] ?>&action=reject" onclick="return confirm('Bạn có chắc muốn từ chối bài này?')" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="fa-solid fa-xmark me-1"></i>Từ chối
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-circle-check text-success fs-3 d-block mb-2"></i>
                            Tuyệt vời! Hiện tại không có bài viết nào đang chờ duyệt.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// Nhúng Footer & JS
include 'admin_layout_end.php'; 
?>