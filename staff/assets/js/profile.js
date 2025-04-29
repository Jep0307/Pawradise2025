document.addEventListener('DOMContentLoaded', function () {
    fetch('../assets/php/get_profile.php')
      .then(response => response.json())
      .then(data => {
        // Directly populate the profile fields
        document.getElementById('profileName').textContent = data.name || 'N/A';
        document.getElementById('profileEmail').textContent = data.email || 'N/A';
        document.getElementById('profilePhone').textContent = data.phone || 'N/A';
        document.getElementById('profileAddress').textContent = data.address || 'N/A';
      })
      .catch(error => {
        console.error('Error loading profile:', error);
      });
  });
  