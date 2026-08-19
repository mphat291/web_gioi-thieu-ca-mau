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

// 3. Lấy dữ liệu tương tác bài viết
$likeCount = $article_obj->getLikeCount($article_id);
$userLiked = isLoggedIn() ? $article_obj->isLikedByUser($article_id, $_SESSION['user_id']) : false;
$userFavorited = isLoggedIn() ? $article_obj->isFavoritedByUser($article_id, $_SESSION['user_id']) : false;

// 4. Xử lý History
if (!isset($_SESSION['reading_history'])) $_SESSION['reading_history'] = [];
if (($key = array_search($article_id, $_SESSION['reading_history'])) !== false) unset($_SESSION['reading_history'][$key]);
array_unshift($_SESSION['reading_history'], $article_id);
if (count($_SESSION['reading_history']) > 20) array_pop($_SESSION['reading_history']);

// 5. Xử lý Comment (POST)
$comment_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
    if (!isLoggedIn()) {
        $comment_message = '<div class="alert alert-warning">Vui lòng <a href="login.php">đăng nhập</a> để bình luận</div>';
    } else {
        try {
            $content = trim($_POST['comment_content']);
            $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
            $username = $_SESSION['username'] ?? 'Thành viên';
            
            if (!empty($content)) {
                $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_name, content, parent_id, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$article_id, $username, $content, $parent_id]);
                $comment_message = '<div class="alert alert-success">✅ Gửi bình luận thành công!</div>'; 
            }
        } catch (Exception $e) {
            $comment_message = '<div class="alert alert-danger">❌ Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// 6. Lấy danh sách bình luận GỐC (parent_id IS NULL)
$sql_comments = "SELECT c.*, 
                (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) as like_count,
                (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id AND cl.user_id = ?) as is_liked
                FROM comments c 
                WHERE c.article_id = ? AND c.parent_id IS NULL 
                ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql_comments);
