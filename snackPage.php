<?php
require_once "include/config.php";
include "include/header.php";
include "include/errorSucessPopups.php";

$pdo = db();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0)redirect("snacks.php");

$recentPages = [];
if (isset($_COOKIE['recentPages'])) {
    $recentPages = json_decode($_COOKIE['recentPages'], true);
    if (!is_array($recentPages)) {
        $recentPages = [];
    }
}
$recentPages = array_filter($recentPages, function($page) use ($id) {
    return $page !== $id;
});
array_unshift($recentPages, $id);
$recentPages = array_slice($recentPages, 0, 4);
setcookie('recentPages', json_encode($recentPages));


$stmt = $pdo->prepare("
    SELECT
        s.id,
        s.name,
        s.description,
        s.categorie_id,
        s.image_path,
        b.name AS brand_name,
        c.name AS categorie_name,
        ROUND(AVG(r.rating), 2) AS avg_rating,
        COUNT(r.id) AS review_count
    FROM snacks s
    JOIN brands b ON b.id = s.brand_id
    LEFT JOIN reviews r ON r.snack_id = s.id
    LEFT JOIN categories c ON  c.id = s.categorie_id
    WHERE s.id = :id
    GROUP BY s.id, s.name, s.description, s.image_path, b.name
    LIMIT 1
");
$stmt->execute([':id' => $id]);
$snack = $stmt->fetch();

if (!$snack) {
    redirect("snacks.php");
}

$stmt = $pdo->prepare("
    SELECT
        r.id,
        r.title,
        r.body,
        r.rating,
        r.created_at,
        u.username,
        u.avatar_path
    FROM reviews r
    JOIN users u ON u.id = r.user_id
    WHERE r.snack_id = :id
    ORDER BY r.created_at DESC
");
$stmt->execute([':id' => $id]);

$reviews = db_fetch_all("SELECT * FROM reviews WHERE snack_id = :id", ['id' => $id]);
$user = current_user();
?>

<body class="d-flex flex-column min-vh-100">
    <div class="container-fluid row my-5">
        <div class="col-5">
            <img src="<?= e($snack['image_path']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= e($snack['name']) ?>">
        </div>

        <div class="col pt-5">
            <h1 class="display-1 fw-bold"><?= e($snack['name']) ?></h1>
            <h1 class="display-3"><?= e($snack['brand_name']) ?></h1>
            <p class="fs-5 fw-light">
                <a class="text-decoration-none text-dark" href="snacks.php?categorie=<?= $snack['categorie_id'] ?>"> 
                    <?= $snack['categorie_name'] ?>
                </a>
            <p>

            <div class="d-flex justify-content-start mb-3">
                <img src="data/images/star.png"
                     style="height:60px; object-fit:contain;"
                     alt="Star">
                <p class="fs-1 mx-3 fw-bold">
                    <?= e((string)($snack['avg_rating'] ?? "—")) ?>
                </p>
            </div>

            <div class="my-5">
                <?php if ($user): ?>
                    <div class="d-flex space">
                        <a href="review.php?id=<?= $snack['id'] ?>" class="btn btn-lg btn-primary me-4">Write Review</a>
                        <a href="#" class="btn btn-lg btn-primary">Eaten +1</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex space">
                        <a href="review.php?id=<?= $snack['id'] ?>" class="btn btn-lg btn-secondary me-4 disabled">Write Review</a>
                        <a href="#" class="btn btn-lg btn-secondary disabled">Eaten +1</a>
                    </div>
                    <p class="my-2 fw-bold">Login or create account to review/track</p>
                <?php endif; ?>
            </div>

            <?php if (count($reviews) === 0): ?>
                <p class="text-muted">No reviews yet. Be the first to write one!</p>
            <?php endif; ?>
        </div>

        <div class="container-fluid d-flex flex-wrap justify-content-center">
            <?php foreach ($reviews as $review): ?>
                <?php include __DIR__ . "/include/reviewCard.php"; ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php include "include/footer.php"; ?>
