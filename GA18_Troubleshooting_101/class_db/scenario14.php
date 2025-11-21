<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (
    isset($_POST['first_name'], $_POST['last_name'], $_POST['email']) &&
    !empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email'])
) {

    $first = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "INSERT INTO students (first_name, last_name, email) 
            VALUES ('$first', '$last', '$email')";

    if (mysqli_query($conn, $sql)) {
        echo "Student inserted successfully!";
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }
} else {
    echo "Please fill in all fields.";
}
