<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitPaymentProof"])) {
    // Assuming you have a users table with a column 'username' as a foreign key
    $username = $_SESSION['username'];
    $filmId = $_POST["filmId"];
    $selectedSeats = $_POST["selectedSeats"];

    // Process uploaded file
    $targetDirectory = "payment_proofs/"; // Create this directory in your project
    $targetFile = $targetDirectory . basename($_FILES["paymentProof"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["paymentProof"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["paymentProof"]["size"] > 5000000) { // Adjust the file size limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowedFormats = array("jpg", "jpeg", "png");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["paymentProof"]["tmp_name"], $targetFile)) {
            echo "The file " . basename($_FILES["paymentProof"]["name"]) . " has been uploaded.";
            echo "<br>";
            echo "Payment successful.";

// Insert the payment proof information into the database
$insertQuery = "INSERT INTO payment_proofs (username, film_id, selected_seats, proof_path, user_id) VALUES (?, ?, ?, ?, (SELECT id FROM users WHERE username = ?))";
$stmt = $db->prepare($insertQuery);
$stmt->bind_param("sssss", $username, $filmId, $selectedSeats, $targetFile, $username);
$stmt->execute();
$stmt->close();


            // Redirect to index.php after successful payment
            header("Location: account.php");
            exit;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
