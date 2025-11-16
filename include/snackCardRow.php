<!-- Beispiel für wie karten in eine reie gesetzt werden können -->
<div class="d-flex justify-content-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
        $snack = include 'data/snacks/doritoCoolRanch.php';
        include 'snackCard.php';

        $snack = include 'data/snacks/doritoNachoChesse.php';
        include 'snackCard.php';
        ?>
    </div>
</div>