<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_POST['fname'])) {
    $fname = $_POST['fname'];

    // Fix: Add quotes to treat the input as a string and prevent SQL errors
    $sql = "SELECT * FROM students WHERE first_name = '$fname'";

    $res = mysqli_query($conn, $sql);
} else {
    echo "No name provided.";
}
