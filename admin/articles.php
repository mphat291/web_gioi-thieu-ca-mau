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
        // Tạo thư mục nếu chưa có
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

// Lấy danh sách danh mục (để hiện trong thẻ <select>)
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bài viết (kèm tên danh mục)
$sql = "SELECT articles.*, categories.category_name 
        FROM articles 
        LEFT JOIN categories ON articles.category_id = categories.id 
        ORDER BY articles.id DESC";
$articles = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Bài Viết - Admin</title>
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
                <li><a href="categories.php" class="nav-link text-white">📁 Quản lý Danh mục</a></li>
                <li><a href="articles.php" class="nav-link text-white active">📝 Quản lý Bài viết</a></li>
                <li><a href="comments.php" class="nav-link text-white">💬 Kiểm duyệt Bình luận</a></li>
                <li><a href="contacts.php" class="nav-link text-white">📬 Quản lý Liên hệ</a></li>
                <li><a href="users.php" class="nav-link text-white">👤 Quản lý Tài khoản</a></li>
                <li class="mt-4"><a href="../index.php" class="nav-link text-info">🌐 Xem Trang chủ Web</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="p-4 flex-grow-1">
            <h2>Quản Lý Bài Viết</h2>

            <!-- Form Thêm Bài Viết Mới -->
            <div class="card p-3 my-3">
                <h5>Thêm Bài Viết Mới</h5>
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
                    <button type="submit" name="add_article" class="btn btn-primary">Đăng Bài Viết</button>
                </form>
            </div>

            <!-- Bảng Danh Sách Bài Viết -->
            <h5 class="mt-4">Danh Sách Bài Viết Hiện Có</h5>
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Lượt xem / Thích</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $art): ?>
                    <tr>
                        <td><?= $art['id'] ?></td>
                        <td style="width: 100px;">
                            <?php if ($art['image']): ?>
                                <img src="../assets/images/<?= $art['image'] ?>" class="img-thumbnail" style="max-height: 60px;">
                            <?php else: ?>
                                <span class="badge bg-secondary">Không ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($art['title']) ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($art['category_name'] ?? 'Chưa phân loại') ?></span></td>
                        <td>👁️ <?= $art['views'] ?> | ❤️ <?= $art['likes'] ?></td>
                        <td>
                           <a href="edit_article.php?id=<?= $art['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                           <a href="articles.php?delete=<?= $art['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Ní chắc chắn muốn xóa bài viết này chứ?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>