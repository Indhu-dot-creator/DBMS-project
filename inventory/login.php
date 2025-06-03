<?php
session_start();

$conn = new mysqli("localhost", "root", "", "inventory_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid password'); window.location.href='users.php';</script>";
    }
} else {
    echo "<script>alert('User not found'); window.location.href='users.php';</script>";
}

$stmt->close();
$conn->close();
?>
