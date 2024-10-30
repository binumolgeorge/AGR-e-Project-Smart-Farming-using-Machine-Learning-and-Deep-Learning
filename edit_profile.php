<?php
// Start the session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";  // Update with your DB server details
$db_username = "root";      // DB username
$password = "";             // DB password
$dbname = "Agre_db";        // DB name

// Create connection
$conn = new mysqli($servername, $db_username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$loggedInUser = $_SESSION['username'];
$sql = "SELECT * FROM farmer WHERE username = '$loggedInUser'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the logged-in user
    $row = $result->fetch_assoc();
} else {
    echo "No profile data found.";
    exit();
}

$conn->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $id_proof = $_POST['id_proof'];
    $id_number = $_POST['id_number'];
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    
    // Password hashing if the password field is not empty
    $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $row['password'];
    
    // Check if any changes were made
    if ($phone == $row['phone'] && $dob == $row['dob'] && $address == $row['address'] && $id_proof == $row['id_proof'] && $id_number == $row['id_number'] && $username == $row['username'] && ($new_password == '' || $hashed_password == $row['password'])) {
        $message = "No changes were made.";
        $redirect = true; // Set flag for redirect
    } else {
        // Update user data
        $conn = new mysqli($servername, $db_username, $password, $dbname);
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("UPDATE farmer SET phone=?, dob=?, address=?, id_proof=?, id_number=?, username=?, password=? WHERE username=?");
        $stmt->bind_param("ssssssss", $phone, $dob, $address, $id_proof, $id_number, $username, $hashed_password, $loggedInUser);
        
        if ($stmt->execute()) {
            // If successful, show a success message and redirect after 5 seconds
            $message = "Profile updated successfully.";
            $redirect = true; // Set flag for redirect
            header("refresh:5;url=profile.php");
        } else {
            $message = "Error updating profile: " . $conn->error;
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Agr-e</title>
    <link rel="stylesheet" href="styles/styles13.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        main {
            padding: 20px;
        }
        .edit-profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .edit-profile-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: black;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #003300;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #003300;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
        .form-group input[type="submit"]:hover {
            background-color: #006400;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: #006400;
        }
    </style>
    <?php if (isset($redirect) && $redirect): ?>
        <meta http-equiv="refresh" content="3;url=profile.php">
    <?php endif; ?>
</head>
<body>
<header>
        <div class="navbar">
            <a href="home.html" class="logo" aria-label="Agr-e Logo">
                <img src="images/logo.png" alt="Agr-e Logo">
            </a>
            <nav>
                <ul>
                    <li><a href="home.html" aria-current="page">Home</a></li>
                    <li class="dropdown">
                        <a >Services</a>
                        <ul class="dropdown-content">
                            <li><a href="soil_testing.html">Soil Testing</a></li>
                            <li><a href="nutrient_deficiency.html">Nutrient Deficiency</a></li>
                            <li><a href="mixed_cropping.html">Mixed Cropping</a></li>
                            <li><a href="insurances.html">Insurances and Subsidies</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="login.html">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
<main>
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Leave empty if not changing">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_proof">ID Proof:</label>
                <input type="text" id="id_proof" name="id_proof" value="<?php echo htmlspecialchars($row['id_proof']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_number">ID Number:</label>
                <input type="text" id="id_number" name="id_number" value="<?php echo htmlspecialchars($row['id_number']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Profile">
            </div>
            <?php if (isset($message)) { ?>
                <div class="message <?php echo ($message == "Profile updated successfully.") ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>
        </form>
    </div>
</main>
<footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h2>About Agr-e</h2>
                <p style="text-align:justify;">Agr-e is dedicated to integrating advanced technology with traditional farming practices, ensuring a sustainable and profitable future for farmers.</p>
            </div>
            <div class="footer-section links">
                <h2>Information</h2>
                <ul>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="terms.html">Terms & Conditions</a></li>
                    <li><a href="privacy.html">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section contact" id="contact">
                <h2>Contact Us</h2>
                <ul>
                    <li><img src="images/favicon1.ico" alt="Location Icon" class="contact-icon"> CHRIST (Deemed to be University) Bangalore Yeshwanthpur Campus, Nalagadderanahalli, Peenya, Bengaluru, Karnataka 560073</li>
                    <li><img src="images/favicon2.ico" alt="Phone Icon" class="contact-icon"> +91 9188465623</li>
                    <li><img src="images/favicon3.ico" alt="Email Icon" class="contact-icon"> <a href="mailto:agre@gmail.com">agre@christuniversity.in</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Agr-e. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
