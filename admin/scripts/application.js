document.addEventListener('DOMContentLoaded', () => {
  const applicationList = document.getElementById('applicationList');
  const modal = document.getElementById('FormModal');
  const openFormBtn = document.getElementById('openFormBtn');
  const cancelBtn = document.getElementById('cancelButton');
  const imageInput = document.getElementById('imageInput');
  const form = document.getElementById('updateASForm');

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

  // openFormBtn.addEventListener('click', () => {
  //   form.reset();
  //   delete form.dataset.editingId;
  //   imageInput.required = true;
  //   modal.style.display = 'flex';
  // });

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

    fetch('../../shared_components/applications/add_applications.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        if (data.success) {
          alert('Application status updated!');
          form.reset();
          delete form.dataset.editingId;
          modal.style.display = 'none';
          loadApplications();
        } else {
          alert('Something went wrong.');
        }
      })
      .catch(() => {
        submitBtn.disabled = false;
        alert('Something went wrong.');
      });
  });

  function loadApplications() {
    fetch('../../shared_components/applications/get_applications.php')
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
                <td>${escapeHTML(application.status)}</td>
                <td>${escapeHTML(application.created_at)}</td>
                <td>
                  <button class="edit-btn1" onclick="editApplication(${application.id})">Edit</button>
                  <button class="delete-btn1" onclick="deleteApplication(${application.id})">Delete</button>
                </td>
              </tr>
            `;
          });
        } else {
          applicationList.innerHTML = '<tr><td colspan="16">No applications found.</td></tr>';
        }
      })
      .catch(err => {
        console.error('Error loading applications:', err);
        applicationList.innerHTML = '<tr><td colspan="16">Error loading applications.</td></tr>';
      });
  }

  window.editApplication = function (id) {
    fetch(`../../shared_components/applications/get_application.php?id=${id}`)
      .then(res => res.json())
      .then(application => {
        if (!application.id) {
          alert("Failed to load application data.");
          return;
        }
  
        // Populate only the status field
        form.status.value = application.status;
  
        // Store editing ID
        form.dataset.editingId = application.id;
  
        // Open the modal
        modal.style.display = 'flex';
      })
      .catch(err => {
        console.error("Failed to fetch application data:", err);
        alert("Failed to load application data.");
      });
  };
  

  window.deleteApplication = function (id) {
    if (confirm("Are you sure you want to delete this application?")) {
      fetch(`../../shared_components/applications/delete_application.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            loadApplications();
          } else {
            alert("Failed to delete application.");
          }
        });
    }
  };

  loadApplications();
});
