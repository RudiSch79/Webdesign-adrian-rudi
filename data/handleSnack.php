<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$snacksFile = 'snacksArray.php';
$snacks = include $snacksFile;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $category = $_POST['category'] ?? '';
    $imagePath = ''; // default if no image uploaded

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/snackImages/'; // folder you manually created

        $tmpName = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('snack_') . '.' . $fileExt; // unique file name

        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            // Relative path to save in the array
            $imagePath = '/Webdesign-adrian-rudi/data/snackImages/' . $newFileName;
        }
    }

    $newId = !empty($snacks) ? end($snacks)['id'] + 1 : 1;

    $newSnack = [
        'id' => $newId,
        'name' => $name,
        'brand' => $brand,
        'category' => $category,
        'rating' => 0,
        'image' => $imagePath
    ];

    $snacks[] = $newSnack;

    // Save back to file
    $export = var_export($snacks, true);
    file_put_contents($snacksFile, "<?php\nreturn $export;\n");

    $showSuccess = true;

    // Redirect back to form
    $_SESSION['success'] = "Snack added successfully!";
    header('Location: ../allSnacks.php');
    exit();
}
?>
