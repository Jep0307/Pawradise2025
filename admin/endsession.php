<?php
include "./components/db_connect.php";

$email = "admin@gmail.com";

$stmt = $conn->prepare("DELETE FROM admin_sessions WHERE admin_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Session deleted.";
?>
