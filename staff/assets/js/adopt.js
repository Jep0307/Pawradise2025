document.addEventListener('DOMContentLoaded', () => {
  const adoptDisplay = document.getElementById('adoptDisplay');

  fetch('../assets/php/get_pet.php')
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data)) {
        data.forEach(pet => {
          const petCard = document.createElement('div');
          petCard.classList.add('pet-card');
          petCard.innerHTML = `
            <img src="../assets/uploads/${pet.image}" alt="${pet.name}" style="width:100px;height:100px;" />
            <h3>${pet.name}</h3>
            <p><strong>Sex:</strong> ${pet.sex}</p>
            <p><strong>Location:</strong> ${pet.location}</p>
            <p>${pet.description}</p>
          `;
          adoptDisplay.appendChild(petCard);
        });
      } else {
        adoptDisplay.innerHTML = '<p>No pets available at the moment.</p>';
      }
    })
    .catch(err => {
      console.error("Error loading pets:", err);
      adoptDisplay.innerHTML = '<p>Failed to load pets.</p>';
    });
});
