<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';
$message_type = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'info';
}
?>

<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Quản lý người dùng FitLife</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        padding: 20px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 10px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .btn-add {
        background-color: #28a745;
    }

    .btn-add:hover {
        background-color: #218838;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    }

    .btn-calo {
        background-color: #1a92be;
    }

    .btn-calo:hover {
        background-color: #157aa1;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #3498db;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #eef6fc;
        transition: background 0.3s;
    }

    .action-buttons a {
        display: inline-block;
        padding: 8px 14px;
        margin: 2px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 0.9em;
        transition: background 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .add-button {
        background-color: #2ecc71;
    }

    .add-button:hover {
        background-color: #27ae60;
    }

    .edit-button {
        background-color: #f39c12;
    }

    .edit-button:hover {
        background-color: #e67e22;
    }

    .delete-button {
        background-color: #e74c3c;
    }

    .delete-button:hover {
        background-color: #c0392b;
    }

    .btn-link {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 18px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    .btn-link:hover {
        background-color: #5a6268;
    }

    .toast {
        visibility: hidden;
        min-width: 260px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 16px 24px;
        position: fixed;
        z-index: 9999;
        left: 50%;
        bottom: 30px;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.5s ease, visibility 0.5s ease;
        font-weight: 500;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .toast.show {
        visibility: visible;
        opacity: 1;
    }

    .toast.success {
        background-color: #28a745;
    }

    .toast.error {
        background-color: #e74c3c;
    }

    .toast.warning {
        background-color: #ffc107;
        color: #333;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý người dùng FitLife</h2>

        <a href="fitlife_users_add.php" class="btn btn-add"  target="_blank">➕ Thêm người dùng mới</a>
        <a href="admin_user_progress.php" class="btn btn-calo"  target="_blank">📊 Xem thống kê calo người dùng</a>
        <a href="admin_user_bmi.php" class="btn btn-calo"  target="_blank">📈 Xem thống kê BMI người dùng</a>
        <a href="admin_user_health.php" class="btn btn-calo"  target="_blank">❤️ Xem huyết áp & nhịp tim</a>


        <?php if (!empty($message)): ?>
            <div id="toast" class="toast show <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php
        $sql = "SELECT id, name, surname, email, created_at FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr>";
            echo "<th>ID</th>";
            echo "<th>Tên</th>";
            echo "<th>Họ</th>";
            echo "<th>Email</th>";
            echo "<th>Thời gian tạo</th>";
            echo "<th>Hành động</th>";
            echo "</tr></thead>";
            echo "<tbody>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td class='action-buttons'>";
                echo "  <a href='fitlife_users_edit.php?id=" . htmlspecialchars($row['id']) . "' class='edit-button'>Sửa</a>";
                echo "  <a href='fitlife_users_delete.php?id=" . htmlspecialchars($row['id']) . "' class='delete-button' onclick=\"return confirm('Bạn có chắc chắn muốn xóa người dùng này?');\">Xóa</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Không có người dùng nào trong database.</p>";
        }

        $conn->close();
        ?>

        <a href="fitlife_bookings.php" class="btn-link">Xem danh sách đặt chỗ</a>
        <a href="logout.php" class="btn" style="background-color: #dc3545;">Đăng xuất</a>
    </div>

    <script>
        window.onload = function() {
            const toast = document.getElementById("toast");
            if (toast && toast.classList.contains('show')) {
                setTimeout(() => {
                    toast.classList.remove("show");
                }, 3000);
            }
        };
    </script>
</body>
</html>
