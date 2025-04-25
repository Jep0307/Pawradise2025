<?php
include '../components/db_connect.php';
include '../components/session.php';
include '../components/popup.php';
include '../components/logger.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

$pet_id = $_GET['id'] ?? $_POST['pet_id'] ?? null;

if (!$pet_id) {
    die("Pet ID not provided.");
}

$sql = "SELECT * FROM pets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet_result = $stmt->get_result();
$pet = $pet_result->fetch_assoc();
$stmt->close();

if (isset($_POST['delete_image_id'])) {
    $image_id = $_POST['delete_image_id'];
    $sql = "DELETE FROM pet_images WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $stmt->close();
    header("Location: edit_pet.php?id=" . $pet_id); // redirect to refresh images
    exit();
}

if (isset($_FILES['pet_images'])) {
    foreach ($_FILES['pet_images']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['pet_images']['error'][$index] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($tmpName);
            $mimeType = $_FILES['pet_images']['type'][$index];

            $sql = "INSERT INTO pet_images (pet_id, image_data, mime_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $pet_id, $imageData, $mimeType);
            $stmt->execute();
            $stmt->close();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pet-name'])) {
    $name = $_POST['pet-name'];
    $species = $_POST['pet-species'];
    $breed = $_POST['pet-breed'];
    $age = $_POST['pet-age'];
    $gender = $_POST['value-radio'];
    $height = $_POST['pet-height'];
    $weight = $_POST['pet-weight'];
    $color = $_POST['pet-color'];
    $vaccination = $_POST['vaccination-status'];
    $neutered = $_POST['neutered'];
    $medical = $_POST['pet-medical-condition'];
    $status = $_POST['adopt-status'];
    $shelter = $_POST['pet-shelter'];
    $intake = $_POST['rescue-date'];

    $_SESSION['popup_message'] = "Pet updated successfully.";

    $sql = "UPDATE pets SET 
        name = ?, species = ?, breed = ?, age = ?, gender = ?, height = ?, weight = ?, color = ?,
        vaccination_status = ?, neutered_status = ?, medical_condition = ?, adoption_status = ?,
        shelter = ?, intake_date = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissdsssssssi",
        $name, $species, $breed, $age, $gender, $height, $weight, $color,
        $vaccination, $neutered, $medical, $status, $shelter, $intake, $pet_id
    );
    $stmt->execute();

    if ($stmt->execute()) {
        $newUserId = $stmt->insert_id;
        $admin_id = $_SESSION['admin_id'];
        log_action($conn, $admin_id, "Edited a pet information", "pet", $newUserId, "Username: $username");
        displayPopup("Pet updated successfully.");
    } else {
        displayPopup("Database error: " . $stmt->error, 'error');
    }

    $stmt->close();
    header("Location: edit-pet.php?id=" . $pet_id);
    exit();
}

if (isset($_SESSION['popup_message'])) {
    displayPopup($_SESSION['popup_message']);
    unset($_SESSION['popup_message']);
}

$pet_images = [];
$image_ids = [];

