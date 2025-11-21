<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_GET['page'])) {
    $page = intval($_GET['page']); // convert to integer
    if ($page < 0) $page = 0;       // restrict negative pages
} else {
    $page = 0; // default page
}

$limit = 5;
$offset = $page * $limit;

// Safe SQL query using validated integer values
$sql = "SELECT * FROM students LIMIT $offset, $limit";

$res = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($res)) {
    echo $row['first_name'] . " " . $row['last_name'] . "<br>";
}
