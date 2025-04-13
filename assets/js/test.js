// function printRow(button) {
//   // Get the parent row of the clicked button
//   const row = button.closest("tr");

//   // Retrieve data from the row
//   const fullName = row.cells[0].textContent;
//   const email = row.cells[1].textContent;
//   const message = row.cells[2].textContent;

//   // Populate the hidden print area with the row's data
//   const printArea = document.getElementById("printArea");
//   printArea.innerHTML = `
//     <h2>Row Details</h2>
//     <div class="section">
//       <label>Full Name:</label>
//       <p>${fullName}</p>
//     </div>
//     <div class="section">
//       <label>Email:</label>
//       <p>${email}</p>
//     </div>
//     <div class="section">
//       <label>Message:</label>
//       <p>${message || "—"}</p>
//     </div>
//   `;

//   // Trigger the print functionality
//   window.print();
// }

// Finalize the application submission
function finalizeApplicationSubmission() {
  const formData = new FormData(document.getElementById("adoptionForm"));

  // Log each form field and its value (for debugging purposes)
  // for (const [key, value] of formData.entries()) {
  //   console.log(`${key}: ${value}`);
  // }

  // You can add your form submission logic here (e.g., send data to a server)
  alert("Application submitted successfully!");
  closePetApplicationForm(); // Close the application form after submission
}

function submitApplication(event) {
  event.preventDefault(); // Prevent the default form submission

  // Show the warning modal

}

const warningModal = document.getElementById('warningModal');
const yesButton = warningModal.querySelector('button:last-of-type');
const noButton = warningModal.querySelector('button:first-of-type');

function showWarningModal() {
  warningModal.style.display = 'block';
}

function hideWarningModal() {
  warningModal.style.display = 'none';
}

// YES = finalize submission
yesButton.addEventListener('click', () => {
  hideWarningModal();
  finalizeApplicationSubmission();
});

// NO = cancel
noButton.addEventListener('click', () => {
  hideWarningModal();
});

  // Function to handle the custom select dropdown
  const form = document.getElementById('adoptionForm');
  const customSelects = document.querySelectorAll('.custom-select');
  const fileInputs = document.querySelectorAll('input[type="file"]');
  const applicantInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');
  
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission
  
    let valid = true; // Track if the form is valid
  
    // Validate custom selects
    customSelects.forEach(select => {
      const selected = select.querySelector('.selected');
      const errorMessage = select.querySelector('.error-message');
  
      if (selected.textContent === 'Select') {
        if (errorMessage) {
          errorMessage.textContent = 'Please select an option.'; // Show error message
        }
        valid = false; // Form is not valid
      } else {
        if (errorMessage) {
          errorMessage.textContent = ''; // Clear error message
        }
      }
    });
  
    // Validate file inputs (Valid ID and Home Photos)
    fileInputs.forEach(input => {
      const errorMessage = document.getElementById(input.id + 'Error');
    
      // Validation on submit
      if (!input.files.length) {
        if (errorMessage) {
          errorMessage.textContent = 'This field is required.';
        }
        valid = false;
      } else {
        if (errorMessage) {
          errorMessage.textContent = '';
        }
      }
    
      // Instant feedback on file select
      input.addEventListener('change', () => {
        if (input.files.length && errorMessage) {
          errorMessage.textContent = '';
        }
      });
    });
    
  
    // Validate applicant info (Full Name, Email, Contact Number, Birthdate, etc.)
    applicantInputs.forEach(input => {
      const errorMessage = document.getElementById(input.id + 'Error'); // Get corresponding error span
      if (!input.value.trim()) {
        if (errorMessage) {
          errorMessage.textContent = 'This field is required.'; // Show error message
        }
        valid = false; // Form is not valid
      } else {
        if (errorMessage) {
          errorMessage.textContent = ''; // Clear error message
        }
      }

      input.addEventListener('input', () => {
        if (input.value.trim()) {
          if (errorMessage) {
            errorMessage.textContent = ''; // Clear error as user types
          }
        }
      });
    });
  
    // If all inputs are valid, proceed with form submission (or logic)
    if (valid) {
      // Collect custom select values
      showWarningModal(); // Show the warning modal

      const customSelectValues = [];
      document.querySelectorAll('.custom-select-container').forEach(container => {
        const label = container.querySelector('.selected').textContent.trim();
        const selectedOption = container.querySelector('.custom-options li:not(.hidden)[data-value]');
        const value = selectedOption ? selectedOption.dataset.value : label; // fallback
        customSelectValues.push(value);
      });
    
      // Collect file inputs
      const fileValues = {};
      fileInputs.forEach(input => {
        fileValues[input.name] = input.files[0]; // grab first file (assuming single upload)
      });
    
      // Collect applicant text/email/tel inputs
      const applicantValues = {};
      applicantInputs.forEach(input => {
        applicantValues[input.name] = input.value.trim();
      });
    
      // Log for now
      // console.log({
      //   customSelects: customSelectValues,
      //   files: fileValues,
      //   applicantInfo: applicantValues
      // });
    
      // Continue with form submission or processing
    }
    
  });
  
  // Toggle custom select options visibility
  customSelects.forEach(select => {
    select.addEventListener('click', function() {
      const options = this.nextElementSibling;
      options.classList.toggle('hidden');
    });
  });
  
  // Get all list items inside the custom options
  const options = document.querySelectorAll('.custom-options li');
  
  options.forEach(option => {
    option.addEventListener('click', function() {
      const selected = this.closest('.custom-select-container').querySelector('.selected');
      const errorMessage = this.closest('.custom-select-container').querySelector('.error-message');
  
      selected.textContent = this.textContent;
      this.closest('.custom-options').classList.add('hidden'); // Hide options after selection
  
      // Clear error message if selection is made
      if (errorMessage) {
        errorMessage.textContent = '';
      }
    });
  });
  
  