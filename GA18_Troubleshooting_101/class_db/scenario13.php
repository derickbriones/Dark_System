<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_POST['id']) && isset($_POST['email']) && !empty($_POST['email'])) {

    $id = intval($_POST['id']); // ensure numeric
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']); // sanitize input

    $sql = "UPDATE students SET email='$newEmail' WHERE student_id=$id";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "Student email updated successfully!";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
} else {
    echo "Please provide both ID and email.";
}
