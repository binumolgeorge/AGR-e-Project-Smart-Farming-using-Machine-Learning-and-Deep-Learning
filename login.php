<?php
/**
 * Login script for authenticating farmers.
 * Connects to the database, validates user credentials, and redirects based on login outcome.
 */

// Configuration
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "Agre_db";

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecting form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, name, password FROM farmer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['farmer_id'] = $row['id']; // Store farmer ID
            $_SESSION['username'] = $username;   // Store username
            $_SESSION['name'] = $row['name'];    // Store name
            
            header('Location: home.html'); // Redirect to the home page upon successful login
            exit();
        } else {
            header('Location: login.html?error=1'); // Redirect with error parameter
            exit();
        }
    } else {
        header('Location: login.html?error=1'); // Redirect with error parameter
        exit();
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
