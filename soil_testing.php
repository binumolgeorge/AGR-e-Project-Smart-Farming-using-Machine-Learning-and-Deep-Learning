<?php
// Configuration
$db_host = "localhost";  // The hostname of your MySQL server (usually 'localhost' for XAMPP)
$db_username = "root";   // The MySQL username (default in XAMPP is 'root')
$db_password = "";       // The MySQL password (default is an empty string in XAMPP)
$db_name = "Agre_db";    // The name of your database

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$crop_type = isset($_POST['crop']) ? $_POST['crop'] : null;
$nitrogen_level = isset($_POST['nitrogen']) ? $_POST['nitrogen'] : null;
$phosphorus_level = isset($_POST['phosphorus']) ? $_POST['phosphorus'] : null;
$potassium_level = isset($_POST['potassium']) ? $_POST['potassium'] : null;
$pH_level = isset($_POST['pH_value']) ? $_POST['pH_value'] : null;

// Validate and sanitize inputs
$crop_type = $conn->real_escape_string($crop_type);
$nitrogen_level = $conn->real_escape_string($nitrogen_level);
$phosphorus_level = $conn->real_escape_string($phosphorus_level);
$potassium_level = $conn->real_escape_string($potassium_level);
$pH_level = $conn->real_escape_string($pH_level);

// Encode crop type
$crop_labels = ['banana' => 0, 'rice' => 1, 'coconut' => 2, 'coffee' => 3];
$label_encoded = isset($crop_labels[$crop_type]) ? $crop_labels[$crop_type] : null;

// Define paths
$python_path = "C://Python312//python.exe";
$script_path = "C://xampp//htdocs//project_sem4//predict.py";

// Prepare command to execute Python script
$command = escapeshellcmd("$python_path $script_path $crop_type $nitrogen_level $potassium_level $phosphorus_level $pH_level");
exec($command, $output, $return_var);

// Check for execution errors
if ($return_var !== 0) {
    die("Python script execution failed. Return code: $return_var Output: " . implode("\n", $output));
}

// Process output (assuming output is in CSV format)
list($nitrogen_result, $phosphorus_result, $potassium_result, $pH_result) = explode(",", implode("\n", $output));

// Insert data into table
$sql = "INSERT INTO soil_testing (crop_type, nitrogen_level, phosphorus_level, potassium_level, pH_level, nitrogen_result, phosphorus_result, potassium_result, pH_result) VALUES ('$crop_type', '$nitrogen_level', '$phosphorus_level', '$potassium_level', '$pH_level', '$nitrogen_result', '$phosphorus_result', '$potassium_result', '$pH_result')";

if ($conn->query($sql) === TRUE) {
    // Redirect to results page with query parameters
    header("Location: soil_result.html?crop=$crop_type&nitrogen=$nitrogen_result&phosphorus=$phosphorus_result&potassium=$potassium_result&ph=$pH_result");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
