<?php
include '../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');

    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Email is required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT email FROM users1 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Account not found."]);
    } else {
        echo json_encode(["status" => "success"]);
    }

    $stmt->close();
    $conn->close();
}
?>
