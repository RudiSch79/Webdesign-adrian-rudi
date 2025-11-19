<?php
  $users = include './data/users.php';
  $user = $users[$review['user']]
?>

<div class="card" style="width: 36rem;">
  <div class="card-body">
    <div class="d-flex row mb-3">
      <img src="/Webdesign-adrian-rudi/data/images/profilePicPlaceholder.png" class="card-img-top img-fluid col"  style="height:50px; object-fit:contain;" alt="...">
      
      <div class="col-6">
        <h5 class="card-title"><?= $review['titel'] ?></h5>
        <h6 class="card-subtitle mb-2 text-body-secondary"><?= $user['username'] ?></h6>
      </div>

      <div class="d-flex align-items-center mb-2 col-4">
          <img src="/Webdesign-adrian-rudi/data/images/star.png" 
              style="height:40px; object-fit:contain; mx-auto" 
              alt="Star">
          <p class="card-text ms-3 fw-bold fs-2"><?= $review['rating'] ?></p>
      </div>
    </div>
    <p class="card-text"><?= $review['text'] ?></p>
  </div>
</div>
