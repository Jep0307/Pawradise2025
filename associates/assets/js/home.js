// Select elements
const menuToggleIcon = document.getElementById("menuToggle");
const navMenu = document.querySelector(".nav-menu");

// Function to toggle the menu
function toggleMenu() {
  const isMenuOpen = navMenu.classList.contains("active");

  if (isMenuOpen) {
    navMenu.classList.remove("active"); // Hide the menu
    menuToggleIcon.src = "/Pawradise2025/associates/assets/imgs/Menu-icon.svg"; // Show hamburger icon
    menuToggleIcon.alt = "Open Menu";
    document.body.style.overflow = "auto"; // Allow scrolling
  } else {
    navMenu.classList.add("active"); // Show the menu
    menuToggleIcon.src = "/Pawradise2025/associates/assets/imgs/X-icon.svg"; // Show close icon
    menuToggleIcon.alt = "Close Menu";
    document.body.style.overflow = "hidden"; // Allow scrolling
  }
}

// Add event listener
menuToggleIcon.addEventListener("click", toggleMenu);