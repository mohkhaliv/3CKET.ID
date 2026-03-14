<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
<html> 
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="images/favicon (1).ico" type="images/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
        .carousel-caption-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.6) 100%);
            padding: 20px; /* Adjust the padding as needed */
        }

        .carousel-caption h1,
        .carousel-caption p,
        .carousel-caption a {
            color: #fff; /* Set text color to white or your desired color */
            margin-bottom: 10px; /* Adjust the margin as needed */
        }
        #myCarousel img {
            width: 100%; /* Make the images fill the container */
            height: auto; /* Maintain aspect ratio */
            max-width: 1920px; /* Set a maximum width if needed */
            max-height: 600px; /* Set a maximum height if needed */
            object-fit: cover; /* Ensure the image covers the entire container */
        }
    </style>
<?php
include("config.php");

if (isset($_GET['id'])) {
    $filmId = $_GET['id'];

    // Use prepared statement to avoid SQL injection
    $sql = "SELECT Judul_Film FROM film WHERE ID_Film = ?";
    $stmt = $db->prepare($sql);
    
    // Bind the parameter as a string
    $stmt->bind_param("s", $filmId);
    
    // Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $filmDetails = $result->fetch_assoc();
        $filmTitle = $filmDetails['Judul_Film'];

        // Set the page title dynamically
        echo "<title>" . $filmTitle . " - 3CKET.ID</title>";
    } else {
        echo "<title>Film Not Found - 3CKET.ID</title>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<title>Invalid Film ID - 3CKET.ID</title>";
}


?>
</head>

<body style="color:white">
<nav class="navbar navbar-expand-sm sticky-top navbar-dark navbar-shadow" style="height: 80px; background-color:#011C3C">

  <div class="container-fluid d-flex justify-content-between align-items-center" style="">
  <a class="navbar-brand ps-4" href="http://127.0.0.1/bioskop/index.php">
            <img src="images/3CKETs.png" alt="Logo 3CKET.ID" style="width:45%">
        </a>
    <!-- Links -->
    <ul class="navbar-nav pe-5 me-5" style="padding-right: 50px">
    <li class="nav-item">
        <a class="nav-link active" href="http://127.0.0.1/bioskop/index.php">Beranda</a>
<li class="dropdown">
      <button type="button" class="btn dropdown-toggle" style="color:white" data-bs-toggle="dropdown">
    Indeks
  </button>
  <ul class="dropdown-menu dropdown-menu-dark" style="background-color:#011C3C">
    <li><a class="dropdown-item" href="http://127.0.0.1/bioskop/film.php">Film</a></li>
  </ul>
      </li>
      </ul>
</li>


    <div class="d-flex gap-2 pe-4">
    <?php
    if (isset($_SESSION['username'])) {
        // User is logged in, show the Keluar button
        echo '<a type="button" class="btn btn-outline-primary rounded-5 fw-bold" href="logout.php" style="width:100px">Keluar</a>';
    } else {
        // User is not logged in, show the Daftar button
        echo '<a type="button" class="btn btn-outline-primary rounded-5 fw-bold" href="http://127.0.0.1/bioskop/register.php" style="width:100px">Daftar</a>';
    }
    ?>
    <?php
    if (isset($_SESSION['username'])) {
        // User is logged in, show the Akun button
        echo '<a type="button" class="btn btn-primary rounded-5 fw-bold"  style="width:100px;color:#011C3C" href="account.php">Akun</a>';
    } else {
        // User is not logged in, show the Masuk button
        echo '<button type="button" class="btn btn-primary rounded-5 fw-bold"  style="width:100px;color:#011C3C" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</button>';
    }
    ?>


    </div>
  </div>

