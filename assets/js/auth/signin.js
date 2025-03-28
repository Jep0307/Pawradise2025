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
