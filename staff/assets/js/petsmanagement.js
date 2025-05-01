document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('petFormModal');
    const openFormBtn = document.getElementById('openFormBtn');
    const cancelBtn = document.getElementById('cancelButton');
    const formTitle = document.getElementById('formTitle');
    const imageInput = document.getElementById('imageInput');
    const form = document.getElementById('addPetForm');
    const adoptList = document.getElementById('adoptList');
    const searchInput = document.querySelector('.search-input');

    function search() {
        const input = document.querySelector('.search-input');
    
        input.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            e.preventDefault();
    
            const keyword = escapeHTML(input.value.trim().toLowerCase());
            const tbody = document.getElementById('adoptList');
            const allRows = tbody.querySelectorAll('tr');
            let found = 0;
    
            // Remove existing no-result row before filtering
            const noResultRow = document.getElementById('noResultRow');
            if (noResultRow) noResultRow.remove();
    
            allRows.forEach(row => {
                if (row.id === 'noResultRow') return;
    
                const {
                    id = '', age = '', type = '', breed = '',
                    name = '', description = '', location = ''
                } = row.dataset;
    
                const values = [id, age, type, breed, name, description, location];
                const match = keyword === '' || values.some(val => val.toLowerCase().includes(keyword));
    
                row.style.display = match ? '' : 'none';
                if (match) found++;
            });
    
            if (found === 0) {
                const row = document.createElement('tr');
                row.id = 'noResultRow';
                row.innerHTML = `
                    <td colspan="11" style="text-align:center; padding:16px;">No results found.</td>`;
                tbody.appendChild(row);
            }
        });
    }

    search();

    function dropdownSelection() {
        const containers = document.querySelectorAll('.custom-select-container1');

        // Close all dropdowns by default
        containers.forEach(container => {
            container.querySelector('.custom-options1').classList.add('hidden1');
        });

        document.addEventListener('click', (e) => {
            containers.forEach(container => {
                const options = container.querySelector('.custom-options1');
                const arrowIcon = container.querySelector('.custom-select1 img');
                if (!container.contains(e.target)) {
                    options.classList.add('hidden1');
                    arrowIcon.src = '../assets/imgs/chevron-down-icon.svg';
                }
            });
        });

        containers.forEach(container => {
            const select = container.querySelector('.custom-select1');
            const options = container.querySelector('.custom-options1');
            const selected = select.querySelector('.selected1');
            const arrowIcon = select.querySelector('img');

            select.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close all other dropdowns
                containers.forEach(other => {
                    if (other !== container) {
                        other.querySelector('.custom-options1').classList.add('hidden1');
                        other.querySelector('.custom-select1 img').src = '../assets/imgs/chevron-down-icon.svg';
                    }
                });

                // Toggle current dropdown
                const isHidden = options.classList.toggle('hidden1');
                arrowIcon.src = isHidden
                    ? '../assets/imgs/chevron-down-icon.svg'
                    : '../assets/imgs/chevron-up-icon.svg';
            });

            options.querySelectorAll('li').forEach(option => {
                option.addEventListener('click', () => {
                    selected.textContent = option.textContent;
                    selected.dataset.value = option.dataset.value;
                    options.classList.add('hidden1');
                    arrowIcon.src = '../assets/imgs/chevron-down-icon.svg';
                });
            });
        });
    }



    dropdownSelection();

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
                                <td><img src="../assets/uploads/${escapeHTML(pet.image)}" class="pet-image" /></td>
                                <td>${escapeHTML(pet.name)}</td>
                                <td>${escapeHTML(pet.sex)}</td>
                                <td>${escapeHTML(pet.type)}</td>
                                <td>${escapeHTML(pet.age)}</td>
                                <td>${escapeHTML(pet.breed)}</td>
                                <td>${escapeHTML(pet.location)}</td>
                                <td>${escapeHTML(pet.description)}</td>
                                <td>${escapeHTML(pet.created_at)}</td>
                                <td>
                                    <button class="edit-btn" onclick="editPet(${pet.id})">Edit</button>
                                    <button class="delete-btn" onclick="deletePet(${pet.id})">Delete</button>
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
