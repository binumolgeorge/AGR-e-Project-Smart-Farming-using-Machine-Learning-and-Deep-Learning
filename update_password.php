<?php
// Configuration
$db_host = "localhost";  // The hostname of your MySQL server (usually 'localhost' for XAMPP)
$db_username = "root";   // The MySQL username (default in XAMPP is 'root')
$db_password = "";       // The MySQL password (default is an empty string in XAMPP)
$db_name = "Agre_db";    // The name of your database

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate passwords
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the update query
    $sql = "UPDATE farmer SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashedPassword, $username);

    if ($stmt->execute()) {
        // Redirect to the success page with a query parameter
        echo "
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>password updation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: flex-end; /* Align content to the right */
                    align-items: flex-end; /* Align content to the bottom */
                    height: 100vh;
                    margin: 0;
                    background: #f0f0f0;
                    position: relative;
                    overflow: hidden;
                }
                .message-container {
                    background: rgba(34, 49, 40, 0.8); /* Transparent dark green */
                    border-radius: 20px;
                    box-shadow: 0 0 30px rgba(34, 139, 34, 0.8), 0 0 60px rgba(34, 139, 34, 0.4); /* Enhanced glowing effect with green shadow */
                    width: 400px;
                    padding: 20px;
                    margin-right: 350px; /* Adjust the box position */
                    margin-bottom: 360px; /* Adjust the box position */
                    z-index: 1;
                    color: #d9ffd9; /* Light greenish-white for readability */
                    text-align: center;
                    animation: glow 2s ease-in-out infinite alternate; /* Glowing animation */
                }

                .message-container h2 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: bold; /* Make the heading text bolder */
                }
                .message-container p {
                    font-size: 18px;
                    margin-top: 10px;
                    font-weight: 600; /* Make the paragraph text slightly bolder */
                }
                .background-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    z-index: 0;
                }
                @keyframes glow {
                    from {
                        box-shadow: 0 0 20px rgba(34, 139, 34, 0.5);
                    }
                    to {
                        box-shadow: 0 0 60px rgba(34, 139, 34, 1);
                    }
                }
            </style>
            <script>
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 5000); // Redirect after 5 seconds
            </script>
        </head>
        <body>
            <img src='images/bg2.jpg' alt='Background Image' class='background-image'>
            <div class='message-container'>
                <h2>Password updated Successfully</h2>
                <p>You will be redirected to the login page shortly.</p>
            </div>
        </body>
        </html>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>