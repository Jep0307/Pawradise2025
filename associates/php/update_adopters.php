<?php
include '../../admin/components/db_connect.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE users1 SET fullname=?, contactno=?, address=? WHERE id=?");
$stmt->bind_param("sssi", $data['fullname'], $data['number'], $data['location'], $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Update failed"]);
}

$conn->close();
?>
