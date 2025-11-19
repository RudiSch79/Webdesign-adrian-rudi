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
            <h1 class="display-1 fw-bolder">Review</h1>
            <h1 class="display-3 fw-medium"><?= $snack['name'] ?></h1>
            <h1 class="display-4 "><?= $snack['brand'] ?></h1>



        <form method="POST" action="/Webdesign-adrian-rudi/data/handleReview.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="mt-5 mb-4">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" aria-describedby="title">
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <input type="number" class="form-control" id="rating"name="rating" >
        </div>
        <div class="mb-3">
            <label for="review" class="form-label">Review</label>
            <textarea class="form-control" id="review" name="text" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        </div>
    </div>

<?php
include 'include/footer.php';
?>