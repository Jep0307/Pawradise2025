<?php
include '../../config/db.php'; // Include the database connection
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Management</title>

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
                <p>Admin > <span>APPLICATION MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="../components/logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Application Management</h1>
                    <p class="subtitle">
                        You can update the application status of adopters.
                    </p>
                </div>
                <div class="top-buttons">
                    <!-- <select name="sort" id="" class="sort-input">
            <option value="sort" disabled selected>Sort
            </option>
            <option value="alphabetical">Alphabetical</option>
            <option value="newest">Newest</option>
          </select> -->
                    <!-- <button class="add-btn" id="openFormBtn">Add</button> -->

                    <!-- <a href="../components/add-pet.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Entry</a> -->
                </div>
            </div>

            <div class="search-container">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>

            <!-- Main Content Section -->
            <main>
                <div class="management-bottom-panel">
                    <table class="general-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Birthdate</th>
                                <th>Occupation</th>
                                <th>Address</th>
                                <th>Social Media</th>
                                <th>Rents?</th>
                                <th>Residence Type</th>
                                <th>Pet Preference</th>
                                <th>Zoom Date</th>
                                <th>Zoom Time?</th>
                                <th>Shelter Visit</th>
                                <th>Submitted At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="applicationList"></tbody>
                    </table>
                </div>

                <div class="form-modal" id="FormModal">
                    <div class="modal-content">
                        <h2>Update Status</h2>
                        <form id="updateASForm" enctype="multipart/form-data">
                            <select name="status" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Pending">Pending </option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Completed">Completed</option>
                            </select>

                            <div class="button-row">
                                <button type="button" id="cancelButton" class="cancel-btn">Cancel</button>
                                <button type="submit" class="save-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <script src="../scripts/application.js"></script>
</body>

</html>