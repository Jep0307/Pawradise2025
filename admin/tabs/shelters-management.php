<?php
include '../components/db_connect.php';
include '../components/session.php';
include '../components/popup.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}
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
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
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
                <div class="top-buttons">
                    <a href="../components/add-pet.html" class="add-btn"> <span
                            class="material-symbols-outlined">add</span>Add Shelter</a>
                </div>
            </div>

            <!-- <div class="search-input">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div> -->

            <!-- <div id="map"></div> -->

            <div class="management-bottom-panel">
                <div class="shelter-cards">
                    <div class="shelter-card">
                        <img src="../images/paws-rehab.jpg" alt="">
                        <div class="shelter-info">
                            <h3>PAWS Animal Rehabilitation Center</h3>
                            <p>123 Random Address, Quezon City</p>
                            <p>Animals in this shelter: <span>123</span></p>
                            <p>Staff: <span>4</span></p>
                            <p>Pending Applications: <span>10</span></p>
                        </div>
                    </div>

                    <div class="shelter-card">
                        <h3>QC Shelter</h3>
                        <p>123 Random Address, Quezon City</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
    async function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 14.6760,
                lng: 121.0437
            },
            zoom: 13,
        });

        try {
            const response = await fetch("../components/get-shelters.php");
            const shelters = await response.json();

            const markers = [];

            shelters.forEach(shelter => {
                const position = {
                    lat: parseFloat(shelter.latitude),
                    lng: parseFloat(shelter.longitude)
                };

                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: shelter.shelter_name
                });

                console.log(
                    `Marker added: ${shelter.shelter_name} at (${shelter.latitude}, ${shelter.longitude})`
                );

                const infoWindow = new google.maps.InfoWindow({
                    content: `<strong>${shelter.shelter_name}</strong><br>${shelter.address}`
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });

        } catch (error) {
            console.error("Error fetching shelters:", error);
        }
    }

    window.initMap = initMap;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX9EY3igYEQtIsdXsxSpbfMosfO00uPiE&callback=initMap"
        async defer></script>

</body>

</html>