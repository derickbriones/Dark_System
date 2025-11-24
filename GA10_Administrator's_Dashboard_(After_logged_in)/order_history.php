<?php
include 'config.php';
check_auth();

// Fetch complete order history
$history_query = "
    SELECT o.OrderID, u.Name as CustomerName, s.Name as SellerName, 
           p.ProductName, od.Quantity, o.FinalAmount as Amount, o.OrderDate, o.Status,
           py.PaymentMethod, py.PaymentDate
    FROM orders o
    JOIN user u ON o.UserID = u.UserID
    JOIN seller s ON o.SellerID = s.SellerID
    JOIN orderdetails od ON o.OrderID = od.OrderID
    JOIN product p ON od.ProductID = p.ProductID
    LEFT JOIN payment py ON o.OrderID = py.OrderID
    ORDER BY o.OrderDate DESC
";
$history_result = $conn->query($history_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Order History - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Order History</h1>
      <div class="recent-orders">
        <h2>Complete Order History</h2>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Amount</th>
              <th>Order Date</th>
              <th>Payment Method</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($order = $history_result->fetch_assoc()): ?>
              <tr>
                <td>#<?php echo $order['OrderID']; ?></td>
                <td><?php echo $order['CustomerName']; ?></td>
                <td><?php echo $order['ProductName']; ?></td>
                <td><?php echo $order['Quantity']; ?></td>
                <td>$<?php echo number_format($order['Amount'] ?? 0, 2); ?></td>
                <td><?php echo date('M j, Y H:i', strtotime($order['OrderDate'])); ?></td>
                <td><?php echo $order['PaymentMethod'] ?? 'N/A'; ?></td>
                <td><?php echo get_status_badge($order['Status']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>