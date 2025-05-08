document.addEventListener('DOMContentLoaded', () => {
  const petContainer = document.getElementById('petsContainer');

  fetch('../../shared_components/pets/get_pet.php')
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data)) {
        data.forEach(pet => {
          const petCard = document.createElement('div');
          petCard.classList.add('cards');
          petCard.setAttribute('onclick', 'showPreview(this)');
          
          // Add data-type and data-location for filtering
          petCard.setAttribute('data-type', pet.type);
          petCard.setAttribute('data-location', pet.location);
          petCard.setAttribute('data-breed', pet.breed);
          
          petCard.innerHTML = `
            <img loading="lazy" src="../../uploads/${pet.image}" alt="${pet.name}" />
            <div class="pet-info">
              <p class="pet-name">${pet.name}</p>
              <p class="breed">${pet.breed}</p>
              <p>${pet.sex}</p>
              <p class="age">${pet.age}</p>
              <p class="location">${pet.location}</p>
              <p class="description">${pet.description}</p>
            </div>
          `;
          petContainer.insertBefore(petCard, petContainer.firstChild);
        });
      } else {
        petContainer.innerHTML = '<p>No pets available at the moment.</p>';
      }
    })
    .catch(err => {
      console.error("Error loading pets:", err);
      petContainer.innerHTML = '<p>Failed to load pets.</p>';
    });
});
