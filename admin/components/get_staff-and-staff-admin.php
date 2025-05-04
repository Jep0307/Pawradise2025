<?php
include '../../components/db_connect.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT id, email, password, created_at, role FROM users");

$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);

$conn->close();
?>
