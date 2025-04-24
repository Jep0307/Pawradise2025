<?php
include '../components/db_connect.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $conn->prepare("DELETE FROM users_table WHERE id = ?");
    if ($stmt === false) {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $stmt->bind_param("i", $user_id);
    
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