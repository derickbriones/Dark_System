<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_POST['id']) && isset($_POST['email']) && !empty($_POST['email'])) {

    $id = intval($_POST['id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE students SET email='$email' WHERE id=$id";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "Updated successfully!";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
} else {
    echo "Please provide both ID and email.";
}
