<?php
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors

require '../../config/db.php'; // Include the database connection

// Get the application ID from the URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id) {
    // Prepare and execute the query to fetch the application data
    $stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
    $stmt->bind_param("i", $id); // Bind the ID to the prepared statement

    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();

    if ($application) {
        echo json_encode($application); // Return the application data as JSON
    } else {
        echo json_encode(['error' => 'Application not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid application ID']);
}
?>
