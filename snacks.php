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

$params = [];
if ($brandID !== null) {
    $sql .= " WHERE b.id = :brandID";
    $params[':brandID'] = $brandID;
}
if ($categorieID !== null) {
    $sql .= " WHERE c.id = :categorieID";
    $params[':categorieID'] = $categorieID;
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

<div class="d-flex flex-column align-items-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
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
    </div>
</div>

<div class="mx-auto mt-4">
    <?php include "include/snackCardAddNew.php"; ?>
</div>

<?php include "include/footer.php"; ?>
