import { supabase } from "./auth/config.js";

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
        const firstname = document.querySelector('input[name="firstname"]').value;
        const lastname = document.querySelector('input[name="lastname"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const number = document.querySelector('input[name="number"]').value;
        const location = document.querySelector('input[name="location"]').value;
    
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
    
       
    
        console.log('Profile and password updated.');
        editButton.style.display = 'flex';
        saveButton.style.display = 'none';
    }
}

editProfile();