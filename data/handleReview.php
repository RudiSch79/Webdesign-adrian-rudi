<?php
require_once "../include/config.php";

require_login();

$pdo  = db();
$user = current_user();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect("../snacks.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $title  = trim($_POST['title'] ?? '');
    $rating = (int)($_POST['rating'] ?? -1);
    $body   = trim($_POST['body'] ?? '');
    $image = $_FILES['image'];

    // Check if an image was uploaded
    if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $image['tmp_name'];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmpPath);

        $allowed = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
        ];

        if (!isset($allowed[$mime])) {
            $_SESSION['error'] = "Only JPG, PNG, or WEBP images are allowed.";
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

        if (!move_uploaded_file($tmpPath, $destFs)) {
            $_SESSION['error'] = "Could not save uploaded image.";
            redirect('../review.php?id=' . $id);
        }

        // Path to store in DB
        $imagePath = "data/uploads/reviews/" . $filename;
    } else {
        // No image uploaded
        $imagePath = NULL;
    }


    if ($title === '' || $body === '' || $rating < 0 || $rating > 5) {
        $_SESSION['error'] = "Please fill everything correctly (rating 1-5).";
        redirect('../review.php?id=' . $id);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO reviews (snack_id, user_id, title, body, rating, image_path)
            VALUES (:snack_id, :user_id, :title, :body, :rating, :image_path)
        ");

        $stmt->execute([
            ':snack_id' => $id,
            ':user_id'  => (int)$user['id'],
            ':title'    => $title,
            ':body'     => $body,
            ':rating'   => $rating,
            ':image_path' => $imagePath
        ]);

        $_SESSION['success'] = "Review added successfully!";
        header('Location: ../snackPage.php?id=' . $id);
        exit();

    } catch (Throwable $e) {
        $_SESSION['error'] = "Could not add review (maybe you already reviewed this snack).";
        header('Location: ../review.php?id=' . $id);
        exit();
    }
}
?>
