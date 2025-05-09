// Select elements
const menuToggleIcon = document.getElementById("menuToggle");
const navMenu = document.querySelector(".nav-menu");

// Function to toggle the menu
function toggleMenu() {
  const isMenuOpen = navMenu.classList.contains("active");

  if (isMenuOpen) {
    navMenu.classList.remove("active"); // Hide the menu
    menuToggleIcon.src = "/SIA02/Pawradise2025/associates/assets/imgs/Menu-icon.svg"; // Show hamburger icon
    menuToggleIcon.alt = "Open Menu";
    document.body.style.overflow = "auto"; // Allow scrolling
  } else {
    navMenu.classList.add("active"); // Show the menu
    menuToggleIcon.src = "/SIA02/Pawradise2025/associates/assets/imgs/X-icon.svg"; // Show close icon
    menuToggleIcon.alt = "Close Menu";
    document.body.style.overflow = "hidden"; // Allow scrolling
  }
}

// Add event listener
menuToggleIcon.addEventListener("click", toggleMenu);

const userEmail = localStorage.getItem("userEmail");
// console.log("Loaded userEmail from localStorage:", userEmail);

var swiper = new Swiper(".mySwiper", {
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },

      autoplay: {
        delay: 3000,
      },
    });