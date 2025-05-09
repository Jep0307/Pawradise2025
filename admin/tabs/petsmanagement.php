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
                <p>Admin><span>PET MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="../components/logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Pet Management</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <!-- <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort
                        </option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select> -->
                    <button class="add-btn" id="openFormBtn"><span
                            class="material-symbols-outlined">add</span>Add</button>

                    <!-- <a href="../components/add-pet.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Entry</a> -->
                </div>
            </div>

            <div class="search-container">
                <input class="search-input" type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>


            <main>
                <div class="management-bottom-panel">
                    <table class="general-table">
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

                <div class="form-modal" id="FormModal">
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

<script type="module" src="../scripts/petsmanagement.js"></script>

</html>
<?php
mysqli_close($conn);
?>