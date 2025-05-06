<?php
require './db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid ID"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else {
    $query = "SELECT * FROM pets";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $pets = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($pets);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>
