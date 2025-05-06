<?php
include '../../admin/components/db_connect.php';
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM users1 WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
    session_destroy();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Delete failed"]);
}

$conn->close();
?>
