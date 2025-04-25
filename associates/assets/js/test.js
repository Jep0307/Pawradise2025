
// This function can be used for printing applicaton form in staff.
// you may add more in this code that fits to your part,
// and you can also customize them to align with your design.
function printRow(button) {
  // Get the parent row of the clicked button
  const row = button.closest("tr");

  // Retrieve data from the row
  const fullName = row.cells[0].textContent;
  const email = row.cells[1].textContent;
  const message = row.cells[2].textContent;

  const printArea = document.getElementById("printArea");
  printArea.innerHTML = `
    <h2>Row Details</h2>
    <div class="section">
      <label>Full Name:</label>
      <p>${fullName}</p>
    </div>
    <div class="section">
      <label>Email:</label>
      <p>${email}</p>
    </div>
    <div class="section">
      <label>Message:</label>
      <p>${message || "—"}</p>
    </div>
  `;

  // Trigger the print functionality
  window.print();
}

  
// This function is for pet catalogs modal, 
// If you post new pet in the pets page (in customer side), then the location of
// will able to view if they click the button "location".
  function addPost() {
    const imageInput = document.getElementById('imageInput');
    const location = document.getElementById('locationSelect').value;

    if (!imageInput.files[0] || !location) {
      alert('Please select an image and a location');
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      const imgSrc = e.target.result;
      const postHTML = `
        <div style="margin-bottom: 20px;">
          <img src="${imgSrc}" alt="Uploaded" style="width: 300px; display: block;" />
          <button onclick="viewMap('${location}')">View Location on Map</button>
        </div>
      `;
      document.getElementById('postsContainer').innerHTML += postHTML;
    };
    reader.readAsDataURL(imageInput.files[0]);
  }

  function viewMap(location) {
    const mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(location)}`;
    window.open(mapUrl, '_blank');
  }



  document.getElementById('adoptionForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission

    const location = event.target.location.value;  // Get selected location URL
    const locationText = event.target.location.options[event.target.location.selectedIndex].text;  // Get selected option text

    const postedLocationDiv = document.getElementById('postedLocation');
    postedLocationDiv.innerHTML = `<a href="http://${location}" target="_blank">${locationText}</a>`;
});
