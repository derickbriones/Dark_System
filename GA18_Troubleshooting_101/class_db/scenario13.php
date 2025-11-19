<?php
$newEmail = $_POST['email'];

#Without a WHERE clause, the update would affect ALL rows
$sql = "UPDATE students SET email='$newEmail' WHERE student_id=$id";
mysqli_query($conn,$sql);
?>
