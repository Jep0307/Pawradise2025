<?php
include '../../config/db.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT id, fullname, address, contactno, email, password, created_at FROM users1");

$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);

$conn->close();
?>
