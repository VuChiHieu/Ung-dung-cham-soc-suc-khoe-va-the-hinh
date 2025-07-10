<?php
session_start();
require 'connect.php';

header('Content-Type: application/json');

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        // Gán session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        echo json_encode([
            "status" => "success",
            "name" => $user['name'],
            "role" => $user['role'] 
        ]);
    } else {
        echo json_encode(["status" => "wrong-password"]);
    }
} else {
    echo json_encode(["status" => "no-user"]);
}

$stmt->close();
$conn->close();
