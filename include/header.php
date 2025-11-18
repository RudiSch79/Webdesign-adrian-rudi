<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$username   =  $isLoggedIn ? $_SESSION['user']['username'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnackScout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
  <div class="container">

    <a class="navbar-brand" href="./index.php">SnackScout</a>

    <div class="collapse navbar-collapse" id="blogNav">

      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/forum.php">Forum</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/reviews.php">Reviews</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">

        <?php if (!$isLoggedIn): ?>
        <li class="nav-item">
            <a class="btn btn-outline-light me-lg-2" href="./login.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-warning text-dark" href="./register.php">Register</a>
        </li> 

        <?php else: ?>
        <li class="nav-item dropdown">
            <a class="btn btn-outline-light dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <?= htmlspecialchars($username) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="./logout.php">Logout</a></li>
            </ul>
        </li>
        <?php endif; ?>

      </ul>

    </div>
  </div>
</nav>

    
