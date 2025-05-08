<?php
include '../../config/db.php';
include '../components/logger.php';
include '../components/session.php';

if (!isset($_GET['id'])) {
    header("Location: ../tabs/shelters-management.php");
    exit();
}

$shelter_id = intval($_GET['id']);

// Delete the shelter
$stmt = $conn->prepare("DELETE FROM shelters WHERE id = ?");
$stmt->bind_param("i", $shelter_id);

if ($stmt->execute()) {
    logActivity($conn, $_SESSION['admin_id'], "Deleted a shelter (ID: $shelter_id)");
    header("Location: ../tabs/shelters-management.php?delete_success=1");
    exit();
} else {
    logActivity($conn, $_SESSION['admin_id'], "Failed to delete shelter (ID: $shelter_id)");
    header("Location: ../tabs/shelters-management.php?delete_error=1");
    exit();
}
?>