<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'classes/Article.php';
require_once 'classes/Comment.php';
require_once 'classes/Category.php';

$article_obj = new Article($pdo);
$comment_obj = new Comment($pdo);

// Hàm xử lý đường dẫn ảnh chuẩn cho thư mục assets/img/
function getArticleImage($imageName) {
    if (empty($imageName)) {
        return 'assets/img/bacbaphi.jpg'; 
    }
    
    if (strpos($imageName, 'assets/img/') === 0) {
        return htmlspecialchars($imageName);
    }
    
    return 'assets/img/' . htmlspecialchars($imageName);
}

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

// Kiểm tra quyền Admin
$isAdmin = false;
if (isLoggedIn()) {
    if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ||
        (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ||
        (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') ||
        (function_exists('isAdmin') && isAdmin())) {
        $isAdmin = true;
    }
}

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

                <!-- Hiển thị ảnh bài viết dùng getArticleImage -->
                <?php if (!empty($article['image'])): ?>
                    <img src="<?= getArticleImage($article['image']) ?>" 
                         class="img-fluid rounded-3 mb-4 w-100" 
                         style="max-height: 450px; object-fit: cover;" 
                         alt="<?= htmlspecialchars($article['title']) ?>"
                         onerror="this.src='assets/img/bacbaphi.jpg';">
                <?php endif; ?>

                <div class="lead lh-lg mb-4"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

                <!-- Actions Bài Viết -->
                <div class="d-flex gap-2 mt-4">
                    <?php if (isLoggedIn()): ?>
                        <button class="btn <?= $userLiked ? 'btn-primary' : 'btn-outline-primary' ?>" id="like-btn" onclick="likeArticle(<?= $article_id ?>)">
                            <?= $userLiked ? '💛' : '❤️' ?> Thích (<span id="like-text"><?= $likeCount ?></span>)
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary">❤️ Đăng nhập để thả cảm xúc</a>
                    <?php endif; ?>
                    
                    <!-- Nút Chia Sẻ Gọi Hàm Thông Minh -->
                    <button class="btn btn-secondary px-3 py-2 fw-semibold rounded-3" onclick="shareArticle()">
                        <i class="fa-solid fa-share-nodes me-2"></i>Chia Sẻ
                    </button>
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
                                <textarea class="form-control rounded-3" id="comment_content" name="comment_content" rows="3" placeholder="Bạn thấy bài viết này thế nào? Chia sẻ ý kiến nhé..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4 rounded-pill fw-semibold">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Gửi Bình Luận
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <p class="text-muted mb-2">Bạn cần đăng nhập để tham gia bình luận bài viết này nhé!</p>
                            <a href="login.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">Đăng Nhập Ngay</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Danh Sách Bình Luận -->
                <div class="comment-list d-flex flex-column gap-3">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $com): 
                            $com_username = !empty($com['user_name']) ? $com['user_name'] : (!empty($com['username']) ? $com['username'] : 'Thành viên');
                        ?>
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                <div class="d-flex align-items-start gap-3">
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

                                        <div class="d-flex align-items-center gap-3 pt-1">
                                            <button class="btn btn-sm text-danger border-0 p-0 btn-like-comment" data-id="<?= $com['id'] ?>">
                                                <i class="<?= $com['is_liked'] ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                                <span class="like-count"><?= $com['like_count'] ?></span>
                                            </button>

                                            <?php if (isLoggedIn()): ?>
                                                <button class="btn btn-sm text-secondary border-0 p-0 btn-toggle-reply" data-id="<?= $com['id'] ?>" style="font-size: 0.85rem;">
                                                    <i class="fa-regular fa-comment-dots me-1"></i>Trả lời
                                                </button>

                                                <?php if ($isAdmin): ?>
                                                    <a href="delete_comment.php?id=<?= $com['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?')" class="btn btn-sm text-danger border-0 p-0 ms-2 text-decoration-none" style="font-size: 0.85rem;">
                                                        <i class="fa-solid fa-trash me-1"></i>Xóa
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Nút Tố Cáo Bình Luận Chính Kích Hoạt Modal Chuẩn -->
                                                    <button type="button" 
                                                            class="btn btn-sm text-danger border-0 p-0 ms-2 fw-bold" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#reportModal" 
                                                            onclick="document.getElementById('report_comment_id').value = <?= $com['id'] ?>" 
                                                            style="font-size: 0.85rem;">
                                                        <i class="fa-solid fa-flag me-1"></i>Tố cáo
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                        <div class="reply-form mt-3 d-none" id="reply-form-<?= $com['id'] ?>">
                                            <form action="detail.php?id=<?= $article_id ?>#comments" method="POST">
                                                <input type="hidden" name="parent_id" value="<?= $com['id'] ?>">
                                                <div class="d-flex gap-2">
                                                    <input type="text" name="comment_content" class="form-control form-control-sm rounded-pill px-3" placeholder="Trả lời <?= htmlspecialchars($com_username) ?>..." required>
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 flex-shrink-0">Gửi</button>
                                                </div>
                                            </form>
                                        </div>

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
                                                    $reply_username = !empty($reply['user_name']) ? $reply['user_name'] : (!empty($reply['username']) ? $reply['username'] : 'Thành viên');
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
                                                            
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button class="btn btn-sm text-danger border-0 p-0 btn-like-comment" data-id="<?= $reply['id'] ?>" style="font-size: 0.75rem;">
                                                                    <i class="<?= $reply['is_liked'] ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                                                                    <span class="like-count"><?= $reply['like_count'] ?></span>
                                                                </button>
                                                                <?php if (isLoggedIn()): ?>
                                                                    <?php if ($isAdmin): ?>
                                                                        <a href="delete_comment.php?id=<?= $reply['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?')" class="btn btn-sm text-danger border-0 p-0 ms-2 text-decoration-none" style="font-size: 0.75rem;">
                                                                            <i class="fa-solid fa-trash me-1"></i>Xóa
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <!-- Nút Tố Cáo Bình Luận Trả Lời Kích Hoạt Modal Chuẩn -->
                                                                        <button type="button" 
                                                                                class="btn btn-sm text-danger border-0 p-0 ms-2 fw-bold" 
                                                                                data-bs-toggle="modal" 
                                                                                data-bs-target="#reportModal" 
                                                                                onclick="document.getElementById('report_comment_id').value = <?= $reply['id'] ?>" 
                                                                                style="font-size: 0.75rem;">
                                                                            <i class="fa-solid fa-flag me-1"></i>Tố cáo
                                                                        </button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
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

        <div class="col-lg-4">
        </div>
    </div>
