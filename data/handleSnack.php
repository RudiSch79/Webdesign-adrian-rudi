<?php
$snacksFile = 'snacks.php';
$snacks = include $snacksFile;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $category = $_POST['category'] ?? '';

    $newId = !empty($snacks) ? end($snacks)['id'] + 1 : 1;

    $newSnack = [
        'id' => $newId,
        'name' => $name,
        'brand' => $brand,
        'category' => $category,
        'rating' => 0,
        'image' => ''
    ];

    $snacks[] = $newSnack;

    // Save back to file
    $export = var_export($snacks, true);
    file_put_contents($snacksFile, "<?php\nreturn $export;\n");

    // Redirect back to form
    header('Location: ../addSnack.php');
    exit();
}
?>
