<?php
// Start the session to check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";  // Update with your DB server details
$username = "root";         // DB username
$password = "";             // DB password
$dbname = "Agre_db";        // DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Agr-e</title>
    <link rel="stylesheet" href="styles/styles13.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        html {
    scroll-behavior: smooth;
}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url('../images/background2.jpg') no-repeat center center fixed;
    background-size: cover;
    color: white;
    position: relative;
}

header {
    padding: 10px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background: rgba(38, 61, 38, 0.7); /* Dark green with transparency */
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    height: 60px;
}

.navbar .logo img {
    height: 200px;
    margin-left: 120px;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

nav ul li {
    margin-left: 20px;
    position: relative;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

nav ul li a:hover {
    background-color: rgba(38, 61, 38, 0.7);
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: rgba(38, 61, 38, 0.7);
    min-width: 200px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 4px;
}

.dropdown-content a {
    color: white;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    border-bottom: 1px solid #333;
    font-size: 14px;
}

.dropdown-content a:hover {
    background-color: rgba(38, 61, 38, 0.7);
}

.dropdown:hover .dropdown-content {
    display: block;
}
.profile-link {
    display: flex;
    align-items: center; /* Aligns the icon and text vertically */
}

.profile-icon {
    width: 20px; /* Adjust the size of the icon as needed */
    height: 20px;
    margin-right: 8px; /* Space between icon and text */
    vertical-align: middle; /* Ensures vertical alignment */
}



.profile-container {
    max-width: 600px;
    margin: 40px auto;
    height: auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 9px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative; /* To position the edit link absolutely */
}

.profile-header {
    text-align: center;
    margin-bottom: 20px;
}

.profile-header h1 {
    margin: 0;
    color: black;
    border-bottom: 2px solid rgba(255, 255, 255, 0.7); /* Adds a line below the heading */
    padding-bottom: 10px; /* Adds some spacing between the text and the line */
}

.profile-header p {
    margin: 0;
    color:#003300;
    font-size: 24px; /* Increase the font size for a bigger welcome message */
    font-weight: bold; /* Optional: Makes the text bold */
    margin-top: 10px; /* Adds some spacing above the welcome text */
}


.profile-details {
    margin: 20px 0;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #ffffff;
}

.detail-item .label {
    font-weight: bold;
    color: rgba(38, 61, 38, 0.7);
}

.detail-item .value {
    color: #333;
}

.edit-profile-link {
    position: absolute;
    bottom: 10px;
    right: 20px;
}

.edit-profile-link a {
    color: rgba(38, 61, 38, 0.7);
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.edit-profile-link a:hover {
    color: rgba(102, 191, 102, 0.7);
}


footer {
    background-color: rgba(38, 61, 38, 0.7);
    color: white;
    padding: 20px 0;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.footer-section {
    flex: 1;
    padding: 0 20px;
    margin-bottom: 20px;
}

.footer-section h2 {
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 2px solid #014601;
    padding-bottom: 10px;
}

.footer-section p,
.footer-section ul li {
    font-size: 14px;
    line-height: 1.6;
}

.footer-section ul li {
    margin-bottom: 10px;
    display: flex;
    align-items: center; /* Align the icon and text vertically */
}

.contact-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    vertical-align: middle;
}

.footer-section ul li a {
    color: white;
    text-decoration: none;
}

.footer-section ul li a:hover {
    color: #cccccc;
}

.footer-bottom {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #034b03;
}

.footer-bottom p {
    font-size: 14px;
}
    .profile-header h1 {
    margin: 0;
    color: black;
    border-bottom: 2px solid rgba(255, 255, 255, 0.7); /* Adds a line below the heading */
    padding-bottom: 10px; /* Adds some spacing between the text and the line */
}

    .profile-header p {
        margin: 0;
        color:#003300;
        font-size: 24px; /* Increase the font size for a bigger welcome message */
        font-weight: bold; /* Optional: Makes the text bold */
        margin-top: 10px; /* Adds some spacing above the welcome text */
    }
    .profile-details .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .detail-item span.label {
        font-weight: bold;
        color: #003300;
    }
    .detail-item span.value {
         color: #006400;
    }
    .edit-profile-link {
        text-align: center;
        margin-top: 80px; /* Adjust this value to move the link further down */
    }

    .edit-profile-link a {
        background-color: transparent; /* Remove the button background */
        color: #003300; /* Set the text color */
        padding: 0; /* Remove padding */
        text-decoration: underline; /* Add underline to make it look like a link */
        font-size: 16px; /* Adjust font size if needed */
    }

    .edit-profile-link a:hover {
        color: #006400; /* Change color on hover for better visibility */
        text-decoration: underline; /* Keep the underline on hover */
    }
    </style>
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
        <div class="profile-container">
            <div class="profile-header">
                <h1>User Profile</h1>
                <p>Welcome, <?php echo htmlspecialchars($row['name']); ?></p>
            </div>
            <div class="profile-details">
                <div class="detail-item">
                    <span class="label">Username:</span>
                    <span class="value"><?php echo htmlspecialchars($row['username']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Phone Number:</span>
                    <span class="value"><?php echo htmlspecialchars($row['phone']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Date of Birth:</span>
                    <span class="value"><?php echo htmlspecialchars($row['dob']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Address:</span>
                    <span class="value"><?php echo htmlspecialchars($row['address']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">ID Proof:</span>
                    <span class="value"><?php echo htmlspecialchars($row['id_proof']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">ID Number:</span>
                    <span class="value"><?php echo htmlspecialchars($row['id_number']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Password:</span>
                    <span class="value">[Password Hidden]</span>
                </div>
            </div>
            <div class="edit-profile-link">
                <a href="edit_profile.php">Edit Profile</a>
            </div>
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
