async function populateProfileFields() {
    try {
        const res = await fetch('');
        const data = await res.json();

        if (data.error) throw new Error(data.error);

        document.getElementById('fullname').value = data.fullname;
        document.getElementById('email').value = data.email;
        document.getElementById('number').value = data.contactno;
        document.getElementById('location').value = data.address;
    } catch (err) {
        console.error("Failed to load user data:", err.message);
    }
}

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
            fullname: document.getElementById('fullname').value,
            number: document.getElementById('number').value,
            location: document.getElementById('location').value,
        };

        if (!payload.fullname || !payload.number || !payload.location) {
            alert("Please fill out all fields.");
            return false;
        }

        try {
            const res = await fetch('../php/update_adopters.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await res.json();

            if (result.error) throw new Error(result.error);

            profileInputs.forEach(input => input.disabled = true);
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
