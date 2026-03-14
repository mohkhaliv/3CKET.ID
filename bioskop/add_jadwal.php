<?php
include("config.php");

// Fetch existing film IDs for the dropdown
$filmIdsQuery = "SELECT ID_Film FROM film";
$filmIdsResult = mysqli_query($db, $filmIdsQuery);

$filmIds = [];
while ($row = mysqli_fetch_assoc($filmIdsResult)) {
    $filmIds[] = $row['ID_Film'];
}

// Handle form submission to add new schedules
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addJadwal'])) {
    // Retrieve form data
    $jadwalId = $_POST['jadwalId']; // Include this line to fetch ID from the form
    $jadwalMulai = $_POST['jadwalMulai'];
    $jadwalSelesai = $_POST['jadwalSelesai'];
    $tanggal = $_POST['tanggal'];
    $idStudio = $_POST['idStudio'];
    $idFilm = $_POST['idFilm'];

    // Add the new schedule to the database
    $insertQuery = "INSERT INTO Jadwal (ID_Jadwal, Jadwal_Mulai, Jadwal_Selesai, Tanggal, ID_Studio, ID_Film) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bind_param("ssssss", $jadwalId, $jadwalMulai, $jadwalSelesai, $tanggal, $idStudio, $idFilm);

    if ($stmt->execute()) {
        // Schedule added successfully, redirect to jadwal.php or another page
        header("Location: jadwal.php");
        exit;
    } else {
        // Error in adding schedule
        $errorMessage = "Error adding schedule: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Jadwal</title>
    <!-- Add any additional styles or scripts needed for your add_jadwal page -->
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
            max-width: 600px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Add New Jadwal</h1>
        <!-- Add any header content specific to your add_jadwal page -->
    </header>

    <main>
        <!-- Main content area -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="jadwalId">ID Jadwal:</label>
            <input type="text" name="jadwalId" required>

            <label for="jadwalMulai">Jadwal Mulai:</label>
            <input type="text" name="jadwalMulai" required>

            <label for="jadwalSelesai">Jadwal Selesai:</label>
            <input type="text" name="jadwalSelesai" required>

            <label for="tanggal">Tanggal:</label>
            <input type="text" name="tanggal" required>

            <label for="idStudio">ID Studio:</label>
            <input type="text" name="idStudio" required>

            <label for="idFilm">ID Film:</label>
            <select name="idFilm" required>
                <?php foreach ($filmIds as $filmId) : ?>
                    <option value="<?php echo $filmId; ?>"><?php echo $filmId; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="addJadwal">Add Jadwal</button>
        </form>

        <?php
        if (isset($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
    </main>

    <!-- Add any additional footer content if needed -->

</body>
</html>

<?php
// Close the database connection
mysqli_close($db);
?>
