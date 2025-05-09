<?php
session_start();
include '../config/db.php';

if (isset($_SESSION['admin_email'])) {
    $email = $_SESSION['admin_email'];
    $stmt = $conn->prepare("DELETE FROM admin_sessions WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

session_unset();
session_destroy();

header("Location: ./login.php");
exit();
?>