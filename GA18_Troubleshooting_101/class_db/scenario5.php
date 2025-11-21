<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_POST['email']) && !empty($_POST['email'])) {

    $email = $_POST['email']; // corrected spelling

    $sql = "SELECT * FROM students WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    echo "Query executed successfully.";
} else {
    echo "Please enter an email.";
}
