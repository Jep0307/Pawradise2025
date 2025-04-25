<?php
ini_set('session.cookie_secure', '1'); // Ensures cookies are sent only over HTTPS
ini_set('session.cookie_httponly', '1'); // Prevents JavaScript from accessing session cookies
ini_set('session.cookie_samesite', 'Strict'); // Restricts cookie sending to same-site requests
session_start();

include 'db_connect.php';

define('SESSION_TIMEOUT', 1800); // 30 minutes

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

$session_id = session_id();
$email = $_SESSION['admin_email'];

$stmt = $conn->prepare("SELECT session_id FROM admin_sessions WHERE admin_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['session_id'] !== $session_id) {
        session_unset();
        session_destroy();
        echo "<script>alert('Session invalid. You may have logged in from another device.'); window.location='../login.php';</script>";
        exit();
    }
} else {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}

$admin_stmt = $conn->prepare("SELECT id, name, admin_img FROM admins WHERE email = ?");
$admin_stmt->bind_param("s", $email);
$admin_stmt->execute();
$admin = $admin_stmt->get_result()->fetch_assoc();

if ($admin) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_img'] = $admin['admin_img'];
}
?>