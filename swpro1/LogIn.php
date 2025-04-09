
<?php
session_start();
include 'connection.php'; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header("Location:ProfilePage.php"); 
            exit();
        } else {
            $error = "The entered data is incorrect.";
        }
    } else {
        $error = "The entered data is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Log In - ECO</title>
    <link rel="stylesheet" href="EcoStyle.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo2.jpg" alt="ECO Logo">
        </div>
        <h2>Log In</h2>
        <form method="POST" action="">
            <div class="input-group">
                <label for="email">email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Log in</button>
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <p class="link">You don't have an account? <a href="signUp.php"> Sign Up</a></p>
    </div>
</body>
</html>