$stmt->execute([$_SESSION['user_id'] ?? 0, $article_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - Khám Phá Cà Mau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <img src="assets/images/<?= htmlspecialchars($article['image']) ?>" class="img-fluid rounded-3 mb-4 w-100" style="max-height: 450px; object-fit: cover;" alt="<?= htmlspecialchars($article['title']) ?>">
                <?php endif; ?>

                <div class="lead lh-lg mb-4"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

                <!-- Actions Bài Viết -->
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

            <!-- PHẦN BÌNH LUẬN -->
            <section class="comments-section" id="comments">
                <h4 class="fw-bold mb-4"><i class="fa-regular fa-comments me-2 text-success"></i>Bình Luận (<?= count($comments) ?>)</h4>
                
                <?= $comment_message ?>

                <!-- Form Nhập Bình Luận Chính -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <?php if (isLoggedIn()): ?>
                        <form action="detail.php?id=<?= $article_id ?>#comments" method="POST">
                            <div class="mb-3">
                                <label for="comment_content" class="form-label fw-semibold">
                                    Viết bình luận với tư cách: <span class="text-success"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                                </label>
                                <textarea class="form-control rounded-3" id="comment_content" name="comment_content" rows="3" placeholder="Ní thấy bài viết này thế nào? Chia sẻ ý kiến nhé..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4 rounded-pill fw-semibold">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Gửi Bình Luận
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <p class="text-muted mb-2">Ní cần đăng nhập để tham gia bình luận bài viết này nhé!</p>
                            <a href="login.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">Đăng Nhập Ngay</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Danh Sách Bình Luận -->
                <div class="comment-list d-flex flex-column gap-3">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $com): 
                            $com_username = $com['user_name'] ?? $com['username'] ?? 'Thành viên';
                        ?>
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Avatar Chữ Đầu -->
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 flex-shrink-0" style="width: 45px; height: 45px;">
                                        <?= strtoupper(mb_substr($com_username, 0, 1)) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($com_username) ?></h6>
                                            <small class="text-muted" style="font-size: 0.8rem;">
                                                <i class="fa-regular fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($com['created_at'])) ?>
                                            </small>
                                        </div>
                                        <p class="text-secondary mb-2" style="line-height: 1.5; font-size: 0.95rem;">
                                            <?= nl2br(htmlspecialchars($com['content'] ?? '')) ?>
                                        </p>

                                        <!-- Nút Tương Tác: Thả Tim & Trả Lời -->
                                        <div class="d-flex align-items-center gap-3 pt-1">
                                            <button class="btn btn-sm text-danger border-0 p-0 btn-like-comment" data-id="<?= $com['id'] ?>">
                                                <i class="<?= $com['is_liked'] ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                                <span class="like-count"><?= $com['like_count'] ?></span>
                                            </button>

                                            <?php if (isLoggedIn()): ?>
                                                <button class="btn btn-sm text-secondary border-0 p-0 btn-toggle-reply" data-id="<?= $com['id'] ?>" style="font-size: 0.85rem;">
                                                    <i class="fa-regular fa-comment-dots me-1"></i>Trả lời
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Form Trả Lời (Ẩn mặc định) -->
                                        <div class="reply-form mt-3 d-none" id="reply-form-<?= $com['id'] ?>">
                                            <form action="detail.php?id=<?= $article_id ?>#comments" method="POST">
                                                <input type="hidden" name="parent_id" value="<?= $com['id'] ?>">
                                                <div class="d-flex gap-2">
                                                    <input type="text" name="comment_content" class="form-control form-control-sm rounded-pill px-3" placeholder="Trả lời <?= htmlspecialchars($com_username) ?>..." required>
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 flex-shrink-0">Gửi</button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- HIỂN THỊ CÁC BÌNH LUẬN CON (REPLIES) -->
                                        <?php
                                        $sql_replies = "SELECT c.*, 
                                                       (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) as like_count,
                                                       (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id AND cl.user_id = ?) as is_liked
                                                       FROM comments c 
                                                       WHERE c.parent_id = ? 
                                                       ORDER BY c.created_at ASC";
                                        $stmt_reply = $pdo->prepare($sql_replies);
                                        $stmt_reply->execute([$_SESSION['user_id'] ?? 0, $com['id']]);
                                        $replies = $stmt_reply->fetchAll(PDO::FETCH_ASSOC);
                                        ?>

                                        <?php if (!empty($replies)): ?>
                                            <div class="replies-list mt-3 pt-3 border-top">
                                                <?php foreach ($replies as $reply): 
                                                    $reply_username = $reply['user_name'] ?? $reply['username'] ?? 'Thành viên';
                                                ?>
                                                    <div class="d-flex align-items-start gap-2 mb-2">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                                            <?= strtoupper(mb_substr($reply_username, 0, 1)) ?>
                                                        </div>
                                                        <div class="flex-grow-1 bg-light p-2 rounded-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <strong class="small text-dark"><?= htmlspecialchars($reply_username) ?></strong>
                                                                <small class="text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?></small>
                                                            </div>
                                                            <p class="mb-1 text-secondary small"><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
                                                            
                                                            <div>
                                                                <button class="btn btn-sm text-danger border-0 p-0 btn-like-comment" data-id="<?= $reply['id'] ?>" style="font-size: 0.75rem;">
                                                                    <i class="<?= $reply['is_liked'] ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                                                    <span class="like-count"><?= $reply['like_count'] ?></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 bg-white rounded-3 text-muted border border-dashed">
                            <i class="fa-regular fa-comment-dots fs-2 d-block mb-2 text-secondary"></i>
                            Chưa có bình luận nào. Hãy là người đầu tiên để lại ý kiến nhé!
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script>
// 1. JS Xử lý Thích & Lưu Bài Viết
function likeArticle(articleId) {
    fetch('ajax_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
            if (data.message.includes('đăng nhập')) window.location.href = 'login.php';
        }
    })
    .catch(error => console.error('Lỗi kết nối:', error));
}

function favoriteArticle(articleId) {
    fetch('ajax_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
            if (data.message.includes('đăng nhập')) window.location.href = 'login.php';
        }
    })
    .catch(error => console.error('Lỗi kết nối:', error));
}

function shareArticle() {
    navigator.clipboard.writeText(window.location.href);
    alert('Đã sao chép liên kết bài viết vào bộ nhớ tạm!');
}

// 2. JS Xử lý Trả lời & Thả tim Bình luận
document.addEventListener('DOMContentLoaded', function () {
    // Ẩn / Hiện khung trả lời
    document.querySelectorAll('.btn-toggle-reply').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                replyForm.classList.toggle('d-none');
            }
        });
    });

    // Thả tim bình luận bằng AJAX
    document.querySelectorAll('.btn-like-comment').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('.like-count');

            const formData = new FormData();
            formData.append('comment_id', commentId);

            fetch('ajax_like_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    countSpan.textContent = data.total_likes;
                    if (data.action === 'liked') {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                    } else {
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error('Lỗi:', err));
        });
    });
});
</script>
</body>
</html>