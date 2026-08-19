<?php
/**
 * Model User - Quản lý người dùng
 */

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Lấy tất cả người dùng
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        
        if ($limit) {
            $limit = (int)$limit;
            $offset = (int)$offset;
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo username
     */
    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo email
     */
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm người dùng mới
     */
    public function create($username, $email, $password, $role = 'user') {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, email, password, role, created_at) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$username, $email, $hashed_password, $role]);
    }

    /**
     * Cập nhật người dùng
     */
    public function update($id, $username, $email, $role = null) {
        if ($role) {
            $sql = "UPDATE users SET username = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$username, $email, $role, $id]);
        } else {
            $sql = "UPDATE users SET username = ?, email = ?, updated_at = NOW() WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$username, $email, $id]);
        }
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword($id, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$hashed_password, $id]);
    }

    /**
     * Xóa người dùng
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Đếm số người dùng
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users")->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Đếm số admin
     */
    public function countAdmins() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'")->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
