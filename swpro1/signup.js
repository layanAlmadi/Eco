document.getElementById("signupForm").addEventListener("submit", function(event) {
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let errorMessage = document.getElementById("errorMessage");

    // Clear previous errors
    errorMessage.textContent = "";

    // Validation checks
    if (!username || !email || !password || !confirmPassword) {
        errorMessage.textContent = "Please fill all the fields.";
        event.preventDefault();
        return;
    }

    if (!email.includes("@")) {
        errorMessage.textContent = "Please enter a valid email.";
        event.preventDefault();
        return;
    }

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords don't match.";
        event.preventDefault();
        return;
    }

    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters long.";
        event.preventDefault();
        return;
    }

    // ✅ Do NOT show success message here
    // ✅ Remove alert("The account has been created successfully.!");
    // ✅ Allow form submission (No preventDefault)
});
