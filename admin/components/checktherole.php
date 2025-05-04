<?php
include '../components/db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');

    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Email is required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Account not found."]);
    } else {
        $stmt->bind_result($role);
        $stmt->fetch();

        if (empty($role)) {
            echo json_encode(["status" => "error", "message" => "No role assigned to this account."]);
        } else {
            echo json_encode(["status" => "success", "role" => $role]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
