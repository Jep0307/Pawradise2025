// Preview Modal Functions
function showPreview(card) {
  const modal = document.getElementById("previewModal");
  const image = card.querySelector("img").src;
  const title = card.querySelector(".pet-info p:first-child").textContent;
  const age = card.querySelector(".pet-info p:nth-child(2)").textContent;
  const sex = card.querySelector(".pet-info p:nth-child(3)").textContent;
  const breed = card.querySelector(".pet-info p:nth-child(4)").textContent;
  const location = card.querySelector(".pet-info p:nth-child(5)").textContent;
  const description = card.querySelector(".pet-info .description").textContent;

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
  flatpickr("#zoomtime", { enableTime: true, noCalendar: true, dateFormat: "H:i" });

  spaceOnTop();
}

function closePetApplicationForm() {
  document.getElementById("applicationForm").style.display = "none";
}

// Pet Application Form Handling
const form = document.getElementById('adoptionForm');



// Form submit
let confirmHandler; // store globally so we can remove it if needed

form.addEventListener('submit', (e) => {
  e.preventDefault();
  let valid = true;

  const requiredInputs = ['fullname', 'email', 'contactno', 'birthdate', 'occupation', 'address', 'zoomdate', 'zoomtime'];
  requiredInputs.forEach(id => {
    const input = document.getElementById(id);
    const errorMessage = document.getElementById(id + 'Error');
    if (!input.value.trim()) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    } else {
      errorMessage.textContent = '';
    }
  });

  const selectIds = ['rent', 'residenceType', 'petType', 'shelterVisit'];
  selectIds.forEach(id => {
    const select = document.getElementById(id);
    const errorMessage = document.getElementById(id + 'Error');
    if (!select.value.trim()) {
      errorMessage.textContent = 'Please select an option.';
      valid = false;
    } else {
      errorMessage.textContent = '';
    }
  });

  const fileIds = ['validid', 'housepicFront', 'housepicLiving', 'housepicYard'];
  fileIds.forEach(id => {
    const input = document.getElementById(id);
    const errorMessage = document.getElementById(id + 'Error');
    if (!input.files.length) {
      errorMessage.textContent = 'This field is required.';
      valid = false;
    } else {
      errorMessage.textContent = '';
    }
  });

  if (valid) {
    showWarningModal(finalizeApplicationSubmission);
  }
});

function showWarningModal(onConfirm) {
  const modal = document.getElementById('warningModal');
  const confirmBtn = modal.querySelector('button:last-of-type');
  const cancelBtn = modal.querySelector('button:first-of-type');

  // Clear old handler if exists
  if (confirmHandler) {
    confirmBtn.removeEventListener('click', confirmHandler);
  }

  confirmHandler = () => {
    modal.style.display = 'none';
    onConfirm();
  };

  confirmBtn.addEventListener('click', confirmHandler);

  cancelBtn.onclick = () => {
    modal.style.display = 'none';
  };

  modal.style.display = 'flex';
}

function finalizeApplicationSubmission() {
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');
  submitBtn.disabled = true;

  fetch('../php/add_applications.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(response => {
    console.log(response);
    alert('Application submitted successfully!');
  })
  .catch((err) => {
    console.error('Something went wrong:', err);
    submitBtn.disabled = false;
  });
}



// HTML Escape
function escapeHTML(str) {
  if (!str) return '';
  return str.replace(/[&<>"']/g, m => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
  }[m]));
}

// Search Input Functionality
function searchInput() {
  const searchInput = document.getElementById('searchInput');

  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();

      const query = escapeHTML(searchInput.value.trim().toLowerCase());
      const cards = document.querySelectorAll('.cards-container .cards');
      const noResultText = document.getElementById('noResultFound');
      let visibleCount = 0;

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
        cards.forEach(card => card.style.display = 'block');
        noResultText.textContent = '';
      } else {
        noResultText.textContent = visibleCount === 0 ? 'No result found for: ' + query : '';
      }

      closePreview();
      closePetApplicationForm();
    }
  });
}

searchInput();

// Dropdown Filters
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

// Filter Pets based on dropdown selection
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

// Categories Menu Toggle
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
