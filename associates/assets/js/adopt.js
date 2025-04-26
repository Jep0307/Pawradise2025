document.addEventListener('DOMContentLoaded', () => {
  const petContainer = document.getElementById('petsContainer');

  fetch('/SIA02/Pawradise2025/staff/assets/php/get_pet.php')
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
          
          petCard.innerHTML = `
            <img loading="lazy" src="/SIA02/Pawradise2025/staff/assets/uploads/${pet.image}" alt="${pet.name}" />
            <div class="pet-info">
              <p class="pet-name">${pet.name}</p>
              <p>${pet.sex}</p>
              <p class="location">${pet.location}</p>
              <p class="description">${pet.description}</p>
            </div>
          `;
          petContainer.appendChild(petCard);
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
