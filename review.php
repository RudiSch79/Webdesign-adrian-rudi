<?php
require_once "include/config.php";

require_login();

$pdo = db();

$snackId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($snackId <= 0) {
    redirect("snacks.php");
}

$stmt = $pdo->prepare("
    SELECT s.id, s.name, s.image_path, b.name AS brand_name
    FROM snacks s
    JOIN brands b ON b.id = s.brand_id
    WHERE s.id = :id
    LIMIT 1
");
$stmt->execute([":id" => $snackId]);
$snack = $stmt->fetch();

if (!$snack) {
    redirect("snacks.php");
}

include "include/header.php";
?>

<body class="d-flex flex-column">
  <div class="container-fluid row my-5">
    <div class="col-5">
      <img src="<?= e($snack['image_path']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= e($snack['name']) ?>">
    </div>

    <div class="col pt-5">
      <h1 class="display-1 fw-bolder">Review</h1>
      <h1 class="display-3 fw-medium"><?= e($snack['name']) ?></h1>
      <h1 class="display-4"><?= e($snack['brand_name']) ?></h1>

      <form method="POST"
            action="data/handleReview.php?id=<?= (int)$snack['id'] ?>"
            enctype="multipart/form-data">

        <div class="mt-5 mb-4">
          <label for="title" class="form-label">Title</label>
          <input type="text" class="form-control" id="title" name="title" required maxlength="160">
        </div>

        <div class="mb-3">
          <label for="rating" class="form-label">Rating (0-5)</label>
          <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1" required>
        </div>

        <div class="mb-3">
          <label for="review" class="form-label">Review</label>
          <textarea class="form-control" id="review" name="body" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control" id="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

    </div>
  </div>

<?php include "include/footer.php"; ?>
