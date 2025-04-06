// Select elements
const menuToggleIcon = document.getElementById("menuToggle");
const navMenu = document.querySelector(".nav-menu");

// Function to toggle the menu
function toggleMenu() {
  const isMenuOpen = navMenu.classList.contains("active");

  if (isMenuOpen) {
    navMenu.classList.remove("active"); // Hide the menu
    menuToggleIcon.src = "/Pawradise2025/assets/imgs/Menu-icon.svg"; // Show hamburger icon
    menuToggleIcon.alt = "Open Menu";
    document.body.style.overflow = "auto"; // Allow scrolling
  } else {
    navMenu.classList.add("active"); // Show the menu
    menuToggleIcon.src = "/Pawradise2025/assets/imgs/X-icon.svg"; // Show close icon
    menuToggleIcon.alt = "Close Menu";
    document.body.style.overflow = "hidden"; // Allow scrolling
  }
}

// Add event listener
menuToggleIcon.addEventListener("click", toggleMenu);


// Show and unshow the header on scroll
const header = document.querySelector('header');
let lastScrollY = window.scrollY;
const isMenuOpen = navMenu.classList.contains("active");

window.addEventListener('scroll', () => {

  if(lastScrollY < window.scrollY){
    header.style.opacity = '0';
    header.style.visibility = 'hidden';
  }

  else {
    header.style.opacity = '1';
    header.style.visibility = 'visible';
  }

  if(header && isMenuOpen){
    isMenuOpen.classList.remove('active');
  }

  lastScrollY = window.scrollY;
});