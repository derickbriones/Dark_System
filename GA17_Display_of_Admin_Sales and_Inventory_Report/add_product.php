<?php
include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $product_name = $_POST['product_name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $seller_id = $_POST['seller_id'];

  // Handle image upload (optional)
  $image_name = NULL;
  if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['product_image']['tmp_name'];
    $fileName = $_FILES['product_image']['name'];
    $fileSize = $_FILES['product_image']['size'];
    $fileType = $_FILES['product_image']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Allowed extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($fileExtension, $allowedExtensions)) {
      $uploadDir = __DIR__ . '/images/products';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
      }
      // generate unique name
      $newFileName = uniqid('prod_', true) . '.' . $fileExtension;
      $destPath = $uploadDir . '/' . $newFileName;

      if (move_uploaded_file($fileTmpPath, $destPath)) {
        $image_name = 'images/products/' . $newFileName;
      } else {
        $error = "There was an error moving the uploaded file.";
      }
    } else {
      $error = "Upload failed. Allowed file types: " . implode(", ", $allowedExtensions);
    }
  }

  if (!isset($error)) {
    $insert_query = "INSERT INTO product (ProductName, Category, Price, StockQuantity, SellerID, Image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    // types: s = string, s = string, d = double, i = int, i = int, s = string
    $stmt->bind_param("ssdiis", $product_name, $category, $price, $stock, $seller_id, $image_name);

    if ($stmt->execute()) {
      header("Location: products.php");
      exit();
    } else {
      $error = "Error adding product: " . $conn->error;
    }
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
    <aside>
      <div class="top">
        <div class="logo">
          <img src="images/icon.png" alt="Logo" class="site-logo" />
          <h2>FABULOUS <span class="primary">FINDS</span></h2>
        </div>
        <div class="close" id="close-btn">
          <span class="material-icons-sharp">close</span>
        </div>
      </div>
      <div class="sidebar">
        <a href="index.php">
          <span class="material-icons-sharp">grid_view</span>
          <h3>Dashboard</h3>
        </a>
        <a href="products.php">
          <span class="material-icons-sharp">inventory</span>
          <h3>Products</h3>
        </a>
        <a href="orders.php">
          <span class="material-icons-sharp">receipt_long</span>
          <h3>Orders</h3>
        </a>
        <a href="order_summary.php">
          <span class="material-icons-sharp">summarize</span>
          <h3>Order Summary</h3>
        </a>
        <a href="order_history.php">
          <span class="material-icons-sharp">history</span>
          <h3>Order History</h3>
        </a>
        <a href="invoice.php">
          <span class="material-icons-sharp">receipt</span>
          <h3>Invoice/Receipt</h3>
        </a>
        <a href="reports.php">
          <span class="material-icons-sharp">assessment</span>
          <h3>Reports</h3>
        </a>
        <a href="add_product.php" class="active">
          <span class="material-icons-sharp">add</span>
          <h3>Add Product</h3>
        </a>
        <a href="#">
          <span class="material-icons-sharp">logout</span>
          <h3>Logout</h3>
        </a>
      </div>
    </aside>
    <main>
      <h1>Add New Product</h1>
      <?php if (isset($error)): ?>
        <div style="color: var(--color-danger); margin-bottom: 1rem;"><?php echo $error; ?></div>
      <?php endif; ?>
      <div class="form-container">
        <form method="POST" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" required>
          </div>
          <div class="form-group">
            <label for="product_image">Image</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">
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
        </form>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>