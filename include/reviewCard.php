<?php
  $user = db_fetch_one("SELECT * FROM users WHERE id = :id", ['id' => $review['user_id']]);
?>

<div class="card mx-1 my-1 position-relative" style="width: 31rem;">
  <!-- Dropdown-Button oben rechts -->
  <div class="position-absolute top-0 end-0 p-2">
    <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">...</button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="#">Option 1</a></li>
        <li><a class="dropdown-item" href="#">Option 2</a></li>
        <li><a class="dropdown-item" href="#">Option 3</a></li>
    </ul>
  </div>

  <div class="card-body">
    <div class="d-flex row mb-3">
      <img src="<?= $user['avatar_path'] ?>" class="card-img-top img-fluid col" style="height:50px; object-fit:contain;" alt="...">

      <div class="col-5">
        <h5 class="card-title"><?= $review['title'] ?></h5>
        <h6 class="card-subtitle mb-2 text-body-secondary"><?= $user['username'] ?></h6>
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
          <img src="<?= $review['image_path'] ?>" class="card-img-top img-fluid col" style="width:100px; object-fit:contain;" alt="...">
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

