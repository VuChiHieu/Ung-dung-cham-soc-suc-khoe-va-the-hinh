<?php
include 'config.php';

$message = '';
$message_type = '';
$user = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT id, name, surname, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        $message = "Không tìm thấy người dùng.";
        $message_type = 'error';
        $user = null;
    }
    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($id) || empty($name) || empty($surname) || empty($email)) {
        $message = "Vui lòng điền đầy đủ các trường bắt buộc.";
        $message_type = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Định dạng Email không hợp lệ.";
        $message_type = 'error';
    } else {
        $sql = "UPDATE users SET name = ?, surname = ?, email = ?";
        $params = "sss";
        $values = [&$name, &$surname, &$email];

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params .= "s";
            $values[] = &$hashed_password;
        }

        $sql .= " WHERE id = ?";
        $params .= "i";
        $values[] = &$id;

        $stmt = $conn->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), array_merge([$params], $values));

        if ($stmt->execute()) {
            header("Location: fitlife_users.php?message=" . urlencode("Cập nhật người dùng thành công!") . "&type=success");
            exit();
        } else {
            $message = "Lỗi: " . $stmt->error;
            $message_type = 'error';
        }
        $stmt->close();
    }
    if ($message_type == 'error' && !empty($id)) {
        $stmt = $conn->prepare("SELECT id, name, surname, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
} else {
    header("Location: fitlife_users.php?message=" . urlencode("ID người dùng không hợp lệ hoặc không được cung cấp.") . "&type=error");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Sửa người dùng</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 30px;
        background: linear-gradient(to right, #e0eafc, #cfdef3);
    }

    .container {
        max-width: 600px;
        margin: auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
    }

    form div {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: #34495e;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1em;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    input:focus {
        border-color: #3498db;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.2s ease;
    }

    button:hover {
        background-color: #0056b3;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .back-link {
        display: block;
        margin-top: 25px;
        padding: 10px 15px;
        text-align: center;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .back-link:hover {
        background-color: #5a6268;
    }

    .message {
        padding: 12px 18px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-weight: 500;
    }

    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Sửa người dùng</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($user): ?>
        <form action="fitlife_users_edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div>
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div>
                <label for="surname">Họ:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div>
                <label for="password">Mật khẩu (Để trống nếu không đổi):</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit">Cập nhật người dùng</button>
        </form>
        <?php else: ?>
            <p>Không thể tải thông tin người dùng để chỉnh sửa. Vui lòng quay lại danh sách.</p>
        <?php endif; ?>

        <a href="fitlife_users.php" class="back-link">Quay lại danh sách</a>
    </div>
</body>
</html>