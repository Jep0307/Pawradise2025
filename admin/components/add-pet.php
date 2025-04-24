<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Pet</title>
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
                <p>Admin > <span>ADD PET</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <form class="add-pet-container">
            <div class="add-pet-top-panel">
                <div class="header">
                    <h1>Add Pet</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <button class="add-btn">Add Pet</button>
                </div>
            </div>

            <div class="add-pet-bottom-panel">
                <div class="add-pet-left-panel">
                    <div class="photo-gallery" id="photoGallery"></div>
                    <input type="file" id="photoInput" multiple accept="image/*" style="display: none" />
                </div>

                <div class="right-panel">
                    <h3>Characteristics</h3>
                    <div class="input-division">
                        <input type="text" name="pet-name" id="" placeholder="Pet Name" />
                        <select name="pet-species" id="">
                            <option value="" selected disabled>Select a species</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Rabbit">Rabbit</option>
                            <option value="Parrot">Parrot</option>
                        </select>
                        <input type="text" placeholder="Breed" />
                        <input type="number" name="pet-age" id="" placeholder="Age" />
                        <div class="radio-input">
                            <label>
                                <input value="male" name="value-radio" id="value-1" type="radio" checked />
                                <span>Male</span>
                            </label>
                            <label>
                                <input value="female" name="value-radio" id="value-2" type="radio" />
                                <span>Female</span>
                            </label>
                            <span class="selection"></span>
                        </div>
                        <input type="number" name="pet-height" id="" placeholder="Height in cm" />
                        <input type="number" name="pet-weight" id="" placeholder="Weight in kg" />
                        <input type="text" name="pet-color" id="" placeholder="Color" />
                    </div>

                    <h3>Health and Medical</h3>
                    <div class="input-division">
                        <select name="vaccination-status" id="vacc-status">
                            <option value="" disabled selected>Vaccination Status</option>
                            <option value="Fully Vaccinated">Fully Vaccinated</option>
                            <option value="Partially Vaccinated">
                                Partially Vaccinated
                            </option>
                            <option value="Not Vaccinated">Not Vaccinated</option>
                        </select>
                        <select name="neutered" id="neutered">
                            <option value="" disabled selected>Neutered Status</option>
                            <option value="neutered">Yes</option>
                            <option value="not-neutered">No</option>
                        </select>
                        <input type="text" name="pet-medical-condition" id="" placeholder="Medical Condition" />
                    </div>

                    <h3>Adoption Details</h3>
                    <div class="input-division">
                        <select name="adopt-status" id="">
                            <option value="" disabled selected>Adoption Status</option>
                            <option value="Available">Available</option>
                            <option value="Pending">Pending</option>
                            <option value="Adopted">Adopted</option>
                        </select>
                        <input type="text" name="pet-shelter" id="" placeholder="Current Shelter" />
                        <div class="date-time">
                            <label for="rescue-date" id="rescueDate">Date of Intake

                                <input type="date" name="rescue-date" id="dateInput" /></label>
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
    const petPhotos = [];

    function renderGallery() {
        gallery.innerHTML = "";

        petPhotos.forEach((src) => {
            const img = document.createElement("img");
            img.src = src;
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