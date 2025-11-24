<?php
include 'config.php';
check_auth();

// Fetch orders for invoice generation
$invoices_query = "
    SELECT DISTINCT o.OrderID, u.Name as CustomerName, u.Email, u.Address,
           s.Name as SellerName, s.Email as SellerEmail,
           o.FinalAmount as Amount, o.OrderDate, o.Status
    FROM orders o
    JOIN user u ON o.UserID = u.UserID
    JOIN seller s ON o.SellerID = s.SellerID
    ORDER BY o.OrderDate DESC
    LIMIT 5
";
$invoices_result = $conn->query($invoices_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Invoices - Fabulous Finds</title>
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main>
      <h1>Invoice & Receipt Management</h1>

      <?php while ($invoice = $invoices_result->fetch_assoc()): ?>
        <div class="invoice-container">
          <div class="invoice-header">
            <div>
              <h2>FABULOUS FINDS</h2>
              <p>123 Business Street<br>City, State 12345<br>Phone: (555) 123-4567</p>
            </div>
            <div style="text-align: right;">
              <h2>INVOICE</h2>
              <p>Invoice #: FF<?php echo str_pad($invoice['OrderID'], 6, '0', STR_PAD_LEFT); ?></p>
              <p>Date: <?php echo date('M j, Y', strtotime($invoice['OrderDate'])); ?></p>
            </div>
          </div>

          <div class="invoice-details">
            <div>
              <h3>Bill To:</h3>
              <p><strong><?php echo $invoice['CustomerName']; ?></strong><br>
                <?php echo $invoice['Email']; ?><br>
                <?php echo $invoice['Address']; ?></p>
            </div>
            <div>
              <h3>From:</h3>
              <p><strong><?php echo $invoice['SellerName']; ?></strong><br>
                <?php echo $invoice['SellerEmail']; ?></p>
            </div>
          </div>

          <div class="invoice-items">
            <table>
              <thead>
                <tr>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Unit Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $items_query = "
                  SELECT p.ProductName, od.Quantity, od.UnitPrice, od.TotalPrice
                  FROM orderdetails od
                  JOIN product p ON od.ProductID = p.ProductID
                  WHERE od.OrderID = ?
                ";
                $stmt = $conn->prepare($items_query);
                $stmt->bind_param("i", $invoice['OrderID']);
                $stmt->execute();
                $items_result = $stmt->get_result();

                while ($item = $items_result->fetch_assoc()):
                ?>
                  <tr>
                    <td><?php echo $item['ProductName']; ?></td>
                    <td><?php echo $item['Quantity']; ?></td>
                    <td>$<?php echo number_format($item['UnitPrice'], 2); ?></td>
                    <td>$<?php echo number_format($item['TotalPrice'], 2); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div class="invoice-total">
            <p>Total: $<?php echo number_format($invoice['Amount'] ?? 0, 2); ?></p>
            <p>Status: <?php echo get_status_badge($invoice['Status']); ?></p>
          </div>

          <button class="print-btn" onclick="window.print()">Print Invoice</button>
        </div>
      <?php endwhile; ?>
    </main>
  </div>
  <script src="script.js"></script>
</body>

</html>
<?php $conn->close(); ?>