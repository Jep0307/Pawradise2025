import { supabase } from "./config.js";

document.getElementById("newPasswordForm").addEventListener("submit", async (event) => {
  event.preventDefault();

  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  // Check if passwords match
  if (newPassword !== confirmPassword) {
    alert("Passwords do not match. Please try again.");
    return;
  }

  try {
    // Update the user's password
    const { data, error } = await supabase.auth.updateUser({ password: newPassword });

    if (error) {
      console.error("Error updating password:", error.message);
      alert("Failed to update password. Please try again.");
    } else {
      console.log("Password updated successfully:", data);
      alert("Password updated successfully! You can now log in with your new password.");
      // Redirect to login page
      window.location.href = "./signin.html";
    }
  } catch (err) {
    console.error("Unexpected error:", err);
    alert("An unexpected error occurred. Please try again.");
  }
});