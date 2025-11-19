<?php
$id = $_GET['id'];

#ID is a number, so it should not be inside quotes
$sql = "SELECT * FROM students WHERE id = $id";
?>
