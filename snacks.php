<?php
require_once "include/config.php";
include "include/header.php";
include "include/errorSucessPopups.php";

$pdo = db();

$brandID = isset($_GET['brand']) ? (int)$_GET['brand'] : null;
$categorieID = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;

$sql = "
    SELECT
        s.id,
        s.name,
        s.image_path,
        b.name AS brand_name,
        ROUND(COALESCE(AVG(r.rating), 0), 2) AS avg_rating,
        COUNT(r.id) AS review_count
    FROM snacks s
    JOIN brands b ON b.id = s.brand_id
    Join categories c ON c.id = s.categorie_id
    LEFT JOIN reviews r ON r.snack_id = s.id
";

$conditions = [];
$params = [];

if ($brandID !== null) {
    $conditions[] = "b.id = :brandID";
    $params[':brandID'] = $brandID;
}
if ($categorieID !== null) {
    $conditions[] = "c.id = :categorieID";
    $params[':categorieID'] = $categorieID;
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= "
    GROUP BY s.id, s.name, s.image_path, b.name
    ORDER BY s.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="d-flex flex-column min-vh-100">

<!-- filter -->
<div class="container-lg my-4">
    <form method="GET" class="row g-2 align-items-center justify-content-center">
        <div class="col-auto">
            <select name="brand" class="form-select">
                <option value="">All Brands</option>
                <?php
                $brands = $pdo->query("SELECT id, name FROM brands")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($brands as $brand) {
                    $selected = ($brandID == $brand['id']) ? 'selected' : '';
                    echo "<option value='{$brand['id']}' $selected>{$brand['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-auto">
            <select name="categorie" class="form-select">
                <option value="">All Categories</option>
                <?php
                $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $cat) {
                    $selected = ($categorieID == $cat['id']) ? 'selected' : '';
                    echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>


<div class="d-flex flex-column align-items-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php if (!$rows): ?>
            <div class="col-12 text-center my-5">
                <p class="text-muted">No result for this filter. Be the first to create one!</p>
            </div>
    <?php else:
        foreach ($rows as $row) {
            $snack = [
                'id'     => $row['id'],
                'name'   => $row['name'],
                'brand'  => $row['brand_name'],
                'rating' => (float)$row['avg_rating'],
                'image'  => $row['image_path'],
            ];

            include "include/snackCard.php";
        }
    ?>
    <?php endif; ?>
    </div>
</div>

<div class="mx-auto mt-4">
    <?php include "include/snackCardAddNew.php"; ?>
</div>

<?php include "include/footer.php"; ?>
