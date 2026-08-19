<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_camau_vanhoa";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Đặt alias cho compatibility
    $pdo = $conn;
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>