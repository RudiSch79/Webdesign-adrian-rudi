<div class="card m-1 py-2" style="width: 18rem; height: 350px;">

<a href="/Webdesign-adrian-rudi/snackPage.php">
    <img src="<?= $snack['image'] ?>" class="card-img-top img-fluid"  style="height:150px; object-fit:contain;" alt="...">
</a>

<div class="card-body">
    <h3 class="card-title"><?= $snack['name'] ?></h3>
    <p class="card-text"><?= $snack['brand'] ?></p>
    <div class="d-flex align-items-center mb-2">
        <img src="data/images/star.png" 
            style="height:20px; width:20px; object-fit:contain;" 
            alt="Star">
        <h6 class="card-text mb-0 ms-2"><?= $snack['rating'] ?></h6>
    </div>
    <a href="/Webdesign-adrian-rudi/snackPage.php?id=<?= $snack['id'] ?>" class="btn btn-primary">See more</a>
</div>
</div>


