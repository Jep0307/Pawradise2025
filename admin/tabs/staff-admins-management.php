<?php
include '../components/db_connect.php';
include '../components/session.php';
include '../components/popup.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM staffs WHERE role = 'staff-admin'");

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
    <title>Pet Management</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="pet-management-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>STAFF-ADMINS MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Staff Admins Management</h1>
                    <p class="subtitle">
                        View, modify, and create Staff-Admins
                    </p>
                </div>
                <div class="top-buttons">
                    <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort</option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select>
                    <a href="../components/add-sadmin.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Entry</a>
                </div>
            </div>

            <div class="search-input">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>

            <div class="management-bottom-panel">
                <table class="general-table">
                    <tr>
                        <th>ID</th>
                        <th>IMAGE</th>
                        <th>NAME</th>
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
                        $imageSrc = !empty($row['sadmin_img']) ? 'data:image/jpeg;base64,' . base64_encode($row['sadmin_img']) : '../images/default.png';
                        
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td><img src='{$imageSrc}' alt='User Image' width='50' /></td>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['phone']."</td>";
                        echo "<td>".$row['role']."</td>";
                        echo "<td>".$row['shelter_id']."</td>";
                        echo "<td class='text-overflow'>".$row['address']."</td>";
                        echo "<td>".$row['created_at']."</td>";
                        echo "<td class='options-btn'>
                                <span class='material-symbols-outlined'>edit</span>
                                <div class='pop-up'>
                                    <a href='../components/edit-sadmin.php?sadmin_id={$row['id']}'><span class='material-symbols-outlined'>edit</span>Edit</a>
                                    <form action='../components/delete-sadmin.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                                        <input type='hidden' name='sadmin_id' value='{$row['id']}'>
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
</body>

</html>