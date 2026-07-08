<?php
require_once '../config/db.php';

$name = "System Admin";
$email = "adminaccessedu@gmail.com";
$password = "access123"; // Change this to your desired password
$phone = "09889013089";

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admin (name, email, password, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone);

if ($stmt->execute()) {
    echo "Admin user seeded successfully!";
} else {
    if ($conn->errno == 1062) {
        echo "Error: Admin email already exists.";
    } else {
        echo "Error: " . $conn->error;
    }
}
$stmt->close();
?>