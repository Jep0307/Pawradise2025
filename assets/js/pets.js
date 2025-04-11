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




function spaceOnTop() {
  const modal = document.getElementById("previewModal");
  // Scroll to the modal
  const offset = 88; // Offset in pixels
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
  document.getElementById("applicationForm").style.display = "none";
}




// Handle form submission
function submitApplication(event) {
  event.preventDefault(); // Prevent the default form submission

  // Show the warning modal
  const warningModal = document.getElementById("warningModal");
  warningModal.style.display = "flex";

  // Add event listeners for the "Yes" and "No" buttons in the warning modal
  const yesButton = warningModal.querySelector(".modal-buttons button:nth-child(2)");
  const noButton = warningModal.querySelector(".modal-buttons button:nth-child(1)");

  // If "Yes" is clicked, proceed with form submission
  yesButton.addEventListener("click", () => {
    warningModal.style.display = "none"; // Hide the warning modal
    finalizeApplicationSubmission(); // Call a function to finalize the submission
  });

  // If "No" is clicked, close the warning modal
  noButton.addEventListener("click", () => {
    warningModal.style.display = "none"; // Hide the warning modal
  });
}

// Finalize the application submission
function finalizeApplicationSubmission() {
  const formData = new FormData(document.getElementById("adoptionForm"));

  // Log each form field and its value (for debugging purposes)
  // for (const [key, value] of formData.entries()) {
  //   console.log(`${key}: ${value}`);
  // }

  // You can add your form submission logic here (e.g., send data to a server)
  alert("Application submitted successfully!");
  closePetApplicationForm(); // Close the application form after submission
}



const searchInput = document.getElementById('searchInput');
const petcardsName = document.querySelectorAll('.cards-container .cards .pet-name');
const noFound = document.getElementById('noResultFound');
const petCards = document.querySelectorAll('.cards-container .cards');

searchInput.addEventListener('keypress', (event) => {
  if (event.key === 'Enter') {
    const searchValue = searchInput.value.toLowerCase();
    let found = false;

    petcardsName.forEach(item => {
      const text = item.textContent.toLowerCase();
      const card = item.closest('.cards'); // Correctly get the card element

      if (text.includes(searchValue)) {
        card.style.display = 'block'; // Show matching card
        found = true;
        closePreview();
        closePetApplicationForm();
        filterCards('all');
      } else {
        card.style.display = 'none'; // Hide non-matching card
      }
    });

    if (!found) {
      noFound.textContent = "No results found";
      closePreview()
      closePetApplicationForm()
    } else {
      noFound.textContent = ""; // Clear the message if results are found
    }
  }
});



// Get all custom select elements
const customSelects = document.querySelectorAll('.custom-select');

customSelects.forEach(select => {
  // When the select element is clicked, toggle the options visibility
  select.addEventListener('click', function() {
    const options = this.nextElementSibling;
    options.classList.toggle('hidden');
  });
});

// Get all list items inside the custom options
const options = document.querySelectorAll('.custom-options li');

options.forEach(option => {
  option.addEventListener('click', function() {
    const selected = this.closest('.custom-select-container').querySelector('.selected');
    selected.textContent = this.textContent;
    this.closest('.custom-options').classList.add('hidden'); // Hide options after selection
  });
});


flatpickr("#birthdate", {
  enableTime: false,
  dateFormat: "Y-m-d"
});

flatpickr("#zoomdate", {
  enableTime: false,
  dateFormat: "Y-m-d"
});

flatpickr("#zoomtime", {
  enableTime: true,
  noCalendar: true,
  dateFormat: "H:i",
});

