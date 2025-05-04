<?php
include '../components/db_connect.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Shelter</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
    #map {
        width: 100%;
        height: 300px;
        margin-bottom: 15px;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="user-mgmt-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>Add Shelter</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="user-mgmt-container" method="POST" action="add-shelter.php" enctype="multipart/form-data">
            <div class="user-mgmt-top-panel">
                <div class="header">
                    <h1>Add Shelter</h1>
                    <p class="subtitle">Create a new shelter record</p>
                </div>
                <div class="top-buttons">
                    <button type="submit" class="add-btn">
                        <span class="material-symbols-outlined">add</span> Add Shelter
                    </button>
                </div>
            </div>
            <div id="map"></div>
            <div class="user-mgmt-bottom-panel">

                <div class="user-mgmt-left-panel">
                    <img src="../assets/default-shelter.png" alt="" class="user-profile-photo" id="preview-image">
                    <input type="file" name="shelter_img" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Upload Photo</button>
                </div>

                <div class="right-panel">
                    <h3>Shelter Info</h3>
                    <div class="input-division">
                        <input type="text" name="shelter_name" value="" placeholder="Shelter Name" required />
                        <input type="text" name="address" value="" placeholder="Address" required />
                    </div>

                    <h3>Latitude and Longitude</h3>
                    <div class="input-division">
                        <input type="text" id="latitude" name="latitude" value="" placeholder="Latitude" required />
                        <input type="text" id="longitude" name="longitude" value="" placeholder="Longitude" required />
                    </div>

                    <h3>Shelter Description</h3>
                    <div class="input-division">
                        <textarea name="description" placeholder="Description" required
                            style="grid-column: 1/4; padding: 10px 15px;"></textarea>
                    </div>

                </div>
            </div>
            </div>
        </form>
    </section>

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

    document.getElementById('photo-input').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('preview-image').src = URL.createObjectURL(file);
        }
    });
    </script>

    <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 14.6760,
                lng: 121.0437
            }, // Default: somewhere (Quezon City)
            zoom: 12,
        });

        let marker;

        map.addListener('click', function(event) {
            if (marker) {
                marker.setPosition(event.latLng);
            } else {
                marker = new google.maps.Marker({
                    position: event.latLng,
                    map: map
                });
            }
            document.getElementById('latitude').value = event.latLng.lat().toFixed(6);
            document.getElementById('longitude').value = event.latLng.lng().toFixed(6);
        });
    }

    window.initMap = initMap;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer>
    </script>

    <script src="../script.js"></script>
</body>

</html>