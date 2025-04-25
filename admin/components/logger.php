<?php
function log_action($conn, $admin_id, $action, $target_type, $target_id = null, $details = null) {
    $target_id = is_null($target_id) ? 0 : $target_id;
    $details = is_null($details) ? "" : $details;

    $stmt = $conn->prepare("INSERT INTO logs (admin_id, admin_action, target_type, target_id, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $admin_id, $action, $target_type, $target_id, $details);
    $stmt->execute();
    $stmt->close();
}
?>