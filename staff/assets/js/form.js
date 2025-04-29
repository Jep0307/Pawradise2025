document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('petFormModal');
  const openFormBtn = document.getElementById('openFormBtn');
  const cancelBtn = document.getElementById('cancelButton');
  const formTitle = document.getElementById('formTitle');
  const imageInput = document.getElementById('imageInput');
  const form = document.getElementById('addPetForm');
  const adoptList = document.getElementById('adoptList');

  const searchInput = document.querySelector('.search-input');

  document.getElementById('searchBtn').addEventListener('click', () => {
    const keyword = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('#adoptList .row');

    rows.forEach(row => {
      const name = row.dataset.name;
      const description = row.dataset.description;
      const location = row.dataset.location;
      const isMatch = name.includes(keyword) || description.includes(keyword) || location.includes(keyword);
      row.style.display = isMatch ? 'grid' : 'none';
    });
  });


  // Escape function to prevent XSS
  function escapeHTML(str) {
    if (!str) return ''; // Return an empty string if the input is null or undefined
    return str.replace(/[&<>"']/g, m => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    }[m]));
  }


  // ✅ Open modal when "Add a pet" is clicked
  openFormBtn.addEventListener('click', () => {
    form.reset();
    delete form.dataset.editingId;
    formTitle.textContent = 'Add a Pet';
    imageInput.required = true;
    modal.style.display = 'flex';
  });

  // ✅ Close modal on cancel
  cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  // ✅ Close modal when clicking outside the form
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  document.getElementById("openFormBtn").addEventListener("click", () => {
    document.getElementById("petFormModal").style.display = "flex";
  });

  document.getElementById("cancelButton").addEventListener("click", () => {
    document.getElementById("petFormModal").style.display = "none";
  });


  // ✅ Submit form (Add or Edit)
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const formData = new FormData(form);
    const editingId = form.dataset.editingId;

    if (editingId) {
      formData.append('editingId', editingId);
    }

    fetch('../assets/php/add_pet.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        if (data.success) {
          alert(editingId ? 'Pet updated!' : 'Pet added!');
          form.reset();
          delete form.dataset.editingId;
          modal.style.display = 'none';
          loadPets(); // reload after update
        } else {
          alert('Something went wrong.');
        }
      })
      .catch(() => {
        submitBtn.disabled = false;
        alert('Something went wrong.');
      });
  });

  // ✅ Load pets from DB and show them
  function loadPets(search = '') {
    const url = search ? `../assets/php/get_pets.php?search=${encodeURIComponent(search)}` : '../assets/php/get_pets.php';

    fetch(url)
      .then(res => res.json())
      .then(data => {
        adoptList.innerHTML = '';

        if (data.length === 0) {
          adoptList.innerHTML = '<div style="padding: 1rem;">No pets found.</div>';
          return;
        }

        data.forEach(pet => {
          adoptList.innerHTML += `
              <div class="row" data-name="${pet.name.toLowerCase()}" 
                data-description="${pet.description.toLowerCase()}"
                data-location="${pet.location.toLowerCase()}">
                <div class="cols">${pet.id}</div>
                <div class="cols"><img src="../assets/uploads/${escapeHTML(pet.image)}" class="pet-image" /></div>
                <div class="cols">${escapeHTML(pet.name)}</div>
                <div class="cols">${escapeHTML(pet.sex)}</div>
                <div class="cols">${escapeHTML(pet.location)}</div>
                <div class="cols">${escapeHTML(pet.description)}</div>
                <div class="cols">${escapeHTML(pet.created_at)}</div>
                <div class="cols">
                  <button onclick="editPet(${pet.id})">Edit</button>
                  <button onclick="deletePet(${pet.id})">Delete</button>
                </div>
              </div>
            `;
          });
        })
        .catch(err => {
          console.error('Error loading pets:', err);
        });
    }
  
  
    searchInput.addEventListener('input', () => {
      const keyword = searchInput.value.trim().toLowerCase();
      const rows = document.querySelectorAll('#adoptList .row');
    
      rows.forEach(row => {
        const name = row.dataset.name;
        const description = row.dataset.description;
        const location = row.dataset.location;
    
        if (name.includes(keyword) || description.includes(keyword) || location.includes(keyword)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
    
  
  
  // ✅ Edit handler
  window.editPet = function (id) {
    fetch(`../assets/php/get_pet.php?id=${id}`)
      .then(res => res.json())
      .then(pet => {
        if (!pet.id) {
          alert("Failed to load pet data.");
          return;
        }

        form.name.value = pet.name;
        form.sex.value = pet.sex;
        form.type.value = pet.type;
        form.age.value = pet.age;
        form.breed.value = pet.breed;
        form.location.value = pet.location;
        form.description.value = pet.description;
        form.type.value = pet.type;
        form.dataset.editingId = pet.id;
        imageInput.removeAttribute('required');
        formTitle.textContent = 'Edit Pet';
        modal.style.display = 'flex';
      });
  };

  // ✅ Delete handler
  window.deletePet = function (id) {
    if (confirm("Are you sure you want to delete this pet?")) {
      fetch(`../assets/php/delete_pet.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            loadPets();
          } else {
            alert("Failed to delete pet.");
          }
        });
    }
  };

  // Load pets initially
  loadPets();
});
