<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

// Fix: Use $_GET instead of $_POST to read value from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM students WHERE id = $id";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $r = mysqli_fetch_assoc($res);
        echo $r['first_name'];
    } else {
        echo "No student found.";
    }
} else {
    echo "No ID provided in URL.";
}
