<?php
include '../components/session.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT logs.*, admins.name AS admin_name
        FROM logs
        LEFT JOIN admins ON logs.admin_id = admins.id
        ORDER BY created_at DESC
        LIMIT 20";
$result = $conn->query($sql);
if (!$result) {
    die("Error in query: " . $conn->error);
}

$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

$currentMonth = date('Y-m');

$queryPetsListed = "SELECT COUNT(*) AS total FROM pets";
$stmt = $conn->prepare($queryPetsListed);
$stmt->execute();
$resultPetsListed = $stmt->get_result();
$petsListed = $resultPetsListed->fetch_assoc()['total'];
$stmt->close();

$queryAdoptionCompleted = "SELECT COUNT(*) AS total FROM pets WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
$stmt = $conn->prepare($queryAdoptionCompleted);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$resultAdoptionCompleted = $stmt->get_result();
$adoptionCompleted = $resultAdoptionCompleted->fetch_assoc()['total'];
$stmt->close();

$queryRegisteredUsers = "SELECT COUNT(*) AS total FROM users1 WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
$stmt = $conn->prepare($queryRegisteredUsers);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$resultRegisteredUsers = $stmt->get_result();
$registeredUsers = $resultRegisteredUsers->fetch_assoc()['total'];
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="dashboard-analytics" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>HOME</span></p>
            </div>

            <div class="right">
                <a href="../logout.php"><span class="material-symbols-outlined"> logout
                    </span>Logout</a>
            </div>
        </div>

        <div class="dashboard-analytics-container-top">
            <div class="dashboard-header">
                <div class="admin-greetings">
                    <p class="current-date"><?php echo date("M d, Y l")?></p>
                    <h1>Good morning, <span><?= htmlspecialchars($_SESSION['admin_name']) ?></span>!</h1>
                </div>

                <div class="recent-activities">
                    <h3>Recent Activities</h3>
                    <p style="padding-bottom: 1rem;">Changes made by the admins</p>
                    <div class="activities-container">
                        <?php if (count($activities) > 0): ?>
                        <ul>
                            <?php foreach ($activities as $row): ?>
                            <li class="activity">
                                <div class="activity-name">
                                    <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                    <p><?= htmlspecialchars(trim($row['admin_action'])) ?: 'No action available' ?></p>
                                </div>
                                <p class="activity-time"><?= date("M d, Y | h:i a", strtotime($row['created_at'])) ?>
                                </p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p>No recent activities to display.</p>
                        <?php endif; ?>

                    </div>


                </div>
            </div>

            <div class="dashboard-adoption-data">
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">pets</span>
                            <p class="number"><?php echo $petsListed; ?></p>
                        </div>
                        <p>Pets Listed</p>
                        <p class="month">All times</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">task_alt</span>
                            <p class="number"><?php echo $adoptionCompleted; ?></p>
                        </div>
                        <p>Adoption Completed</p>
                        <p class="month">Current month</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">description</span>
                            <p class="number">25</p> <!-- Static for now -->
                        </div>
                        <p>Pending Applications</p>
                        <p class="month">Current month</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">person</span>
                            <p class="number"><?php echo $registeredUsers; ?></p>
                        </div>
                        <p>Registered Users</p>
                        <p class="month">Current month</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- 
        <div class="dashboard-analytics-container-bottom">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis quia quibusdam magnam veritatis officiis id
            nisi assumenda laboriosam aperiam, ullam, sapiente magni tenetur, asperiores ducimus sunt praesentium
            voluptatum! Nemo, consequatur.
        </div>

        <div>
            <canvas id="myChart" width="400" height="200"></canvas>
        </div> -->
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script.js"></script>
</body>

</html>