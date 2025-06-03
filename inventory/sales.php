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

// Add Sale
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total = $quantity * $price;
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO sales (product_name, quantity_sold, total_price, sale_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $product, $quantity, $total, $date);
    $stmt->execute();
}

// Fetch Sales
$result = $conn->query("SELECT * FROM sales ORDER BY sale_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Sales</title>
    <style>
        body {
            font-family: Arial;
            background: #f1f3f6;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #28a745;
        }
        form {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th {
            background: #28a745;
            color: white;
            padding: 10px;
        }
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .actions {
            margin-top: 20px;
            text-align: right;
        }
        .actions a {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Sales</h1>

    <form method="POST">
        <input type="text" name="product" placeholder="Product Name" required>
        <input type="number" name="quantity" placeholder="Quantity Sold" required>
        <input type="number" name="price" step="0.01" placeholder="Unit Price" required>
        <input type="submit" value="Add Sale">
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity_sold'] ?></td>
                    <td>$<?= number_format($row['total_price'], 2) ?></td>
                    <td><?= $row['sale_date'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No sales recorded yet.</p>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
