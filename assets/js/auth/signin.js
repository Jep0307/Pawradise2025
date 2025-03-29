import { supabase } from "./config.js";

const signinForm = document.getElementById("signinForm");
signinForm.addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent page reload

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  try {
    const { error, data } = await supabase.auth.signInWithPassword({
      email,
      password,
    });

    if (error) {
      console.error("Sign-in error:", error.message);
      alert(error.message); // Show error message
    } else {
      console.log("User signed in:", data);
      alert("Sign-in successful!");
      window.location.href = "http://127.0.0.1:5500/Pawradise2025/index.html"; // Redirect to a protected page
    }
  } catch (err) {
    console.error("Unexpected error:", err);
  }
});

const passwordInput = document.getElementById("password");
const passwordIcon = document.getElementById("passwordIcon");

passwordIcon.addEventListener("click", () => {
  console.log("Password icon clicked"); // Debugging line

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    passwordIcon.src = "/Pawradise2025/assets/imgs/eye-closed-icon.svg"; // Change to the "close" icon
    passwordIcon.alt = "Hide Password";
  } else {
    passwordInput.type = "password";
    passwordIcon.src = "/Pawradise2025/assets/imgs/eye-icon.svg"; // Change back to the "open" icon
    passwordIcon.alt = "Show Password";
  }
})