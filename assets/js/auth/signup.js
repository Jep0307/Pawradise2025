import { supabase } from "./config.js";

const signupForm = document.getElementById("signupForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
signupForm.addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent form from reloading the page

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  let isValid = true;

  // Validate email
  const emailValue = emailInput.value.trim();
  if (!emailValue) {
    emailError.textContent = "Email is required";
    emailError.style.display = "block";
    isValid = false;
  } else if (!isValidEmail(emailValue)) {
    emailError.textContent = "Please enter a valid email address.";
    emailError.style.display = "block";
    isValid = false;
  } else {
    emailError.style.display = "none";
  }

  // Validate password
  const passwordValue = passwordInput.value.trim();
  if (!passwordValue) {
    passwordError.textContent = "Password is required";
    passwordError.style.display = "block";
    isValid = false;
  } else if (passwordValue.length < 6) {
    passwordError.textContent = "Password must be at least 6 characters.";
    passwordError.style.display = "block";
    isValid = false;
  } else {
    passwordError.style.display = "none";
  }

  // If validation fails, stop the process
  if (!isValid) {
    return;
  }

  try {
    const { error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        emailRedirectTo: "http://127.0.0.1:5500/Pawradise2025/pages/auth/signin.html",
      },
    });
  
    // Clear previous status
    formMessage.classList.remove("success", "error");
  
    if (error) {
      // console.error("Sign-up error:", error.message);
      formMessage.textContent = error.message;
      formMessage.classList.add("error");
    } else {
      formMessage.textContent = "Sign-up successful! Check your email for verification.";
      formMessage.classList.add("success");
  
      setTimeout(() => {
        window.location.href = "./signin.html";
      }, 2000);
    }
  } catch (err) {
    // console.error("Unexpected error:", err);
    formMessage.textContent = "An unexpected error occurred. Please try again.";
    formMessage.classList.remove("success");
    formMessage.classList.add("error");
  }
});

// Helper function to validate email format
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Password visibility toggle
const passwordIcon = document.getElementById("passwordIcon");
passwordIcon.addEventListener("click", () => {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    passwordIcon.src = "/Pawradise2025/assets/imgs/Eye-closed-icon.svg"; // Change to the "close" icon
    passwordIcon.alt = "Hide Password";
  } else {
    passwordInput.type = "password";
    passwordIcon.src = "/Pawradise2025/assets/imgs/Eye-icon.svg"; // Change back to the "open" icon
    passwordIcon.alt = "Show Password";
  }
});


