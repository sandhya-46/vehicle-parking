<?php
/**
 * BACKEND FILE — Database Connection
 * Runs only on server. User never sees this file.
 */

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "parking_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set timezone (optional)
date_default_timezone_set('Asia/Kolkata');
?>