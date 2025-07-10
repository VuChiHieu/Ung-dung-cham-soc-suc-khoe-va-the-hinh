<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Bạn chưa đăng nhập!";
    exit();
}

$user_id = $_SESSION['user_id'];
$calo_in = intval($_POST['calo_in']);
$calo_out = intval($_POST['calo_out']);
$date = date('Y-m-d');

// Kiểm tra xem hôm nay đã có dữ liệu chưa
$check = $conn->prepare("SELECT id FROM calories_tracker WHERE user_id = ? AND date = ?");
$check->bind_param("is", $user_id, $date);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Đã có → cộng dồn
    $stmt = $conn->prepare("UPDATE calories_tracker 
        SET calories_in = calories_in + ?, calories_out = calories_out + ? 
        WHERE user_id = ? AND date = ?");
    $stmt->bind_param("iiis", $calo_in, $calo_out, $user_id, $date);
    $stmt->execute();
    echo "Đã cập nhật: +{$calo_in} calo nạp, +{$calo_out} calo tiêu hao.";
} else {
    // Chưa có → thêm mới
    $stmt = $conn->prepare("INSERT INTO calories_tracker (user_id, date, calories_in, calories_out) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $user_id, $date, $calo_in, $calo_out);
    $stmt->execute();
    echo "Đã lưu mới: {$calo_in} calo nạp, {$calo_out} calo tiêu hao.";
}

$stmt->close();
$conn->close();
?>
