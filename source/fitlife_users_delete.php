<?php
include 'config.php';

$message = '';
$message_type = '';
$redirect_url = 'fitlife_users.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Xóa người dùng thành công!";
        $message_type = "success";
        $redirect_url .= "?message=" . urlencode($message) . "&type=success";
    } else {
        $message = "Lỗi khi xóa: " . $stmt->error;
        $message_type = "error";
        $redirect_url .= "?message=" . urlencode($message) . "&type=error";
    }
    $stmt->close();
} else {
    $message = "ID người dùng không hợp lệ.";
    $message_type = "error";
    $redirect_url .= "?message=" . urlencode($message) . "&type=error";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đang xử lý...</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            font-size: 1.2em;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .loading {
            margin-top: 15px;
            font-size: 0.9em;
            color: #555;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
    </style>
    <meta http-equiv="refresh" content="2;url=<?php echo $redirect_url; ?>">
</head>
<body>
    <div class="message-box <?php echo $message_type; ?>">
        <?php echo $message; ?>
        <div class="loading">Đang chuyển hướng...</div>
    </div>
</body>
</html>
