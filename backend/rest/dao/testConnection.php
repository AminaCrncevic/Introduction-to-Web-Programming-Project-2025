<?php
require_once 'config.php';  // Include the database configuration

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test the database connection
$conn = Database::connect();
if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>
