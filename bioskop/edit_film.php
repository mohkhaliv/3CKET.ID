<?php
include("config.php");
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php"); // Redirect to the login page or another page
    exit;
}

// Check if film ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect to the film list if ID is not provided or invalid
    header("Location: admin_dashboard.php?action=film");
    exit;
}

$filmId = $_GET['id'];

// Fetch film details based on the provided film ID
$query = "SELECT * FROM film WHERE ID_Film = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $filmId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    // Redirect to the film list if film ID is not found
    header("Location: admin_dashboard.php?action=film");
    exit;
}

$row = $result->fetch_assoc();

// Handle form submission to update film details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judulFilm = $_POST["judul_film"];
    $genre = $_POST["genre"];
    $durasi = $_POST["durasi"];
    $deskripsi = $_POST["deskripsi"];
    $tahunRilis = $_POST["tahun_rilis"];
    $link = $_POST["link"];

    // Handle image upload
    $imageUploadPath = "images/"; // Set your upload directory
    $imageFileName = $_FILES["image"]["name"];
    $imageTempName = $_FILES["image"]["tmp_name"];

    if (!empty($imageFileName)) {
        // Upload the new image file
        $newImageFilePath = $imageUploadPath . $imageFileName;
        move_uploaded_file($imageTempName, $newImageFilePath);

        // Update the image file path in the database
        $updateImagePathQuery = "UPDATE film SET Image=? WHERE ID_Film=?";
        $updateImagePathStmt = $db->prepare($updateImagePathQuery);
        $updateImagePathStmt->bind_param("ss", $newImageFilePath, $filmId);
        $updateImagePathStmt->execute();
    }

    // Update film details in the database
    $updateQuery = "UPDATE film SET Judul_Film=?, Genre=?, Durasi=?, Deskripsi=?, Tahun_Rilis=?, Link=? WHERE ID_Film=?";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bind_param("sssssss", $judulFilm, $genre, $durasi, $deskripsi, $tahunRilis, $link, $filmId);
    $updateStmt->execute();

    // Redirect to the film list after updating
    header("Location: admin_dashboard.php?action=film");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film - Admin Dashboard</title>
    <!-- Add any additional styles or scripts needed for your admin dashboard -->
    <style>
        /* Your styles go here */
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        <!-- Add any header content specific to your admin dashboard -->
    </header>

    <nav>
        <!-- Sidebar -->
        <a href="index.php">Back to 3CKET.ID</a>
        <a href="admin_dashboard.php?action=film">Film Indeks</a>
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <!-- Main content area -->
        <h2>Edit Film</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <!-- Film details form -->
            <label for="judul_film">Title:</label>
            <input type="text" name="judul_film" value="<?php echo $row['Judul_Film']; ?>" required>
            <br>
            <label for="genre">Genre:</label>
            <input type="text" name="genre" value="<?php echo $row['Genre']; ?>" required>
            <br>
            <label for="durasi">Duration:</label>
            <input type="text" name="durasi" value="<?php echo $row['Durasi']; ?>" required>
            <br>
            <label for="deskripsi">Description:</label>
            <textarea name="deskripsi" required><?php echo $row['Deskripsi']; ?></textarea>
            <br>
            <label for="tahun_rilis">Release Year:</label>
            <input type="text" name="tahun_rilis" value="<?php echo $row['Tahun_Rilis']; ?>" required>
            <br>
            <label for="link">Link:</label>
            <input type="text" name="link" value="<?php echo $row['Link']; ?>" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" name="image">
            <br>
            <input type="submit" value="Update Film">
        </form>
    </main>

    <footer>
        <!-- Footer content -->
        <p>&copy; <?php echo date('Y'); ?> Admin Dashboard</p>
    </footer>
</body>
</html>

<?php
// Close the prepared statement and the database connection
$stmt->close();
$db->close();
?>
