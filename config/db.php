<?php
// Database configuration
$host = 'localhost';  // Your host (usually localhost)
$user = 'root';       // Your database username
$pass = '';           // Your database password (default is an empty string on local setup)
$dbname = 'pawradisedb';   // Your database name (update this)

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