$sql = "SELECT id, image_data, mime_type FROM pet_images WHERE pet_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $pet_images[] = "data:" . $row['mime_type'] . ";base64," . base64_encode($row['image_data']);
    $image_ids[] = $row['id'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Pet</title>
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
                <p>Admin > <span>Edit Pet</span></p>
            </div>
            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="pet-management-container" method="POST" action="edit-pet.php" enctype="multipart/form-data">
            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">

            <div class="pet-management-top-panel">
                <div class="header">
                    <h1>Edit Pet</h1>
                    <p class="subtitle">Update the pet details</p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn" type="submit"><span class="material-symbols-outlined">save</span>Save
                        Changes</button>
                </div>
            </div>

            <div class="pet-management-bottom-panel">
                <div class="pet-management-left-panel">
                    <div class="photo-gallery" id="photoGallery">
                        <?php foreach ($pet_images as $index => $image): ?>
                        <div class="image-container">
                            <img src="<?php echo $image; ?>" alt="Pet Image" width="100">
                            <form method="POST" action="edit-pet.php">
                                <input type="hidden" name="delete_image_id" value="<?php echo $image_ids[$index]; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <input type="file" id="photoInput" name="pet_images[]" multiple accept="image/*"
                        style="display: none;" />
                </div>

                <div class="right-panel">
                    <h3>Characteristics</h3>
                    <div class="input-division">
                        <input type="text" name="pet-name" value="<?php echo htmlspecialchars($pet['name']); ?>"
                            placeholder="Pet Name" required />
                        <select name="pet-species" required>
                            <option value="" disabled>Select a species</option>
                            <option value="Dog" <?php echo ($pet['species'] == 'Dog') ? 'selected' : ''; ?>>Dog</option>
                            <option value="Cat" <?php echo ($pet['species'] == 'Cat') ? 'selected' : ''; ?>>Cat</option>
                            <option value="Rabbit" <?php echo ($pet['species'] == 'Rabbit') ? 'selected' : ''; ?>>Rabbit
                            </option>
                            <option value="Parrot" <?php echo ($pet['species'] == 'Parrot') ? 'selected' : ''; ?>>Parrot
                            </option>
                        </select>
                        <input type="text" name="pet-breed" value="<?php echo htmlspecialchars($pet['breed']); ?>"
                            placeholder="Breed" />
                        <input type="number" name="pet-age" value="<?php echo htmlspecialchars($pet['age']); ?>"
                            placeholder="Age" required />

                        <div class="radio-input">
                            <label>
                                <input value="Male" name="value-radio" id="value-1" type="radio"
                                    <?php echo ($pet['gender'] == 'Male') ? 'checked' : ''; ?> />
                                <span>Male</span>
                            </label>
                            <label>
                                <input value="Female" name="value-radio" id="value-2" type="radio"
                                    <?php echo ($pet['gender'] == 'Female') ? 'checked' : ''; ?> />
                                <span>Female</span>
                            </label>
                            <span class="selection"></span>
                        </div>

                        <input type="number" name="pet-height" value="<?php echo htmlspecialchars($pet['height']); ?>"
                            placeholder="Height in cm" />
                        <input type="number" name="pet-weight" value="<?php echo htmlspecialchars($pet['weight']); ?>"
                            placeholder="Weight in kg" />
                        <input type="text" name="pet-color" value="<?php echo htmlspecialchars($pet['color']); ?>"
                            placeholder="Color" />
                    </div>

                    <h3>Health and Medical</h3>
                    <div class="input-division">
                        <select name="vaccination-status" required>
                            <option value="" disabled>Select Vaccination Status</option>
                            <option value="Fully Vaccinated"
                                <?php echo ($pet['vaccination_status'] == 'Fully Vaccinated') ? 'selected' : ''; ?>>
                                Fully Vaccinated</option>
                            <option value="Partially Vaccinated"
                                <?php echo ($pet['vaccination_status'] == 'Partially Vaccinated') ? 'selected' : ''; ?>>
                                Partially Vaccinated</option>
                            <option value="Not Vaccinated"
                                <?php echo ($pet['vaccination_status'] == 'Not Vaccinated') ? 'selected' : ''; ?>>Not
                                Vaccinated</option>
                        </select>

                        <select name="neutered" required>
                            <option value="" disabled>Neutered Status</option>
                            <option value="neutered"
                                <?php echo ($pet['neutered_status'] == 'neutered') ? 'selected' : ''; ?>>Yes</option>
                            <option value="not-neutered"
                                <?php echo ($pet['neutered_status'] == 'not-neutered') ? 'selected' : ''; ?>>No</option>
                        </select>

                        <input type="text" name="pet-medical-condition"
                            value="<?php echo htmlspecialchars($pet['medical_condition']); ?>"
                            placeholder="Medical Condition" />
                    </div>

                    <h3>Adoption Details</h3>
                    <div class="input-division">
                        <select name="adopt-status" required>
                            <option value="" disabled>Select Adoption Status</option>
                            <option value="Available"
                                <?php echo ($pet['adoption_status'] == 'Available') ? 'selected' : ''; ?>>Available
                            </option>
                            <option value="Pending"
                                <?php echo ($pet['adoption_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Adopted"
                                <?php echo ($pet['adoption_status'] == 'Adopted') ? 'selected' : ''; ?>>Adopted</option>
                        </select>
                        <input type="text" name="pet-shelter" value="<?php echo htmlspecialchars($pet['shelter']); ?>"
                            placeholder="Current Shelter" />
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script src="../script.js"></script>
    <script>
    const gallery = document.getElementById("photoGallery");
    const input = document.getElementById("photoInput");
    const petPhotos = <?php echo json_encode($pet_images); ?>;

    function renderGallery() {
        gallery.innerHTML = "";

        petPhotos.forEach((src) => {
            const container = document.createElement("div");
            container.classList.add("image-container");

            const img = document.createElement("img");
            img.src = src;
            img.alt = "Pet Image";
            img.width = 100;

            container.appendChild(img);
            gallery.appendChild(container);
        });

        // Always add the add-photo button at the end
        const addBtnContainer = document.createElement("div");
        addBtnContainer.classList.add("image-container");

        const addBtnImg = document.createElement("img");
        addBtnImg.src = "../images/add-photo.png";
        addBtnImg.alt = "Add photo";
        addBtnImg.style.cursor = "pointer";
        addBtnImg.width = 100;
        addBtnImg.onclick = () => input.click();

        addBtnContainer.appendChild(addBtnImg);
        gallery.appendChild(addBtnContainer);
    }

    input.addEventListener("change", (e) => {
        const files = Array.from(e.target.files);
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                petPhotos.push(event.target.result);
                renderGallery();
            };
            reader.readAsDataURL(file);
        });
    });

    renderGallery();

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