<?php
session_start();

// 15-minute timeout
$timeout_duration = 9000;

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    include '../components/db_connect.php';
    $email = $_SESSION['admin_email'];
    $stmt = $conn->prepare("DELETE FROM admin_sessions WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

include '../components/db_connect.php';
$result = $conn->query("SELECT * FROM staffs_table");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Staffs Management</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="staffs-management-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>STAFFS MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Staffs Management</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <a href="../components/add-pet.html" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Staff</a>
                    <a href="../components/pending-staffs.html" class="pending-btn"><span
                            class="material-symbols-outlined">schedule</span>Pending</a>
                </div>
            </div>

            <div class="search-input">
                <input type="text" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>

            <div class="management-bottom-panel">
                <table class="general-table">
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>ROLE</th>
                        <th>SHELTER</th>
                        <th>LOCATION</th>
                        <th>CREATED</th>
                        <th></th>
                    </tr>
                    <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['shelter']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td class="options-btn">
                            <span class="material-symbols-outlined">edit</span>
                            <div class="pop-up">
                                <a href="edit-staff.php?id=<?= $row['id'] ?>"><span
                                        class="material-symbols-outlined">edit</span>Edit</a>
                                <a href="delete-staff.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Are you sure you want to delete this staff?')">
                                    <span class="material-symbols-outlined">delete</span>Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">No staff records found.</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </section>
</body>

</html>