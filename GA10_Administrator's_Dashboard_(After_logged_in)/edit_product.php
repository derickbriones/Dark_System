<?php
include 'config.php';
check_auth();

if (!isset($_GET['id'])) {
  header("Location: products.php");
  exit();
}

$product_id = $_GET['id'];

// Fetch product details
$product_query = "SELECT * FROM product WHERE ProductID = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

if (!$product) {
  header("Location: products.php");
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $product_name = $_POST['product_name'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $seller_id = $_POST['seller_id'];

  $update_query = "UPDATE product SET ProductName = ?, Description = ?, Category = ?, Price = ?, StockQuantity = ?, SellerID = ? WHERE ProductID = ?";
  $stmt = $conn->prepare($update_query);
  $stmt->bind_param("sssdiii", $product_name, $description, $category, $price, $stock, $seller_id, $product_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Product updated successfully!";
    header("Location: products.php");
    exit();
  } else {
    $error = "Error updating product: " . $conn->error;
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
  <title>Edit Product - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Edit Product</h1>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="form-container">
        <form method="POST" action="">
          <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['ProductName']); ?>" required>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($product['Description'] ?? ''); ?></textarea>
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['Category']); ?>" required>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['Price']; ?>" required>
          </div>
          <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" value="<?php echo $product['StockQuantity']; ?>" required>
          </div>
          <div class="form-group">
            <label for="seller_id">Seller</label>
            <select id="seller_id" name="seller_id" required>
              <option value="">Select Seller</option>
              <?php while ($seller = $sellers_result->fetch_assoc()): ?>
                <option value="<?php echo $seller['SellerID']; ?>" <?php echo $seller['SellerID'] == $product['SellerID'] ? 'selected' : ''; ?>>
                  <?php echo $seller['Name']; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Update Product</button>
          <a href="products.php" class="btn btn-danger">Cancel</a>
        </form>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>