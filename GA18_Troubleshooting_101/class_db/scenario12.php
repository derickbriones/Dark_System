<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = intval($_GET['id']); // ensure it is numeric

    $sql = "SELECT * FROM students WHERE id = $id";

    // Example execution
    $conn = mysqli_connect("localhost", "root", "", "class_db");
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        echo "Student: " . $row['first_name'] . " " . $row['last_name'];
    } else {
        echo "No student found with this ID.";
    }
} else {
    echo "No ID provided.";
}
