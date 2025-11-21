<?php
$conn = mysqli_connect("localhost", "root", "", "class_db");

if (isset($_GET['age'])) {

    $age = $_GET['age'];

    // Fix: Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM students WHERE age = ?");
    $stmt->bind_param("i", $age);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['first_name'] . "<br>";
        }
    } else {
        echo "No students found with that age.";
    }
} else {
    echo "No age provided in URL.";
}
