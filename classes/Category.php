<?php
/**
 * Model Category - Quản lý danh mục
 */

class Category {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM categories ORDER BY category_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh mục theo ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm danh mục mới
     */
    public function create($category_name, $description = '') {
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (category_name, description) VALUES (?, ?)"
        );
        return $stmt->execute([$category_name, $description]);
    }

    /**
     * Cập nhật danh mục
     */
    public function update($id, $category_name, $description = '') {
        $stmt = $this->conn->prepare(
            "UPDATE categories SET category_name = ?, description = ? WHERE id = ?"
        );
        return $stmt->execute([$category_name, $description, $id]);
    }

    /**
     * Xóa danh mục
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Đếm số danh mục
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM categories")->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
