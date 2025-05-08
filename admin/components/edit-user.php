<?php
include '../../config/db.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

if (!isset($_GET['id'])) {
    die("No user ID provided.");
}
$id = (int) $_GET['id'];

$sql = "SELECT * FROM users1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $fullname = sanitize($_POST['fullname']);
    $email = sanitize($_POST['email']);
    $contactno = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{7,15}$/', $contactno)) {
        $errors[] = "Phone number should be numeric and 7–15 digits.";
    }

    if (empty($fullname) || empty($email) || empty($contactno)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (count($errors) === 0) {
        $stmt = $conn->prepare("UPDATE users1 SET fullname = ?, email = ?, contactno = ?, address = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fullname, $email, $contactno, $address, $id);

        if ($stmt->execute()) {
            $admin_id = $_SESSION['admin_id'];
            log_action($conn, $admin_id, "Edited user profile", "users", $id, "Username: $fullname");
            displayPopup("User updated successfully.");
        } else {
            displayPopup("Database error: " . $stmt->error, 'error');
        }

        $stmt->close();

        $sql = "SELECT * FROM users1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            displayPopup($error, 'error');
        }
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit User</title>
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
                <p>Admin > <span>Edit User</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="user-mgmt-container" method="POST" action="edit-user.php?id=<?php echo $id; ?>"
            enctype="multipart/form-data">
            <div class="user-mgmt-top-panel">
                <div class="header">
                    <h1>Edit User</h1>
                    <p class="subtitle">
                        Update user information and photo.
                    </p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn">
                        <span class="material-symbols-outlined">edit</span> Save Changes</button>
                </div>
            </div>

            <div class="user-mgmt-bottom-panel">
                <!-- <div class="user-mgmt-left-panel">
                    <img src="data:image/png;base64,<?php echo base64_encode($user['user_img']); ?>" alt=""
                        class="user-profile-photo" id="preview-image">
                    <input type="file" name="photo" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Change Photo</button>
                </div> -->

                <div class="right-panel">
                    <h3>User Info</h3>
                    <div class="input-division">
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>"
                            placeholder="Full Name" required />
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                            placeholder="Email" required />
                        <input type="number" name="phone" value="<?php echo htmlspecialchars($user['contactno']); ?>"
                            placeholder="Phone Number" required />
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"
                            placeholder="Address" />
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