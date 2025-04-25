document.addEventListener('DOMContentLoaded', () => {
  const adoptDisplay = document.getElementById('adoptDisplay');

  // Fetch and display pets
  fetch('../assets/php/get_pet.php')
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data)) {
        data.forEach(pet => {
          const petCard = document.createElement('div');
          petCard.classList.add('pet-card');

          // Save pet.type and pet.location in dataset for filtering
          petCard.dataset.type = pet.type;
          petCard.dataset.location = pet.location;

          petCard.innerHTML = `
            <img src="../assets/uploads/${pet.image}" alt="${pet.name}" style="width:100px;height:100px;" />
            <h3>${pet.name}</h3>
            <p>${pet.sex}</p>
            <p>${pet.location}</p>
            <p>${pet.description}</p>
          `;
          adoptDisplay.appendChild(petCard);
        });

        setupCustomFilters(); // Hook filters after cards are rendered
      } else {
        adoptDisplay.innerHTML = '<p>No pets available at the moment.</p>';
      }
    })
    .catch(err => {
      console.error("Error loading pets:", err);
      adoptDisplay.innerHTML = '<p>Failed to load pets.</p>';
    });
});

function setupCustomFilters() {
  const petSelected = document.querySelector('.pet-selected');
  const locSelected = document.querySelector('.loc-selected');

  if (!petSelected || !locSelected) return;

  const filterPets = () => {
    const selectedType = petSelected.dataset.value;
    const selectedLocation = locSelected.dataset.value;

    document.querySelectorAll('.pet-card').forEach(card => {
      const matchType = !selectedType || card.dataset.type === selectedType;
      const matchLoc = !selectedLocation || card.dataset.location === selectedLocation;

      card.style.display = (matchType && matchLoc) ? '' : 'none';
    });
  };

  // Listen to option selection from both dropdowns
  document.querySelectorAll('.cate-options li').forEach(option => {
    option.addEventListener('click', () => {
      setTimeout(filterPets, 10); // delay to let dataset.value update
    });
  });
}
