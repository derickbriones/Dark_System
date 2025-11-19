<?php
$conn = mysqli_connect("localhost","root","","class_db");

$res = mysqli_query($conn,"SELECT * FROM students");

#We use while loop to fetch all user instead
#Of just one user
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['email'] . "<br>";
}

?>
