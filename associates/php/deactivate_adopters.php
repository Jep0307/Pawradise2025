<?php
include '../../config/db.php';
session_start();

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $conn->prepare("DELETE FROM users1 WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
        session_destroy();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Delete failed"]);
    }

} else {
    echo json_encode(["error" => "No email provided"]);
}

$conn->close();
?>
