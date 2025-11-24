<?php
include 'config.php';
check_auth();

// Handle delete with CSRF protection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
  if (!verify_csrf_token($_POST['csrf_token'])) {
    die("Security violation detected.");
  }

  $delete_id = intval($_POST['delete_id']);

  try {
    // Check if product has orders
    $check_orders = $conn->prepare("SELECT COUNT(*) as order_count FROM orderdetails WHERE ProductID = ?");
    $check_orders->bind_param("i", $delete_id);
    $check_orders->execute();
    $result = $check_orders->get_result();
    $order_count = $result->fetch_assoc()['order_count'];

    if ($order_count > 0) {
      $_SESSION['error'] = "Cannot delete product with existing orders.";
    } else {
      $delete_query = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
      $delete_query->bind_param("i", $delete_id);
      if ($delete_query->execute()) {
        $_SESSION['success'] = "Product deleted successfully.";
      } else {
        $_SESSION['error'] = "Error deleting product.";
      }
    }
  } catch (Exception $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
  }

  header("Location: products.php");
  exit();
}

// Fetch products with pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$where = '';
if (!empty($search)) {
  $where = "WHERE p.ProductName LIKE ? OR p.Category LIKE ?";
}

$products_query = "
    SELECT p.*, s.Name as SellerName,
           (SELECT COALESCE(SUM(od.Quantity), 0) FROM orderdetails od WHERE od.ProductID = p.ProductID) as TotalSold
    FROM product p 
    LEFT JOIN seller s ON p.SellerID = s.SellerID 
    {$where}
    ORDER BY p.ProductID DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($products_query);
if (!empty($search)) {
  $search_param = "%{$search}%";
  $stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
} else {
  $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$products_result = $stmt->get_result();

// Count total products for pagination
$count_query = "SELECT COUNT(*) as total FROM product p";
if (!empty($search)) {
  $count_query .= " WHERE p.ProductName LIKE ? OR p.Category LIKE ?";
}
$count_stmt = $conn->prepare($count_query);
if (!empty($search)) {
  $search_param = "%{$search}%";
  $count_stmt->bind_param("ss", $search_param, $search_param);
}
$count_stmt->execute();
$total_products = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_products / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Products - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Products Management</h1>

      <!-- Messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success'];
                                          unset($_SESSION['success']); ?></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <!-- Search Form -->
      <div class="search-box">
        <form method="GET" action="">
          <div class="form-group" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if (!empty($search)): ?>
              <a href="products.php" class="btn btn-danger">Clear</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <div class="recent-orders">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
          <h2>All Products</h2>
          <a href="add_product.php" class="btn btn-primary">Add New Product</a>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Product Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Sold</th>
              <th>Seller</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($products_result->num_rows > 0): ?>
              <?php while ($product = $products_result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $product['ProductID']; ?></td>
                  <td><?php echo htmlspecialchars($product['ProductName']); ?></td>
                  <td><?php echo htmlspecialchars($product['Category']); ?></td>
                  <td><?php echo format_currency($product['Price']); ?></td>
                  <td class="<?php echo $product['StockQuantity'] < 10 ? 'danger' : 'success'; ?>">
                    <?php echo $product['StockQuantity']; ?>
                  </td>
                  <td><?php echo $product['TotalSold']; ?></td>
                  <td><?php echo htmlspecialchars($product['SellerName'] ?? 'N/A'); ?></td>
                  <td class="action-buttons">
                    <a href="edit_product.php?id=<?php echo $product['ProductID']; ?>" class="btn btn-primary">Edit</a>
                    <form method="POST" action="" style="display: inline;">
                      <input type="hidden" name="delete_id" value="<?php echo $product['ProductID']; ?>">
                      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                      <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align: center;">No products found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                class="<?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
      </div>
    </main>

    <!-- Right Section -->
    <div class="right">
      <div class="top">
        <button id="menu-btn">
          <span class="primary material-icons-sharp">menu</span>
        </button>
        <div class="theme-toggler">
          <span class="material-icons-sharp active">light_mode</span>
          <span class="material-icons-sharp">dark_mode</span>
        </div>
        <div class="profile">
          <div class="info">
            <p>Hey, <b>Admin</b></p>
            <small class="text-muted">Administrator</small>
          </div>
          <div class="profile-photo">
            <img src="images/profile.jpg" alt="Admin Profile" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>