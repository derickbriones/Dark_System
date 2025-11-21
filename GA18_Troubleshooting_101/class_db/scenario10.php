<?php
if (isset($_POST['age']) && !empty($_POST['age'])) {

    $age = $_POST['age'];

    $sql = "SELECT * FROM students WHERE age = $age";

    // Example execution (optional)
    $conn = mysqli_connect("localhost", "root", "", "class_db");
    $res = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($res)) {
        echo $row['first_name'] . " " . $row['last_name'] . "<br>";
    }
} else {
    echo "Please provide an age.";
}
