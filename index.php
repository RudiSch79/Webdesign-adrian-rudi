<?php
require_once "include/config.php";

$user = current_user();
$isLoggedIn = $user !== null;
$username = $isLoggedIn ? $user["username"] : "";

include "include/header.php";
?>

<body class="d-flex flex-column min-vh-100">
    <main>
        <div class="container py-5">
            <div class="text-center my-5 p-5 rounded-4 shadow-lg text-light"
                style="background: linear-gradient(135deg, #ff7e5f, #feb47b);">
                <?php if ($isLoggedIn): ?>
                    <h1 class="fw-bold mb-3 animate__fadeIn">
                        Welcome back, <strong><?= e($username) ?></strong>! 👋
                    </h1>
                    <p class="lead animate__fadeIn">Discover your next favorite snack 🍫</p>
                <?php else: ?>
                    <h1 class="fw-bold mb-3 animate__fadeIn">Welcome to SnackScout</h1>
                    <p class="lead animate__fadeIn">Your adventure in snacks starts here 🍿</p>
                <?php endif; ?>
                <a href="snacks.php" class="btn btn-light btn-lg px-4 py-2 rounded-pill fs-4 fw-bold text-center">Dive in</a>
            </div>

            <hr class="border-4 border-dark my-5">

            <?php include "include/snackCardsTopRated.php"; ?>
            <?php include "include/snackCardsRecent.php"; ?>

        </div>
    </main>
<?php include "include/footer.php"; ?>
