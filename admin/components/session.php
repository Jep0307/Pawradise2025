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

$email = $_SESSION['admin_email'];

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