<?php
if (isset($_GET['email']) && !empty($_GET['email'])) {
    $email = $_GET['email'];
    echo "Email submitted: " . htmlspecialchars($email);
} else {
    echo "No email provided.";
}
