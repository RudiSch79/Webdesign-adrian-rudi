<div class="d-flex flex-column justify-content-center mt-4">
  <h1>Recently Viewed</h1>
  <div class="container-lg row justify-content-center">
    <?php
        $recentPages = [];
        if (isset($_COOKIE['recentPages'])) {
            $recentPages = json_decode($_COOKIE['recentPages'], true);
        }
        if (empty($recentPages)) {
            echo '<p class="text-muted text-center">No recently visited snacks yet.</p>';
            return;
        }
        $ids = implode(',', $recentPages);
        $stmt = $pdo->query("
            SELECT
                s.id,
                s.name,
                s.image_path,
                b.name AS brand_name,
                ROUND(IFNULL(AVG(r.rating),0),2) AS avg_rating,
                COUNT(r.id) AS review_count
            FROM snacks s
            LEFT JOIN brands b ON b.id = s.brand_id
            LEFT JOIN reviews r ON r.snack_id = s.id
            WHERE s.id IN ($ids)
            GROUP BY s.id, s.name, s.image_path, b.name
            ORDER BY FIELD(s.id, $ids)
        ");

        $snacks = $stmt->fetchAll();

        foreach ($snacks as $snack) {
            $snack = [
            'id'     => (int)$snack['id'],
            'name'   => $snack['name'],
            'brand'  => $snack['brand_name'],
            'rating' => (float)$snack['avg_rating'],
            'image'  => $snack['image_path'],
            ];

            include __DIR__ . '/snackCard.php';
        }
    ?>
  </div>
</div>
