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
    <title>Quản lý đặt chỗ FitLife</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 30px;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #28a745;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            color: #fff;
        }

        .btn-add {
            background-color: #007bff;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        .action-buttons a {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            font-weight: 500;
        }

        .edit-button {
            background-color: #ffc107;
        }
        .edit-button:hover {
            background-color: #e0a800;
        }

        .delete-button {
            background-color: #dc3545;
        }
        .delete-button:hover {
            background-color: #c82333;
        }

        .btn-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 18px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }
        .btn-link:hover {
            background-color: #5a6268;
        }

        .toast {
            visibility: hidden;
            min-width: 280px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 16px;
            position: fixed;
            z-index: 100;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 0;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
        }

        .toast.success {
            background-color: #28a745;
        }
        .toast.error {
            background-color: #dc3545;
        }
        .toast.warning {
            background-color: #ffc107;
            color: #333;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý đặt chỗ FitLife</h2>

        <a href="fitlife_bookings_add.php" class="btn btn-add">➕ Thêm đặt chỗ mới</a>
        <?php if (!empty($message)): ?>
            <div id="toast" class="toast show <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php
        $sql = "SELECT id, name, age, email, phone, created_at FROM bookings";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead><tr>";
            echo "<th>ID</th>";
            echo "<th>Tên</th>";
            echo "<th>Tuổi</th>";
            echo "<th>Email</th>";
            echo "<th>Số điện thoại</th>";
            echo "<th>Thời gian tạo</th>";
            echo "<th>Hành động</th>";
            echo "</tr></thead>";
            echo "<tbody>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td class='action-buttons'>";
                echo "  <a href='fitlife_bookings_edit.php?id=" . htmlspecialchars($row['id']) . "' class='edit-button'>Sửa</a>";
                echo "  <a href='fitlife_bookings_delete.php?id=" . htmlspecialchars($row['id']) . "' class='delete-button' onclick=\"return confirm('Bạn có chắc chắn muốn xóa đặt chỗ này?');\">Xóa</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Không có đặt chỗ nào trong database.</p>";
        }

        $conn->close();
        ?>

        <a href="fitlife_users.php" class="btn-link">Xem danh sách người dùng</a>
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