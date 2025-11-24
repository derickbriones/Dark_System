<?php
include 'config.php';
check_auth();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $product_name = $_POST['product_name'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $seller_id = $_POST['seller_id'];

  $insert_query = "INSERT INTO product (ProductName, Description, Category, Price, StockQuantity, SellerID) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("sssdii", $product_name, $description, $category, $price, $stock, $seller_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Product added successfully!";
    header("Location: products.php");
    exit();
  } else {
    $error = "Error adding product: " . $conn->error;
  }
}

// Fetch sellers for dropdown
$sellers_query = "SELECT SellerID, Name FROM seller";
$sellers_result = $conn->query($sellers_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Add Product - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Add New Product</h1>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="form-container">
        <form method="POST" action="">
          <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" required>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" required>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>
          </div>
          <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" required>
          </div>
          <div class="form-group">
            <label for="seller_id">Seller</label>
            <select id="seller_id" name="seller_id" required>
              <option value="">Select Seller</option>
              <?php while ($seller = $sellers_result->fetch_assoc()): ?>
                <option value="<?php echo $seller['SellerID']; ?>"><?php echo $seller['Name']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Product</button>
          <a href="products.php" class="btn btn-danger">Cancel</a>
        </form>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>