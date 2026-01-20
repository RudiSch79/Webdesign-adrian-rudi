<?php
include 'include/config.php';
include 'include/header.php';
?>

<body class="d-flex flex-column min-vh-100">

<form class="container-lg" method="POST" action="/Webdesign-adrian-rudi/data/handleSnack.php" enctype="multipart/form-data">
    <h1 class="my-3">Add New Snack</h1>
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input name="name" class="form-control" id="name" required>
    </div>

    <div class="mb-3">
        <label for="brand" class="form-label">Brand</label>
        <input name="brand" class="form-control" id="brand" required>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input name="category" class="form-control" id="category" required>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>


</form>