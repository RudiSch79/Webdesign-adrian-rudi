<div class="d-flex flex-column justify-content-center">
  <h1>Top Rated</h1>
  <div class="container-lg row justify-content-center">
    <?php
      $pdo = db();

      $stmt = $pdo->query("
        SELECT
          s.id,
          s.name,
          s.image_path,
          b.name AS brand_name,
          ROUND(AVG(r.rating), 2) AS avg_rating,
          COUNT(r.id) AS review_count
        FROM snacks s
        JOIN brands b ON b.id = s.brand_id
        JOIN reviews r ON r.snack_id = s.id
        GROUP BY s.id, s.name, s.image_path, b.name
        ORDER BY avg_rating DESC, review_count DESC, s.created_at DESC
        LIMIT 3
      ");
      $rows = $stmt->fetchAll();

      // If there are no reviews yet, show newest snacks (still requires image_path)
      if (!$rows) {
        $stmt = $pdo->query("
          SELECT
            s.id,
            s.name,
            s.image_path,
            b.name AS brand_name,
            0.00 AS avg_rating,
            0 AS review_count
          FROM snacks s
          JOIN brands b ON b.id = s.brand_id
          ORDER BY s.created_at DESC
          LIMIT 3
        ");
        $rows = $stmt->fetchAll();
      }

      foreach ($rows as $row) {
        $snack = [
          'id'     => (int)$row['id'],
          'name'   => $row['name'],
          'brand'  => $row['brand_name'],
          'rating' => (float)$row['avg_rating'],
          'image'  => $row['image_path'],
        ];

        include __DIR__ . '/snackCard.php';
      }

      include __DIR__ . '/snackCardShowAll.php';
    ?>
  </div>
</div>
