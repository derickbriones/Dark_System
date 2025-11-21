<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "Student ID: " . $id;
} else {
    echo "No ID received.";
}
?>

<a href="view.php?id=3">View Student</a>