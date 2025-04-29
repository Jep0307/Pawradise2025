<?php
require 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $searchWildcard = '%' . $conn->real_escape_string($search) . '%';
    $stmt = $conn->prepare("SELECT * FROM pets WHERE name LIKE ? OR description LIKE ? OR location LIKE ?");
    $stmt->bind_param("sss", $searchWildcard, $searchWildcard, $searchWildcard);
    
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
