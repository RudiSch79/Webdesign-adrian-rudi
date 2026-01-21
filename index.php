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

            <div class="text-center my-5">
                <?php if ($isLoggedIn): ?>
                    <h1 class="fw-semibold">
                        Welcome back to SnackScout,
                        <strong><?= e($username) ?></strong>! 👋
                    </h1>
                <?php else: ?>
                    <h1 class="fw-semibold">Welcome to SnackScout</h1>
                <?php endif; ?>
            </div>

            <div class="text-center mb-5">
                <a href="forum.php" class="btn btn-dark btn-lg mx-3 px-4">Forum</a>
                <a href="snacks.php" class="btn btn-dark btn-lg mx-3 px-4">Reviews</a>
            </div>

            <hr class="border-4 border-dark my-5">

            <?php include "include/snackCardsTopRated.php"; ?>
            <?php include "include/snackCardsRecent.php"; ?>

        </div>
    </main>
<?php include "include/footer.php"; ?>
