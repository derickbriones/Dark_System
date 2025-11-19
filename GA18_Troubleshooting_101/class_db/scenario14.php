<?php
$data = $_POST;

#Array keys must be written correctly inside the string
#String values must also be placed inside quotes in SQL
$sql = "INSERT INTO students (first_name, last_name, email)
    VALUES ('{$data['first_name']}', '{$data['last_name']}', '{$data['email']}')";
?>