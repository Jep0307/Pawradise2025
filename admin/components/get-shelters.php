<?php
header('Content-Type: application/json');
include '../../config/db.php';

$sql = "SELECT * FROM shelters";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$shelters = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $shelters[] = $row;
    }
}

echo json_encode($shelters);
$conn->close();
?>