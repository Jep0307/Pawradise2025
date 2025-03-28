import { supabase } from "./config.js";

document.addEventListener("DOMContentLoaded", async () => {
    const userStatus = document.getElementById("userStatus");
    const signoutBtn = document.getElementById("signoutBtn");

    // Check authentication state
    const { data: { session } } = await supabase.auth.getSession();

    if (!session) {
        // Redirect to sign-in page if not logged in
        window.location.href = "./pages/auth/signin.html";
    } else {
        userStatus.textContent = `Logged in as ${session.user.email}`;
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
            window.location.href = "./pages/auth/signin.html"; // Redirect to sign-in page
        }
    });
});
