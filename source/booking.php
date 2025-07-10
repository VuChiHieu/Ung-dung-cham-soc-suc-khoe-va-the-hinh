<?php
require 'connect.php';

$name = $_POST['name'];
$age = $_POST['age'];
$email = $_POST['email'];
$phone = $_POST['phone'];

if (empty($name) || empty($age) || empty($email) || empty($phone)) {
    echo "error: missing fields";
    exit;
}

if (!is_numeric($age) || $age < 0 || $age > 120) {
    echo "error: invalid age";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "error: invalid email";
    exit;
}

if (!preg_match('/^\d{10}$/', $phone)) {
    echo "error: invalid phone";
    exit;
}

$sql = "INSERT INTO bookings (name, age, email, phone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $name, $age, $email, $phone);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
