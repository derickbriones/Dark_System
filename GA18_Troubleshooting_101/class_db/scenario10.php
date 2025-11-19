<?php
$age = $_POST['age'];

#The original code misspelled the variable as 'aeg'
#We fixed it to use the correct variable name 'age'
$sql = "SELECT * FROM students WHERE age = $age";
name
?>