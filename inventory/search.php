<?php
// Include database connection
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$search = "";
$search_result = [];

// Handle search functionality
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search_query = "SELECT * FROM inventory WHERE item_name LIKE '%$search%'";
    $search_result = mysqli_query($conn, $search_query);

    // Debugging search query and result
    if ($search_result === false) {
        echo "Error: " . mysqli_error($conn); // Show error if query fails
    }
}

// Handle delete functionality
if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];
    $delete_query = "DELETE FROM inventory WHERE id = $item_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Item deleted successfully!'); window.location.href = 'search_delete.php';</script>";
    } else {
        echo "<script>alert('Error deleting item.');</script>";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Delete Inventory</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styles here... (same as previous example) */
    </style>
</head>
<body>

    <div class="container">
        <h1>Search and Delete Inventory Items</h1>

        <!-- Search Form -->
        <form method="POST" action="search_delete.php">
            <input type="text" name="search" placeholder="Search for an item..." value="<?php echo htmlspecialchars($search); ?>" required>
            <button type="submit">Search</button>
        </form>

        <!-- Display Search Results -->
        <?php if ($search_result && mysqli_num_rows($search_result) > 0): ?>
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($search_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <!-- Delete Link -->
                                <a href="search_delete.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No results found for "<?php echo htmlspecialchars($search); ?>"</p>
        <?php endif; ?>

    </div>

</body>
</html>

