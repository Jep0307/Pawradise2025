<?php
include '../components/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $address = $_POST['address'] ?? '';
    $contactno = $_POST['contactno'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullname && $address && $contactno && $email && $password) {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "Email already exists.";
            $checkStmt->close();
            $conn->close();
            exit;
        }
        $checkStmt->close();

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (fullname, address, contactno, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $address, $contactno, $email, $passwordHash);

        if ($stmt->execute()) {
            echo "✅ Signup successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: All fields are required.";
    }
}

$conn->close();
?>
