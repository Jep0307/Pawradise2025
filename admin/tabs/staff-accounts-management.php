<?php
include '../components/db_connect.php';
include '../components/session.php';
include '../components/popup.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM staffs");

if (!$result) {
    die("Database query failed: " . $conn->error);
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    displayPopup("Staff deleted successfully.");
} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
    displayPopup("Something went wrong. Please try again.", 'error');
}

$staffs_table = [];
while ($row = $result->fetch_assoc()) {
    $staffs_table[] = $row;
}

$conn->close();
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
                        Manage staff roles, staff modifications, and pending applications
                    </p>
                </div>
                <div class="top-buttons">
                    <a href="../components/pending-staffs.html" class="pending-btn"><span
                            class="material-symbols-outlined">schedule</span>Pending</a>
                    <a href="../components/add-staff.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Entry
                    </a>

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
                        <th>IMAGE</th>
                        <th>FULLNAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>ROLE</th>
                        <th>SHELTER</th>
                        <th>ADDRESS</th>
                        <th>CREATED</th>
                        <th></th>
                    </tr>

                    <?php
                    if (count($staffs_table) > 0):
                        foreach($staffs_table as $row):
                        $imageSrc = !empty($row['staff_img']) ? 'data:image/jpeg;base64,' . base64_encode($row['staff_img']) : '../images/default.png';
                        
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td><img src='{$imageSrc}' alt='User Image' width='50' /></td>";
                        echo "<td>".$row['fullname']."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['phone']."</td>";
                        echo "<td>".$row['role']."</td>";
                        echo "<td>".$row['shelter']."</td>";
                        echo "<td class='text-overflow'>".$row['address']."</td>";
                        echo "<td>".$row['created_at']."</td>";
                        echo "<td class='options-btn'>
                                <span class='material-symbols-outlined'>edit</span>
                                <div class='pop-up'>
                                    <a href='../components/edit-staff.php?staff_id={$row['id']}'><span class='material-symbols-outlined'>edit</span>Edit</a>
                                    <form action='../components/delete-staff.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                                        <input type='hidden' name='staff_id' value='{$row['id']}'>
                                        <button type='submit' class='delete-btn'>
                                            <span class='material-symbols-outlined'>delete</span>Delete
                                        </button>
                                    </form>
                                </div>
                              </td>";
                        echo "</tr>";                        
                    endforeach;
                    ?>
                    <?php else: ?>
                    <p>No available data</p>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </section>

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
    </script>
</body>

</html>