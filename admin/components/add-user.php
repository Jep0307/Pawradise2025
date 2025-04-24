<?php
include '../components/db_connect.php';
include 'popup.php';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

$success = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = sanitize($_POST['fullname']);
    $username = sanitize($_POST['username']);
    $bio = sanitize($_POST['bio']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{7,15}$/', $phone)) {
        $errors[] = "Phone number should be numeric and 7–15 digits.";
    }

    if (empty($fullname) || empty($username) || empty($email) || empty($phone)) {
        $errors[] = "Please fill in all required fields.";
    }

    // Only process and display popup on success or error
    if (count($errors) === 0) {
        $user_img = null;

        if (!empty($_FILES["photo"]["tmp_name"])) {
            $user_img = file_get_contents($_FILES["photo"]["tmp_name"]);
        } else {
            $user_img = file_get_contents("../images/default.png");
        }
        
        $stmt = $conn->prepare("INSERT INTO users_table (fullname, username, bio, email, phone, address, user_img) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullname, $username, $bio, $email, $phone, $address, $user_img);
        $stmt->send_long_data(6, $user_img);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: add-user.php?success=1");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
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
    <?php 
    if ($success = true) {
        displayPopup("User added successfully.");
    } else {
        displayPopup("Error adding user", type: 'error');
    }
    ?>

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
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn">
                        <span class="material-symbols-outlined">add</span> Add User</button>
                </div>
            </div>

            <div class="user-mgmt-bottom-panel">
                <div class="user-mgmt-left-panel">
                    <img src="../images/default.png" alt="" class="user-profile-photo" id="preview-image">
                    <input type="file" name="photo" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Add Photo</button>
                </div>

                <div class="right-panel">
                    <h3>User Info</h3>
                    <div class="input-division">
                        <input type="text" name="fullname" placeholder="Full Name" required />
                        <input type="text" name="username" placeholder="Username" required />
                        <input type="text" name="bio" placeholder="Bio" />
                        <input type="email" name="email" placeholder="Email" required />
                        <input type="number" name="phone" placeholder="Phone Number" required />
                        <input type="text" name="address" placeholder="Address" />
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

    document.getElementById('photo-input').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('preview-image').src = URL.createObjectURL(file);
        }
    });
    </script>
</body>

</html>