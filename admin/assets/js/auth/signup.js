import { supabase } from "./config.js";

const signupForm = document.getElementById("signupForm");
const fullnameInput = document.getElementById("fullname");
const addressInput = document.getElementById("address");
const contactnoInput = document.getElementById("contactno");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");

const fullnameError = document.getElementById("fullnameError");
const addressError = document.getElementById("addressError");
const contactnoError = document.getElementById("contactnoError");
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
  } else if (
    passwordValue.length < 6 ||
    !/[A-Z]/.test(passwordValue) ||
    !/[a-z]/.test(passwordValue)
  ) {
    passwordError.textContent = "contain at least 6 characters, at least 1 upper case letter and at least 1 lower case letter.";
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
        emailRedirectTo: "http://localhost/SIA02/Pawradise2025/admin/auth/signin.html",
      },
    });
  
    // Clear previous status
    formMessage.classList.remove("success", "error");
  
    if (error) {
      formMessage.textContent = error.message;
      formMessage.classList.add("error");
      return;
    }
  
    // Supabase signup succeeded, now save to custom DB via PHP
    const formData = new FormData(signupForm);
    const response = await fetch("/SIA02/Pawradise2025/Admin/components/add-newuser.php", {
      method: "POST",
      body: formData,
    });
  
    const result = await response.text();
  
    if (result.includes("Email already exists")) {
      formMessage.textContent = "This email is already registered.";
      formMessage.classList.add("error");
      return;
    }
  
    // All good
    formMessage.textContent = "Account Created successfully! Check your email for verification.";
    formMessage.classList.add("success");
  
    setTimeout(() => {
      window.location.href = "./signin.html";
    }, 2000);
  
  } catch (err) {
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
 const isPasswordHidden = passwordInput.type === "password";

  passwordInput.type = isPasswordHidden ? "text" : "password";
  passwordIcon.src = isPasswordHidden
    ? "../images/Eye-closed-icon.svg"     // Eye open for visible password
    : "../images/Eye-icon.svg"; // Eye closed for hidden password
  passwordIcon.alt = isPasswordHidden ? "Show Password" : "Hide Password";
});