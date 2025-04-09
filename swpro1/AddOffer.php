<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $username = $_SESSION['username'];
    $status = 'PENDING';
    $image_url = '';

    
    if (empty($name) || empty($phone) || empty($category) || empty($description)) {
        $_SESSION['message'] = "Please fill in all fields before submitting!";
        header("Location: AddOffer.php");
        exit();
    }

    
    if (!preg_match('/^05[0-9]{8}$/', $phone)) {
        $_SESSION['message'] = "Please enter a valid phone number (should start with 05 and contain 10 digits).";
        header("Location: AddOffer.php");
        exit();
    }

    
    
    
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != 0) {
        $_SESSION['message'] = "Please upload an image for the material.";
        header("Location: AddOffer.php");
        exit();
    }

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . time() . "_" . basename($_FILES["photo"]["name"]);

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        $_SESSION['message'] = "Failed to upload the image.";
        header("Location: AddOffer.php");
        exit();
    }
    
    $sql = "INSERT INTO material (material_name, description, image_url, category, username, status, phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $description, $image_url, $category, $username, $status, $phone);
    
    if ($stmt->execute()) {
        // ✅ 3. Update user points (+10 points)
        $updatePointsSql = "UPDATE user SET points = points + 10 WHERE username = ?";
        $updateStmt = $conn->prepare($updatePointsSql);
        $updateStmt->bind_param("s", $username);
        $updateStmt->execute();
        $updateStmt->close();

        // ✅ 4. Update user level based on points
        $levelUpdateSql = "UPDATE user 
        SET level_name = CASE 
            WHEN points >= 100 THEN 'Advanced'
            WHEN points >= 30 THEN 'Intermediate'
            ELSE 'Beginner'
        END
        WHERE username = ?";

$levelStmt = $conn->prepare($levelUpdateSql);
$levelStmt->bind_param("s", $username);
$levelStmt->execute();
$levelStmt->close();

        $_SESSION['message'] = "Material added successfully! You earned 10 points.";
    } else {
        $_SESSION['message'] = "An error occurred while adding the material!";
    }

    $stmt->close();
    $conn->close();

    header("Location: AddOffer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="StyleSheet.css">
    <title>ECO</title>
</head>
<body>
<header>
        <a href="#" class="logo"><img src="logo2.jpg" alt=""></a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="home.html">home</a></li>
            <li><a href="home.html">Motivation</a></li>
            <li><a href="home.html">point </a></li>
            <li><a href="AddOffer.php">offer Recycled</a></li>
            <li><a href="find.php">Find Recycled</a></li>
            <li><a href="ProfilePage.php">Profile</a></li>
            <li><a href="home.html">About us</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </header>

    <div id="fadd" style="display: flex; flex-direction: column; align-items: center; margin: 20px;">
       
        <?php if (isset($_SESSION['message'])): ?>
            <script>
                alert("<?php echo $_SESSION['message']; ?>");
            </script>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="AddOffer.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        <div class="image-frame">
                            <img src="img/add.png" alt="add image" id="addImage">
                        </div>
                        <div class="upload-section">
                            <label>Add material's photo:<input type="file" name="photo"></label>
                        </div>
                    </td>
                </tr>
            </table>

            <h2 style="text-align: center;">Material Information:</h2>
            <table id="protable">
                <tr>
                    <td><label for="name"> Material Name:</label></td>
                    <td><input type="text" id="name" name="name"></td>
                </tr>
                <tr>
                    <td><label for="phone">Phone Number (Visible to others):</label></td>
                    <td><input type="tel" id="phone" name="phone" placeholder="05XXXXXXXX"></td>
                </tr>
                <tr>
                    <td><label for="category">Type:</label></td>
                    <td>
                        <select id="category" name="category">
                            <option value="Plastic">Plastic</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                            <option value="Clothes">Clothes</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="description">Description:</label></td>
                    <td><textarea id="description" name="description" placeholder="Description"></textarea></td>
                </tr>
            </table>

            <div style="text-align: center;">
                <input id="sub" type="submit" value="Add" style="margin-bottom: 30px;">
            </div>
        </form>
    </div>

    <footer>
        <section class="contact" id="contact">
            <div class="social">
                <a href="#"><i class='bx bxl-twitter'></i></a>
                <a href="#"><i class='bx bxl-instagram'></i></a>
            </div>
            <p>&#169; CarpoolVenom All Rights Reserved.</p>
        </section>
    </footer>

    <script src="main.js"></script> 
</body>
</html>
