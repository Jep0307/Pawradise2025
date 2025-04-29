document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('applicationFormModal');
  const openFormBtn = document.getElementById('openFormBtn');
  const cancelBtn = document.getElementById('cancelButton');
  const formTitle = document.getElementById('formTitle');
  const form = document.getElementById('addApplicationForm');
  const applicationList = document.getElementById('applicationList');

  const searchInput = document.querySelector('.search-input');

  document.getElementById('searchBtn').addEventListener('click', () => {
    const keyword = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('#applicationList .row');
  
    rows.forEach(row => {
      const name = row.dataset.name;
      const email = row.dataset.email;
      const status = row.dataset.status;
      const isMatch = name.includes(keyword) || email.includes(keyword) || status.includes(keyword);
      row.style.display = isMatch ? 'grid' : 'none';
    });
  });

  function escapeHTML(str) {
    return str.replace(/[&<>"']/g, m => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    }[m]));
  }

  openFormBtn.addEventListener('click', () => {
    form.reset();
    delete form.dataset.editingId;
    formTitle.textContent = 'Add an Application';
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

    fetch('../assets/php/add_application.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        if (data.success) {
          alert(editingId ? 'Application updated!' : 'Application added!');
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

  function loadApplications(search = '') {
    const url = search ? `../assets/php/get_applications.php?search=${encodeURIComponent(search)}` : '../assets/php/get_applications.php';
    
    fetch(url)
      .then(res => res.json())
      .then(data => {
        applicationList.innerHTML = '';

        if (data.length === 0) {
          applicationList.innerHTML = '<div style="padding: 1rem;">No applications found.</div>';
          return;
        }

        data.forEach(application => {
          applicationList.innerHTML += `
            <div class="row" data-name="${application.name.toLowerCase()}"
              data-email="${application.email.toLowerCase()}"
              data-status="${application.status.toLowerCase()}">
              <div class="cols">${application.id}</div>
              <div class="cols">${escapeHTML(application.name)}</div>
              <div class="cols">${escapeHTML(application.email)}</div>
              <div class="cols">${application.date || '—'}</div>
              <div class="cols">${escapeHTML(application.status)}</div>
              <div class="cols">
                <button onclick="editApplication(${application.id})">Edit</button>
                <button onclick="deleteApplication(${application.id})">Delete</button>
              </div>
            </div>
          `;
        });
      })
      .catch(err => {
        console.error('Error loading applications:', err);
      });
  }

  searchInput.addEventListener('input', () => {
    const keyword = searchInput.value.trim().toLowerCase();
    const rows = document.querySelectorAll('#applicationList .row');
  
    rows.forEach(row => {
      const name = row.dataset.name;
      const email = row.dataset.email;
      const status = row.dataset.status;
  
      if (name.includes(keyword) || email.includes(keyword) || status.includes(keyword)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  window.editApplication = function (id) {
    fetch(`../assets/php/get_application.php?id=${id}`)
      .then(res => res.json())
      .then(application => {
        if (!application.id) {
          alert("Failed to load application data.");
          return;
        }

        form.name.value = application.name;
        form.email.value = application.email;
        form.description.value = application.description;
        form.status.value = application.status;
        form.dataset.editingId = application.id;
        formTitle.textContent = 'Edit Application';
        modal.style.display = 'flex';
      });
  };

  window.deleteApplication = function (id) {
    if (confirm("Are you sure you want to delete this application?")) {
      fetch(`../assets/php/delete_application.php?id=${id}`)
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
