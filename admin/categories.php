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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Danh Mục - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3 min-vh-100" style="width: 250px;">
            <h4 class="text-center text-warning">Admin Cà Mau</h4>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="index.php" class="nav-link text-white">📊 Dashboard</a></li>
                <li><a href="categories.php" class="nav-link text-white active">📁 Quản lý Danh mục</a></li>
                <li><a href="articles.php" class="nav-link text-white">📝 Quản lý Bài viết</a></li>
                <li><a href="comments.php" class="nav-link text-white">💬 Kiểm duyệt Bình luận</a></li>
                <li><a href="contacts.php" class="nav-link text-white">📬 Quản lý Liên hệ</a></li>
                <li><a href="users.php" class="nav-link text-white">👤 Quản lý Tài khoản</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="p-4 flex-grow-1">
            <h2>Quản Lý Danh Mục Bài Viết</h2>

            <!-- Form thêm danh mục -->
            <div class="card p-3 my-3">
                <h5>Thêm Danh Mục Mới</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên Danh Mục</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-success">Thêm Danh Mục</button>
                </form>
            </div>

            <!-- Bảng hiển thị danh mục -->
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
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
                            <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Ní có chắc muốn xóa không?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>