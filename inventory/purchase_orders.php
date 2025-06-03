<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: users.php");
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM purchase_orders ORDER BY order_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase Orders</title>
    <style>
        body {
            font-family: Arial;
            background: #f7f9fc;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            max-width: 1000px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .back-btn {
            margin-top: 20px;
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Purchase Orders</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Supplier</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Total Cost</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= htmlspecialchars($row['item']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>$<?= number_format($row['total_cost'], 2) ?></td>
                    <td><?= $row['order_date'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No purchase orders found.</p>
    <?php endif; ?>

    <a href="index.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>
