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
            header("Location: account.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans" rel="stylesheet">
    <link rel="icon" href="images/favicon (1).ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css"/>
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
    <li><a class="dropdown-item" href="http://127.0.0.1/bioskop/jadwal.php">Jadwal</a></li>
    <li><a class="dropdown-item" href="http://127.0.0.1/bioskop/petugas.php">Petugas</a></li>
  </ul>
      </li>
      </ul>
    <div class="d-flex gap-2 pe-4">
    <a type="button" class="btn btn-outline-primary rounded-5 fw-bold" href="http://127.0.0.1/bioskop/register.php" style="width:100px">Daftar</a>
    <a type="button" class="btn btn-primary rounded-5 fw-bold" href="http://127.0.0.1/bioskop/login.php" style="width:100px;color:#011C3C">Masuk</a>
    </div>
  </div>

</nav>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
  Launch Login Modal
</button>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
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
        <form method="post" action="">
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
