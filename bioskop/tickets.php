<style>
    .ticket-container {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        max-width: 400px; /* Set the maximum width for the ticket container */
        margin: 0 auto; /* Center the ticket container on the page */
    }

    /* Add some margin between ticket containers */
    .ticket-container + .ticket-container {
        margin-top: 20px;
    }
</style>

<?php
include("config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve payment_proofs details based on ID
if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    // Fetch payment proof details
    $paymentQuery = "SELECT pp.film_id, pp.selected_seats, pp.timestamp, f.Judul_Film, j.Tanggal, j.Jadwal_Mulai, j.Jadwal_Selesai
                    FROM payment_proofs pp
                    LEFT JOIN film f ON pp.film_id = f.ID_Film
                    LEFT JOIN Jadwal j ON f.ID_Film = j.ID_Film
                    WHERE pp.id='$paymentId'";
    $paymentResult = mysqli_query($db, $paymentQuery);

    if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
        $paymentDetails = mysqli_fetch_assoc($paymentResult);

        echo "<html>";
        echo "<body>";

        // Display selected seats
        $selectedSeats = explode(" ", $paymentDetails['selected_seats']);
        
        // Fetch film details outside the loop
        $filmDetailsQuery = "SELECT * FROM film WHERE ID_Film = '{$paymentDetails['film_id']}'";
        $filmDetailsResult = mysqli_query($db, $filmDetailsQuery);
        $filmDetails = mysqli_fetch_assoc($filmDetailsResult);

        // Display film details for each seat
        for ($i = 0; $i < count($selectedSeats); $i++) {
            $seat = $selectedSeats[$i];

            echo "<div class='ticket-container'>";

            echo "<h2 style='text-align: center;'>Tiket Anda - " . $filmDetails['Judul_Film'] . "</h2>";


            // Display additional film details for each ticket
            echo "<p>Judul Film: " . $paymentDetails['Judul_Film'] . "</p>";
            echo "<p>Kursi: $seat</p>";
            echo "<p>Tanggal: " . $paymentDetails['Tanggal'] . "</p>";
            echo "<p>Jadwal: " . $paymentDetails['Jadwal_Mulai'] . ' - ' . $paymentDetails['Jadwal_Selesai'] . "</p>";
            // Add additional ticket information here

            echo "</div>";
        }

        echo "<button onclick='goBack()'>Kembali</button>";
        echo "</body>";
        echo "</html>";
    } else {
        echo "Payment proof not found.";
    }
} else {
    echo "Invalid payment proof ID.";
}

// Close the database connection
mysqli_close($db);
?>

<script>
function goBack() {
    window.history.back();
}
</script>
