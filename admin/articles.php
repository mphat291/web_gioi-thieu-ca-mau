<?php
require_once '../config/db.php';

// 1. Xử lý Thêm bài viết mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_article'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    
    // Xử lý Upload Ảnh
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO articles (category_id, title, content, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$category_id, $title, $content, $image]);
        header("Location: articles.php");
        exit();
    }
}

// 2. Xử lý Xóa bài viết
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: articles.php");
    exit();
}

// Lấy danh sách danh mục
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bài viết (kèm tên danh mục và đếm số lượt thích chính xác từ bảng likes)
$sql = "SELECT articles.*, categories.category_name, COUNT(likes.id) AS likes 
        FROM articles 
        LEFT JOIN categories ON articles.category_id = categories.id 
        LEFT JOIN likes ON articles.id = likes.article_id 
        GROUP BY articles.id 
        ORDER BY articles.id DESC";
$articles = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Gọi Layout chung của Admin
$page_title = "Quản Lý Bài Viết";
require_once 'admin_layout.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-1">
    <h2 class="mb-0"><i class="fa-solid fa-newspaper me-2 text-primary"></i>Quản Lý Bài Viết</h2>
</div>

<!-- Form Thêm Bài Viết Mới -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3 text-secondary"><i class="fa-solid fa-plus-circle me-1"></i> Thêm Bài Viết Mới</h5>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Tiêu đề bài viết</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Chuyên mục</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh minh họa</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung chi tiết</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" name="add_article" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane me-1"></i> Đăng Bài Viết
            </button>
        </form>
    </div>
</div>

<!-- Bảng Danh Sách Bài Viết -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">ID</th>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Lượt xem / Thích</th>
                        <th class="text-center pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $art): ?>
                        <tr>
                            <td class="px-3"><?= $art['id'] ?></td>
                            <td>
                                <?php if ($art['image']): ?>
                                    <img src="../assets/images/<?= htmlspecialchars($art['image']) ?>" class="img-thumbnail rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">Không ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($art['title']) ?></strong></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($art['category_name'] ?? 'Chưa phân loại') ?></span></td>
                            <td>👁️ <?= $art['views'] ?> | ❤️ <?= $art['likes'] ?></td>
                            <td class="text-center pe-3">
                               <a href="edit_article.php?id=<?= $art['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                   <i class="fa-solid fa-pen"></i>
                               </a>
                               <a href="articles.php?delete=<?= $art['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ní chắc chắn muốn xóa bài viết này chứ?');">
                                   <i class="fa-solid fa-trash"></i>
                               </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có bài viết nào!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'admin_layout_end.php'; ?>