<?php
include '../../config/db.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email']; // Get the email from the request payload

$stmt = $conn->prepare("UPDATE users1 SET fullname=?, contactno=?, address=? WHERE email=?");
$stmt->bind_param("ssss", $data['fullname'], $data['number'], $data['location'], $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Update failed"]);
}

$conn->close();
?>
