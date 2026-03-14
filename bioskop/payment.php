<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {
            $_SESSION['username'] = $username;

            // Redirect logic based on username
            if ($username === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: account.php");
            }
            
            exit;
        } else {
            $errorMessage = "Incorrect password. Please try again.";
        }
    } else {
        $errorMessage = "User not found. Please register.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Details</title>
  <!-- Add your CSS styles here -->
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 20px;
    }

    .payment-details {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <h1>Payment Details</h1>

  <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve selected seats and total price from the form data
      $selectedSeats = isset($_POST["selectedSeats"]) ? $_POST["selectedSeats"] : "";
      $totalPrice = isset($_POST["totalPrice"]) ? $_POST["totalPrice"] : "";

      // Display payment details
      if (!empty($selectedSeats) && !empty($totalPrice)) {
        echo '<div class="payment-details">';
        echo '<h2>Selected Seats:</h2>';
        echo '<p>' . $selectedSeats . '</p>';
        echo '<h2>Total Price:</h2>';
        echo '<p>Rp ' . number_format($totalPrice) . '</p>';
        echo '</div>';
      } else {
        echo '<p>No payment details available.</p>';
      }
    } else {
      echo '<p>Invalid request.</p>';
    }
  ?>

<?php
if (isset($_GET['id'])) {
    $filmId = $_GET['id'];

    // Fetch the film details based on the specified movie ID
    $sqlFilmDetails = "SELECT * FROM film WHERE ID_Film = ? LIMIT 1";
    $stmtFilmDetails = $db->prepare($sqlFilmDetails);
    $stmtFilmDetails->bind_param("s", $filmId);
    $stmtFilmDetails->execute();
    $resultFilmDetails = $stmtFilmDetails->get_result();

    if ($resultFilmDetails->num_rows > 0) {
        echo "<div class='latest'>";
        echo "<div class='box'>";
        $row = $resultFilmDetails->fetch_assoc();
        echo "<div class='card'>";
        echo "<img src='images/" . $row['Image'] . "' style='max-width: 200px;'>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "Film not found.";
    }

    $stmtFilmDetails->close();
} else {
    echo "Invalid film ID.";
}

$db->close();
?>
<!-- Add this form within the body of your HTML -->
<form action="process_payment.php" method="post" enctype="multipart/form-data">
    <!-- Existing form elements... -->

    <!-- Add these hidden input fields within the form in your HTML -->
    <input type="hidden" name="filmId" value="<?php echo $filmId; ?>">
    <input type="hidden" name="selectedSeats" value="<?php echo $selectedSeats; ?>">

    <label for="paymentProof">Upload Payment Proof:</label>
    <input type="file" name="paymentProof" id="paymentProof" accept=".jpg, .jpeg, .png" required>
    <br>
    <input type="submit" value="Submit Payment Proof" name="submitPaymentProof">
  </form>
  <p style="color: red; font-weight: bold;">JIKA SUDAH SUBMIT TIDAK BISA DITARIK</p>


</body>
</html>
