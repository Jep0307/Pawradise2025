function showPreview(card) {
  const modal = document.getElementById("previewModal");

  closePetApplicationForm();

  // Retrieve data from the clicked card
  const image = card.querySelector("img").src;
  const title = card.querySelector(".header-and-text p:first-child").textContent;
  const sex = card.querySelector(".header-and-text p:nth-child(2)").textContent;
  const location = card.querySelector(".header-and-text p:nth-child(3)").textContent;
  const description = card.querySelector(".header-and-text .description").textContent;

  // Update modal content
  document.getElementById("modalImage").src = image;
  document.getElementById("modalTitle").textContent = title;
  document.getElementById("modalPetSex").textContent = sex;
  document.getElementById("modalPetLocation").textContent = location;
  document.getElementById("modalDiscription").textContent = description;

  // Show the modal
  modal.style.display = "block";

  spaceOnTop();
}

function closePreview() {
  document.getElementById("previewModal").style.display = "none";
}




function spaceOnTop(){
  const modal = document.getElementById("previewModal");
    // Scroll to the modal
    const offset = 32; // Offset in pixels
    const modalTop = modal.getBoundingClientRect().top + window.scrollY - offset;
  
    window.scrollTo({
      top: modalTop,
      behavior: "instant", // Smooth scrolling effect
    });
}




function filterCards(category) {
  const cards = document.querySelectorAll(".cards");
  const buttons = document.querySelectorAll(".categories button");
  closePreview();
  closePetApplicationForm();

  // Highlight the active button
  buttons.forEach((button) => {
    button.classList.remove("active");
  });
  document.querySelector(`#${category === 'all' ? 'allBtn' : category === 'cat' ? 'CatsBtn' : 'DogsBtn'}`).classList.add("active");

  // Show or hide cards based on the category
  cards.forEach((card) => {
    const cardCategory = card.getAttribute("data-category");

    if (category === "all" || cardCategory === category) {
      card.style.display = "block"; // Show the card
    } else {
      card.style.display = "none"; // Hide the card
    }
  });
}




// Show the application form
function showApplicationForm() {
  const modalTitle = document.getElementById("modalTitle").textContent;
  document.getElementById("petName").value = modalTitle; // Pass the pet name to the form
  
  document.getElementById("applicationForm").style.display = "flex";
  document.getElementById("previewModal").style.display = "none"; // Hide the preview modal

  spaceOnTop();
}

// Close the application form
function closePetApplicationForm() {
  document.getElementById("applicationForm").style.display ="none";
}




// Handle form submission
function submitApplication(event) {
  event.preventDefault(); // Prevent the default form submission

  const formData = new FormData(document.getElementById("adoptionForm"));

  //Send the form data to the PHP backend
  fetch("../assets/php/submitApplication.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      alert("Application submitted successfully!");
      closePetApplicationForm();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("There was an error submitting your application.");
    });
}