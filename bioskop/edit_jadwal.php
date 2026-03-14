<?php
include("config.php");

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $jadwalId = $_GET['id'];

    // Fetch the schedule details from the database
    $query = "SELECT * FROM Jadwal WHERE ID_Jadwal = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $jadwalId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $jadwalDetails = $result->fetch_assoc();

        // Fetch existing film IDs for the dropdown
        $filmIdsQuery = "SELECT ID_Film FROM film";
        $filmIdsResult = mysqli_query($db, $filmIdsQuery);

        $filmIds = [];
        while ($row = mysqli_fetch_assoc($filmIdsResult)) {
            $filmIds[] = $row['ID_Film'];
        }

        // Handle form submission to update schedule details
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateJadwal'])) {
            // Update the schedule details in the database
            $updatedJadwalMulai = $_POST['jadwalMulai'];
            $updatedJadwalSelesai = $_POST['jadwalSelesai'];
            $updatedTanggal = $_POST['tanggal'];
            $updatedIdStudio = $_POST['idStudio'];
            $updatedIdFilm = $_POST['idFilm'];

            $updateQuery = "UPDATE Jadwal SET Jadwal_Mulai=?, Jadwal_Selesai=?, Tanggal=?, ID_Studio=?, ID_Film=? WHERE ID_Jadwal=?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("ssssss", $updatedJadwalMulai, $updatedJadwalSelesai, $updatedTanggal, $updatedIdStudio, $updatedIdFilm, $jadwalId);

            if ($updateStmt->execute()) {
                // Schedule details updated successfully
                header("Location: jadwal.php"); // Redirect to the schedule list page
                exit;
            } else {
                // Handle the error if the update fails
                echo "Error updating schedule details: " . $updateStmt->error;
            }

            $updateStmt->close();
        }
    } else {
        // Schedule not found
        echo "Schedule not found.";
        exit;
    }
} else {
    // ID not provided in the URL
    echo "Invalid request. Please provide a schedule ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <!-- Add any additional styles or scripts needed for your edit_jadwal page -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        header {
            background-color: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        form {
            width: 50%;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            background-color: #343a40;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Jadwal</h1>
        <!-- Add any header content specific to your edit_jadwal page -->
    </header>

    <main>
        <!-- Main content area -->
        <form method="post" action="">
            <label for="jadwalMulai">Jadwal Mulai:</label>
            <input type="text" name="jadwalMulai" value="<?php echo $jadwalDetails['Jadwal_Mulai']; ?>" required>

            <label for="jadwalSelesai">Jadwal Selesai:</label>
            <input type="text" name="jadwalSelesai" value="<?php echo $jadwalDetails['Jadwal_Selesai']; ?>" required>

            <label for="tanggal">Tanggal:</label>
            <input type="text" name="tanggal" value="<?php echo $jadwalDetails['Tanggal']; ?>" required>

            <label for="idStudio">ID Studio:</label>
            <input type="text" name="idStudio" value="<?php echo $jadwalDetails['ID_Studio']; ?>" required>

            <label for="idFilm">ID Film:</label>
            <select name="idFilm" required>
                <?php foreach ($filmIds as $filmId) : ?>
                    <option value="<?php echo $filmId; ?>" <?php echo ($filmId == $jadwalDetails['ID_Film']) ? 'selected' : ''; ?>><?php echo $filmId; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="updateJadwal">Update Jadwal</button>
        </form>
    </main>

    <!-- Add any additional footer content if needed -->

</body>
</html>

<?php
// Close the database connection
mysqli_close($db);
?>
