<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fabulous_finds');

// Create connection
try {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
  }

  $conn->set_charset("utf8mb4");
} catch (Exception $e) {
  die("Database connection error. Please check your database setup.");
}

// Include helper functions
require_once 'includes/functions.php';
require_once 'includes/auth.php';
