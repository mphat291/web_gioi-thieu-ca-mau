<?php
// classes/Comment.php
require_once __DIR__ . '/../config/db.php';

class Comment {
    private $conn;

    public function __construct($conn = null) {
        global $pdo;
        $this->conn = $conn ?? $pdo;
    }

    // Lấy tất cả bình luận theo bài viết
    public function getByArticleId($article_id) {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy TOÀN BỘ bình luận (Phục vụ cho trang admin/comments.php)
    public static function getAll($conn = null) {
        global $pdo;
        $db = $conn ?? $pdo;
        $sql = "SELECT c.*, a.title as article_title 
                FROM comments c 
                LEFT JOIN articles a ON c.article_id = a.id 
                ORDER BY c.created_at DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái duyệt (approved / pending)
    public static function updateStatus($id, $status, $conn = null) {
        global $pdo;
        $db = $conn ?? $pdo;
        $stmt = $db->prepare("UPDATE comments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Thêm bình luận
    public function create($article_id, $user_name, $content) {
        $sql = "INSERT INTO comments (article_id, user_name, content, created_at, status) VALUES (?, ?, ?, NOW(), 'approved')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$article_id, $user_name, $content]);
    }

    // Xóa bình luận
    public static function delete($id, $conn = null) {
        global $pdo;
        $db = $conn ?? $pdo;
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Đếm số lượng bình luận theo bài viết
    public function countByArticleId($article_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM comments WHERE article_id = ?");
        $stmt->execute([$article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    // Đếm tổng số lượng tất cả bình luận
    public static function count($conn = null) {
        global $pdo;
        $db = $conn ?? $pdo;
        $stmt = $db->query("SELECT COUNT(*) FROM comments");
        return $stmt->fetchColumn();
    }

    // Đếm số lượng bình luận chờ duyệt
    public static function countPending($conn = null) {
        global $pdo;
        $db = $conn ?? $pdo;
        $stmt = $db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'");
        return $stmt->fetchColumn();
    }
}
?>