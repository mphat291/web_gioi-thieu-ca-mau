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
     * Lấy tất cả bài viết
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                ORDER BY a.created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
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
     * Lấy bài viết theo danh mục
     */
    public function getByCategory($category_id, $limit = 10, $offset = 0) {
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.category_id = :category_id 
                ORDER BY a.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':category_id', (int)$category_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm bài viết mới
     */
    public function create($category_id, $title, $content, $image = '') {
        $stmt = $this->conn->prepare(
            "INSERT INTO articles (category_id, title, content, image, created_at) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$category_id, $title, $content, $image]);
    }

    /**
     * Cập nhật bài viết
     */
    public function update($id, $category_id, $title, $content, $image = null) {
        if ($image) {
            $sql = "UPDATE articles 
                    SET category_id = ?, title = ?, content = ?, image = ?, updated_at = NOW() 
                    WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$category_id, $title, $content, $image, $id]);
        } else {
            $sql = "UPDATE articles 
                    SET category_id = ?, title = ?, content = ?, updated_at = NOW() 
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
     * Kiểm tra user đã like bài viết chưa
     */
    public function isLikedByUser($article_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE article_id = ? AND user_id = ?");
        $stmt->execute([$article_id, $user_id]);
        return $stmt->fetch() !== false;
    }

    /**
     * Thêm like từ user
     */
    public function addLikeByUser($article_id, $user_id) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO likes (article_id, user_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)"
            );
            return $stmt->execute([$article_id, $user_id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Bỏ like từ user
     */
    public function removeLikeByUser($article_id, $user_id) {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE article_id = ? AND user_id = ?");
        return $stmt->execute([$article_id, $user_id]);
    }

    /**
     * Đếm tổng likes của bài viết
     */
    public function getLikeCount($article_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM likes WHERE article_id = ?");
        $stmt->execute([$article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Kiểm tra user đã yêu thích (save) bài viết chưa
     */
    public function isFavoritedByUser($article_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT id FROM favorites WHERE article_id = ? AND user_id = ?");
        $stmt->execute([$article_id, $user_id]);
        return $stmt->fetch() !== false;
    }

    /**
     * Thêm favorite từ user
     */
    public function addFavoriteByUser($article_id, $user_id) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO favorites (article_id, user_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)"
            );
            return $stmt->execute([$article_id, $user_id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Bỏ favorite từ user
     */
    public function removeFavoriteByUser($article_id, $user_id) {
        $stmt = $this->conn->prepare("DELETE FROM favorites WHERE article_id = ? AND user_id = ?");
        return $stmt->execute([$article_id, $user_id]);
    }

    /**
     * Tìm kiếm bài viết
     */
    public function search($keyword, $limit = 10, $offset = 0) {
        $keyword = "%" . trim($keyword) . "%";
        $sql = "SELECT a.*, c.category_name, 
                (SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) as real_likes 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.title LIKE ? OR a.content LIKE ? 
                ORDER BY a.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
        $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số bài viết
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM articles")->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Đếm số bài viết theo danh mục
     */
    public function countByCategory($category_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM articles WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>