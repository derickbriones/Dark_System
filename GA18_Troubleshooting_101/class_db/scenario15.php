<?php
# Get the page number from the URL
$page = $_GET['page'];

# Convert it to a number (prevents text or symbols)
$page = intval($page);

# Prevent negative page numbers
if ($page < 0) {
    $page = 0;
}

# Simple pagination math
$limit = 5;
$offset = $page * $limit;

# Final SQL query
$sql = "SELECT * FROM students LIMIT $offset, $limit";
?>
