<?php
include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($name) || empty($email)) {
        $message = "Vui lòng điền đầy đủ Tên và Email.";
        $message_type = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Định dạng Email không hợp lệ.";
        $message_type = 'error';
    } else if (!empty($age) && !is_numeric($age)) {
        $message = "Tuổi phải là một số.";
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO bookings (name, age, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $email, $phone);

        if ($stmt->execute()) {
            header("Location: fitlife_bookings.php?message=" . urlencode("Thêm đặt chỗ thành công!") . "&type=success");
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
    <title>Thêm đặt chỗ mới</title>
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
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 25px;
    }

    form div {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"] {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1em;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }
    input:focus {
        border-color: #28a745;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    button:hover {
        background-color: #218838;
    }

    .back-link {
        display: inline-block;
        margin-top: 25px;
        padding: 10px 16px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .back-link:hover {
        background-color: #5a6268;
    }

    .message.error {
        color: #dc3545;
        background-color: #f8d7da;
        padding: 10px 15px;
        border-left: 4px solid #dc3545;
        border-radius: 4px;
        margin-bottom: 15px;
    }
    .message.success {
        color: #155724;
        background-color: #d4edda;
        padding: 10px 15px;
        border-left: 4px solid #28a745;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm đặt chỗ mới</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="fitlife_bookings_add.php" method="post">
            <div>
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="age">Tuổi:</label>
                <input type="number" id="age" name="age">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone">
            </div>
            <button type="submit">Thêm đặt chỗ</button>
        </form>

        <a href="fitlife_bookings.php" class="back-link">Quay lại danh sách</a>
    </div>
</body>
</html>
