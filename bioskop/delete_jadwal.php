<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $jadwalId = $_GET["id"];

    // Perform the deletion
    $deleteQuery = "DELETE FROM Jadwal WHERE ID_Jadwal = '$jadwalId'";
    $deleteResult = mysqli_query($db, $deleteQuery);

    if ($deleteResult) {
        echo "Jadwal deleted successfully.";

        // Redirect back to jadwal.php after deletion
        echo '<script>window.location.replace("jadwal.php");</script>';
        exit();
    } else {
        echo "Error deleting jadwal: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
