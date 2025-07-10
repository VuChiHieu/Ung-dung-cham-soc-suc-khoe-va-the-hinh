<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Vui lòng đăng nhập trước.";
    exit();
}

$user_id = $_SESSION['user_id'];

$systolic = intval($_POST['systolic']);
$diastolic = intval($_POST['diastolic']);
$heart_rate = intval($_POST['heart_rate']);
$date = date('Y-m-d');

// Kiểm tra dữ liệu
if ($systolic <= 0 || $systolic > 250 ||
    $diastolic <= 0 || $diastolic > 150 ||
    $heart_rate <= 0 || $heart_rate > 200) {
    echo "Giá trị không hợp lệ!";
    exit();
}

// Lưu vào DB
$stmt = $conn->prepare("INSERT INTO health_metrics (user_id, systolic, diastolic, heart_rate, date) 
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiis", $user_id, $systolic, $diastolic, $heart_rate, $date);
$stmt->execute();

$msg = "Dữ liệu đã được lưu thành công.<br>";

$hasAlert = false;
// Huyết áp cao
if ($systolic >= 140 || $diastolic >= 90) {
    $msg .= "<strong style='color:red;'>⚠️ Bạn có dấu hiệu huyết áp cao!</strong><br>";
    $msg .= "<em style='color:#555;'>👉 Khuyến nghị: Hạn chế ăn mặn, tránh căng thẳng và nên theo dõi thêm.</em><br>";
    $hasAlert = true;
}

// Huyết áp thấp
if ($systolic < 90 || $diastolic < 60) {
    $msg .= "<strong style='color:orange;'>⚠️ Huyết áp có thể đang thấp.</strong><br>";
    $msg .= "<em style='color:#555;'>👉 Khuyến nghị: Nên nghỉ ngơi, uống đủ nước và tránh đứng dậy quá nhanh.</em><br>";
    $hasAlert = true;
}

// Nhịp tim thấp
if ($heart_rate < 60) {
    $msg .= "<strong style='color:orange;'>⚠️ Nhịp tim thấp hơn bình thường.</strong><br>";
    $msg .= "<em style='color:#555;'>👉 Khuyến nghị: Hạn chế tập luyện cường độ cao và nên theo dõi thêm nếu có triệu chứng.</em><br>";
    $hasAlert = true;
}

// Nhịp tim cao
if ($heart_rate > 100) {
    $msg .= "<strong style='color:red;'>⚠️ Nhịp tim cao!</strong><br>";
    $msg .= "<em style='color:#555;'>👉 Khuyến nghị: Hạn chế vận động mạnh, nên thư giãn và nghỉ ngơi nhiều hơn.</em><br>";
    $hasAlert = true;
}

// Nếu mọi thứ bình thường
if (!$hasAlert) {
    $msg .= "<strong style='color:green;'>✔️ Huyết áp và nhịp tim đang trong mức bình thường. Tiếp tục duy trì nhé!</strong>";
}

echo $msg;