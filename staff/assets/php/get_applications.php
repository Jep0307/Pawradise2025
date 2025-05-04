<?php
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors

require 'db.php'; // Include the database connection

// Fetch all applications from the database
$query = "SELECT * FROM applications";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // If applications are found, return them as a JSON array
    $applications = [];
    while ($application = $result->fetch_assoc()) {
        $applications[] = $application;
    }
    echo json_encode($applications); // Return the applications data as JSON
} else {
    echo json_encode(['error' => 'No applications found']);
}
?>
