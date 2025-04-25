<?php
include '../components/db_connect.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

$staff_id = $_GET['staff_id'];
$sql="SELECT * FROM staffs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$staff_result = $stmt->get_result();
$user = $staff_result->fetch_assoc();
$stmt->close();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $fullname = sanitize($_POST['fullname']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $role = sanitize($_POST['role']);
    $shelter = sanitize($_POST['shelter']);
    $address = sanitize($_POST['address']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{7,15}$/', $phone)) {
        $errors[] = "Phone number should be numeric and 7–15 digits.";
    }

    if (empty($fullname) || empty($email) || empty($phone) || empty($role) || empty($shelter) || empty($address)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (count($errors) === 0) {
        if (!empty($_FILES["photo"]["tmp_name"])) {
            $staff_img = file_get_contents($_FILES["photo"]["tmp_name"]);
        } else {
            $staff_img = $user['staff_img'];
        }

        $stmt = $conn->prepare("UPDATE staffs SET fullname = ?, email = ?, phone = ?, role = ?, shelter = ?, address = ?, staff_img = ? WHERE id = ?");
        $stmt->bind_param(
            "sssssssi",
            $fullname, $email, $phone, $role, $shelter, $address, $staff_img, $staff_id
        );
        if ($stmt->execute()) {
            $admin_id = $_SESSION['admin_id'];
            log_action($conn, $admin_id, "Edited staff profile", "staff", $staff_id, "Username: $fullname");
            displayPopup("User updated successfully.");
        } else {
            displayPopup("Database error: " . $stmt->error, 'error');
        }

        $stmt->close();

        $sql = "SELECT * FROM staffs WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $staff_result = $stmt->get_result();
        $user = $staff_result->fetch_assoc();
        $stmt->close();

    } else {
        foreach ($errors as $error) {
            displayPopup("$error", 'error');
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

        <form class="user-mgmt-container" method="POST" action="edit-staff.php?staff_id=<?php echo $staff_id; ?>"
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
                <div class="user-mgmt-left-panel">
                    <img src="<?php echo empty($user['staff_img']) 
                    ? '../images/default.png' 
                    : 'data:image/png;base64,' . base64_encode($user['staff_img']); ?>" alt=""
                        class="user-profile-photo" id="preview-image">

                    <input type="file" name="photo" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Change Photo</button>
                </div>

                <div class="right-panel">
                    <h3>User Info</h3>
                    <div class="input-division">
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>"
                            placeholder="Full Name" required />
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                            placeholder="Email" required />
                        <input type="text" name="role" value="<?php echo htmlspecialchars($user['role']); ?>"
                            placeholder="Role" required />
                        <input type="number" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                            placeholder="Phone Number" required />
                        <input type="text" name="shelter" value="<?php echo htmlspecialchars($user['shelter']); ?>"
                            placeholder="Shelter" required />
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