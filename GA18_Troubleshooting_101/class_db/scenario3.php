<?php
$conn = mysqli_connect("localhost","root","","class_db");

#We put prepared statement to protect the sql injection
#If we put user input directly in sql is not safe
$stmt = $conn->prepare("SELECT * FROM students WHERE age = ?");
$stmt->bind_param("i", $age);
$stmt->execute();
?>
