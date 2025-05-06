<?php
require '../../admin/components/db_connect.php';


// Get the pet ID from the query string
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users1 WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete user."]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "User ID not provided."]);
}
?>
