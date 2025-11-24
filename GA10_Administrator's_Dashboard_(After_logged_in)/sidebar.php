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
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">grid_view</span>
            <h3>Dashboard</h3>
        </a>
        <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">inventory</span>
            <h3>Products</h3>
        </a>
        <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">receipt_long</span>
            <h3>Orders</h3>
        </a>
        <a href="order_summary.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'order_summary.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">summarize</span>
            <h3>Order Summary</h3>
        </a>
        <a href="order_history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'order_history.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">history</span>
            <h3>Order History</h3>
        </a>
        <a href="invoice.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'invoice.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">receipt</span>
            <h3>Invoices</h3>
        </a>
        <a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">assessment</span>
            <h3>Reports</h3>
        </a>
        <a href="add_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>">
            <span class="material-icons-sharp">add</span>
            <h3>Add Product</h3>
        </a>
        <a href="logout.php">
            <span class="material-icons-sharp">logout</span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>