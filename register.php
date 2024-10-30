<?php
// Configuration
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "Agre_db";

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecting form data
    $username = $_POST['username'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $id_proof = $_POST['id-proof'];
    $id_number = $_POST['id-number'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO farmer (username, name, phone, dob, address, password, id_proof, id_number) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $username, $name, $phone, $dob, $address, $password, $id_proof, $id_number);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful
        echo "
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Registration Success</title>
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
                <h2>Registration Successful</h2>
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
