<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="text-primary"><i class="fas fa-box"></i> Manage Products</h3>

  <!-- Form to add product -->
  <form method="POST" class="bg-white p-4 rounded shadow-sm mt-4">
    <input type="text" name="name" class="form-control mb-2" placeholder="Product Name" required>
    <input type="text" name="category" class="form-control mb-2" placeholder="Category" required>
    <input type="number" name="quantity" class="form-control mb-2" placeholder="Quantity" required>
    <input type="number" step="0.01" name="price" class="form-control mb-3" placeholder="Price" required>
    <button type="submit" name="add_product" class="btn btn-success w-100">
      <i class="fas fa-plus"></i> Add
    </button>
  </form>

  <!-- Success Message -->
  <?php
  if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $query = "INSERT INTO products (name, category, quantity, price) VALUES ('$name', '$category', '$quantity', '$price')";
    if (mysqli_query($conn, $query)) {
      echo "<script>alert('✅ Product added successfully'); window.location.href='products.php';</script>";
    } else {
      echo "<div class='alert alert-danger mt-2'>Error adding product: " . mysqli_error($conn) . "</div>";
    }
  }
  ?>

  <!-- Product List -->
  <div class="bg-white p-4 mt-4 rounded shadow-sm">
    <h5 class="text-secondary"><i class="fas fa-list-ul"></i> Product List</h5>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
    if (mysqli_num_rows($result) > 0) {
      echo "<table class='table table-striped mt-3'>
              <thead>
                <tr>
                  <th>ID</th><th>Name</th><th>Category</th><th>Qty</th><th>Price</th>
                </tr>
              </thead>
              <tbody>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['category']}</td>
                <td>{$row['quantity']}</td>
                <td>₹{$row['price']}</td>
              </tr>";
      }
      echo "</tbody></table>";
    } else {
      echo "<p class='text-muted mt-3'>No products found.</p>";
    }
    ?>
  </div>

  <!-- Back button -->
  <a href="index.php" class="btn btn-dark mt-4"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>
</html>

