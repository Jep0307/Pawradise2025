import { supabase } from "./config.js";

document.getElementById("resetPasswordForm").addEventListener("submit", async (event) => {
  event.preventDefault();

  const email = document.getElementById("email").value;
  console.log("Email entered:", email); // Debugging log

  try {
    const { error } = await supabase.auth.resetPasswordForEmail(email, {
      redirectTo: "http://127.0.0.1:5500/Pawradise2025/pages/auth/newpassword.html", // Adjust to your reset password page
    });

    if (error) {
      console.error("Error sending reset email:", error.message);
      alert("Failed to send reset email. Please try again.");
    } else {
      alert("Password reset email sent! Check your inbox.");
    }
  } catch (err) {
    console.error("Unexpected error:", err);
  }
});