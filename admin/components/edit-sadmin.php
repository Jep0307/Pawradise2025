<?php
include '../../config/db.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

$sadmin_id = $_GET['sadmin_id'] ?? null;
if (!$sadmin_id) {
    displayPopup("No staff-admin ID provided.", 'error');
    exit;
}

// Only fetch users with role = 'staff-admin'
$stmt = $conn->prepare("SELECT * FROM staffs WHERE id = ? AND role = 'staff-admin'");
$stmt->bind_param("i", $sadmin_id);
$stmt->execute();
$result = $stmt->get_result();
$staffadmin = $result->fetch_assoc();
$stmt->close();

if (!$staffadmin) {
    displayPopup("Staff admin not found or not a staff-admin.", 'error');
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $role = sanitize($_POST['role']); // can be 'staff' or 'staff-admin'
    $shelter_id = intval($_POST['shelter_id']);
    $address = sanitize($_POST['address']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{7,15}$/', $phone)) {
        $errors[] = "Phone number should be numeric and 7–15 digits.";
    }

    if (empty($name) || empty($email) || empty($phone) || empty($role) || empty($shelter_id) || empty($address)) {
        $errors[] = "Please fill in all required fields.";
    }

    // Allow either 'staff' or 'staff-admin' roles
    if (!in_array($role, ['staff', 'staff-admin'])) {
        $errors[] = "Invalid role. Please select a valid role.";
    }

    if (count($errors) === 0) {
        $stmt = $conn->prepare("UPDATE staffs SET name = ?, email = ?, phone = ?, role = ?, shelter_id = ?, address = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $email, $phone, $role, $shelter_id, $address, $sadmin_id);

        if ($stmt->execute()) {
            $admin_id = $_SESSION['admin_id'];
            log_action($conn, $admin_id, "Edited staff-admin profile", "staffs", $sadmin_id, "Name: $name");
        
            displayPopup("Staff admin updated successfully.");
            header("Location: ../tabs/staff-admins-management.php");
            exit;
        } else {
            displayPopup("Database error: " . $stmt->error, 'error');
        }

        $stmt->close();

        // Re-fetch updated staff-admin
        $stmt = $conn->prepare("SELECT * FROM staffs WHERE id = ? AND role = 'staff-admin'");
        $stmt->bind_param("i", $sadmin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $staffadmin = $result->fetch_assoc();
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
    <title>Edit Staff Admin</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <section class="user-mgmt-section" style="margin-left: 85px;">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>Edit Staff Admin</span></p>
            </div>
            <div class="right">
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <form class="user-mgmt-container" method="POST" action="edit-sadmin.php?sadmin_id=<?php echo $sadmin_id; ?>">
            <div class="user-mgmt-top-panel">
                <div class="header">
                    <h1>Edit Staff Admin</h1>
                    <p class="subtitle">Update staff admin information.</p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn" type="submit">Save Changes</button>
                </div>
            </div>

            <div class="user-mgmt-bottom-panel">
                <div class="right-panel">
                    <h3>User Info</h3>
                    <div class="input-division">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($staffadmin['name']); ?>"
                            placeholder="Full Name" required />

                        <input type="email" name="email" value="<?php echo htmlspecialchars($staffadmin['email']); ?>"
                            placeholder="Email" required />

                        <select name="role" required>
                            <option value="" disabled
                                <?php if ($staffadmin['role'] !== 'staff' && $staffadmin['role'] !== 'staff-admin') echo 'selected'; ?>>
                                Select a role</option>
                            <option value="staff" <?php if ($staffadmin['role'] === 'staff') echo 'selected'; ?>>Staff
                            </option>
                            <option value="staff-admin"
                                <?php if ($staffadmin['role'] === 'staff-admin') echo 'selected'; ?>>Staff Admin
                            </option>
                        </select>

                        <input type="number" name="phone" value="<?php echo htmlspecialchars($staffadmin['phone']); ?>"
                            placeholder="Phone Number" required />

                        <select name="shelter_id" required>
                            <option value="" disabled <?php if (!$staffadmin['shelter_id']) echo 'selected'; ?>>Select a
                                shelter</option>
                            <option value="1" <?php if ($staffadmin['shelter_id'] == 1) echo 'selected'; ?>>PAWS Animal
                                Rehabilitation Center</option>
                            <option value="2" <?php if ($staffadmin['shelter_id'] == 2) echo 'selected'; ?>>Quezon City
                                Animal Care and Adoption Center</option>
                        </select>

                        <input type="text" name="address" value="<?php echo $staffadmin['address']; ?>"
                            placeholder="Address" required />
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script src="../script.js"></script>
    <script>
    function closePopup() {
        const popup = document.querySelector(".popup-overlay");
        if (popup) popup.classList.remove("show");
    }
    window.addEventListener("load", () => {
        const popup = document.querySelector(".popup-overlay");
        if (popup) setTimeout(closePopup, 3000);
    });
    </script>
</body>

</html>