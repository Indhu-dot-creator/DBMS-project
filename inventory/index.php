<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .menu {
            max-width: 500px;
            margin: 30px auto;
        }
        .menu a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .blue { background: #007bff; }
        .yellow { background: #f1c40f; }
        .cyan { background: #17a2b8; }
        .green { background: #28a745; }
        .purple { background: #6f42c1; }
        .brown { background: #795548; }
        .orange { background: #fd7e14; }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Inventory Management System</h1>

<div class="menu">
    <a href="products.php" class="blue">📦 Manage Products</a>
    <a href="categories.php" class="yellow">📚 Manage Categories</a>
    
    

    
    <a href="purchase_orders.php" class="brown">📄 Purchase Orders</a>
    <a href="sales.php" class="orange">🛒 Manage Sales</a>
    <a href="reports.php" class="green">📈 View Reports</a>
</div>

<div class="logout">
    <p>Welcome, <?= $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
</div>

</body>
</html>

