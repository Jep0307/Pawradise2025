<?php
session_start();
include 'db_connect.php';

// Fetch pet data based on the ID parameter in the URL
$pet_id = $_GET['id'];
$sql = "SELECT * FROM pets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

$pet = $result->fetch_assoc();
$stmt->close();

// If the pet doesn't exist, redirect to an error page (optional)
if (!$pet) {
    header("Location: error_page.php"); // or a relevant error page
    exit();
}

// Prepare images (assuming you are storing the images in the database as BLOBs or paths)
$pet_images = !empty($pet['pet_img']) ? [base64_encode($pet['pet_img'])] : [];
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
    <section class="add-pet-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>Edit Pet</span></p>
            </div>
            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="add-pet-container" method="POST" action="update_pet.php" enctype="multipart/form-data">
            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">

            <div class="add-pet-top-panel">
                <div class="header">
                    <h1>Edit Pet</h1>
                    <p class="subtitle">Update the pet details</p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn" type="submit">Save Changes</button>
                </div>
            </div>

            <div class="add-pet-bottom-panel">
                <div class="add-pet-left-panel">
                    <div class="photo-gallery" id="photoGallery">
                        <?php
                        foreach ($pet_images as $image) {
                            echo '<img src="data:image/jpeg;base64,' . $image . '" alt="Pet Image" width="100">';
                        }
                        ?>
                    </div>
                    <input type="file" id="photoInput" multiple accept="image/*" style="display: none" />
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
                                <input value="male" name="value-radio" id="value-1" type="radio"
                                    <?php echo ($pet['gender'] == 'Male') ? 'checked' : ''; ?> />
                                <span>Male</span>
                            </label>
                            <label>
                                <input value="female" name="value-radio" id="value-2" type="radio"
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
                        <div class="date-time">
                            <label for="rescue-date">Date of Intake
                                <input type="date" name="rescue-date" value="<?php echo $pet['intake_date']; ?>"
                                    required />
                            </label>
                        </div>
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
            const img = document.createElement("img");
            img.src = "data:image/jpeg;base64," + src;
            gallery.appendChild(img);
        });

        const addBtnImg = document.createElement("img");
        addBtnImg.src = "../images/add-photo.png";
        addBtnImg.alt = "Add photo";
        addBtnImg.style.cursor = "pointer";
        addBtnImg.onclick = () => input.click();
        gallery.appendChild(addBtnImg);
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
    </script>
</body>

</html>