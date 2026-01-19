<?php
require_once __DIR__ . "/../include/bootstrap.php";

$pdo = db();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("../addSnack.php");
}

$name     = trim($_POST["name"] ?? "");
$brand    = trim($_POST["brand"] ?? "");
$category = trim($_POST["category"] ?? "");

if ($name === "" || $brand === "" || $category === "") {
    $_SESSION["success"] = "Please fill in all fields.";
    redirect("../addSnack.php");
}

if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    $_SESSION["success"] = "Image upload failed.";
    redirect("../addSnack.php");
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
    $_SESSION["success"] = "Only JPG, PNG, or WEBP images are allowed.";
    redirect("../addSnack.php");
}

$ext = $allowed[$mime];

$uploadDirFs = ROOT_PATH . "/data/uploads/snacks";
if (!is_dir($uploadDirFs)) {
    mkdir($uploadDirFs, 0777, true);
}

$filename = bin2hex(random_bytes(16)) . "." . $ext;
$destFs   = $uploadDirFs . "/" . $filename;

$imagePathForDb = "data/uploads/snacks/" . $filename;

if (!move_uploaded_file($tmpPath, $destFs)) {
    $_SESSION["success"] = "Could not save uploaded image.";
    redirect("../addSnack.php");
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = :n LIMIT 1");
    $stmt->execute([":n" => $brand]);
    $brandId = $stmt->fetchColumn();

    if (!$brandId) {
        $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (:n)");
        $stmt->execute([":n" => $brand]);
        $brandId = (int)$pdo->lastInsertId();
    } else {
        $brandId = (int)$brandId;
    }

    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = :n LIMIT 1");
    $stmt->execute([":n" => $category]);
    $categoryId = $stmt->fetchColumn();

    if (!$categoryId) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:n)");
        $stmt->execute([":n" => $category]);
        $categoryId = (int)$pdo->lastInsertId();
    } else {
        $categoryId = (int)$categoryId;
    }

    $stmt = $pdo->prepare("
        INSERT INTO snacks (brand_id, name, image_path, description)
        VALUES (:brand_id, :name, :image_path, NULL)
    ");
    $stmt->execute([
        ":brand_id"   => $brandId,
        ":name"       => $name,
        ":image_path" => $imagePathForDb,
    ]);

    $snackId = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare("
        INSERT INTO snack_categories (snack_id, category_id)
        VALUES (:snack_id, :category_id)
    ");
    $stmt->execute([
        ":snack_id"    => $snackId,
        ":category_id" => $categoryId,
    ]);

    $pdo->commit();

    $_SESSION["success"] = "Snack added successfully!";
    redirect("../snacks.php");

} catch (Throwable $e) {
    $pdo->rollBack();

    if (isset($destFs) && is_file($destFs)) {
        @unlink($destFs);
    }

    $_SESSION["success"] = "Could not add snack (maybe duplicate name for that brand).";
    redirect("../addSnack.php");
}
