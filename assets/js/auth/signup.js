import { supabase } from "./config.js";

const signupForm = document.getElementById("signupForm");

signupForm.addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent form from reloading the page

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  try {
    const { error } = await supabase.auth.signUp({ email, password, options: {
      emailRedirectTo: "http://127.0.0.1:5500/Pawradise2025/pages/auth/signin.html"
    } });

    if (error) {
      console.error("Sign-up error:", error.message);
      alert(error.message); // Display error to user
    } else {
      alert("Sign-up successful! Check your email for verification.");
      window.location.href = "./signin.html"; // Redirect to sign-in page
    }
  } catch (err) {
    console.error("Unexpected error:", err);
  }
});


