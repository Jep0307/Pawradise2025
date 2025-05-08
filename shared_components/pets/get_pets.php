<?php
require '../../config/db.php'; // Include the database connection

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $searchWildcard = '%' . $conn->real_escape_string($search) . '%';
    $stmt = $conn->prepare("SELECT * FROM pets WHERE name LIKE ? OR description LIKE ? OR location LIKE ? OR type LIKE ?");
    $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
} else {
    $stmt = $conn->prepare("SELECT * FROM pets ORDER BY id DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}

echo json_encode($pets);
$conn->close();
?>
