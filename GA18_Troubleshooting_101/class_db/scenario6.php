<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = intval($_GET['id']); // Secured input (only numbers allowed)

    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    echo "Student record deleted.";
} else {
    echo "No valid ID provided.";
}
