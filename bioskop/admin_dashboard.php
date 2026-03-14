<?php
include("config.php");
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php"); // Redirect to the login page or another page
    exit;
}

// Fetch films from the database
$query = "SELECT * FROM film";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Error fetching films: " . mysqli_error($db));
}

// Check if the film list should be displayed
$showFilmList = isset($_GET['action']) && $_GET['action'] === 'film';

// Handle form submission to update showing values
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['showing'])) {
    $showingIds = $_POST['showing'];

    // Update selected films to showing
    $updateSelectedQuery = "UPDATE film SET Showing = 0";
    mysqli_query($db, $updateSelectedQuery);

    foreach ($showingIds as $filmId) {
        $updateFilmQuery = "UPDATE film SET Showing = 1 WHERE ID_Film = ?";
        $updateStmt = $db->prepare($updateFilmQuery);
        $updateStmt->bind_param("s", $filmId);
        $updateStmt->execute();
    }
}

// Handle form submission to add new films
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addNewFilm'])) {
    // Add logic to process adding new films
    // Redirect or display a message after processing
    header("Location: add_film.php"); // Example: Redirect to a page to add a new film
    exit;
}

// Handle form submission to delete films
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteFilm'])) {
    $filmIdsToDelete = $_POST['deleteFilm'];

    foreach ($filmIdsToDelete as $filmId) {
        // Add logic to delete the film from the database
        $deleteFilmQuery = "DELETE FROM film WHERE ID_Film = ?";
        $deleteStmt = $db->prepare($deleteFilmQuery);
        $deleteStmt->bind_param("s", $filmId);
        $deleteStmt->execute();
    }

    // Redirect to the same page after deletion
    header("Location: admin_dashboard.php?action=film");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add any additional styles or scripts needed for your admin dashboard -->
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

        nav {
            width: 250px;
            height: 100vh;
            background-color: #212529;
            padding-top: 20px;
            padding-bottom: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        nav a {
            display: block;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            margin-bottom: 10px;
            border-bottom: 1px solid #495057;
        }

        nav a:hover {
            background-color: #343a40;
        }

        main {
            margin-left: 250px;
            padding: 20px;
        }

        footer {
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: white;
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

        .update-button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        <!-- Add any header content specific to your admin dashboard -->
    </header>

    <nav>
        <!-- Sidebar -->
        <a href="javascript:history.back()">Back to Last Page</a>
        <a href="index.php">3CKET.ID</a>
        <a href="?action=film">Film</a>
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="approval.php">Approval</a>
        <a href="jadwal.php">Jadwal</a>
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <!-- Main content area -->
        <h2>Dashboard Overview</h2>

        <?php if ($showFilmList) : ?>
            <!-- Update Showing and Add New Film buttons -->
            <form method="post" action="" id="showingForm">
                <button class="update-button" type="submit">Update Showing</button>
                <button class="add-new-button" type="submit" name="addNewFilm">Add New Film</button>
                <button class="delete-button" type="submit" name="deleteFilm" onclick="return confirm('Are you sure you want to delete selected films?')">Delete Selected Films</button>

                <!-- Film Indeks -->
                <h2>Film Indeks</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Duration</th>
                            <th>Description</th>
                            <th>Release Year</th>
                            <th>Link</th>
                            <th>Image</th>
                            <th>Showing</th>
                            <th>Action</th> <!-- New column for Edit action -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <?php
                            // Check if the film has associated schedules
                            $hasSchedules = false;
                            $checkScheduleQuery = "SELECT * FROM Jadwal WHERE ID_Film = ?";
                            $checkScheduleStmt = $db->prepare($checkScheduleQuery);
                            $checkScheduleStmt->bind_param("s", $row['ID_Film']);
                            $checkScheduleStmt->execute();
                            $checkScheduleResult = $checkScheduleStmt->get_result();

                            if ($checkScheduleResult->num_rows > 0) {
                                $hasSchedules = true;
                            }

                            $checkScheduleStmt->close();
                            ?>
                            <tr>
                                <td><?php echo $row['ID_Film']; ?></td>
                                <td><?php echo $row['Judul_Film']; ?></td>
                                <td><?php echo $row['Genre']; ?></td>
                                <td><?php echo $row['Durasi']; ?></td>
                                <td><?php echo $row['Deskripsi']; ?></td>
                                <td><?php echo $row['Tahun_Rilis']; ?></td>
                                <td><?php echo $row['Link']; ?></td>
                                <td><?php echo $row['Image']; ?></td>
                                <td>
                                <?php if ($hasSchedules) : ?>
                                        <input type="checkbox" name="showing[]" value="<?php echo $row['ID_Film']; ?>"
                                               <?php echo ($row['Showing'] == 1) ? 'checked' : ''; ?>>
                                    <?php else : ?>
                                        <span>No Schedules</span>
                                    <?php endif; ?>
                                </td>
                                <td><a href="edit_film.php?id=<?php echo $row['ID_Film']; ?>">Edit</a></td>
                                <td><input type="checkbox" name="deleteFilm[]" value="<?php echo $row['ID_Film']; ?>"></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
    </main>

    <script>
    // Limit the number of checked checkboxes to 6
    document.getElementById('showingForm').addEventListener('change', function (event) {
        var checkboxes = document.querySelectorAll('input[name="showing[]"]:checked');
        if (checkboxes.length > 6) {
            event.target.checked = false;
        }
    });
</script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($db);
?>
