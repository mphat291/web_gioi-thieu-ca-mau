<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {

        $message = "<div class='alert alert-danger'>Vui lòng nhập đầy đủ thông tin.</div>";

    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user;

            if ($user['role'] == "admin") {

                header("Location: admin/index.php");

            } else {

                header("Location: index.php");

            }

            exit();

        } else {

            $message = "<div class='alert alert-danger'>Sai tài khoản hoặc mật khẩu.</div>";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Đăng nhập</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="text-center">Đăng nhập</h4>

</div>

<div class="card-body">

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label>Tài khoản</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Mật khẩu</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
type="submit"
name="login"
class="btn btn-primary w-100">

Đăng nhập

</button>

</form>

<hr>

<p class="text-center">

Chưa có tài khoản?

<a href="register.php">

Đăng ký

</a>

</p>

</div>

</div>

</div>

</div>

</div>

</body>

</html>