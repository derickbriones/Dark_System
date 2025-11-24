<?php
include 'config.php';
check_auth();

// Sales report data
$sales_report_query = "
    SELECT 
        DATE(o.OrderDate) as order_date,
        COUNT(*) as order_count,
        SUM(o.FinalAmount) as daily_revenue,
        AVG(o.FinalAmount) as avg_order_value
    FROM orders o
    WHERE o.OrderDate >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(o.OrderDate)
    ORDER BY order_date DESC
";
$sales_report_result = $conn->query($sales_report_query);

// Inventory report
$inventory_report_query = "
    SELECT 
        p.ProductName,
        p.Category,
        p.Price,
        p.StockQuantity,
        s.Name as SellerName,
        COUNT(od.ProductID) as times_ordered
    FROM product p
    LEFT JOIN seller s ON p.SellerID = s.SellerID
    LEFT JOIN orderdetails od ON p.ProductID = od.ProductID
    GROUP BY p.ProductID
    ORDER BY p.StockQuantity ASC
";
$inventory_report_result = $conn->query($inventory_report_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Reports - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Sales & Inventory Reports</h1>

      <div class="recent-orders">
        <h2>Sales Report (Last 7 Days)</h2>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Orders</th>
              <th>Revenue</th>
              <th>Avg Order Value</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($report = $sales_report_result->fetch_assoc()): ?>
              <tr>
                <td><?php echo date('M j, Y', strtotime($report['order_date'])); ?></td>
                <td><?php echo $report['order_count']; ?></td>
                <td>$<?php echo number_format($report['daily_revenue'] ?? 0, 2); ?></td>
                <td>$<?php echo number_format($report['avg_order_value'] ?? 0, 2); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <div class="recent-orders" style="margin-top: 2rem;">
        <h2>Inventory Report</h2>
        <table>
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Times Ordered</th>
              <th>Seller</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($inventory = $inventory_report_result->fetch_assoc()): ?>
              <tr>
                <td><?php echo $inventory['ProductName']; ?></td>
                <td><?php echo $inventory['Category']; ?></td>
                <td>$<?php echo number_format($inventory['Price'], 2); ?></td>
                <td class="<?php echo $inventory['StockQuantity'] < 10 ? 'danger' : 'success'; ?>">
                  <?php echo $inventory['StockQuantity']; ?>
                </td>
                <td><?php echo $inventory['times_ordered']; ?></td>
                <td><?php echo $inventory['SellerName']; ?></td>
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