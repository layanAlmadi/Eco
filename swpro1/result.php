<?php
include 'connection.php'; 



if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    
    $stmt = $conn->prepare("
        SELECT m.material_name, m.description, m.image_url, m.phone
        FROM material m 
        JOIN user u ON m.username = u.username
        WHERE LOWER(m.material_name) LIKE LOWER(?)
        AND m.status = 'PENDING'
    ");
    
    $searchTerm = "%$search%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "<h2>Please enter a search term.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<header class="head1">
    <a href="#" class="logo"><img src="images/logo2.jpg" alt=""></a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href="home.html">home</a></li>
        <li><a href="home.html">Motivation</a></li>
        <li><a href="home.html">point</a></li>
        <li><a href="AddOffer.php">offer Recycled</a></li>
        <li><a href="find.php">Find Recycled</a></li>
        <li><a href="ProfilePage.php">Profile</a></li>
        <li><a href="home.html">About us</a></li>
        <li><a href="home.html">Contact</a></li>
    </ul>
</header>

<br><br>

<div id="features-wrapper">
    <div class="container">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-4 col-12-medium'>";
                    echo "<section class='box feature'>";
                    echo "<div class='image left'>";
                    if (!empty($row['image_url'])) {
                        echo "<a href='#' class='image featured'><img src='" . htmlspecialchars($row['image_url']) . "' alt='Material Image' /></a>";
                    }
                    echo "</div>";
                    echo "<div class='inner'>";
                    echo "<header class='mine'>";
                    echo "<div class='content'>";
                    echo "<h2>" . htmlspecialchars($row['material_name']) . "</h2>";
                    echo "</header>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p id='imgpara'> Contact: " . htmlspecialchars($row['phone']) . "</p>";
                    echo "</div></div></section></div>";
                }
            } else {
                echo "<h2>No results found for '<strong>" . htmlspecialchars($search) . "</strong>'.</h2>";
            }
            ?>
        </div>
    </div>
</div>

<section class="contact" id="contact" style="align-items: center; text-align: center; background: #fff;">
    <div class="social">
        <a href="#"><i class='bx bxl-twitter'></i></a>
        <a href="#"><i class='bx bxl-instagram'></i></a>
    </div>
    <p>&#169; Recycling Project. All Rights Reserved.</p>
</section>

</body>
</html>
