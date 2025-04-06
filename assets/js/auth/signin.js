import { supabase } from "./config.js";

const signinForm = document.getElementById("signinForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");

signinForm.addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent page reload

  let isValid = true;

  // Validate email
  const emailValue = emailInput.value.trim();
  const passwordValue = passwordInput.value.trim();
  
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
    const { error, data } = await supabase.auth.signInWithPassword({
      email: emailValue,
      password: passwordValue,
    });

    if (error) {
      console.error("Sign-in error:", error.message);
      alert(error.message); // Show error message
    } else {
      console.log("User signed in:", data);
      alert("Sign-in successful!");
      window.location.href = "/Pawradise2025/pages/adopt.html"; // Redirect to a protected page
    }
  } catch (err) {
    console.error("Unexpected error:", err);
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