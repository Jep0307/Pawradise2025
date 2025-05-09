<?php
session_start(); // Make sure the session is started

// Check if the admin is logged in by checking the session email
if (!isset($_SESSION['admin_email'])) {
    // Redirect to login page if no session email is found (admin not logged in)
    header('Location: ../../../../admin/login.php');
    exit;
}

// Get the admin's email from session (this is the one used to login)
$admin_email = $_SESSION['admin_email'];

// Include the database connection file
include '../../config/db.php';

// Query to fetch admin details based on the email stored in the session
$sql = "SELECT name, email FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email); // Bind the session email to the query
$stmt->execute();
$result = $stmt->get_result();

// If no admin found for the given email (in case of an error)
if ($result->num_rows === 0) {
    echo "Admin not found.";
    exit;
}

$admin = $result->fetch_assoc();

// Extract admin details
$adminName = $admin['name'] ?? 'Admin';
$adminEmail = $admin['email'] ?? 'admin@gmail.com';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Application Management</title>
  <!-- <link rel="stylesheet" href="../assets/styles/form.css" /> -->
  <link rel="stylesheet" href="../styles/global.css">

</head>

<body>

  <header>
    <nav>
      <div class="content">
        <h3 class="title">Pet Management</h3>
      </div>

      <div class="content">
        <div class="search-container">
          <input type="text" class="searchbar search-input" placeholder="Search...">
          <img src="../assets/imgs/Search-icon.svg" alt="">
        </div>

        <button class="add-btn" id="openFormBtn">Add</button>
      </div>
    </nav>
  </header>

  <div class="sidebar">
    <div class="logo-and-text">
      <img src="../assets/imgs/Logo.svg" alt="">
      <h3>Pawradise</h3>
    </div>
    <hr>
    <ul>
      <li><img src="../assets/imgs/Paw-print-icon.svg" alt=""> <a href="./petsmanagement.php">Pet Management</a></li>
      <li><img src="../assets/imgs/Paw-print-icon.svg" alt=""> <a href="./application.php">Applications Management</a></li>
    </ul>
    <hr>
    <ul>
      <li><a href="#"><?= htmlspecialchars($adminEmail) ?></a></li>
      <li id="signoutBtn"><a href="#">Logout</a></li>
    </ul>

    <p>© 2025 Pawradise. All rights reserved.</p>
  </div>

  <!-- Main Content Section -->
  <main>
    <div class="table-wrapper">
      <table>
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
    </div>
  </main>

  <script src="../scripts/application.js"></script>
</body>

</html>