<?php
require_once '../config/db.php';

// Kiểm tra xem có ID bài viết gửi lên không
if (!isset($_GET['id'])) {
    header("Location: articles.php");
    exit();
}

$id = $_GET['id'];

// Lấy thông tin bài viết hiện tại
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header("Location: articles.php");
    exit();
}

// Xử lý khi nhấn nút "Cập Nhật"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_article'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $image = $article['image']; // Giữ lại ảnh cũ mặc định

    // Nếu người dùng chọn ảnh mới thì upload ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
    }

    $stmt = $conn->prepare("UPDATE articles SET category_id = ?, title = ?, content = ?, image = ? WHERE id = ?");
    $stmt->execute([$category_id, $title, $content, $image, $id]);
    
    // Cập nhật xong thì quay về trang danh sách articles.php
    header("Location: articles.php");
    exit();
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Gọi Layout chung
$page_title = "Sửa Bài Viết";
require_once 'admin_layout.php';
?>

<div class="card p-4 shadow-sm">
    <h2 class="mb-3 fw-bold"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Cập Nhật Bài Viết</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">Tiêu đề bài viết</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Chuyên mục</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $article['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh minh họa hiện tại:</label><br>
            <?php if ($article['image']): ?>
                <img src="../assets/img/<?= $article['image'] ?>" height="80" class="mb-2 img-thumbnail">
            <?php else: ?>
                <span class="text-muted">Chưa có ảnh</span><br>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Để trống nếu không muốn đổi ảnh mới</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Nội dung chi tiết</label>
            <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>
        <div>
            <button type="submit" name="update_article" class="btn btn-warning px-4">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi
            </button>
            <a href="articles.php" class="btn btn-secondary px-3">Hủy Bỏ</a>
        </div>
    </form>
</div>

<?php require_once 'admin_footer.php'; ?>