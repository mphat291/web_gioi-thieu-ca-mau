<?php
require_once '../config/db.php';

// Xử lý Thêm Danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $cat_name = $_POST['category_name'];
    $desc = $_POST['description'];

    if (!empty($cat_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->execute([$cat_name, $desc]);
        header("Location: categories.php");
        exit();
    }
}

// Xử lý Xóa Danh mục
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit();
}

// Lấy danh sách danh mục
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Gọi Layout
$page_title = "Quản Lý Danh Mục";
require_once 'admin_layout.php';
?>

<!-- Form thêm danh mục -->
<div class="card p-4 shadow-sm mb-4">
    <h5 class="mb-3 fw-bold"><i class="fa-solid fa-folder-plus me-2"></i>Thêm Danh Mục Mới</h5>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên Danh Mục</label>
            <input type="text" name="category_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>
        <button type="submit" name="add_category" class="btn btn-success px-4">
            <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục
        </button>
    </form>
</div>

<!-- Bảng hiển thị danh mục -->
<div class="card p-4 shadow-sm">
    <h5 class="mb-3 fw-bold">Danh Sách Danh Mục</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên Danh Mục</th>
                    <th>Mô Tả</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><strong><?= htmlspecialchars($cat['category_name']) ?></strong></td>
                    <td><?= htmlspecialchars($cat['description']) ?></td>
                    <td>
                        <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Ní có chắc muốn xóa không?');">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>