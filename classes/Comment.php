<?php
// classes/Comment.php
require_once __DIR__ . '/../config/db.php';

class Comment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy tất cả bình luận theo bài viết
    public function getByArticleId($article_id) {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm bình luận - Cột trong DB của bạn là 'user_name'
    public function create($article_id, $user_name, $content) {
        $sql = "INSERT INTO comments (article_id, user_name, content, created_at, status) VALUES (?, ?, ?, NOW(), 'approved')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$article_id, $user_name, $content]);
    }

    // Xóa bình luận
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Đếm số lượng bình luận
    public function countByArticleId($article_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM comments WHERE article_id = ?");
        $stmt->execute([$article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }
}
?>