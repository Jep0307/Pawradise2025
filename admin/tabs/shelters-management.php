<?php
include('../components/db_connect.php');

$query = "SELECT * FROM shelters"; 
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
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
                <p>Admin > <span>USERS MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>User Accounts Management</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort</option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select>
                    <a href="../components/add-pet.html" class="add-btn">Add User</a>
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
                        <th>LOGO</th>
                        <th>SHELTER</th>
                        <th>LOCATION</th>
                        <th>DESCRIPTION</th>
                        <th>CREATED</th>
                        <th></th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Convert image blob to base64
                        $imageData = base64_encode($row['shelter_img']);
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;

                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td><img src='{$imageSrc}' alt='Shelter Logo' width='50' /></td>";
                        echo "<td>{$row['shelter_name']}</td>";
                        echo "<td>{$row['location']}</td>";
                        echo "<td>{$row['description']}</td>";
                        echo "<td>{$row['created_at']}</td>";
                        echo "<td class='options-btn'>
                                <span class='material-symbols-outlined'>edit</span>
                                <div class='pop-up'>
                                    <a href='edit-user.php?id={$row['id']}'><span class='material-symbols-outlined'>edit</span>Edit</a>
                                    <a href='delete-user.php?id={$row['id']}'><span class='material-symbols-outlined'>delete</span>Delete</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No users found.</td></tr>";
                }

                $conn->close();
                ?>
                </table>
            </div>
        </div>
    </section>
</body>

</html>