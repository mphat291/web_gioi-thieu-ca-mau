<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Nhập file kết nối CSDL
if (file_exists(__DIR__ . '/config/db.php')) {
    require_once __DIR__ . '/config/db.php';
} else if (file_exists(__DIR__ . '/includes/db.php')) {
    require_once __DIR__ . '/includes/db.php';
} else if (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
} else {
    die("Lỗi: Không tìm thấy file kết nối CSDL (db.php). Vui lòng kiểm tra lại đường dẫn thư mục.");
}

// Đồng bộ biến kết nối CSDL (nếu file db.php dùng $conn hoặc $db thay vì $pdo)
if (!isset($pdo)) {
    if (isset($conn)) {
        $pdo = $conn;
    } else if (isset($db)) {
        $pdo = $db;
    } else {
        die("Lỗi: Chưa khởi tạo được biến kết nối CSDL trong db.php");
    }
}

// Lấy danh sách danh mục
$stmt_cat = $pdo->query("SELECT * FROM categories");
$categories = $stmt_cat->fetchAll();

// Lấy danh sách bài viết mới nhất
$stmt_art = $pdo->query("SELECT a.*, c.category_name 
                        FROM articles a 
                        LEFT JOIN categories c ON a.category_id = c.id 
                        ORDER BY a.created_at DESC");
$articles = $stmt_art->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Cà Mau - Trang Chủ</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; padding: 0; background-color: #f4f6f9; }
        header { background-color: #006633; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: white; text-decoration: none; margin-left: 15px; }
        .container { max-width: 1100px; margin: 20px auto; padding: 0 15px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-body { padding: 15px; }
        .card-title { font-size: 18px; margin: 0 0 10px 0; color: #333; }
        .card-text { color: #666; font-size: 14px; line-height: 1.5; }
        .card-meta { margin-top: 15px; font-size: 12px; color: #888; display: flex; justify-content: space-between; }
        .btn { display: inline-block; padding: 8px 15px; background: #006633; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px; }
        .user-info { font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h2>Văn Hóa & Du Lịch Cà Mau</h2>
    <nav>
        <a href="index.php">Trang chủ</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Độc giả') ?></span>
            <a href="logout.php">Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        <?php endif; ?>
        <a href="contact.php">Liên hệ</a>
    </nav>
</header>

<div class="container">
    <h3>Bài viết mới nhất</h3>
    <div class="grid">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $item): ?>
                <div class="card">
                    <img src="assets/images/<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'default.jpg' ?>" alt="Ảnh bài viết" onerror="this.src='https://via.placeholder.com/300x180?text=Ca+Mau'">
                    <div class="card-body">
                        <small style="color: #006633; font-weight: bold;"><?= htmlspecialchars($item['category_name'] ?? 'Chung') ?></small>
                        <h4 class="card-title"><?= htmlspecialchars($item['title']) ?></h4>
                        <p class="card-text"><?= htmlspecialchars(mb_substr(strip_tags($item['content']), 0, 100)) ?>...</p>
                        <a href="detail.php?id=<?= $item['id'] ?>" class="btn">Đọc tiếp</a>
                        <div class="card-meta">
                            <span>Lượt xem: <?= $item['views'] ?></span>
                            <span>Lượt thích: ❤️ <?= $item['likes'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bài viết nào trong CSDL.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>