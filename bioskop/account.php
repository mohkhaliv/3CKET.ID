
<?php
include("config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch user details including the role
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    echo "<html>";
    echo "<body>";
    echo "<h2>Selamat datang, $username!</h2>";

    // Display user account details
    echo "<p>Detail akun Anda:</p>";
    echo "<ul>";
    echo "<li>User ID: " . $user['id'] . "</li>";
    echo "<li>Username: " . $user['username'] . "</li>";
    // Add more fields as needed
    echo "</ul>";
    if ($user['username'] === 'admin') {
        echo "<a href='admin_dashboard.php'><button>Pergi ke Dasbor Admin</button></a>";
    }
    // Display buttons
    echo "<a href='index.php'><button>Kembali ke Website setelah membeli</button></a>";
    // Clear the session after displaying the button
    unset($_SESSION['payment_completed']);
    echo "<button onclick='goBack()'>Go Back</button>";
    echo "<script>";
    echo "function goBack() {";
    echo "window.history.back();";
    echo "}";
    echo "</script>";

    // Display purchased films
    $paymentQuery = "SELECT pp.id, pp.film_id, pp.selected_seats, pp.timestamp, pp.approval, f.Judul_Film, f.Image
                    FROM payment_proofs pp
                    LEFT JOIN film f ON pp.film_id = f.ID_Film
                    WHERE pp.username='$username'
                    ORDER BY pp.timestamp DESC";

    $paymentResult = mysqli_query($db, $paymentQuery);

    while ($payment = mysqli_fetch_assoc($paymentResult)) {
        $paymentId = $payment['id'];
        $filmId = $payment['film_id'];
        $selectedSeats = $payment['selected_seats'];
        $timestamp = strtotime($payment['timestamp']);
        $approvalStatus = $payment['approval'];

        // Fetch the film details based on the specified movie ID
        $sqlFilmDetails = "SELECT * FROM film WHERE ID_Film = ?";
        $stmtFilmDetails = $db->prepare($sqlFilmDetails);
        $stmtFilmDetails->bind_param("s", $filmId);
        $stmtFilmDetails->execute();
        $resultFilmDetails = $stmtFilmDetails->get_result();

        if ($resultFilmDetails->num_rows > 0) {
            // Display film details
            $row = $resultFilmDetails->fetch_assoc();
            echo "<div class='latest'>";
            echo "<div class='box'>";
            echo "<div class='card'>";
            echo "<img src='images/" . $row['Image'] . "' style='max-width: 100px;'>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            // Display purchased details
            echo "<p>Dibeli pada: " . date('Y-m-d H:i:s', $timestamp) . "</p>";
            echo "<p>Kursi yang Anda pilih untuk " . $row['Judul_Film'] . ": $selectedSeats</p>";

            // Display approval status
            echo "<p>Status Persetujuan: " . ($approvalStatus == 1 ? 'Disetujui' : 'Belum Disetujui') . "</p>";

            // If the payment is approved, provide a link to tickets.php with the payment ID
            if ($approvalStatus == 1) {
                echo "<p><a href='tickets.php?id=$paymentId'>Lihat Tiket</a></p>";
            }
            
            // Display admin button
            
        } else {
            echo "Film not found.";
        }

        $stmtFilmDetails->close();
    }

    echo "</body>";
    echo "</html>";
} else {
    echo "Error fetching user data.";
}

$db->close();
?>


