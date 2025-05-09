<?php
include '../../config/db.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT id, email, name, password, created_at, role FROM admins");

$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);

$conn->close();
?>
