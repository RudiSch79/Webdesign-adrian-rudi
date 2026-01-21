<?php
include 'include/config.php';
include 'include/header.php';

$suggestBrand = isset($_GET['suggestBrand']);
$suggestCategorie = isset($_GET['suggestCategorie']);
?>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= e($_SESSION['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php unset($_SESSION['success']); endif; ?>

<body class="d-flex flex-column min-vh-100">

<form class="container-lg" method="POST" action="/Webdesign-adrian-rudi/data/handleSnack.php" enctype="multipart/form-data">
    <h1 class="my-3">Add New Snack</h1>
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input name="name" class="form-control" id="name" required>
    </div>


    <?php if (!$suggestBrand): ?>
        <div class="mb-3">
            <label for="brandId" class="form-label">Brand</label>
            <select name="brandId" class="form-control" id="brandId" required>
                <option value="">Select a brand</option>
                <?php
                $brands = db_fetch_all("SELECT * FROM brands");
                foreach ($brands as $brand) {
                    echo '<option value="' . htmlspecialchars($brand['id']) . '">' . htmlspecialchars($brand['name']) . '</option>';
                }
                ?>
            </select>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label for="newBrand" class="form-label">Brand</label>
            <input name="newBrand" class="form-control" id="newBrand" required>
        </div>
    <?php endif; ?>

    <?php if (!$suggestCategorie): ?>
        <div class="mb-3">
            <label for="categorieId" class="form-label">Categorie</label>
            <select name="categorieId" class="form-control" id="categorieId" required>
                <option value="">Select a categorie</option>
                <?php
                $categories = db_fetch_all("SELECT * FROM categories");
                foreach ($categories as $categorie) {
                    echo '<option value="' . htmlspecialchars($categorie['id']) . '">' . htmlspecialchars($categorie['name']) . '</option>';
                }
                ?>
            </select>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label for="newCategorie" class="form-label">Categorie</label>
            <input name="newCategorie" class="form-control" id="newCategorie" required>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*">
    </div>
    
    <?php if (!isset($_GET['suggest'])): ?>
        <div class="mb-3">
            <p1 class="small"> Cant find qhat you are looking for? Suggest new: </p1>
            <p1 class="small"><u><a href="addSnack.php?suggestBrand<?= $suggestCategorie ? '&suggestCategorie' : '' ?>"> Brand </a></u></p1>
            <p1 class="small">/</p1>
            <p1 class="small"><u><a href="addSnack.php?suggestCategorie<?= $suggestBrand ? '&suggestBrand' : '' ?>"> Categorie </a></u></p1>
        </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</body>

<?php include "include/footer.php"; ?>