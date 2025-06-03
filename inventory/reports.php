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

// Filters
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';
$productFilter = $_GET['product'] ?? '';

$where = [];
$params = [];
$types = '';

if (!empty($startDate) && !empty($endDate)) {
    $where[] = "sale_date BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $types .= "ss";
}

if (!empty($productFilter)) {
    $where[] = "product_name LIKE ?";
    $params[] = "%" . $productFilter . "%";
    $types .= "s";
}

$query = "SELECT product_name, quantity_sold, total_price, sale_date FROM sales";
if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$query .= " ORDER BY sale_date DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// For chart data
$chartQuery = "SELECT sale_date, SUM(total_price) as total FROM sales GROUP BY sale_date ORDER BY sale_date ASC";
$chartResult = $conn->query($chartQuery);
$chartData = [];
while ($row = $chartResult->fetch_assoc()) {
    $chartData[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial;
            background: #f1f3f6;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 25px;
            max-width: 1100px;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        form {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        input[type="date"], input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background: #007bff;
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
            background: #007bff;
            color: white;
            padding: 10px;
        }
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .actions {
            margin-top: 15px;
            text-align: right;
        }
        .actions a, .actions button {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            margin-left: 10px;
        }
        canvas {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sales Report</h1>

    <form method="GET">
        <label>Start Date:</label>
        <input type="date" name="start" value="<?= htmlspecialchars($startDate) ?>">
        <label>End Date:</label>
        <input type="date" name="end" value="<?= htmlspecialchars($endDate) ?>">
        <label>Product:</label>
        <input type="text" name="product" placeholder="Search product..." value="<?= htmlspecialchars($productFilter) ?>">
        <input type="submit" value="Filter">
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty Sold</th>
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
        <p>No data available for the selected filters.</p>
    <?php endif; ?>

    <div class="actions">
        <button onclick="window.print()">🖨️ Print</button>
        <a href="index.php">← Back to Dashboard</a>
    </div>

    <canvas id="salesChart" height="100"></canvas>
</div>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($chartData, 'sale_date')) ?>,
        datasets: [{
            label: 'Total Sales ($)',
            data: <?= json_encode(array_column($chartData, 'total')) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Sales Trend Over Time'
            }
        }
    }
});
</script>

</body>
</html>

