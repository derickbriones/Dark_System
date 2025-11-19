<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

# Change the POST to GET 
#We need to change it because we are sending data through URL
#And we use GET method to retrieve data from URL
$id = $_GET['id'];

$sql = "SELECT * FROM students WHERE id = $id";
$res = mysqli_query($conn, $sql);
$r = mysqli_fetch_assoc($res);

echo $r['first_name'];
?>
