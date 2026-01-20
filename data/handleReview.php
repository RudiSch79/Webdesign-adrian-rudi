<?php
require_once "include/bootstrap.php";

require_login();

$pdo  = db();
$user = current_user();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect("../snacks.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);

    // Compatibility: old form used "text", new one uses "body"
    $body = trim($_POST['body'] ?? ($_POST['text'] ?? ''));

    if ($title === '' || $body === '' || $rating < 1 || $rating > 5) {
        $_SESSION['success'] = "Please fill everything correctly (rating 1-5).";
        redirect('../review.php?id=' . $id);
    }

    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $_SESSION['success'] = "Please upload an image.";
        redirect('../review.php?id=' . $id);
    }

    $tmpPath = $_FILES["image"]["tmp_name"];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($tmpPath);

    $allowed = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp",
    ];

    if (!isset($allowed[$mime])) {
        $_SESSION['success'] = "Only JPG, PNG, or WEBP images are allowed.";
        redirect('../review.php?id=' . $id);
    }

    $ext = $allowed[$mime];

    // Ensure upload folder exists
    $uploadDirFs = ROOT_PATH . "/data/uploads/reviews";
    if (!is_dir($uploadDirFs)) {
        mkdir($uploadDirFs, 0777, true);
    }

    // Unique filename
    $filename = bin2hex(random_bytes(16)) . "." . $ext;
    $destFs   = $uploadDirFs . "/" . $filename;

    // Path stored in DB and used in <img src="">
    $imagePathForDb = "data/uploads/reviews/" . $filename;

    if (!move_uploaded_file($tmpPath, $destFs)) {
        $_SESSION['success'] = "Could not save uploaded image.";
        redirect('../review.php?id=' . $id);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO reviews (snack_id, user_id, title, body, rating, image_path)
            VALUES (:snack_id, :user_id, :title, :body, :rating, :image_path)
        ");

        $stmt->execute([
            ':snack_id'   => $id,
            ':user_id'    => (int)$user['id'],
            ':title'      => $title,
            ':body'       => $body,
            ':rating'     => $rating,
            ':image_path' => $imagePathForDb,
        ]);

        $_SESSION['success'] = "Review added successfully!";
        header('Location: ../snackPage.php?id=' . $id);
        exit();

    } catch (Throwable $e) {
        // Clean up uploaded file if DB insert fails
        $full = ROOT_PATH . "/" . $imagePathForDb;
        if (is_file($full)) {
            @unlink($full);
        }

        // Most likely: user already reviewed this snack (unique constraint)
        $_SESSION['success'] = "Could not add review (maybe you already reviewed this snack).";
        header('Location: ../snackPage.php?id=' . $id);
        exit();
    }
}
?>
