import { supabase } from "./config.js";

document.addEventListener("DOMContentLoaded", async () => {
    const userStatus = document.getElementById("userStatus");
    const signoutBtn = document.getElementById("signoutBtn");

    // Check authentication state
    const { data: { session } } = await supabase.auth.getSession();

    if (!session) {
        // Redirect to sign-in page if not logged in
        window.location.href = "/SIA02/Pawradise2025/associates/index.html"; // if using the 3000
        // window.location.href = "/Pawradise2025/associates/index.html"; // if using the 127.0.0.1
    } else {
        const emailInput = document.getElementById("email");

        if (emailInput) {
            emailInput.value = `${session.user.email}`;
        } 

        // userStatus.textContent = `${session.user.email}`;
        signoutBtn.style.display = "block"; // Show sign-out button
    }

    // Handle sign-out
    signoutBtn.addEventListener("click", async () => {
        const { error } = await supabase.auth.signOut();
        if (error) {
            console.error("Sign-out error:", error.message);
            alert(error.message);
        } else {
            alert("Signed out successfully!");
            window.location.href = "/SIA02/Pawradise2025/associates/index.html"; // if using the 3000
            // window.location.href = "/Pawradise2025/associates/index.html"; // if using the 127.0.0.1
        }
    });
});