</nav>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" >
      <div class="modal-header" style="background-color:#011C3C">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color:#011C3C">
        <!-- Your login form content goes here -->
        <?php
        if (isset($errorMessage)) {
            echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="second pt-4" style="background: linear-gradient(to top, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.1) 100%);">


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
        echo "<img src='images/" . $row['Image'] . "'>";
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


</div>

<!-- Details Baru -->
<?php
include("config.php");

if (isset($_SESSION['username'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}

if (isset($_GET['id'])) {
    $filmId = $_GET['id'];

    // Use prepared statement to avoid SQL injection
    $sql = "SELECT * FROM film WHERE ID_Film = ?";
    $stmt = $db->prepare($sql);
    
    // Bind the parameter as a string
    $stmt->bind_param("s", $filmId);
    
    // Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $filmDetails = $result->fetch_assoc();

        // Display film details here
        echo "<div style='position: absolute; top: 12%; left: 23%;'>";
        echo "<h1><b>" . $filmDetails['Judul_Film'] . "</b></h1>";
        echo "<p>" . $filmDetails['Genre'] . " <i class='fa-regular fa-clock ps-3' style='color: #ffd500; margin-right: 2px;'></i> " . $filmDetails['Durasi'] . " Menit</p>";
        echo "<p style='max-width: 1000px;'>" . $filmDetails['Deskripsi'] . "</p>";
        echo "<br>";
        echo "<p style='opacity: 0.5;'>Tahun Rilis:</p>";
        echo "<p>" . $filmDetails['Tahun_Rilis'] . "</p>";
        echo "<br>";
        $sqlCheckSchedule = "SELECT * FROM Jadwal WHERE ID_Film = ?";
        $stmtCheckSchedule = $db->prepare($sqlCheckSchedule);
        $stmtCheckSchedule->bind_param("s", $filmId);
        $stmtCheckSchedule->execute();
        $resultCheckSchedule = $stmtCheckSchedule->get_result();
        
        if ($resultCheckSchedule->num_rows > 0) {
            // Schedules exist, display the schedule information
            echo "<p style='opacity: 0.5;'>Jadwal Tayang:</p>";
        
            // Loop through each schedule and display the time
            while ($rowSchedule = $resultCheckSchedule->fetch_assoc()) {
                echo "<p>". $rowSchedule['Tanggal'] ."<i class='fa-regular fa-calendar ps-1 pe-4' style='color: #ffd500; margin-left: 2px;'></i>". $rowSchedule['Jadwal_Mulai'] .'-'. $rowSchedule['Jadwal_Selesai'] ."</p>";
            }
        }
        
        $stmtCheckSchedule->close();
        echo "<br>";
        echo "<a href='" . $filmDetails['Link'] . "' target='_blank' class='btn btn-outline-primary rounded-5 fw-bold' style='width:100px;'>Trailer</a>";
        

        if ($row['Showing'] == 1) {
            // Check if the user is logged in
            if ($isLoggedIn) {
                echo "<a href='seat.php?id=" . $row['ID_Film'] . "' type='button' class='btn btn-primary rounded-5 fw-bold' style='width:100px;color:#011C3C;margin-left: 8px;'>Pesan</a>";
            } else {
                // User is not logged in, display the login message
                echo "<a href='seat.php?id=" . $row['ID_Film'] . "' type='button' class='btn btn-primary rounded-5 fw-bold disabled' style='width:100px;color:#011C3C;margin-left: 8px;'>Pesan</a>";
                echo "<p style='color: #ff0000; font-size: 14px;' class='pt-2'>Log in untuk memesan</p>";
            }
        } else {
            // Film is not showing
        echo "<p style='color: #fff0000; opacity: 0.5; font-size: 14px;' class='pt-2'>Film ini tidak sedang tayang.</p>";
        }
        
        echo "</div>";
        // Add more details as needed
    } else {
        echo "Film not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid film ID.";
}

$db->close();
?>
<br>
<div class="container fixed-bottom" data-bs-theme="dark">
  <footer>
    <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
      <p>&copy; 2023 3CKET.ID, Inc. All rights reserved.</p>
      <ul class="list-unstyled d-flex">
        <li class="ms-3"><a class="link-body-emphasis" href="https://twitter.com/" style="font-size: 24px;" target="_blank"><i class="fab fa-twitter"></i><use xlink:href="#twitter"/></svg></a></li>
        <li class="ms-3"><a class="link-body-emphasis" href="https://www.instagram.com/" style="font-size: 24px;" target="_blank"><i class="fab fa-instagram"></i><use xlink:href="#instagram"/></svg></a></li>
        <li class="ms-3"><a class="link-body-emphasis" href="https://www.facebook.com/" style="font-size: 24px;" target="_blank"><i class="fab fa-facebook"></i><use xlink:href="#facebook"/></svg></a></li>
      </ul>
    </div>
    <ul class="pe-5 ps-4" style="position:relative; bottom:50px;">
    <li><a href="tentang.php" class="nav-link px-2 text-body-secondary">Tentang</a></li>
    <li><a href="syarat.php" class="nav-link px-2 text-body-secondary">Syarat & Kebijakan Privasi</a></li>
    </ul>

  </footer>
</div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>

