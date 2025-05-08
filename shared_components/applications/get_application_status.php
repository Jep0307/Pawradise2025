<?php
require '../../config/db.php'; // Include the database connection
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if (!$email) {
    echo json_encode(['message' => 'Email is required.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, created_at, status FROM applications WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];

while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

if (empty($applications)) {
    echo json_encode(['message' => 'No applications found for this email.']);
} else {
    echo json_encode($applications);
}

$stmt->close();
$conn->close();
?>
