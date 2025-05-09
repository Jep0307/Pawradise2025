<?php
include '../../config/db.php';
session_start();

// Check if email is provided via GET
if (!isset($_GET['email'])) {
    echo json_encode(['error' => 'Email is required']);
    exit;
}

$email = $_GET['email'];

// Prepare the SQL query to fetch user data by email
$sql = "SELECT fullname, email, contactno, address FROM users1 WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Query preparation failed
    echo json_encode(['error' => 'Failed to prepare the query']);
    exit;
}

$stmt->bind_param("s", $email); // Bind email as a string parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch and return the user data
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    // User not found
    echo json_encode(['error' => 'User not found']);
}

$conn->close();
?>
