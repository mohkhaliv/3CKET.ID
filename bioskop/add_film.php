<?php
include("config.php");
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php"); // Redirect to the login page or another page
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addFilm'])) {
    // Retrieve form data
    $filmId = $_POST['filmId'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $releaseYear = $_POST['releaseYear'];
    $link = $_POST['link'];

    // Image handling
    $imageFileName = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];
    $imageUploadPath = $imageFileName;

    // Move the uploaded image to the desired location
    move_uploaded_file($imageTempName, "images/" . $imageFileName);

    // Insert new film into the database
    $insertFilmQuery = "INSERT INTO film (ID_Film, Judul_Film, Genre, Durasi, Deskripsi, Tahun_Rilis, Link, Image, Showing) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
    
    $insertStmt = $db->prepare($insertFilmQuery);
    $insertStmt->bind_param("ssssssss", $filmId, $title, $genre, $duration, $description, $releaseYear, $link, $imageUploadPath);
    
    if ($insertStmt->execute()) {
        // Successful insertion
        header("Location: admin_dashboard.php"); // Redirect to the admin dashboard or another page
        exit;
    } else {
        // Error in insertion
        $error_message = "Error adding the film: " . $insertStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Film</title>
    <!-- Add any additional styles or scripts needed for your form -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <header>
        <h1>Add New Film</h1>
        <!-- Add any header content specific to this page -->
    </header>

    <nav>
        <!-- Sidebar -->
        <a href="index.php">Back to 3CKET.ID</a>
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <!-- Main content area -->
        <form method="post" action="" enctype="multipart/form-data">
            <label for="filmId">Film ID:</label>
            <input type="text" id="filmId" name="filmId" required>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>
            
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="releaseYear">Release Year:</label>
            <input type="text" id="releaseYear" name="releaseYear" required>
            
            <label for="link">Link:</label>
            <input type="text" id="link" name="link" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit" name="addFilm">Add Film</button>
        </form>

        <?php
        if (isset($error_message)) {
            echo "<p>Error: $error_message</p>";
        }
        ?>
    </main>

    <footer>
        <!-- Footer content -->
        <p>&copy; <?php echo date('Y'); ?> Admin Dashboard</p>
    </footer>
</body>
</html>
