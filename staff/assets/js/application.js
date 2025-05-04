document.addEventListener('DOMContentLoaded', () => {
  const applicationList = document.getElementById('applicationList');

  function loadApplications() {
    fetch('../assets/php/get_applications.php')
      .then(res => res.json())
      .then(data => {
        applicationList.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
          data.forEach(application => {
            applicationList.innerHTML += `
              <tr>
                <td>${application.id}</td>
                <td>${escapeHTML(application.fullname)}</td>
                <td>${escapeHTML(application.email)}</td>
                <td>${escapeHTML(application.contactno)}</td>
                <td>${escapeHTML(application.birthdate)}</td>
                <td>${escapeHTML(application.occupation)}</td>
                <td>${escapeHTML(application.address)}</td>
                <td>${escapeHTML(application.social)}</td>
                <td>${escapeHTML(application.rent)}</td>
                <td>${escapeHTML(application.residence_type)}</td>
                <td>${escapeHTML(application.pet_type)}</td>
                <td>${escapeHTML(application.zoomdate)}</td>
                <td>${escapeHTML(application.zoomtime)}</td>
                <td>${escapeHTML(application.shelter_visit)}</td>
                <td>${escapeHTML(application.created_at)}</td>
              </tr>
            `;
          });
        } 
      })
      .catch(err => {
        console.error('Error loading applications:', err);
        applicationList.innerHTML = '<div>No applications found.</div>';
        // applicationList.innerHTML = '<div class>Error loading applications. Please try again later.</div>';
      });
  }

  loadApplications();
});
