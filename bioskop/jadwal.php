<?php
include("config.php");

// Fetch schedules from the database
$query = "SELECT * FROM Jadwal";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Error fetching schedules: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal</title>
    <!-- Add any additional styles or scripts needed for your jadwal page -->
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        .add-new-button {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
        }

        .add-new-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Jadwal</h1>
        <!-- Add any header content specific to your jadwal page -->
    </header>

    <main>
        <!-- Main content area -->
        <form action="admin_dashboard.php">
            <button type="submit" class="btn">Admin Dashboard</button>
        </form>
        <h2>Schedule Indeks</h2>
        <a href="add_jadwal.php" class="add-new-button">Add New Jadwal</a> <!-- Add New Jadwal button -->
        <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Jadwal Mulai</th>
            <th>Jadwal Selesai</th>
            <th>Tanggal</th>
            <th>ID Studio</th>
            <th>ID Film</th>
            <th>Action</th>
            <th>Action</th> <!-- New column for Delete action -->
        </tr>
    </thead>
    <tbody>
        <?php while ($jadwal = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $jadwal['ID_Jadwal']; ?></td>
                <td><?php echo $jadwal['Jadwal_Mulai']; ?></td>
                <td><?php echo $jadwal['Jadwal_Selesai']; ?></td>
                <td><?php echo $jadwal['Tanggal']; ?></td>
                <td><?php echo $jadwal['ID_Studio']; ?></td>
                <td><?php echo $jadwal['ID_Film']; ?></td>
                <td><a href="edit_jadwal.php?id=<?php echo $jadwal['ID_Jadwal']; ?>">Edit</a></td>
                <td><a href="delete_jadwal.php?id=<?php echo $jadwal['ID_Jadwal']; ?>" onclick="return confirm('Are you sure you want to delete this jadwal?')">Delete</a></td> <!-- Delete button -->
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </main>

    <!-- Add any additional footer content if needed -->

</body>
</html>

<?php
// Close the database connection
mysqli_close($db);
?>
