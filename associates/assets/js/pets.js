function showPreview(card) {
  const modal = document.getElementById("previewModal");
  const image = card.querySelector("img").src;
  const title = card.querySelector(".pet-info p:first-child").textContent;
  const age = card.querySelector(".pet-info p:nth-child(2)").textContent;
  const sex = card.querySelector(".pet-info p:nth-child(3)").textContent;
  const breed = card.querySelector(".pet-info p:nth-child(4)").textContent;
  const location = card.querySelector(".pet-info p:nth-child(5)").textContent;
  const description = card.querySelector(".pet-info .description").textContent;

  // Convert the location to a link
  const locationLink = `<a href="https://www.google.com/maps/search/?q=${encodeURIComponent(location)}" target="_blank">${location}</a>`;

  // Update modal content
  document.getElementById("modalImage").src = image;
  document.getElementById("modalTitle").textContent = title;
  document.getElementById("modalPetSex").textContent = sex;
  document.getElementById("modalPetAge").textContent = age;
  document.getElementById("modalPetBreed").textContent = breed;
  document.getElementById("modalPetLocation").innerHTML = locationLink;  // Use innerHTML to add the link
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

  const offset = 88;
  const modalTop = modal.getBoundingClientRect().top + window.scrollY - offset;

  window.scrollTo({
    top: modalTop,
    behavior: "instant",
  });
}

function showApplicationForm() {
  const modalTitle = document.getElementById("modalTitle").textContent;
  document.getElementById("petName").value = modalTitle;
  document.getElementById("applicationForm").style.display = "flex";
  document.getElementById("previewModal").style.display = "none";

  flatpickr("#birthdate", { enableTime: false, dateFormat: "Y-m-d" });
  flatpickr("#zoomdate", { enableTime: false, dateFormat: "Y-m-d" });
  flatpickr("#zoomtime", { enableTime: true, noCalendar: true, dateFormat: "H:i", });

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

// Custom Select Handling
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

  // Validate text/email/tel inputs
  textInputs.forEach(input => {
    const value = input.value.trim();
    const errorMessage = document.getElementById(input.id + 'Error');
    if (!value && errorMessage) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    }
  });

  // Validate file inputs
  fileInputs.forEach(input => {
    const errorMessage = document.getElementById(input.id + 'Error');
    if (!input.files.length && errorMessage) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    }
  });

  // Validate custom selects
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

  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();

      const query = searchInput.value.trim().toLowerCase();
      const cards = document.querySelectorAll('.cards-container .cards');
      const noResultText = document.getElementById('noResultFound');
      let visibleCount = 0;

      // Reset dropdowns
      const petSelected = document.querySelector('.pet-selected');
      const locSelected = document.querySelector('.loc-selected');

      if (petSelected) {
        petSelected.textContent = 'Pet';
        petSelected.dataset.value = '';
      }
      if (locSelected) {
        locSelected.textContent = 'Location';
        locSelected.dataset.value = '';
      }

      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const type = (card.dataset.type || '').toLowerCase();
        const location = (card.dataset.location || '').toLowerCase();

        const combinedSearchable = `${text} ${type} ${location}`;

        if (combinedSearchable.includes(query)) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (query === '') {
        // If search is empty, show all cards
        cards.forEach(card => card.style.display = 'block');
        noResultText.textContent = '';
      } else {
        noResultText.textContent = visibleCount === 0 ? 'No result found' : '';
      }

      closePreview();
      closePetApplicationForm();
    }
  });
}

searchInput();


searchInput();

function dropdownLocationAndPet() {
  const customSelectContainers = document.querySelectorAll('.cate-select-container');

  // Close dropdowns when clicking anywhere outside
  document.addEventListener('click', (e) => {
    customSelectContainers.forEach(container => {
      const options = container.querySelector('.cate-options');
      const icon = container.querySelector('.cate-select img');

      // Close dropdown if the click is outside the container
      if (!container.contains(e.target)) {
        options.classList.add('hidden');
        if (icon) icon.src = "/SIA02/Pawradise2025/associates/assets/imgs/chevron-down-icon.svg";
      }
    });
  });

  // Loop through each custom select container
  customSelectContainers.forEach(container => {
    const select = container.querySelector('.cate-select');
    const selectIcon = container.querySelector('.cate-select img');
    const options = container.querySelector('.cate-options');
    const selected = select.querySelector('.loc-selected, .pet-selected, .breed-selected');

    // When the select is clicked, toggle the dropdown
    select.addEventListener('click', (e) => {
      e.stopPropagation();

      // Close all other dropdowns first
      customSelectContainers.forEach(c => {
        if (c !== container) {
          const otherOptions = c.querySelector('.cate-options');
          const otherIcon = c.querySelector('.cate-select img');
          otherOptions.classList.add('hidden');
          if (otherIcon) otherIcon.src = "/SIA02/Pawradise2025/associates/assets/imgs/chevron-down-icon.svg";
        }
      });

      // Toggle this dropdown
      options.classList.toggle('hidden');
      selectIcon.src = options.classList.contains('hidden') 
        ? "/SIA02/Pawradise2025/associates/assets/imgs/chevron-down-icon.svg" 
        : "/SIA02/Pawradise2025/associates/assets/imgs/chevron-up-icon.svg";
    });

    // Handle option selection
    options.querySelectorAll('li').forEach(option => {
      option.addEventListener('click', (e) => {
        e.stopPropagation();

        selected.textContent = option.textContent;
        selected.dataset.value = option.dataset.value;
        options.classList.add('hidden');
        selectIcon.src = "/SIA02/Pawradise2025/associates/assets/imgs/chevron-down-icon.svg";

        filterPets();
      });
    });
  });
}


dropdownLocationAndPet();

function filterPets() {
  const type = document.querySelector('.pet-selected')?.dataset.value;
  const location = document.querySelector('.loc-selected')?.dataset.value;
  const breed = document.querySelector('.breed-selected')?.dataset.value;
  const cards = document.querySelectorAll('.cards');
  const noResult = document.getElementById('noResultFound');

  let count = 0;

  cards.forEach(card => {
    const matches =
      (!type || card.dataset.type === type) &&
      (!location || card.dataset.location === location) &&
      (!breed || card.dataset.breed === breed);

    card.style.display = matches ? 'block' : 'none';
    if (matches) count++;
  });

  noResult.textContent = count === 0 ? 'No result found' : '';

  closePreview();
  closePetApplicationForm();
}

// Function to toggle the menu
function toggleCategories() {
  const filterIcon = document.getElementById('filterIcon');
  const categories = document.getElementById('categories');
  const closeCateBtn = document.getElementById('closeCateBtn');

 filterIcon.addEventListener('click', () => {
  categories.classList.add('active');
  document.body.style.overflow = 'hidden'; // Prevent scrolling
 });

 closeCateBtn.addEventListener('click', () => {
  categories.classList.remove('active');
  document.body.style.overflow = '';
 });
 
}


toggleCategories();
