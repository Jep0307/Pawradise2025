import { supabase } from "./config.js";

document.getElementById("newPasswordForm").addEventListener("submit", async (event) => {
  event.preventDefault();

  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  
  // Clear previous messages
  formMessage.classList.remove("success", "error");
  
  // Validate new password
  if (!newPassword) {
    formMessage.textContent = "New password is required.";
    formMessage.classList.add("error");
    return;
  } else if (newPassword.length < 6) {
    formMessage.textContent = "Password must be at least 6 characters.";
    formMessage.classList.add("error");
    return;
  }
  
  // Validate confirm password
  if (newPassword !== confirmPassword) {
    formMessage.textContent = "Passwords do not match. Please try again.";
    formMessage.classList.add("error");
    return;
  }

  try {
    const { data, error } = await supabase.auth.updateUser({ password: newPassword });
    formMessage.classList.remove("success", "error");
  
    if (error) {
      // console.error("Error updating password:", error.message);
      formMessage.textContent = "Failed to update password. Please try again.";
      formMessage.classList.add("error");
    } else {
      // console.log("Password updated successfully:", data);
      formMessage.textContent = "Password updated successfully!";
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

