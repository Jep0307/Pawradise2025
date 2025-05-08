import { supabase } from "../../../admin/scripts/auth/config.js";

const signinForm = document.getElementById("signinForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const formMessage = document.getElementById("formMessage");

signinForm.addEventListener("submit", async (event) => {
  event.preventDefault();

  let isValid = true;
  const emailValue = emailInput.value.trim();
  const passwordValue = passwordInput.value.trim();

  // Email validation
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

  // Password validation
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

  if (!isValid) return;

  try {
    // Step 1: Check email in DB
    const response = await fetch("http://localhost/SIA02/Pawradise2025/associates/php/checkuser.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email: emailValue })
    });

    const result = await response.json();

    if (result.status !== "success") {
      formMessage.textContent = result.message;
      formMessage.className = "error";
      return;
    }

    // Step 2: Try Supabase login
    const { error } = await supabase.auth.signInWithPassword({
      email: emailValue,
      password: passwordValue
    });

    formMessage.classList.remove("error", "success");

    if (error) {
      formMessage.textContent =
        error.message === "Invalid login credentials"
          ? "Incorrect email or password. Please try again."
          : error.message;
      formMessage.classList.add("error");
    } else {
      formMessage.textContent = "Sign-in successful! Redirecting...";
      formMessage.classList.add("success");
      localStorage.setItem("userEmail", emailValue);
      window.location.href = "http://localhost/SIA02/Pawradise2025/associates/pages/home.html";
    }
  } catch (err) {
    console.error("Unexpected error:", err);
    formMessage.textContent = "An unexpected error occurred. Please try again.";
    formMessage.className = "error";
  }
});

// Email format validator
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Password visibility toggle
const passwordIcon = document.getElementById("passwordIcon");
passwordIcon.addEventListener("click", () => {
  const isHidden = passwordInput.type === "password";
  passwordInput.type = isHidden ? "text" : "password";
  passwordIcon.src = isHidden
    ? "../../assets/imgs/eye-closed-icon.svg"
    : "../../assets/imgs/eye-icon.svg";
  passwordIcon.alt = isHidden ? "Show Password" : "Hide Password";
});
