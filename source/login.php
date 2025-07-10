<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: fitlife_users.php");
                exit();
            } else {
                $error = "Bạn không có quyền truy cập.";
            }
        } else {
            $error = "Sai mật khẩu.";
        }
    } else {
        $error = "Tài khoản không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập quản trị</title>
  <style>
    body {
      background: linear-gradient(to right, #2193b0, #6dd5ed);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-box {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      text-align: center;
      width: 350px;
    }

    .login-box h2 {
      margin-bottom: 20px;
      color: #2193b0;
    }

    .login-box input {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-box button {
      background: #2193b0;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    .login-box button:hover {
      background: #197693;
    }

    .error {
      color: red;
      margin-top: -10px;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Đăng nhập Admin</h2>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" >
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
    </form>
  </div>
</body>
</html>
