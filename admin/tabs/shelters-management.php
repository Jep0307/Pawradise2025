<?php
include '../../config/db.php'; // Include the database connection
include '../components/session.php';
include '../components/popup.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "
    SELECT 
        s.id,
        s.shelter_name,
        s.address,
        s.description,
        s.shelter_img,
        s.created_at,
        (SELECT COUNT(*) FROM staffs WHERE shelter_id = s.id) AS staff_count,
        (SELECT COUNT(*) FROM pets1 WHERE shelter = s.shelter_name) AS pet_count
    FROM shelters s
";

$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

$shelters = [];
while ($row = $result->fetch_assoc()) {
    $shelters[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shelters Management</title>
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
                <p>Admin > <span>SHELTERS MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="../components/logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Shelters Management</h1>
                    <p class="subtitle">
                        Manage shelters
                    </p>
                </div>
                <!-- <div class="top-buttons">
                    <a href="../components/add-shelter.php" class="add-btn">
                        <span class="material-symbols-outlined">add</span>Add Shelter
                    </a>
                </div> -->
            </div>

            <div class="management-bottom-panel">
                <div class="shelter-cards">
                    <?php if (count($shelters) > 0): ?>
                    <?php foreach ($shelters as $shelter): 
                            $imageSrc = !empty($shelter['shelter_img']) 
                                ? 'data:image/jpeg;base64,' . base64_encode($shelter['shelter_img']) 
                                : '../assets/default-shelter.png';
                        ?>
                    <a href="<?php echo "../../mgmt_components/shelters/edit_shelter.php?id={$shelter['id']}" ?>"
                        class="shelter-card">
                        <img src="<?= $imageSrc ?>" alt="Shelter Image">
                        <div class="shelter-info">
                            <h3><?= htmlspecialchars($shelter['shelter_name']) ?></h3>
                            <p class="shelter-description"><?= nl2br(htmlspecialchars($shelter['description'])) ?></p>

                            <p style="font-weight: 700;">Shelter Information</p>
                            <p><?= htmlspecialchars($shelter['address']) ?></p>
                            <p>Staff: <span><?= $shelter['staff_count'] ?></span></p>
                            <p>Created on: <span><?= date('F j, Y', strtotime($shelter['created_at'])) ?></span></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p>No shelters found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</body>

</html>