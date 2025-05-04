import { supabase } from "../../../admin/assets/js/auth/config.js";

function editProfile() {
    const profileInputs = document.querySelectorAll('.profile-form input');
    const editButton = document.getElementById('editBtn');
    const saveButton = document.getElementById('saveBtn');
    const warningModal = document.getElementById('warningModal');
    const confirmButton = document.getElementById('confirmSave');
    const cancelButton = document.getElementById('cancelSave');

    editButton.addEventListener("click", () => {
        profileInputs.forEach(input => {
            // Keep email field disabled, enable everything else
            input.disabled = input.name === "email";
        });
        saveButton.style.display = 'flex';
        editButton.style.display = 'none';
    });

    saveButton.addEventListener("click", () => {
        warningModal.classList.add('warning-modal-visible');
    });

    confirmButton.addEventListener('click', () => {
        saveProfile();
        warningModal.classList.remove('warning-modal-visible');
    });

    cancelButton.addEventListener('click', () => {
        warningModal.classList.remove('warning-modal-visible');
    });

    async function saveProfile() {
        profileInputs.forEach(input => {
            input.disabled = true;
        });

        const password = document.querySelector('input[name="password"]').value;

        // Update Supabase password if changed
        if (password) {
            const { error: passwordError } = await supabase.auth.updateUser({
                password: password
            });

            if (passwordError) {
                console.error('Password update failed:', passwordError.message);
                return;
            }
        }

        // console.log('Profile and password updated.');
        editButton.style.display = 'flex';
        saveButton.style.display = 'none';
    }
}

editProfile();

function deleteAccount() {
    const deleteButton = document.getElementById('deleteBtn');
    const dangerModal = document.getElementById('dangerModal');
    const confirmButton = document.getElementById('confirmDeletion');
    const cancelButton = document.getElementById('cancelDeletion');

    deleteButton.addEventListener("click", () => {
        dangerModal.classList.add('danger-modal-visible');
    });

    confirmButton.addEventListener('click', () => {
        deleteAccountPermanently();
        dangerModal.classList.remove('danger-modal-visible');
    });

    cancelButton.addEventListener('click', () => {
        dangerModal.classList.remove('danger-modal-visible');
    });

    function deleteAccountPermanently(){
        console.log("Account deleted successfully!");

        // Your may add the function here for backend on how this account should be deleted.
        //...
        //...
    }
}

deleteAccount();