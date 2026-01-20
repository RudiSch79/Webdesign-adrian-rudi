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
        <select name="brand" class="form-control" id="brand" required>
            <option value="">Select a brand</option>
            <?php
            $brands = db_fetch_all("SELECT * FROM brands");
            foreach ($brands as $brand) {
                echo '<option value="' . htmlspecialchars($brand['id']) . '">' . htmlspecialchars($brand['name']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select name="categorie" class="form-control" id="categorie" required>
            <option value="">Select a categorie</option>
            <?php
            $categories = db_fetch_all("SELECT * FROM categories");
            foreach ($categories as $categorie) {
                echo '<option value="' . htmlspecialchars($categorie['id']) . '">' . htmlspecialchars($categorie['name']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>


</form>