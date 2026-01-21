<?php
require_once __DIR__ . "/../include/config.php";

$pdo = db();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("../addSnack.php");
}

$name     = trim($_POST["name"] ?? "");
$brandId    = $_POST["brandId"] ?? null;
$categorieId = $_POST["categorieId"] ?? null;

$newBrand = trim($_POST["newBrand"]);
$newCategorie = trim($_POST["newCategorie"]);


if ($name === "" || $brandId === "" || $categorieId === "") {
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

$imagePath = "data/uploads/snacks/" . $filename;

if (!move_uploaded_file($tmpPath, $destFs)) {
    $_SESSION["success"] = "Could not save uploaded image.";
    redirect("../addSnack.php");
}

try {
    $pdo->beginTransaction();

    //New brandID / categoryID
    if(!$brandId) {
        $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (:n)");
        $stmt->execute([":n" => $newBrand]);
        $brandId = (int)$pdo->lastInsertId();
    }
    if(!$categorieId) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:n)");
        $stmt->execute([":n" => $newCategorie]);
        $categorieId = (int)$pdo->lastInsertId();
    }

    //Inserting Value into DB
    $stmt = $pdo->prepare("
        INSERT INTO snacks (brand_id, categorie_id, name, image_path, description)
        VALUES (:brand_id, :categorie_id, :name, :image_path, NULL)
    ");
    $stmt->execute([
        ":brand_id"   => $brandId,
        ":name"       => $name,
        ":image_path" => $imagePath,
        ":categorie_id" => $categorieId
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
    redirect("../snacks.php");
}
