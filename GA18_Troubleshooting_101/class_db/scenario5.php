<?php
$conn = mysqli_connect("localhost","root","","class_db");

# The POST key was misspelled before, so we fix it to 'email'
$email = $_POST['email'];

$sql = "SELECT * FROM students WHERE email='$email'";
$res = mysqli_query($conn, $sql);
?>
