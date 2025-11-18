<?php
include 'include/header.php';
?>

<body class="d-flex flex-column">
    <div class="container-fluid row my-5">
        <div class="col-5">
            <img src="data/snackImages/Doritos-Tortilla-Chips-Nacho-Cheese-Flavored-Snack-Chips-2-75-oz-Bag_c6a6c39d-c8b5-4478-beba-35e7f14e8171.26c8df2fae95aeb86bc07a3c81489399.avif" class="img-fluid" alt="...">
        </div>

        <div class="col pt-5">
            <h1 class="display-1 fw-bold">Nacho Cheese</h1>
            <h1 class="display-3">Dorritos</h1>

            <div class="d-flex justify-content-start mb-3">
                <img src="data/images/star.png" 
                    class=""
                    style="height:60px; object-fit:contain;" 
                    alt="Star">
                <p class="fs-1 mx-3 fw-bold">8</p>
            </div>

            <div class="d-flex space my-5">
            <a href="#" class="btn btn-lg btn-primary me-4">Write Review</a>
            <a href="#" class="btn btn-lg btn-primary">Eaten +1</a>
            </div> 
            <?php
                include 'include/reviewCard.php';
            ?>
        </div>
    </div>