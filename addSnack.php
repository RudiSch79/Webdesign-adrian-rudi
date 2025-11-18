<?php
include 'include/header.php';
?>

<body class="d-flex flex-column min-vh-100">

<form class="container-lg" method="POST" action="/Webdesign-adrian-rudi/data/handleSnack.php">
    <h1 class="my-3">Add New Snack</h1>
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input name="name" class="form-control" id="name">
    </div>
    <div class="mb-3">
        <label for="brand" class="form-label">Brand</label>
        <input name="brand" class="form-control" id="brand">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input name="category" class="form-control" id="category">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</form>