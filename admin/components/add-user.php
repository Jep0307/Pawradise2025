<?php
include '../../config/db.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';

function sanitize($data)
{
    return trim($data); // htmlspecialchars removed — not needed for DB input
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $email = sanitize($_POST['email'] ?? '');
    $name = sanitize($_POST['name'] ?? '');
    $password = sanitize($_POST['password'] ?? '');
    $role = sanitize($_POST['role'] ?? '');
    $shelter = sanitize($_POST['shelter'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($email) || empty($name) || empty($password) || empty($role) || empty($shelter)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (count($errors) === 0) {
        // Remove password hashing for plain text password storage
        $plainPassword = $password;

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }

        $checkStmt->close();

        $stmt = $conn->prepare("INSERT INTO admins (email, name, password, role, shelter) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $name, $plainPassword, $role, $shelter);

        if ($stmt->execute()) {
            $newUserId = $stmt->insert_id;
            $admin_id = $_SESSION['admin_id'];
            log_action($conn, $admin_id, "Added new user", "user", $newUserId, "Email: $email");
            displayPopup("User added successfully.");
        } else {
            displayPopup("Database error: " . $stmt->error, 'error');
        }

        $stmt->close();
        $conn->close();
    } else {
        foreach ($errors as $error) {
            displayPopup($error, 'error');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add User</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="user-mgmt-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>ADD USER</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="user-mgmt-container" method="POST" action="add-user.php" enctype="multipart/form-data">
            <div class="user-mgmt-top-panel">
                <div class="header">
                    <h1>Add User</h1>
                    <p class="subtitle">
                        Add a user. Fill all the required details
                    </p>
                </div>
                <div class="top-buttons">
                    <button type="submit" class="add-btn">
                        <span class="material-symbols-outlined">add</span> Add User
                    </button>
                </div>
            </div>

            <div class="user-mgmt-bottom-panel">
                <!-- <div class="user-mgmt-left-panel">
                    <img src="../images/default.png" alt="" class="user-profile-photo" id="preview-image">
                    <input type="file" name="photo" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Add Photo</button>
                </div> -->

                <div class="right-panel">
                    <h3>User Info</h3>
                    <div class="input-division">
                        <!-- <input type="text" name="fullname" placeholder="Full Name" required /> -->
                        <input type="email" name="email" placeholder="Email" required />
                        <input type="text" name="name" placeholder="Name" required />
                        <input type="password" name="password" placeholder="Password" required />
                        <input type="text" name="role" placeholder="Role" required />
                        <input type="text" name="shelter" placeholder="Shelter" required />
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script src="../script.js"></script>
    <script>
        function closePopup() {
            const popup = document.querySelector(".popup-overlay");
            if (popup) {
                popup.classList.remove("show");
            }
        }

        window.addEventListener("load", function() {
            const popup = document.querySelector(".popup-overlay");
            if (popup) {
                setTimeout(() => {
                    closePopup();
                }, 3000);
            }
        });

        // document.getElementById('photo-input').addEventListener('change', function(event) {
        //     const [file] = event.target.files;
        //     if (file) {
        //         document.getElementById('preview-image').src = URL.createObjectURL(file);
        //     }
        // });
    </script>
</body>

</html>