</main>

<!-- MODAL TỐ CÁO BÌNH LUẬN -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="report_comment.php" method="POST" class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Báo cáo bình luận vi phạm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <input type="hidden" name="comment_id" id="report_comment_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Lý do tố cáo:</label>
                    <select name="reason" class="form-select rounded-3" required>
                        <option value="Spam / Quảng cáo">Spam / Quảng cáo</option>
                        <option value="Ngôn từ thô tục / Xúc phạm">Ngôn từ thô tục / Xúc phạm</option>
                        <option value="Thông tin sai lệch">Thông tin sai lệch</option>
                        <option value="Lý do khác">Lý do khác</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Chi tiết thêm (tùy chọn):</label>
                    <textarea name="detail" class="form-control rounded-3" rows="3" placeholder="Nhập mô tả chi tiết nếu cần..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-semibold">Gửi báo cáo</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CHIA SẺ -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-share-nodes text-success me-2"></i>Chia sẻ bài viết
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-muted small mb-4">Chọn nền tảng bạn muốn chia sẻ bài viết đến bạn bè:</p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
                    <a href="#" id="share-facebook" target="_blank" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;" title="Chia sẻ lên Facebook">
                        <i class="fa-brands fa-facebook-f fs-5"></i>
                    </a>
                    
                    <a href="#" id="share-messenger" target="_blank" class="btn btn-outline-info rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;" title="Chia sẻ qua Messenger">
                        <i class="fa-brands fa-facebook-messenger fs-5"></i>
                    </a>
                    
                    <a href="#" id="share-zalo" target="_blank" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold text-white" style="width: 50px; height: 50px; background-color: #0068ff; border-color: #0068ff;" title="Chia sẻ qua Zalo">
                        Zalo
                    </a>

                    <a href="#" id="share-telegram" target="_blank" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;" title="Chia sẻ qua Telegram">
                        <i class="fa-brands fa-telegram fs-5"></i>
                    </a>
                </div>

                <div class="input-group mb-2">
                    <input type="text" class="form-control bg-light" id="share-url-input" readonly>
                    <button class="btn btn-success px-3 fw-semibold" type="button" onclick="copyShareUrl()">
                        <i class="fa-regular fa-copy me-1"></i> Sao chép
                    </button>
                </div>
                
                <div id="copy-alert" class="text-success small fw-semibold d-none">
                    <i class="fa-solid fa-circle-check me-1"></i> Đã sao chép liên kết vào bộ nhớ tạm!
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- Thư viện Bootstrap JS Bundle (Bắt buộc để Modal chạy được) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
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

// Xử lý Chia Sẻ Thông Minh
function shareArticle() {
    const title = <?= json_encode($article['title'] ?? 'Bài viết hay') ?>;
    const url = window.location.href;

    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Mời bạn xem bài viết: ' + title,
            url: url
        })
        .then(() => console.log('Chia sẻ thành công'))
        .catch((err) => console.log('Đã hủy chia sẻ', err));
    } else {
        const urlInput = document.getElementById('share-url-input');
        if (urlInput) urlInput.value = url;

        document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        document.getElementById('share-messenger').href = `https://www.facebook.com/dialog/send?link=${encodeURIComponent(url)}&app_id=291494419107518&redirect_uri=${encodeURIComponent(url)}`;
        document.getElementById('share-zalo').href = `https://zalo.me/share?url=${encodeURIComponent(url)}`;
        document.getElementById('share-telegram').href = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;

        const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
        shareModal.show();
    }
}

// Sao chép liên kết
function copyShareUrl() {
    const urlInput = document.getElementById('share-url-input');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(urlInput.value).then(() => {
        const alertBox = document.getElementById('copy-alert');
        if (alertBox) {
            alertBox.classList.remove('d-none');
            setTimeout(() => alertBox.classList.add('d-none'), 3000);
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Trả lời bình luận
    document.querySelectorAll('.btn-toggle-reply').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                replyForm.classList.toggle('d-none');
            }
        });
    });

    // Thả tim bình luận
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