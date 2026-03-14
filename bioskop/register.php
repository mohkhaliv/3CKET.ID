<?php
include("config.php");

// Connection code (as you provided)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = mysqli_query($db, $query);

    if ($result) {
        // Registration successful, start a session and store the username
        session_start();
        $_SESSION['username'] = $username;

        // Redirect to the specified page (account.php in this case)
        header("Location: index.php");
        exit();
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>

<html>
<body>

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
<title>3CKET.ID</title>
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

      </div>
    </div>
  </div>
</div>

<h2 style="text-align:center" class="pt-5">Registration Form</h2>
<form method="post" action="" style="text-align:center">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" class="btn btn-primary">Daftar</button>
</form>

</form>

</body>
</html>
</form>
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
</body>
</html>
