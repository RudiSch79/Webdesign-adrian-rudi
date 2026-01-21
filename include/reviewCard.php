<?php
  $reviewer = db_fetch_one("SELECT * FROM users WHERE id = :id", ['id' => $review['user_id']]);
  $sessionUser = current_user();
  $snackId = $review["snack_id"];
  $editingReviewId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
?>

<div class="card mx-1 my-1 position-relative" style="width: 31rem;" id="review-<?= $review['id'] ?>">
  <?php if ($editingReviewId === $review['id']): ?>
    <div class="card-body">
      <form method="POST" action="data/handleReview.php">
        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
        <input type="hidden" name="snack_id" value="<?= $review['snack_id'] ?>">

        <input class="form-control mb-2" name="title" value="<?= htmlspecialchars($review['title']) ?>">

        <textarea class="form-control mb-2" name="body" style="resize: none;"><?= htmlspecialchars($review['body']) ?></textarea>

        <input type="number" class="form-control mb-2" id="rating" name="rating" min="0" max="5" step="0.1" required value="<?= htmlspecialchars($review['rating']) ?>">
        </select>

        <button class="btn btn-success btn-sm">Save</button>
        <a href="snackPage.php?id=<?= $id ?>#review-<?= $review['id'] ?>" class="btn btn-secondary btn-sm">Cancel</a>
      </form>
    </div>

  <?php else: ?>
    <div class="position-absolute top-0 end-0 p-2">
      <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">...</button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <?php if ($review["user_id"] === $sessionUser["id"]): ?>
            <li><a class="dropdown-item" href="snackPage.php?id=<?= $review['snack_id'] ?>&edit=<?= $review['id'] ?>#review-<?= $review['id'] ?>">Edit</a></li>
            <li>
              <form method="POST" action="data/handleReview.php" style="display:inline;">
                <input type="hidden" name="snack_id" value="<?= $snackId ?>">
                <input type="hidden" name="delete_review_id" value="<?= $review["id"] ?>">
                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                  Delete
                </button>
              </form>
            </li>
          <?php else: ?>
            <li><a class="dropdown-item" href="#">Report</a></li>
          <?php endif; ?>
      </ul>
    </div>

    <div class="card-body">
      <div class="d-flex row mb-3">
        <img src="<?= $reviewer['avatar_path'] ?>" class="card-img-top img-fluid col" style="height:50px; object-fit:contain;" alt="...">

        <div class="col-5">
          <h5 class="card-title"><?= $review['title'] ?></h5>
          <h6 class="card-subtitle mb-2 text-body-secondary"><?= $reviewer['username'] ?></h6>
        </div>

        <div class="d-flex align-items-center mb-2 col-4">
            <img src="/Webdesign-adrian-rudi/data/images/star.png" style="height:40px; object-fit:contain;" alt="Star">
            <p class="card-text ms-3 fw-bold fs-2"><?= $review['rating'] ?></p>
        </div>
      </div>

      <div class="d-flex row mb-1">
        <div class="col-9">
          <p class="card-text"><?= $review['body'] ?></p>
        </div>
        <?php if ($review['image_path'] !== null): ?>
          <div class="col-3">
            <img src="<?= $review['image_path'] ?>" class="card-img-top img-fluid col rounded"  style="width:100px; object-fit:contain;" alt="...">
          </div>
        <?php endif; ?>
      </div>

      <div class="d-flex justify-content-between align-items-center mx-1">
        <p class="mb-0"><?= $review['created_at'] ?></p>
        <div class="d-flex align-items-center gap-2">
          <form action="data/handleReview.php" method="POST" style="display:inline;">
            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
            <input type="hidden" name="vote" value=1>
            <input type="hidden" name="snack_id" value="<?= $review['snack_id'] ?>">
            <button type="submit" style="border:none; background:none; padding:0;">
              <img src="data/images/arrowUp.jpg" class="img-fluid" style="width:15px; object-fit:contain;" alt="Up">
            </button>
          </form>

          <form action="data/handleReview.php" method="POST" style="display:inline;">
            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
            <input type="hidden" name="vote" value=0>
            <input type="hidden" name="snack_id" value="<?= $review['snack_id'] ?>">
            <button type="submit" style="border:none; background:none; padding:0;">
              <img src="data/images/arrowDown.jpg" class="img-fluid" style="width:15px; object-fit:contain;" alt="Down">
            </button>
          </form>

          <p class="mb-0"><?= $review['score'] ?></p>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

