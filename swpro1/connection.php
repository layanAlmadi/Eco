<?php
$conn = mysqli_connect("localhost", "root", "root", "recycling", 8889);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_errno());
}

?>