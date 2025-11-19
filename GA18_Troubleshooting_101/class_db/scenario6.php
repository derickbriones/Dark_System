<?php
$conn = mysqli_connect("localhost","root","","class_db");

#We convert it into integer to prevent harmful input like ?id=0 OR 1=1.
$id = intval($_GET['id']);
$sql = "DELETE FROM students WHERE id = $id";
mysqli_query($conn, $sql);
?>