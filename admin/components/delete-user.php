<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM users1 WHERE id = ?");
    if ($stmt === false) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../tabs/user-accounts-management.php?deleted=1");
    } else {
        header("Location: ../tabs/user-accounts-management.php?error=1");
    }    

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../tabs/user-accounts-management.php");
    exit();
}
?>