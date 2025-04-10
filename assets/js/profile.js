function editProfile() {
    const profileInputs = document.querySelectorAll('.profile-form input');
    const editButton = document.getElementById('editBtn');
    const warningModal = document.getElementById('warningModal');
    const confirmButton = document.getElementById('confirmSave');
    const cancelButton = document.getElementById('cancelSave');

    editButton.addEventListener("click", () => {
        if (editButton.textContent === 'Save') {
            // Show the warning modal
            warningModal.classList.add('warning-modal-visible');
            // Handle confirm save
            confirmButton.addEventListener('click', () => {
                saveProfile();
                warningModal.classList.remove('warning-modal-visible');
            });

            // Handle cancel save
            cancelButton.addEventListener('click', () => {
                warningModal.classList.remove('warning-modal-visible');
            });
        } else {
            // Enable inputs for editing
            profileInputs.forEach(input => {
                input.disabled = false;
                input.focus();
            });
            editButton.textContent = 'Save';
            editButton.style.backgroundColor = '#cd9b44';
            editButton.classList.remove('hover-edit');
            editButton.classList.add('hover-save');
        }
    });

    function saveProfile() {
        // Disable inputs and save data
        profileInputs.forEach(input => {
            input.disabled = true;
        });
        const jsonData = JSON.stringify({
            firstname: document.querySelector('input[name="firstname"]').value,
            lastname: document.querySelector('input[name="lastname"]').value,
            email: document.querySelector('input[name="email"]').value,
            number: document.querySelector('input[name="number"]').value,
            location: document.querySelector('input[name="location"]').value
        });
        console.log('JSON:', jsonData);
        editButton.textContent = "Edit";
        editButton.style.backgroundColor = 'transparent';
        editButton.classList.remove('hover-save');
        editButton.classList.add('hover-edit');
    }
}

editProfile();