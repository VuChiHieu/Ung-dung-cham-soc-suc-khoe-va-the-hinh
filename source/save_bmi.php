<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "❌ Bạn chưa đăng nhập!";
    exit();
}

$user_id = $_SESSION['user_id'];
$weight = floatval($_POST['weight']);
$height = floatval($_POST['height']);
$date = date('Y-m-d');

// Kiểm tra đầu vào hợp lệ
if ($weight <= 0 || $weight > 300 || $height <= 0 || $height > 250) {
    echo "⚠️ Vui lòng nhập số liệu hợp lệ (cân nặng ≤ 300kg, chiều cao ≤ 250cm)";
    exit();
}

// Tính BMI
$bmi = $weight / pow($height / 100, 2);
$bmi = round($bmi, 1);

// Phân loại
$status = "";
if ($bmi < 18.5) {
    $status = "Gầy (Thiếu cân). 👉 Bạn nên tham khảo <a href='chedoan.html#thieucan'>thực đơn cho người thiếu cân</a> để tăng cường dinh dưỡng.";
} elseif ($bmi < 23) {
    $status = "Bình thường. ✔️ Vóc dáng lý tưởng! Bạn nên duy trì theo <a href='#daydudu'>bữa ăn đầy đủ dinh dưỡng</a>.";
} elseif ($bmi < 25) {
    $status = "Hơi thừa cân. 👉 Bạn nên tham khảo <a href='chedoan.html#thua-can'>thực đơn cho người thừa cân</a> và tăng cường vận động.";
} else {
    $status = "Thừa cân. ⚠️ Hãy cẩn thận với sức khỏe. Bạn nên áp dụng thực đơn giảm cân và <a href='chedoan.html#thua-can'>ăn theo thực đơn cho người thừa cân</a> và <a href='fitness.html#burn-fat'>tập các bài tập để có thân hình đẹp nhé</a>.";
}

// Lưu vào CSDL
$stmt = $conn->prepare("INSERT INTO body_metrics (user_id, weight, height, bmi, date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iddds", $user_id, $weight, $height, $bmi, $date);

if ($stmt->execute()) {
    echo "✔️ Đã lưu BMI thành công!<br>👉 Chỉ số BMI: <strong>$bmi</strong><br>📌 Tình trạng: <strong>$status</strong>";
} else {
    echo "❌ Lỗi khi lưu dữ liệu.";
}
?>
