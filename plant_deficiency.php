<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if an image is uploaded
    if (isset($_FILES['plant-image']) && $_FILES['plant-image']['error'] == 0) {
        // Move the uploaded image to a temporary folder for displaying
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['plant-image']['name']);
        $filePath = $uploadDir . $fileName;
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        move_uploaded_file($_FILES['plant-image']['tmp_name'], $filePath);
        
        // Define the crop name from the form
        $cropName = $_POST['crop'];

        // Initialize a cURL session
        $curl = curl_init();

        // Define the Flask API URL
        $flaskApiUrl = "http://127.0.0.1:5000/predict";

        // Prepare the file to send via POST
        $cFile = new CURLFile($filePath, $_FILES['plant-image']['type'], $fileName);

        // Data to send to the Flask API (the image)
        $data = array('file' => $cFile);

        // cURL options
        curl_setopt($curl, CURLOPT_URL, $flaskApiUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            echo 'cURL error: ' . curl_error($curl);
        } else {
            // Decode the Flask API response (prediction result)
            $prediction = json_decode($response, true);

            // Check if prediction is available
            if (isset($prediction['prediction'])) {
                // Store the prediction for displaying in HTML
                $predictedLabel = htmlspecialchars($prediction['prediction']);
            } else {
                $predictedLabel = "Error: Unable to get prediction.";
            }
        }

        // Close the cURL session
        curl_close($curl);
    } else {
        echo "No file uploaded or there was an error uploading the file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrient Deficiency Result</title>
    <link rel="stylesheet" href="styles/styles7.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Apply the Google Font to the body */
        }
        .container {
            margin: 0 auto;
            max-width: 700px;
            height: auto; /* Remove fixed height to fit content dynamically */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        h1 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }
        p {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
        }
        h2 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-container img {
            max-width: 100%;
            max-height: 400px; /* Limit image height */
            height: auto; /* Maintain aspect ratio */
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
            object-fit: contain; /* Ensures large images are resized properly */
        }
        .details-container {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .details-container p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 50px;
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
                    <a>Services</a>
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
<br><br><br>
<div class="container">
    <h1>Plant Nutrient Deficiency Result</h1>
    
    <?php if (isset($predictedLabel)): ?>
        <div class="result-container">
            <!-- Image Container -->
            <div class="image-container">
                <h2>Uploaded Image:</h2>
                <img src="<?php echo $filePath; ?>" alt="Uploaded plant image">
            </div>
            
            <!-- Prediction Details Container -->
            <div class="details-container">
                <h2>Prediction Details:</h2>
                <p><strong>Crop Name:</strong> <?php echo htmlspecialchars($cropName); ?></p>
                <p><strong>Prediction:</strong> <?php echo $predictedLabel; ?></p>
            </div>
        </div>
    <?php else: ?>
        <p>No prediction available.</p>
    <?php endif; ?>
</div>

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
