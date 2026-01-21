<?php
require_once "include/config.php";
include "include/header.php";
include "include/errorSucessPopups.php";

$pdo = db();

$stmt = $pdo->query("
    SELECT
        s.id,
        s.name,
        s.image_path,
        b.name AS brand_name,
        ROUND(COALESCE(AVG(r.rating), 0), 2) AS avg_rating,
        COUNT(r.id) AS review_count
    FROM snacks s
    JOIN brands b ON b.id = s.brand_id
    LEFT JOIN reviews r ON r.snack_id = s.id
    GROUP BY s.id, s.name, s.image_path, b.name
    ORDER BY s.created_at DESC
");
$rows = $stmt->fetchAll();


?>

<body class="d-flex flex-column min-vh-100">

<div class="d-flex flex-column align-items-center">
    <div class="container-lg row row-2 justify-content-center">
        <?php
        foreach ($rows as $row) {
            $snack = [
                'id'     => (int)$row['id'],
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
