<?php
/**
 * Model Article - Quản lý bài viết
 */

class Article {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Lấy tất cả bài viết (chỉ lấy bài đã duyệt)
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.status = 'approved'
                ORDER BY a.created_at DESC";
        
        if ($limit !== null) {
            $limit = (int)$limit;
            $offset = (int)$offset;
            $sql .= " LIMIT $limit OFFSET $offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } else {
            $stmt = $this->conn->query($sql);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy bài viết theo ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT a.*, c.category_name, 
            (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy bài viết theo danh mục (chỉ lấy bài đã duyệt)
     */
    public function getByCategory($category_id, $limit = 10, $offset = 0) {
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.category_id = :category_id AND a.status = 'approved'
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':category_id', (int)$category_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm bài viết mới (Hỗ trợ truyền status, mặc định là pending cho user gửi)
     */
    public function create($category_id, $title, $content, $image = '', $status = 'pending') {
        $stmt = $this->conn->prepare(
            "INSERT INTO articles (category_id, title, content, image, status, created_at) 
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$category_id, $title, $content, $image, $status]);
    }

    /**
     * Cập nhật bài viết
     */
    public function update($id, $category_id, $title, $content, $image = null) {
        if ($image) {
            $sql = "UPDATE articles 
                    SET category_id = ?, title = ?, content = ?, image = ? 
                    WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$category_id, $title, $content, $image, $id]);
        } else {
            $sql = "UPDATE articles 
                    SET category_id = ?, title = ?, content = ? 
                    WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$category_id, $title, $content, $id]);
        }
    }

    /**
     * Xóa bài viết
     */
    public function delete($id) {
        $article = $this->getById($id);
        if ($article && !empty($article['image'])) {
            $image_path = '../assets/images/' . $article['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Tăng lượt xem
     */
    public function increaseViews($id) {
        $stmt = $this->conn->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Tìm kiếm bài viết (chỉ tìm trong bài đã duyệt)
     */
    public function search($keyword, $limit = 10, $offset = 0) {
        $keyword = "%" . trim($keyword) . "%";
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE (a.title LIKE ? OR a.content LIKE ?) AND a.status = 'approved'
                ORDER BY a.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
        $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số bài viết đã duyệt
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM articles WHERE status = 'approved'")->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Đếm số lượt thích của bài viết
     */
    public function getLikeCount($article_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE article_id = ?");
        $stmt->execute([$article_id]);
        return $stmt->fetchColumn();
    }

    /**
     * Kiểm tra user đã thích bài viết này chưa
     */
    public function isLikedByUser($article_id, $user_id) {
        if (!$user_id) return false;
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE article_id = ? AND user_id = ?");
        $stmt->execute([$article_id, $user_id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>