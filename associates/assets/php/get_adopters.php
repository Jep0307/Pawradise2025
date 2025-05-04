<?php
include '../../../admin/components/db_connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT fullname, email, contactno, address FROM users1 WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}

$conn->close();
?>
