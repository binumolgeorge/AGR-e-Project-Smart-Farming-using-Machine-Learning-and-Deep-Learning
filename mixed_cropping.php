<?php
// mixed_cropping.php

// Database connection details
$host = 'localhost'; // Your database host
$username = 'root';  // Your database username
$password = '';      // Your database password
$database = 'Agre_db'; // Your database name

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$crop_name = $_POST['crop'];
$shade_requirement = $_POST['shade'];
$complementary_crop = $_POST['complementary_crop'];

// Prepare SQL query to insert data into the correct fields
$sql = "INSERT INTO mixed_cropping (crop_name, shade_requirement, complementary_crop) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $crop_name, $shade_requirement, $complementary_crop);

// Execute the query and check for success
if ($stmt->execute()) {
    echo "Data successfully saved!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
