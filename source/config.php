<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$db   = "fitlife_db";
$user = "root";
$pass = "";

// Tạo kết nối (dùng đúng biến $host, $user, $pass, $db)
$conn = new mysqli($host, $user, $pass, $db);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Đặt charset UTF-8 để hiển thị tiếng Việt
$conn->set_charset("utf8");
?>
