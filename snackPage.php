<?php
include 'include/header.php';
$snacks = include 'data/snacksArray.php';
$id = $_GET['id'];
$snack = $snacks[$id];
?>

<body class="d-flex flex-column">
    <div class="container-fluid row my-5">
        <div class="col-5">
            <img src="<?= $snack['image'] ?>" class="w-100 h-100 object-fit-cover" alt="...">
        </div>

        <div class="col pt-5">
            <h1 class="display-1 fw-bold"><?= $snack['name'] ?></h1>
            <h1 class="display-3"><?= $snack['brand'] ?></h1>

            <div class="d-flex justify-content-start mb-3">
                <img src="data/images/star.png" 
                    class=""
                    style="height:60px; object-fit:contain;" 
                    alt="Star">
                <p class="fs-1 mx-3 fw-bold"><?= $snack['rating'] ?></p>
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