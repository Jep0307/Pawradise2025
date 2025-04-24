<?php
session_start();

$timeout_duration = 9000;

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Remove from DB
    include '../components/db_connect.php';
    $email = $_SESSION['admin_email'];
    $stmt = $conn->prepare("DELETE FROM admin_sessions WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    // Destroy session
    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
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
                    <p class="current-date">March 29, 2025 Saturday</p>
                    <h1>Good morning, <span>Admin</span>!</h1>
                </div>

                <div class="recent-activities">
                    <h3>Recent Activities</h3>
                    <p style="padding-bottom: 1rem;">Changes made by the admins</p>

                    <div class="activities-container">
                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>March 29, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>Admin changes</p>
                            </div>

                            <p>March 01, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>Pet adopted</p>
                            </div>

                            <p>February 14, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>February 6, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>February 6, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>February 6, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>February 6, 2025</p>
                        </div>

                        <div class="activity">
                            <div class="activity-name">
                                <span class="material-symbols-outlined activity-icon">chevron_forward</span>
                                <p>New pets added</p>
                            </div>

                            <p>February 6, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-adoption-data">
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">pets</span>
                            <p class="number">428</p>
                        </div>
                        <p>Pets Listed</p>
                        <p class="month">Current month</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">task_alt</span>
                            <p class="number">242</p>
                        </div>
                        <p>Adoption Completed</p>
                        <p class="month">Current month</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">description</span>
                            <p class="number">25</p>
                        </div>
                        <p>Pending Applications</p>
                        <p class="month">Current month</p>
                    </div>

                    <div class="card">
                        <div class="top">
                            <span class="material-symbols-outlined">person</span>
                            <p class="number">167</p>
                        </div>
                        <p>Registered users</p>
                        <p class="month">Current month</p>
                    </div>

                    <button class="view-more-btn">View More</button>
                </div>
            </div>
        </div>
        </div>

        <div class="dashboard-analytics-container-bottom">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis quia quibusdam magnam veritatis officiis id
            nisi assumenda laboriosam aperiam, ullam, sapiente magni tenetur, asperiores ducimus sunt praesentium
            voluptatum! Nemo, consequatur.
        </div>

        <div>
            <canvas id="myChart" width="400" height="200"></canvas>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script.js"></script>
</body>

</html>