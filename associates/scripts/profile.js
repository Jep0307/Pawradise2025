async function populateProfileFields() {
    const email = localStorage.getItem('userEmail'); // Get the email from localStorage
    if (!email) {
        console.error('No email found in localStorage');
        return; // Stop if no email exists in localStorage
    }

    // If email exists, send it to the PHP script
    fetch('../php/get_profile_adopters.php?email=' + encodeURIComponent(email))
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            return; // Stop if there's an error
        }

        // Assuming data is an object instead of an array
        document.getElementById('fullname').value = data.fullname;
        document.getElementById('email').value = data.email;
        document.getElementById('number').value = data.contactno;
        document.getElementById('location').value = data.address;
    })
    .catch(err => {
        console.error('Error loading profile data:', err);
    });
}

function deleteProfile() {
    const deleteButton = document.getElementById('deleteBtn');

    if (!deleteButton) {
        console.error('Delete button not found in the DOM');
        return;
    }

    deleteButton.addEventListener("click", () => {
        if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {

            const email = localStorage.getItem('userEmail'); // Get the email from localStorage
            if (!email) {
                console.error('No email found in localStorage');
                return; // Stop if no email exists in localStorage
            }

            fetch('../php/deactivate_adopters.php?email=' + encodeURIComponent(email))
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Your account has been deleted.");
                    window.location.href = "../index.html"; // Redirect to the homepage
                } else {
                    alert("Failed to delete your account.");
                }
            })
            .catch(error => {
                console.error('Error occurred while deleting account:', error);
                alert('An error occurred. Please try again later.');
            });
        }
    });
}

window.addEventListener('DOMContentLoaded', deleteProfile); // Ensure the DOM is fully loaded before attaching event listeners

async function editProfile() {
    const profileInputs = document.querySelectorAll('.profile-form input');
    const editButton = document.getElementById('editBtn');
    const saveButton = document.getElementById('saveBtn');
    const warningModal = document.getElementById('warningModal');
    const confirmButton = document.getElementById('confirmSave');
    const cancelButton = document.getElementById('cancelSave');

    await populateProfileFields(); // <-- This is how it should be

    editButton.addEventListener("click", () => {
        profileInputs.forEach(input => {
            input.disabled = input.name === "email"; // Email stays disabled
        });
        saveButton.style.display = 'flex';
        editButton.style.display = 'none';
    });

    saveButton.addEventListener("click", () => {
        warningModal.classList.add('warning-modal-visible');
    });

    confirmButton.addEventListener('click', async () => {
        const success = await saveProfile();
        if (success) warningModal.classList.remove('warning-modal-visible');
    });

    cancelButton.addEventListener('click', () => {
        warningModal.classList.remove('warning-modal-visible');
    });

    async function saveProfile() {
        const payload = {
            email: localStorage.getItem('userEmail'), // Add email to the payload
            fullname: document.getElementById('fullname').value,
            number: document.getElementById('number').value,
            location: document.getElementById('location').value,
        };

        if (!payload.fullname || !payload.number || !payload.location) {
            alert("Please fill out all fields.");
            return false;
        }

        try {
            const res = await fetch('../php/update_profile_adopters.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await res.json();

            if (result.error) throw new Error(result.error);

            alert("Profile updated successfully!");

            profileInputs.forEach(input => {
                input.disabled = true;
            });

            editButton.style.display = 'flex';
            saveButton.style.display = 'none';
            return true;
        } catch (err) {
            console.error("Failed to save:", err.message);
            return false;
        }
    }
}

window.addEventListener('DOMContentLoaded', editProfile); // ✅ correct usage
