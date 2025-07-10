<?php
$raw_password = "HieukhoiHieu2005!";
$hashed = password_hash($raw_password, PASSWORD_DEFAULT);
echo "Mật khẩu hash là: " . $hashed;
?>
