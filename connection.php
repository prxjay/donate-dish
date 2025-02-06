<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Only start a session if there isn't one active already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MySQL Database Configuration
$host     = "127.0.0.1";  // Using IP instead of 'localhost' often avoids socket issues
$user     = "root";
$password = "";           // Default in XAMPP is empty
$database = "sample";     // Change to your actual database name
$port     = 4307;         // Change if your MySQL runs on a custom port (3306 is default)

// Attempt to connect to the database
try {
    $connection = new mysqli($host, $user, $password, $database, $port);
    // Set character encoding
    $connection->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Output error and stop script execution
    die("❌ Connection failed: " . $e->getMessage());
}
?>
