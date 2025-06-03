<?php
include('includes/db.php');

$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = trim($_POST["category_name"]);
    if (!empty($categoryName)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);
        if ($stmt->execute()) {
            $success = "Category added successfully!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- optional: your CSS -->
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #007bff;
        }
        .form-box, .list-box {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .form-box input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .btn i {
            margin-right: 6px;
        }
        .list-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }
        .success-popup {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            margin-bottom: 15px;
            display: <?php echo $success ? 'block' : 'none'; ?>;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .back-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-tags"></i> Manage Categories</h2>

    <?php if (!empty($success)): ?>
        <div class="success-popup"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <input type="text" name="category_name" placeholder="Category Name" required>
            <button class="btn" type="submit"><i class="fas fa-plus-circle"></i> Add Category</button>
        </form>
    </div>

    <div class="list-box">
        <h3><i class="fas fa-list"></i> Category List</h3>
        <div class="list">
            <?php
            $query = "SELECT * FROM categories ORDER BY id DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='list-item'>" . htmlspecialchars($row['name']) . "</div>";
                }
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>
    </div>

    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>
</body>
</html>

