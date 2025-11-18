<?php
include 'include/header.php';
?>

<body class="d-flex flex-column min-vh-100">

<div class="d-flex justify-content-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
        $snacks = include __DIR__ . '/data/snacks.php';

        // Loop through all snacks
        foreach ($snacks as $snack) {
            include 'include/snackCard.php';
        }

        include 'snackCardShowAll.php';
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