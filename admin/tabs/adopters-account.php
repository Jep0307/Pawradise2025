<?php
include '../components/db_connect.php';
include '../components/session.php';
include '../components/popup.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Management</title>

    <!-- <link rel="icon" sizes="48x48" href="../assets/imgs/Logo.svg" type="image/x-icon"> -->
    <!-- <link rel="stylesheet" href="../assets/styles/global.css"> -->
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
                <p>Admin > <span>PET MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="../components/logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Adopters account</h1>
                    <!-- <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p> -->
                </div>
                <!-- <div class="top-buttons">
                    <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort
                        </option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select>
                    <button class="add-btn" id="openFormBtn">Add</button>

                    <a href="../components/add-pet.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Entry</a>
                </div> -->
            </div>

            <div class="search-input">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>


            <main>
                <div class="management-bottom-panel">
                    <table class="general-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fullname</th>
                                <th>Address</th>
                                <th>Contact no.</th>
                                <th>Email</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="usersList">
                    </table>
                </div>

            </main>
</body>

<script src="../assets/js/adopters-account.js"></script>
<!-- <script type="module" src="../../admin/scripts/auth/signout.js"></script> -->

</html>
<?php
mysqli_close($conn);
?>