// Function for filtering cards categories
function filterCards(category) {
  const cards = document.querySelectorAll(".cards");
  const buttons = document.querySelectorAll(".categories button");
  
  // Highlight the active button
  buttons.forEach((button) => {
    button.classList.remove("active");
  });
  document.querySelector(`#${category === 'all' ? 'allBtn' : category === 'cat' ? 'CatsBtn' : 'DogsBtn'}`).classList.add("active");
  
  // Show or hide cards based on the category
  cards.forEach((card) => {
    const cardCategory = card.getAttribute("data-category");
    if (category === "all" || cardCategory === category) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });

  closePreview();
  closePetApplicationForm();
}

function showPreview(card) {
  const modal = document.getElementById("previewModal");
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
  closePetApplicationForm();
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

// Function for display pet application form
function showApplicationForm() {
  const modalTitle = document.getElementById("modalTitle").textContent;
  document.getElementById("petName").value = modalTitle;
  document.getElementById("applicationForm").style.display = "flex";
  document.getElementById("previewModal").style.display = "none";

  spaceOnTop();
}

// function to close pet application form
function closePetApplicationForm() {
  document.getElementById("applicationForm").style.display = "none";
}

// function for pet application form
// including the validations
const form = document.getElementById('adoptionForm');
const fileInputs = form.querySelectorAll('input[type="file"]');
const textInputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');

// --- Custom Select Handling ---
const customSelectContainers = document.querySelectorAll('.custom-select-container');
customSelectContainers.forEach(container => {
  const select = container.querySelector('.custom-select');
  const options = container.querySelector('.custom-options');
  const selected = select.querySelector('.selected');

  select.addEventListener('click', () => {
    options.classList.toggle('hidden');
  });

  options.querySelectorAll('li').forEach(option => {
    option.addEventListener('click', () => {
      selected.textContent = option.textContent;
      selected.dataset.value = option.dataset.value;
      options.classList.add('hidden');

      const error = container.nextElementSibling;
      if (error && error.classList.contains('error-message')) {
        error.textContent = '';
      }
    });
  });
});

// Real-time input validation clearing
[...textInputs, ...fileInputs].forEach(input => {
  const errorMessage = document.getElementById(input.id + 'Error');
  const eventType = input.type === 'file' ? 'change' : 'input';
  input.addEventListener(eventType, () => {
    if (input.type === 'file' && input.files.length > 0) {
      errorMessage.textContent = '';
    } else if (input.value.trim() !== '') {
      errorMessage.textContent = '';
    }
  });
});

// Form submit
form.addEventListener('submit', (e) => {
  e.preventDefault();
  let valid = true;

  // --- Validate text/email/tel inputs ---
  textInputs.forEach(input => {
    const value = input.value.trim();
    const errorMessage = document.getElementById(input.id + 'Error');
    if (!value && errorMessage) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    }
  });

  // --- Validate file inputs ---
  fileInputs.forEach(input => {
    const errorMessage = document.getElementById(input.id + 'Error');
    if (!input.files.length && errorMessage) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    }
  });

  // --- Validate custom selects ---
  customSelectContainers.forEach(container => {
    const selected = container.querySelector('.selected');
    const value = selected?.dataset?.value || selected?.textContent?.trim();
    const errorMessage = container.nextElementSibling;

    if (errorMessage && errorMessage.classList.contains('error-message')) {
      if (!value || value === 'Select') {
        errorMessage.textContent = 'Please select an option.';
        valid = false;
      } else {
        errorMessage.textContent = '';
      }
    }
  });

  if (!valid) return;

  showWarningModal();
});

console.log("hellow")

// function for Warning modal
const warningModal = document.getElementById('warningModal');
const yesButton = warningModal?.querySelector('button:last-of-type');
const noButton = warningModal?.querySelector('button:first-of-type');

yesButton?.addEventListener('click', () => {
  hideWarningModal();
  finalizeApplicationSubmission();
});

noButton?.addEventListener('click', () => {
  hideWarningModal();
});

// Function to show warning modal
function showWarningModal() {
  warningModal.style.display = 'flex';
}

// Function to hide warning modal
function hideWarningModal() {
  warningModal.style.display = 'none';
}

// Function for pet application submmission
function finalizeApplicationSubmission() {
  const formData = new FormData(form);
  
  const applicantData = {};
  textInputs.forEach(input => {
    applicantData[input.name] = input.value.trim();
  });

  const fileData = {};
  fileInputs.forEach(input => {
    fileData[input.name] = input.files[0]?.name;
  });

  const dropdownData = {};
  customSelectContainers.forEach(container => {
    const label = container.querySelector('.selected').textContent.trim();
    const value = container.querySelector('.selected').dataset.value || label;
    dropdownData[label] = value;
  });

  // Use this if you want to ensure na may data na lumalabas or na eedit.
  // console.log('Form submission preview:');
  // console.log({ applicantData, fileData, dropdownData });

  closePetApplicationForm();
}

function searchInput() {
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
        const card = item.closest('.cards');
  
        if (text.includes(searchValue)) {
          card.style.display = 'block';
          found = true;
          closePreview();
          closePetApplicationForm();
          filterCards('all');
        } else {
          card.style.display = 'none';
        }
      });
  
      if (!found) {
        noFound.textContent = "No results found";
        closePreview()
        closePetApplicationForm()
      } else {
        noFound.textContent = "";
      }
    }
  });
}
searchInput();


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

