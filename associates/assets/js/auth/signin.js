import { supabase } from "../../../../admin/assets/js/auth/config.js";

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

    formMessage.classList.remove("error", "success");

    if (error) {
      formMessage.textContent = error.message === "Invalid login credentials"
        ? "Incorrect email or password. Please try again."
        : error.message;

      formMessage.classList.add("error");
    } else {
      formMessage.textContent = "Sign-in successful! Redirecting...";
      formMessage.classList.add("success");

      const response = await fetch("http://localhost/SIA02/Pawradise2025/associates/assets/php/checkuser.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ email: emailValue })
      });

      const result = await response.json();

      if (result.status === "success") {
  
          window.location.href = "http://localhost/SIA02/Pawradise2025/associates/pages/home.html";
        
      } else {
        formMessage.textContent = result.message;
        formMessage.classList.add("error");
      }
    }
  } catch (err) {
    console.error("Unexpected error:", err);
    formMessage.textContent = "An unexpected error occurred. Please try again.";
    formMessage.style.color = "red";
    formMessage.style.display = "block";
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
  const isPasswordHidden = passwordInput.type === "password";

  passwordInput.type = isPasswordHidden ? "text" : "password";
  passwordIcon.src = isPasswordHidden
    ? "../../assets/imgs/eye-closed-icon.svg "     // Eye open for visible password
    : "../../assets/imgs/eye-icon.svg"; // Eye closed for hidden password
  passwordIcon.alt = isPasswordHidden ? "Show Password" : "Hide Password";
});