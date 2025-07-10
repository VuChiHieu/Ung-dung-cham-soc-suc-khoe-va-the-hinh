<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "❌ Vui lòng đăng nhập.";
    exit();
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$content = trim($_POST['content']);
$type = $_POST['recommendation_type'];
$category = $_POST['category'];

$stmt = $conn->prepare("INSERT INTO recommendations (user_id, recommendation_type, title, content, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $type, $title, $content, $category);

if ($stmt->execute()) {
    echo "✔️ Đánh giá đã được lưu!";
} else {
    echo "❌ Lỗi khi lưu đánh giá.";
}
