import { supabase } from "./config.js"; // Adjust the path as necessary

document.getElementById("resetPasswordForm").addEventListener("submit", async (event) => {
  event.preventDefault();

  const email = document.getElementById("email").value;

  try {
    const { error } = await supabase.auth.resetPasswordForEmail(email, {
      redirectTo: "http://localhost/SIA02/Pawradise2025/admin/auth/newpassword.html",
    });
  
    formMessage.classList.remove("success", "error");
  
    if (error) {
      // console.error("Error sending reset email:", error.message);
      formMessage.textContent = "Failed to send reset email. Please try again.";
      formMessage.classList.add("error");
    } else {
      formMessage.textContent = "Password reset email sent! Check your inbox.";
      formMessage.classList.add("success");
    }
  } catch (err) {
    // console.error("Unexpected error:", err);
    formMessage.textContent = "An unexpected error occurred. Please try again.";
    formMessage.classList.remove("success");
    formMessage.classList.add("error");
  }
  
});