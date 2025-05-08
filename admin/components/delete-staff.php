<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_id'])) {
    $staff_id = intval($_POST['staff_id']);
    $stmt = $conn->prepare("DELETE FROM staffs WHERE id = ?");
    if ($stmt === false) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $stmt->bind_param("i", $staff_id);
    
    if ($stmt->execute()) {
        header("Location: ../tabs/staff-accounts-management.php?deleted=1");
    } else {
        header("Location: ../tabs/staff-accounts-management.php?error=1");
    }    

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../tabs/staff-accounts-management.php");
    exit();
}
?>