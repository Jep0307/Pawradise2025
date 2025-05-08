<?php
include '../../config/db.php';
include '../components/logger.php';
include '../components/session.php';
include '../components/popup.php';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

if (!isset($_GET['id'])) {
    header("Location: shelters-management.php");
    exit();
}

$shelter_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM shelters WHERE id = ?");
$stmt->bind_param("i", $shelter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: shelters-management.php");
    exit();
}

$shelter = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form values
    $shelter_name = sanitize($_POST['shelter_name']);
    $address = sanitize($_POST['address']);
    $latitude = sanitize($_POST['latitude']);
    $longitude = sanitize($_POST['longitude']);
    $description = sanitize($_POST['description']);

    if (isset($_FILES['shelter_img']) && $_FILES['shelter_img']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['shelter_img']['tmp_name']);
        $image = base64_encode($image);
    } else {
        $image = $shelter['shelter_img'];
    }

    $updateStmt = $conn->prepare("UPDATE shelters SET shelter_name = ?, address = ?, latitude = ?, longitude = ?, description = ?, shelter_img = ? WHERE id = ?");
    $updateStmt->bind_param("ssssssi", $shelter_name, $address, $latitude, $longitude, $description, $image, $shelter_id);

    if ($updateStmt->execute()) {
        log_action($conn, $_SESSION['admin_id'], 'Updated shelter information', 'Shelter', $shelter_id);

        header("Location: shelters-management.php?update=success");
        exit();
    } else {
        echo "Error updating shelter: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Shelter</title>
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
                <p>Admin > <span>Edit Shelter</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="user-mgmt-container" method="POST" enctype="multipart/form-data">
            <div class="user-mgmt-top-panel">
                <div class="header">
                    <h1>Edit Shelter</h1>
                    <p class="subtitle">Modify or delete shelters</p>
                </div>
                <div class="top-buttons">
                    <button type="submit" class="add-btn">
                        <span class="material-symbols-outlined">edit</span> Save Changes
                    </button>
                    <a href="../components/delete-shelter.php?id=<?php echo $shelter_id; ?>" class="delete-btn">
                        <span class="material-symbols-outlined">delete</span>Delete
                    </a>
                </div>
            </div>
            <div id="map"></div>
            <div class="user-mgmt-bottom-panel">

                <div class="user-mgmt-left-panel">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($shelter['shelter_img']); ?>" alt=""
                        class="user-profile-photo" id="preview-image">
                    <input type="file" name="shelter_img" accept="image/*" id="photo-input" style="display: none;" />
                    <button type="button" class="add-photo-btn"
                        onclick="document.getElementById('photo-input').click();">Change Photo</button>
                </div>

                <div class="right-panel">
                    <h3>Shelter Info</h3>
                    <div class="input-division">
                        <input type="text" name="shelter_name"
                            value="<?php echo htmlspecialchars($shelter['shelter_name']); ?>" placeholder="Shelter Name"
                            required />
                        <input type="text" name="address" value="<?php echo htmlspecialchars($shelter['address']); ?>"
                            placeholder="Address" required />
                    </div>

                    <h3>Latitude and Longitude</h3>
                    <div class="input-division">
                        <input type="text" id="latitude" name="latitude"
                            value="<?php echo htmlspecialchars($shelter['latitude']); ?>" placeholder="Latitude"
                            required />
                        <input type="text" id="longitude" name="longitude"
                            value="<?php echo htmlspecialchars($shelter['longitude']); ?>" placeholder="Longitude"
                            required />
                    </div>

                    <h3>Shelter Description</h3>
                    <div class="input-division">
                        <textarea name="description" placeholder="Description" required
                            style="grid-column: 1/4; padding: 10px 15px;"><?php echo htmlspecialchars($shelter['description']); ?></textarea>
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
                lat: parseFloat(<?php echo $shelter['latitude']; ?>),
                lng: parseFloat(<?php echo $shelter['longitude']; ?>)
            },
            zoom: 18,
        });

        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(<?php echo $shelter['latitude']; ?>),
                lng: parseFloat(<?php echo $shelter['longitude']; ?>)
            },
            map: map,
            title: "<?php echo htmlspecialchars($shelter['shelter_name']); ?>"
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<strong><?php echo htmlspecialchars($shelter['shelter_name']); ?></strong><br><?php echo htmlspecialchars($shelter['address']); ?>`
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    window.initMap = initMap;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX9EY3igYEQtIsdXsxSpbfMosfO00uPiE&callback=initMap"
        async defer></script>

    <script src="../script.js"></script>
</body>

</html>