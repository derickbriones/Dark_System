<?php
include 'config.php';
check_auth();

// Fetch statistics
try {
  // Total Sales
  $total_sales_query = "SELECT COALESCE(SUM(FinalAmount), 0) as total_sales FROM orders WHERE Status = 'Delivered'";
  $total_sales_result = $conn->query($total_sales_query);
  $total_sales = $total_sales_result->fetch_assoc()['total_sales'] ?? 0;

  // Total Orders
  $total_orders_query = "SELECT COUNT(*) as total_orders FROM orders";
  $total_orders_result = $conn->query($total_orders_query);
  $total_orders = $total_orders_result->fetch_assoc()['total_orders'] ?? 0;

  // Total Products
  $total_products_query = "SELECT COUNT(*) as total_products FROM product WHERE IsActive = TRUE";
  $total_products_result = $conn->query($total_products_query);
  $total_products = $total_products_result->fetch_assoc()['total_products'] ?? 0;

  // Total Customers
  $total_customers_query = "SELECT COUNT(*) as total_customers FROM user";
  $total_customers_result = $conn->query($total_customers_query);
  $total_customers = $total_customers_result->fetch_assoc()['total_customers'] ?? 0;

  // Calculate percentages
  $sales_percentage = 65;
  $orders_percentage = 42;
  $products_percentage = 28;

  // Fetch recent orders
  $recent_orders_query = "
        SELECT o.OrderID, u.Name as CustomerName, o.OrderDate, o.Status, o.FinalAmount as Amount
        FROM orders o 
        JOIN user u ON o.UserID = u.UserID 
        ORDER BY o.OrderDate DESC 
        LIMIT 5
    ";
  $recent_orders_result = $conn->query($recent_orders_query);

  // Recent updates
  $updates_query = "
        SELECT DISTINCT u.Name, o.OrderDate, p.ProductName
        FROM orders o 
        JOIN user u ON o.UserID = u.UserID 
        JOIN orderdetails od ON o.OrderID = od.OrderID
        JOIN product p ON od.ProductID = p.ProductID
        WHERE o.Status = 'Delivered' 
        ORDER BY o.OrderDate DESC 
        LIMIT 3
    ";
  $updates_result = $conn->query($updates_query);
} catch (Exception $e) {
  error_log("Dashboard query error: " . $e->getMessage());
  $error = "Unable to load dashboard data.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  <link rel="icon" type="image/png" href="images/icon.png" />
  <link rel="stylesheet" href="style.css" />
  <title>Dashboard - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Dashboard</h1>
      <div class="date">
        <input type="date" value="<?php echo date('Y-m-d'); ?>" />
      </div>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="insights">
        <!-- Total Sales -->
        <div class="sales">
          <span class="material-icons-sharp">analytics</span>
          <div class="middle">
            <div class="left">
              <h3>Total Sales</h3>
              <h1><?php echo format_currency($total_sales); ?></h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36" class="progress-circle"></circle>
                <circle cx="38" cy="38" r="36"
                  style="stroke-dashoffset: calc(283px - (283px * <?php echo $sales_percentage; ?>) / 100)"
                  class="progress-circle-fill sales-fill"></circle>
              </svg>
              <div class="number">
                <p><?php echo $sales_percentage; ?>%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 Days</small>
        </div>

        <!-- Total Orders -->
        <div class="expenses">
          <span class="material-icons-sharp">bar_chart</span>
          <div class="middle">
            <div class="left">
              <h3>Total Orders</h3>
              <h1><?php echo $total_orders; ?></h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36" class="progress-circle"></circle>
                <circle cx="38" cy="38" r="36"
                  style="stroke-dashoffset: calc(283px - (283px * <?php echo $orders_percentage; ?>) / 100)"
                  class="progress-circle-fill expenses-fill"></circle>
              </svg>
              <div class="number">
                <p><?php echo $orders_percentage; ?>%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 Days</small>
        </div>

        <!-- Total Products -->
        <div class="income">
          <span class="material-icons-sharp">inventory</span>
          <div class="middle">
            <div class="left">
              <h3>Total Products</h3>
              <h1><?php echo $total_products; ?></h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36" class="progress-circle"></circle>
                <circle cx="38" cy="38" r="36"
                  style="stroke-dashoffset: calc(283px - (283px * <?php echo $products_percentage; ?>) / 100)"
                  class="progress-circle-fill income-fill"></circle>
              </svg>
              <div class="number">
                <p><?php echo $products_percentage; ?>%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Active Products</small>
        </div>
      </div>

      <div class="recent-orders">
        <h2>Recent Orders</h2>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Order Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($recent_orders_result && $recent_orders_result->num_rows > 0): ?>
              <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                <tr>
                  <td>#<?php echo $order['OrderID']; ?></td>
                  <td><?php echo htmlspecialchars($order['CustomerName']); ?></td>
                  <td><?php echo format_currency($order['Amount']); ?></td>
                  <td><?php echo date('M j, Y', strtotime($order['OrderDate'])); ?></td>
                  <td><?php echo get_status_badge($order['Status']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align: center;">No recent orders</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
        <a href="orders.php">Show All</a>
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

      <div class="recent-updates">
        <h2>Recent Updates</h2>
        <div class="updates">
          <?php if ($updates_result && $updates_result->num_rows > 0): ?>
            <?php while ($update = $updates_result->fetch_assoc()): ?>
              <div class="update">
                <div class="profile-photo">
                  <img src="images/profile.jpg" alt="Customer" />
                </div>
                <div class="message">
                  <p><b><?php echo htmlspecialchars($update['Name']); ?></b> purchased <?php echo htmlspecialchars($update['ProductName']); ?></p>
                  <small class="text-muted"><?php echo date('M j, Y', strtotime($update['OrderDate'])); ?></small>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="update">
              <div class="profile-photo">
                <img src="images/profile.jpg" alt="Default" />
              </div>
              <div class="message">
                <p><b>No recent updates</b></p>
                <small class="text-muted">Check back later for new orders</small>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="sales-analytics">
        <h2>Quick Stats</h2>
        <div class="item online">
          <div class="icon">
            <span class="material-icons-sharp">shopping_cart</span>
          </div>
          <div class="right">
            <div class="info">
              <h3>TOTAL CUSTOMERS</h3>
              <small class="text-muted">Registered Users</small>
            </div>
            <h3><?php echo $total_customers; ?></h3>
          </div>
        </div>
        <div class="item offline">
          <div class="icon">
            <span class="material-icons-sharp">local_mall</span>
          </div>
          <div class="right">
            <div class="info">
              <h3>LOW STOCK</h3>
              <small class="text-muted">Products < 10</small>
            </div>
            <h3>
              <?php
              $low_stock_query = "SELECT COUNT(*) as low_stock FROM product WHERE StockQuantity < 10 AND IsActive = TRUE";
              $low_stock_result = $conn->query($low_stock_query);
              echo $low_stock_result->fetch_assoc()['low_stock'] ?? 0;
              ?>
            </h3>
          </div>
        </div>
        <div class="item customers">
          <div class="icon">
            <span class="material-icons-sharp">payments</span>
          </div>
          <div class="right">
            <div class="info">
              <h3>PENDING ORDERS</h3>
              <small class="text-muted">Awaiting processing</small>
            </div>
            <h3>
              <?php
              $pending_query = "SELECT COUNT(*) as pending FROM orders WHERE Status = 'Pending'";
              $pending_result = $conn->query($pending_query);
              echo $pending_result->fetch_assoc()['pending'] ?? 0;
              ?>
            </h3>
          </div>
        </div>
        <div class="item add-product" onclick="window.location.href='add_product.php'">
          <div>
            <span class="material-icons-sharp">add</span>
            <h3>Add Product</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>