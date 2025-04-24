<?php
include '../components/db_connect.php';
include '../components/popup.php';

$query = "SELECT * FROM users_table"; 
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    displayPopup("User deleted successfully.");
} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
    displayPopup("Something went wrong. Please try again.", 'error');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Management</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="users-management-section" style="margin-left: 85px">
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
                    <a href="../components/add-user.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add User</a>
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
                        <th>USERNAME</th>
                        <th>NAME</th>
                        <th>BIO</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>ADDRESS</th>
                        <th>CREATED</th>
                        <th></th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Convert image blob to base64
                        $imageData = base64_encode($row['user_img']);
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;

                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td><img src='{$imageSrc}' alt='User Image' width='50' /></td>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['fullname']}</td>";
                        echo "<td>{$row['bio']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['phone']}</td>"; // You can show actual number if desired
                        echo "<td>{$row['address']}</td>";
                        echo "<td>{$row['created_at']}</td>";
                        echo "<td class='options-btn'>
                                <span class='material-symbols-outlined'>edit</span>
                                <div class='pop-up'>
                                    <a href='../components/edit-user.php?id={$row['id']}'><span class='material-symbols-outlined'>edit</span>Edit</a>
                                    <form action='../components/delete-user.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                                        <input type='hidden' name='user_id' value='{$row['id']}'>
                                        <button type='submit' class='delete-btn'>
                                            <span class='material-symbols-outlined'>delete</span>Delete
                                        </button>
                                    </form>
                                </div>
                              </td>";
                        echo "</tr>";                        
            }
            } else {
            echo "<tr>
                <td colspan='10'>No users found.</td>
            </tr>";
            }

            $conn->close();
            ?>
                </table>
            </div>
        </div>
    </section>

    <script src="../script.js"></script>
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