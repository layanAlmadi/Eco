<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';
$username = $_SESSION['username'];

// Get user data
$sql = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Create a default user array if user not found in database
    $user = [
        'username' => $username,
        'level_name' => 'New User',
        'points' => 0
    ];
}

// Get materials donated by the user
$sql = "SELECT * FROM material WHERE username = '$username'";
$materials = $conn->query($sql);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $recipient = isset($_POST['recipient']) ? mysqli_real_escape_string($conn, $_POST['recipient']) : '';
    
    // Update material status
    $update_sql = "UPDATE material SET status = '$new_status' WHERE material_id = $material_id";
    if (!$conn->query($update_sql)) {
        echo "Error updating status: " . $conn->error;
        exit();
    }
    
    // If status is COMPLETED, create a transaction record
    if ($new_status === 'COMPLETED') {
        if (empty($recipient)) {
            echo "Error: Recipient username is required when status is COMPLETED.";
            exit();
        }
        
        // Check if recipient exists in the database
        $check_recipient = "SELECT * FROM user WHERE username = '$recipient'";
        $recipient_result = $conn->query($check_recipient);
        
        if ($recipient_result && $recipient_result->num_rows > 0) {
            // Insert transaction record
            $transaction_sql = "INSERT INTO transaction (username_donor, username_recipient, material_id) 
                               VALUES ('$username', '$recipient', $material_id)";
            
            if (!$conn->query($transaction_sql)) {
                echo "Error creating transaction: " . $conn->error;
                exit();
            }
            
            // Add points to the donor
            $points_sql = "UPDATE user SET points = points + 10 WHERE username = '$username'";
            if (!$conn->query($points_sql)) {
                echo "Error updating points: " . $conn->error;
                exit();
            }
        } else {
            echo "Error: Recipient username does not exist.";
            exit();
        }
    }
    
    // Redirect to refresh page
    header("Location: ProfilePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Procss.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <title>Eco ProfilePage</title>
    <style>
        .materials-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .material-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(50% - 10px);
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .material-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .material-status {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
        }
        
        .status-pending {
            background-color: #FFA500;
        }
        
        .status-completed {
            background-color: #4CAF50;
        }
        
        .status-canceled {
            background-color: #F44336;
        }
        
        .material-details h3 {
            color: #2E7D32;
            margin-bottom: 10px;
        }
        
        .material-details p {
            margin: 5px 0;
            color: #555;
        }
        
        .material-actions {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            max-width: 500px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .modal-form {
            display: flex;
            flex-direction: column;
        }
        
        .modal-form select, .modal-form input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .no-materials {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 18px;
        }
        
        .error-message {
            color: red;
            background-color: #ffeeee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: none;
        }
        
        @media (max-width: 768px) {
            .material-card {
                width: 100%;
            }
            
            .modal-content {
                width: 90%;
            }
        }
    </style>
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
    
    <div class="main-content">
        <div class="sidebar">
            <img src="profile-placeholder.png" alt="Profile Picture">
            <h3><?php echo $user['username']; ?></h3>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        
        <div class="profile-container">
            <div class="profile-header">
                <img src="profile-placeholder.png" alt="Profile Picture">
                <div>
                    <h2><?php echo $user['username']; ?></h2>
                    <p><?php echo $user['level_name']; ?></p>
                </div>
                <div class="points"><?php echo $user['points']; ?></div>
            </div>
            
            <h2 style="margin: 20px 0; color: #2E7D32;">My Donations</h2>
            
            <div class="materials-container">
                <?php if ($materials && $materials->num_rows > 0): ?>
                    <?php while($material = $materials->fetch_assoc()): ?>
                        <div class="material-card">
                            <?php 
                            $statusClass = '';
                            if ($material['status'] == 'PENDING') {
                                $statusClass = 'status-pending';
                            } else if ($material['status'] == 'COMPLETED') {
                                $statusClass = 'status-completed';
                            } else {
                                $statusClass = 'status-canceled';
                            }
                            ?>
                            <div class="material-status <?php echo $statusClass; ?>">
                                <?php echo $material['status']; ?>
                            </div>
                            
                            <img src="<?php echo $material['image_url']; ?>" alt="<?php echo $material['material_name']; ?>">
                            
                            <div class="material-details"><h3><?php echo $material['material_name']; ?></h3>
                                <p><strong>Category:</strong> <?php echo $material['category']; ?></p>
                                <p><strong>Description:</strong> <?php echo $material['description']; ?></p>
                                <p><strong>Contact:</strong> <?php echo $material['phone']; ?></p>
                            </div>
                            
                            <div class="material-actions">
                                <button class="btn" onclick="openModal(<?php echo $material['material_id']; ?>)">Update Status</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-materials">
                        <p>You haven't donated any materials yet.</p>
                        <a href="AddOffer.php" class="btn" style="margin-top: 20px;">Donate Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal for Status Update -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Donation Status</h2>
                <span class="close">&times;</span>
            </div>
            <div id="error-message" class="error-message"></div>
            <form class="modal-form" method="POST" action="" id="statusForm">
                <input type="hidden" id="material_id" name="material_id" value="">
                
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="PENDING">Pending</option>
                    <option value="COMPLETED">Completed</option>
                    <option value="CANCELED">Canceled</option>
                </select>
                
                <div id="recipient-field" style="display:none;">
                    <label for="recipient">Recipient Username:</label>
                    <input type="text" id="recipient" name="recipient" placeholder="Enter recipient's username">
                    <p style="color: #666; font-size: 0.9em;">Enter the username of the person who received this donation.</p>
                </div>
                
                <button type="submit" name="update_status" class="btn">Save Changes</button>
            </form>
        </div>
    </div>
    
    <section class="contact" id="contact">
        <div class="social">
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
        </div>
        <p>&#169; CarpoolVenom All Rights Reserved.</p>
    </section>
    
    <script>
        // Menu toggle
        let menu = document.querySelector('#menu-icon');
        let navbar = document.querySelector('.navbar');
        
        menu.onclick = () => {
            menu.classList.toggle('bx-x');
            navbar.classList.toggle('active');
        }
        
        // Modal functionality
        var modal = document.getElementById("statusModal");
        var span = document.getElementsByClassName("close")[0];
        var errorMessage = document.getElementById("error-message");
        var statusForm = document.getElementById("statusForm");
        
        function openModal(materialId) {
            document.getElementById("material_id").value = materialId;
            errorMessage.style.display = "none";
            errorMessage.textContent = "";
            modal.style.display = "block";
            
            // Reset form
            document.getElementById("status").value = "PENDING";
            document.getElementById("recipient-field").style.display = "none";
            document.getElementById("recipient").value = "";
        }
        
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        // Show recipient field only when status is COMPLETED
        document.getElementById("status").addEventListener("change", function() {
            var recipientField = document.getElementById("recipient-field");
            if (this.value === "COMPLETED") {
                recipientField.style.display = "block";
            } else {
                recipientField.style.display = "none";
            }
        });
        
        // Form validation before submit
        statusForm.addEventListener("submit", function(event) {
            var status = document.getElementById("status").value;
            var recipient = document.getElementById("recipient").value;
            
            if (status === "COMPLETED" && recipient.trim() === "") {
                event.preventDefault();
                errorMessage.textContent = "Please enter the recipient's username when marking as completed.";
                errorMessage.style.display = "block";
            }
        });
    </script>
</body>

</html>