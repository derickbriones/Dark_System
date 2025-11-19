<?php
$conn = mysqli_connect("localhost","root","","class_db");

$id = $_POST['id'];
$email = $_POST['email'];

#The email value must be inside quotes because it is a string
#We also add error checking so the script does not say “Updated!” if the query fails
$sql = "UPDATE students SET email='$email' WHERE id=$id";
if (!$res = mysqli_query($conn, $sql)) {
    echo "Error updating!";
}
?>
