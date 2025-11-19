<?php
$conn = mysqli_connect("localhost","root","","class_db");

#We check if the fields are not empty before inserting
# This prevents empty data from being saved
if (!empty($_POST['fname']) && !empty($_POST['lname'])) {
    $first = $_POST['fname'];
    $last = $_POST['lname'];

    # Only insert when both have values
    $sql = "INSERT INTO students (first_name, last_name) VALUES ('$first', '$last')";
    mysqli_query($conn, $sql);

    echo "Inserted!";
} else {
    # If one or both fields are empty
    echo "Please fill out both first and last name.";
}
?>
