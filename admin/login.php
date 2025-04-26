<?php
include "./components/db_connect.php";
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // $session_id = session_id();
        // $now = date('Y-m-d H:i:s');
        // $check = $conn->prepare("SELECT session_id FROM admin_sessions WHERE admin_email = ?");
        // $check->bind_param("s", $email);
        // $check->execute();
        // $existing = $check->get_result()->fetch_assoc();

        // if ($existing && $existing['session_id'] !== $session_id) {
        //     echo "<script>alert('You are already logged in on another device.'); window.location='login.php';</script>";
        //     exit();
        // }

        $admin_id = $user['id'];

        $update = $conn->prepare("REPLACE INTO admin_sessions (admin_id, admin_email, session_id, last_activity) VALUES (?, ?, ?, ?)");
        $update->bind_param("isss", $admin_id, $email, $session_id, $now);
        $update->execute();

        $_SESSION['admin_email'] = $email;
        $_SESSION['LAST_ACTIVITY'] = time();

        header("Location: ./tabs/dashboard.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}

if (isset($_SESSION['admin_email'])) {
    header("Location: ./tabs/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <section class="login-section">
        <div class="login-container">
            <div class="left-panel">
                <img src="images/pawradise-logo.png" alt="" />
                <h1>Pawradise</h1>
                <form action="login.php" method="POST" class="login-form">
                    <input type="text" name="email" placeholder="Email" />
                    <input type="password" name="password" placeholder="Password" />
                    <a href="#" class="forgot-pass">Forgot Password</a>
                    <button class="submit-btn">Login</button>
                </form>
            </div>
            <div class="right-panel">
                <img src="images/login-cover.png" alt="" />
            </div>
        </div>
    </section>
</body>

</html>