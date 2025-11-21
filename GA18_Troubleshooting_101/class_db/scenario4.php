<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_POST['fname']) && isset($_POST['lname']) && !empty($_POST['fname']) && !empty($_POST['lname'])) {

    $first = $_POST['fname'];
    $last = $_POST['lname'];

    $sql = "INSERT INTO students (first_name, last_name) VALUES ('$first', '$last')";
    mysqli_query($conn, $sql);

    echo "Student successfully inserted!";
} else {
    echo "Please fill in both first name and last name.";
}
