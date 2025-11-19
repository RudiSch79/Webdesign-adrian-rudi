<?php
include 'include/header.php';
$snacks = include 'data/snacksArray.php';
$id = $_GET['id'];
$snack = $snacks[$id];

$reviews = include 'data/reviews.php'
?>

<!--Sucess Notification if new snack added-->
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $_SESSION['success'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php 
    unset($_SESSION['success']); 
endif; ?>

<body class="d-flex flex-column min-vh-100">
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

            <div class="my-5">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="d-flex space">
                        <a href="review.php?id=<?= $id ?>" class="btn btn-lg btn-primary me-4">Write Review</a>
                        <a href="#" class="btn btn-lg btn-primary">Eaten +1</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex space">
                    <a href="review.php?id=<?= $id ?>" class="btn btn-lg btn-secondary me-4 disabled">Write Review</a>
                    <a href="#" class="btn btn-lg btn-secondary disabled">Eaten +1</a>
                    </div>
                    <p class="my-2 fw-bold">Login or create account to review/track</p>
                <?php endif; ?>
            
            </div> 
            <?php
                $reviews = include 'data/reviews.php';
                $review = $reviews[0];
                include 'include/reviewCard.php';
            ?>
        </div>
        <div class="container-fluid d-flex flex-wrap justify-content-center">
            <?php 
                foreach ($reviews as $rev) {
                    if ($rev['snackID'] == $id) {
                        $review = $rev;
                        include 'include/reviewCard.php';
                    }
                }
            ?>
        </div>
    </div>

<?php
include 'include/footer.php';
?>