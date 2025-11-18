<div class="d-flex flex-column justify-content-center">
    <h1>Top Rated</h1>
    <div class="container-lg row justify-content-center">
        <?php
            $snacks = include './data/snacksArray.php';

            // Sort by rating in descending order
            usort($snacks, function($a, $b) {
                return $b['rating'] - $a['rating'];
            });

            $topSnacks = array_slice($snacks, 0, 3);

            foreach ($topSnacks as $snack) {
                include 'snackCard.php';
            }
            include 'snackCardShowAll.php';
        ?>
    </div>
</div>