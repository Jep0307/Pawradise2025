document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('petFormModal');
    const openFormBtn = document.getElementById('openFormBtn');
    const cancelBtn = document.getElementById('cancelButton');
    const formTitle = document.getElementById('formTitle');
    const imageInput = document.getElementById('imageInput');
    const form = document.getElementById('addPetForm');
    const adoptList = document.getElementById('adoptList');
    const searchInput = document.querySelector('.search-input');

    function escapeHTML(str) {
        if (!str) return '';
        return str.replace(/[&<>"]|'/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m]));
    }

    openFormBtn.addEventListener('click', () => {
        form.reset();
        delete form.dataset.editingId;
        formTitle.textContent = 'Add a Pet';
        imageInput.required = true;
        modal.style.display = 'flex';
      });

    cancelBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

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
                        <tr data-id="${pet.id.toString().toLowerCase()}"
                            data-name="${pet.name.toLowerCase()}" 
                            data-description="${pet.description.toLowerCase()}"
                            data-location="${pet.location.toLowerCase()}"
                            data-age="${pet.age.toLowerCase()}"
                            data-type="${pet.type.toLowerCase()}"
                            data-breed="${pet.breed.toLowerCase()}"
                            data-sex="${pet.sex.toLowerCase()}">
                                <td>${pet.id}</td>
                                <td><img src="../../staff/assets/uploads/${escapeHTML(pet.image)}" class="pet-image" /></td>
                                <td>${escapeHTML(pet.name)}</td>
                                <td>${escapeHTML(pet.sex)}</td>
                                <td>${escapeHTML(pet.type)}</td>
                                <td>${escapeHTML(pet.age)}</td>
                                <td>${escapeHTML(pet.breed)}</td>
                                <td>${escapeHTML(pet.location)}</td>
                                <td>${escapeHTML(pet.description)}</td>
                                <td>${escapeHTML(pet.created_at)}</td>
                                <td>
                                    <button class="edit-btn1" onclick="editPet(${pet.id})">Edit</button>
                                    <button class="delete-btn1" onclick="deletePet(${pet.id})">Delete</button>
                                </td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error('Error loading pets:', err);
            });
    }

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

    loadPets();
});

