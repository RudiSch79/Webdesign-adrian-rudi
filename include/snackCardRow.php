<!-- Beispiel für wie karten in eine reie gesetzt werden können -->
<div class="d-flex justify-content-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
        $snacks = include 'data/snacks.php';

        $snack = $snacks[0];
        include 'snackCard.php';

        $snack = $snacks[1];
        include 'snackCard.php';
        ?>
    </div>
</div>