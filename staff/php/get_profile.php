<?php
require './db.php'; // adjust path if needed

// For now, assume the user id is 1 (hardcoded temporarily)
// Later we will replace this with $_SESSION['user_id']
$userId = 1;

// Fetch the profile data
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user exists, return it. Else, return an empty object
if ($user) {
    echo json_encode($user);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>
