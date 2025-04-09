<!DOCTYPE html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: signUp.php?error=Passwords do not match");
        exit();
    }

    // Check if email exists
    $emailCheckQuery = "SELECT * FROM user WHERE email = ?";
    $emailStmt = $conn->prepare($emailCheckQuery);
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();

    if ($emailResult->num_rows > 0) {
        header("Location: signUp.php?error=Email already exists");
        exit();
    }

    // Check if username exists
    $usernameCheckQuery = "SELECT * FROM user WHERE username = ?";
    $usernameStmt = $conn->prepare($usernameCheckQuery);
    $usernameStmt->bind_param("s", $username);
    $usernameStmt->execute();
    $usernameResult = $usernameStmt->get_result();

    if ($usernameResult->num_rows > 0) {
        header("Location: signUp.php?error=Username already exists");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $points = 0;
    $level_name = "Beginner";

    // Insert new user
    $insertQuery = "INSERT INTO user (username, points, email, password, level_name) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("sisss", $username, $points, $email, $hashedPassword, $level_name);

    if ($insertStmt->execute()) {
        // Auto login
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['points'] = $points;
        $_SESSION['level_name'] = $level_name;

        header("Location: ProfilePage.php");
        exit();
    } else {
        header("Location: signUp.php?error=Signup failed");
        exit();
    }
}
?>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ECO</title>
    <link rel="stylesheet" href="EcoStyle.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo2.jpg" alt="ECO Logo">
        </div>
        <h2>Sign Up</h2>
        <form id="signupForm" action="signUp.php" method="POST">
            <div class="input-group">
                <label for="username">User name</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit">Sign Up</button>
            <p class="error-message" id="errorMessage">
                <?php
                if (isset($_GET['error'])) {
                    echo htmlspecialchars($_GET['error']);
                }
                ?>
            </p>
        </form>
        <p class="link">Already have an account? <a href="LogIn.php">Log in</a></p>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            let username = document.getElementById("username").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirmPassword").value;
            let errorMessage = document.getElementById("errorMessage");

            errorMessage.textContent = "";

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
        });
    </script>
</body>
</html>
