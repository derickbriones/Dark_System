<?php
include 'config.php';
check_auth();

// Fetch orders
$orders_query = "
    SELECT o.OrderID, u.Name as CustomerName, s.Name as SellerName,
           o.OrderDate, o.Status, o.FinalAmount as TotalAmount,
           (SELECT COUNT(*) FROM orderdetails od WHERE od.OrderID = o.OrderID) as ItemCount
    FROM orders o
    JOIN user u ON o.UserID = u.UserID
    JOIN seller s ON o.SellerID = s.SellerID
    ORDER BY o.OrderDate DESC
";
$orders_result = $conn->query($orders_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Orders - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Orders Management</h1>
      <div class="recent-orders">
        <h2>All Orders</h2>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Seller</th>
              <th>Items</th>
              <th>Total Amount</th>
              <th>Order Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($orders_result->num_rows > 0): ?>
              <?php while ($order = $orders_result->fetch_assoc()): ?>
                <tr>
                  <td>#<?php echo $order['OrderID']; ?></td>
                  <td><?php echo htmlspecialchars($order['CustomerName']); ?></td>
                  <td><?php echo htmlspecialchars($order['SellerName']); ?></td>
                  <td><?php echo $order['ItemCount']; ?> items</td>
                  <td><?php echo format_currency($order['TotalAmount']); ?></td>
                  <td><?php echo date('M j, Y H:i', strtotime($order['OrderDate'])); ?></td>
                  <td><?php echo get_status_badge($order['Status']); ?></td>
                  <td>
                    <a href="order_details.php?id=<?php echo $order['OrderID']; ?>" class="btn btn-primary">View Details</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align: center;">No orders found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>