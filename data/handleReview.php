<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
$id = $_GET['id'];
}

$reviewsFile = 'reviews.php';
$reviews = include $reviewsFile;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $text = $_POST['text'] ?? '';

    $newReview = [
        'snackID' => $id,
        'user' => 'userplaceholder',
        'title' => $title,
        'rating' => $rating,
        'text' => $text,
        'image' => ''
    ];

    $reviews[] = $newReview;

    // Save back to file
    $export = var_export($reviews, true);
    file_put_contents($reviewsFile, "<?php\nreturn $export;\n");

    $showSuccess = true;

    // Redirect back to form
    $_SESSION['success'] = "Review added successfully!";
    header('Location: ../snackPage.php?id='.$id);
    exit();
}
?>
