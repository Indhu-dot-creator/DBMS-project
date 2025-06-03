<?php
include("includes/db.php");

$name = $_POST['name'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$sql = "INSERT INTO products (name, category, quantity, price) 
        VALUES ('$name', '$category', $quantity, $price)";
if ($conn->query($sql)) {
    echo "✅ Product added successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>

