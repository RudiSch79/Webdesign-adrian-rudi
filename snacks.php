<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'include/header.php';
?>

<body class="d-flex flex-column min-vh-100">

<!--Sucess Notification if new snack added-->
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $_SESSION['success'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php 
    unset($_SESSION['success']); 
endif; ?>

<div class="d-flex flex-column align-items-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
        $snacks = include __DIR__ . '/data/snacksArray.php';
        foreach ($snacks as $snack) {
            include 'include/snackCard.php';
        }
        ?>
    </div>
</div>

<div class="mx-auto mt-4">
    <?php
    include 'include/snackCardAddNew.php';
    ?>
</div>

<?php
include 'include/footer.php';
?>