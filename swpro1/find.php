<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Recycling Materials</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
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

<div class="s131">
    <form action="result.php" method="GET">
        <div class="inner-form">
            <div class="input-field first-wrap">
                <input id="search" name="search" type="text" placeholder="Search for a material " required />
            </div>
            <div class="input-field third-wrap">
                <button class="btn-search" type="submit" style="background-color: #4d8a06;">SEARCH</button>
            </div>
        </div>
    </form>
</div>

<section class="contact" id="contact">
    <div class="social">
        <a href="#"><i class='bx bxl-twitter'></i></a>
        <a href="#"><i class='bx bxl-instagram'></i></a>
    </div>
    <p>&#169; Recycling Project. All Rights Reserved.</p>
</section>

</body>
</html>
