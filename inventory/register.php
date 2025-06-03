<?php
session_start();

$conn = new mysqli("localhost", "root", "", "inventory_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $email, $password, $role);

if ($stmt->execute()) {
    echo "<script>alert('User registered successfully'); window.location.href='users.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
