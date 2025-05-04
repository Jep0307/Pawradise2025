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
include '../../admin/components/db_connect.php';

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Management</title>

    <link rel="icon" sizes="48x48" href="../assets/imgs/Logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/styles/global.css">
    <link rel="stylesheet" href="../assets/styles/petsmanagement.css">
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
            <li><img src="../assets/imgs/Paw-print-icon.svg" alt=""> <a href="./application.html">Application Forms</a></li>
        </ul>
        <hr>
        <ul>
            <li><a href="#"><?= htmlspecialchars($adminEmail) ?></a></li>
            <li><a href="../../admin/logout.php">Logout</a></li>
        </ul>

        <p>© 2025 Pawradise. All rights reserved.</p>
    </div>

    <main>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Type</th>
                        <th>Age</th>
                        <th>Breed</th>
                        <th>Shelter</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="adoptList">
            </table>
        </div>

        <div class="pet-form-modal" id="petFormModal">
            <div class="modal-content">
                <h2 id="formTitle">Add a Pet</h2>
                <form id="addPetForm" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*" id="imageInput" />

                    <input type="text" name="name" placeholder="Pet Name" required />

                    <select name="type" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                    </select>

                    <input type="text" name="age" placeholder="Age" required />

                    <select name="breed" required>
                        <option value="" disabled selected>Select Breed</option>
                        <option value="Aspin">Aspin </option>
                        <option value="Shih Tzu">Shih Tzu</option>
                        <option value="Chihuahua">Chihuahua</option>
                        <option value="Poodle">Poodle</option>
                        <option value="American Curl">American Curl</option>
                        <option value="Siamese">Siamese</option>
                        <option value="Bengal">Bengal</option>
                        <option value="Persian">Persian</option>
                        <option value="Stray">Stray</option>
                    </select>

                    <select name="sex" required>
                        <option value="" disabled selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>

                    <textarea name="description" placeholder="Description" rows="4" required></textarea>

                    <select name="location" required>
                        <option value="" disabled selected>Select Shelter</option>
                        <option value="PAWS Animal Rehabilitation Center">Paws</option>
                        <option value="Quezon City Animal Care and Adoption center">QC Pound</option>
                    </select>

                    <div class="button-row">
                        <button type="button" id="cancelButton" class="cancel-btn">Cancel</button>
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

<script type="module" src="../assets/js/petsmanagement.js"></script>

</html>