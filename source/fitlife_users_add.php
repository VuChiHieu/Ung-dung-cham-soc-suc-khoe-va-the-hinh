<?php
include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        $message = "Vui lòng điền đầy đủ các trường bắt buộc.";
        $message_type = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Định dạng Email không hợp lệ.";
        $message_type = 'error';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $surname, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: fitlife_users.php?message=" . urlencode("Thêm người dùng thành công!") . "&type=success");
            exit();
        } else {
            $message = "Lỗi: " . $stmt->error;
            $message_type = 'error';
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Thêm người dùng mới</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; text-align: center; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover { background-color: #0056b3; }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover { background-color: #5a6268; }

        .message.error { color: red; margin-bottom: 15px; }
        .message.success { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm người dùng mới</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="fitlife_users_add.php" method="post">
            <div>
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="surname">Họ:</label>
                <input type="text" id="surname" name="surname" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Thêm người dùng</button>
        </form>

        <a href="fitlife_users.php" class="back-link">Quay lại danh sách</a>
    </div>
</body>
</html>