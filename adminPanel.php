<?php
require_once "include/config.php";

require_login();
require_admin();

$pdo = db();

$message = "";
$error = "";

$confirmId = isset($_GET["confirm"]) ? (int)$_GET["confirm"] : 0;
$confirmReview = null;

if ($confirmId > 0) {
    $stmt = $pdo->prepare("
        SELECT
            r.id,
            r.title,
            r.body,
            r.rating,
            r.created_at,
            u.username,
            s.name AS snack_name,
            b.name AS brand_name
        FROM reviews r
        JOIN users u  ON u.id = r.user_id
        JOIN snacks s ON s.id = r.snack_id
        JOIN brands b ON b.id = s.brand_id
        WHERE r.id = :id
        LIMIT 1
    ");
    $stmt->execute([":id" => $confirmId]);
    $confirmReview = $stmt->fetch();

    if (!$confirmReview) {
        $error = "Review not found.";
        $confirmId = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete_review") {
    $reviewId = (int)($_POST["review_id"] ?? 0);

    if ($reviewId <= 0) {
        $error = "Invalid review.";
    } else {
        try {

            $stmt = $pdo->prepare("SELECT image_path FROM reviews WHERE id = :id LIMIT 1");
            $stmt->execute([":id" => $reviewId]);
            $imagePath = $stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
            $stmt->execute([":id" => $reviewId]);

            if ($imagePath) {
                $full = ROOT_PATH . "/" . ltrim((string)$imagePath, "/");
                if (is_file($full)) {
                    @unlink($full);
                }
            }

            $message = "Review deleted.";
        } catch (Throwable $e) {
            $error = "Could not delete review.";
        }
    }
}

$stmt = $pdo->query("
    SELECT
        r.id,
        r.title,
        r.rating,
        r.body,
        r.created_at,
        u.username,
        s.name AS snack_name,
        b.name AS brand_name
    FROM reviews r
    JOIN users u  ON u.id = r.user_id
    JOIN snacks s ON s.id = r.snack_id
    JOIN brands b ON b.id = s.brand_id
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll();

include "include/header.php";
?>

<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1">
<div class="container py-5">

    <?php if ($message): ?>
        <div class="alert alert-success"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($confirmReview): ?>
        <div class="card shadow-sm p-4 mb-4 border-danger">
            <h5 class="mb-2">Confirm delete</h5>
            <p class="mb-3">
                You are about to delete this review:
                <br><strong><?= e($confirmReview["brand_name"] . " " . $confirmReview["snack_name"]) ?></strong>
                <br>By: <strong><?= e($confirmReview["username"]) ?></strong>
                <br>Title: <strong><?= e($confirmReview["title"]) ?></strong>
            </p>

            <div class="d-flex gap-2">
                <a href="adminPanel.php" class="btn btn-outline-secondary">Cancel</a>

                <form method="POST" class="m-0">
                    <input type="hidden" name="action" value="delete_review">
                    <input type="hidden" name="review_id" value="<?= (int)$confirmReview["id"] ?>">
                    <button class="btn btn-danger">Yes, delete</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <div class="row">

            <div class="col-md-3 text-center border-end">
                <h5 class="mt-2">Admin Panel</h5>
                <p class="text-muted mb-4">Review moderation</p>

                <div class="d-grid gap-2">
                    <a href="adminUserManagement.php" class="btn btn-outline-secondary">User management</a>
                    <a href="index.php" class="btn btn-outline-secondary">Back to the homepage</a>
                </div>
            </div>

            <div class="col-md-9 ps-4">
                <h5 class="mt-2 mb-3">Reviews</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Snack</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Rating</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reviews as $r): ?>
                            <tr>
                                <td><?= e($r["brand_name"] . " " . $r["snack_name"]) ?></td>
                                <td><?= e($r["username"]) ?></td>
                                <td><?= e($r["title"]) ?></td>
                                <td><?= e($r["body"]) ?></td>
                                <td><?= e((string)$r["rating"]) ?></td>
                                <td class="text-muted small"><?= e($r["created_at"]) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger"
                                       href="adminPanel.php?confirm=<?= (int)$r["id"] ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</div>
</main>

<?php include "include/footer.php"; ?>
