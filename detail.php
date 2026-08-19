<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';
require_once 'classes/Comment.php';
require_once 'classes/Category.php';

$article_obj = new Article($pdo);
$comment_obj = new Comment($pdo);

// 1. Lấy ID và kiểm tra
$article_id = $_GET['id'] ?? 0;
if (!$article_id) {
    die("Lỗi: Không có ID bài viết trên URL");
}

// 2. Xử lý tăng view và lấy dữ liệu
$article_obj->increaseViews($article_id);
$article = $article_obj->getById($article_id);
if (!$article) {
    die("Lỗi: Không tìm thấy bài viết ID = " . $article_id);
}

// 3. Lấy dữ liệu tương tác
$likeCount = $article_obj->getLikeCount($article_id);
$userLiked = isLoggedIn() ? $article_obj->isLikedByUser($article_id, $_SESSION['user_id']) : false;
$userFavorited = isLoggedIn() ? $article_obj->isFavoritedByUser($article_id, $_SESSION['user_id']) : false;

// 4. Xử lý History
if (!isset($_SESSION['reading_history'])) $_SESSION['reading_history'] = [];
if (($key = array_search($article_id, $_SESSION['reading_history'])) !== false) unset($_SESSION['reading_history'][$key]);
array_unshift($_SESSION['reading_history'], $article_id);
if (count($_SESSION['reading_history']) > 20) array_pop($_SESSION['reading_history']);

// 5. Xử lý Comment
$comments = $comment_obj->getByArticleId($article_id, 20, 0);
$comment_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
    if (!isLoggedIn()) {
        $comment_message = '<div class="alert alert-warning">Vui lòng <a href="login.php">đăng nhập</a> để bình luận</div>';
    } else {
        try {
            $comment_obj->create($article_id, $_SESSION['username'], $_POST['comment_content']);
            $comment_message = '<div class="alert alert-success">✅ Gửi bình luận thành công!</div>'; 
            $comments = $comment_obj->getByArticleId($article_id, 20, 0);
        } catch (Exception $e) {
            $comment_message = '<div class="alert alert-danger">❌ Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<?php require_once 'includes/header.php'; ?>

<main class="container-lg py-5">
    <div class="row">
        <!-- Nội dung bài viết -->
        <div class="col-lg-8">
            <article>
                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($article['title']) ?></h1>
                
                <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
                    <span class="badge bg-success"><?= htmlspecialchars($article['category_name'] ?? 'Chung') ?></span>
                    <span class="text-muted">📅 <?= formatDate($article['created_at'], 'd/m/Y') ?></span>
                    <span class="text-muted">👁️ Lượt xem: <?= $article['views'] ?? 0 ?></span>
                    <span class="text-muted" id="like-count">❤️ <?= $likeCount ?></span>
                </div>

                <?php if (!empty($article['image'])): ?>
                    <img src="assets/images/<?= htmlspecialchars($article['image']) ?>" class="img-fluid rounded-3 mb-4" alt="<?= htmlspecialchars($article['title']) ?>">
                <?php endif; ?>

                <div class="lead lh-lg"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

                <!-- Actions -->
                <div class="d-flex gap-2 mt-4">
                    <?php if (isLoggedIn()): ?>
                        <button class="btn <?= $userLiked ? 'btn-primary' : 'btn-outline-primary' ?>" id="like-btn" onclick="likeArticle(<?= $article_id ?>)">
                            <?= $userLiked ? '💛' : '❤️' ?> Thích (<span id="like-text"><?= $likeCount ?></span>)
                        </button>
                        <button class="btn <?= $userFavorited ? 'btn-warning' : 'btn-outline-warning' ?>" id="fav-btn" onclick="favoriteArticle(<?= $article_id ?>)">
                            <?= $userFavorited ? '⭐ Đã lưu' : '☆ Lưu' ?>
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary">❤️ Thích để đăng nhập</a>
                    <?php endif; ?>
                    <button class="btn btn-outline-secondary" onclick="shareArticle()">🔗 Chia Sẻ</button>
                </div>
            </article>

            <hr class="my-5">

            <!-- Phần bình luận -->
            <section class="comments-section">
                <?= $comment_message ?>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script>
function likeArticle(articleId) {
    fetch('ajax_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'article_id=' + articleId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const likeBtn = document.getElementById('like-btn');
            const likeText = document.getElementById('like-text');
            const likeCountSpan = document.getElementById('like-count');

            if (likeText) likeText.innerText = data.likeCount;
            if (likeCountSpan) likeCountSpan.innerText = '❤️ ' + data.likeCount;

            if (data.action === 'liked') {
                likeBtn.classList.remove('btn-outline-primary');
                likeBtn.classList.add('btn-primary');
                likeBtn.innerHTML = '💛 Thích (<span id="like-text">' + data.likeCount + '</span>)';
            } else {
                likeBtn.classList.remove('btn-primary');
                likeBtn.classList.add('btn-outline-primary');
                likeBtn.innerHTML = '❤️ Thích (<span id="like-text">' + data.likeCount + '</span>)';
            }
        } else {
            alert(data.message);
            if (data.message.includes('đăng nhập')) {
                window.location.href = 'login.php';
            }
        }
    })
    .catch(error => console.error('Lỗi kết nối:', error));
}

function favoriteArticle(articleId) {
    fetch('ajax_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'article_id=' + articleId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const favBtn = document.getElementById('fav-btn');
            if (data.action === 'favorited') {
                favBtn.classList.remove('btn-outline-warning');
                favBtn.classList.add('btn-warning');
                favBtn.innerHTML = '⭐ Đã lưu';
            } else {
                favBtn.classList.remove('btn-warning');
                favBtn.classList.add('btn-outline-warning');
                favBtn.innerHTML = '☆ Lưu';
            }
        } else {
            alert(data.message);
            if (data.message.includes('đăng nhập')) {
                window.location.href = 'login.php';
            }
        }
    })
    .catch(error => console.error('Lỗi kết nối:', error));
}

function shareArticle() {
    navigator.clipboard.writeText(window.location.href);
    alert('Đã sao chép liên kết bài viết vào bộ nhớ tạm!');
}
</script>
</body>
</html>