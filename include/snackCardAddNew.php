<div class="card m-1 py-2" style="width: 18rem; height: 350px;">

  <?php if (isset($_SESSION['user'])): ?>
      <a href="/Webdesign-adrian-rudi/addSnack.php">
          <img src="/Webdesign-adrian-rudi/data/images/addMore.png" class="card-img-top img-fluid" style="height:150px; object-fit:contain;" alt="...">
      </a>
      <div class="d-flex justify-content-center mt-5">
          <a href="/Webdesign-adrian-rudi/addSnack.php" class="btn btn-lg btn-primary">Add new</a>
      </div>
      <?php else: ?>
        <img src="/Webdesign-adrian-rudi/data/images/addMore.png" class="card-img-top img-fluid" style="height:150px; object-fit:contain;" alt="...">
        <div class="d-flex flex-column justify-content-center align-items-center mt-5">
            <a href="/Webdesign-adrian-rudi/addSnack.php" class="btn btn-lg btn-secondary disabled mb-2">
                Add new
            </a>
            <p class="fw-bold text-center mx-2">Login or create account to add new snack</p>
        </div>
      <?php endif; ?>
</div>
