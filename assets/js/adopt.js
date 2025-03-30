function showPreview(card) {
  const modal = document.getElementById("previewModal");

  // Retrieve data from the clicked card
  const image = card.querySelector("img").src;
  const title = card.querySelector(".header-and-text p:first-child").textContent;
  const location = card.querySelector(".header-and-text p:nth-child(2)").textContent;
  const description = card.querySelector(".header-and-text .description").textContent;

  // Update modal content
  document.getElementById("modalImage").src = image;
  document.getElementById("modalTitle").textContent = title;
  document.getElementById("modalPetType").textContent = location;
  document.getElementById("modalDiscription").textContent = description;

  // Show the modal
  modal.style.display = "block";

  // Scroll to the modal
  const offset = 100; // Offset in pixels
  const modalTop = modal.getBoundingClientRect().top + window.scrollY - offset;

  window.scrollTo({
    top: modalTop,
    behavior: "instant", // Smooth scrolling effect
  });
}

function closePreview() {
  const modal = document.getElementById("previewModal");
  modal.style.display = "none";
}

function filterCards(category) {
  const cards = document.querySelectorAll(".cards");
  const buttons = document.querySelectorAll(".categories button");
  const modal = document.getElementById("previewModal");

   // Close the modal if it's open
   modal.style.display = "none";

  // Highlight the active button
  buttons.forEach((button) => {
    button.classList.remove("active");
  });
  document.querySelector(`#${category === 'all' ? 'allBtn' : category === 'cat' ? 'CatsBtn' : 'DogsBtn'}`).classList.add("active");

  // Show or hide cards based on the category
  cards.forEach((card) => {
    const cardCategory = card.getAttribute("data-category");

    if (category === "all" || cardCategory === category) {
      card.style.display = "block"; // Show the card
    } else {
      card.style.display = "none"; // Hide the card
    }
  });
}