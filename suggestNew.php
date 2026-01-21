<?php
require_once "include/config.php";

require_login();

$pdo = db();

$message = "";
$error = "";

$type = $_GET["type"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST["type"] ?? "";
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $error = "Please enter a name.";
    } elseif (mb_strlen($name) > 120) {
        $error = "Name is too long (max 120 characters).";
    } else {
            $userId = (int)current_user()["id"];

            $stmt = $pdo->prepare("
                SELECT id
                FROM suggestions
                WHERE type = :t AND name = :n AND status = 'pending'
                LIMIT 1
            ");
            $stmt->execute([":t" => $type, ":n" => $name]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $error = "This suggestion is already pending.";
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO suggestions (user_id, type, name)
                    VALUES (:uid, :t, :n)
                ");
                $stmt->execute([
                    ":uid" => $userId,
                    ":t"   => $type,
                    ":n"   => $name,
                ]);

                $_SESSION["success"] = "Suggestion submitted! An admin will review it.";
                redirect("suggestNew.php");
            }
    }
}

include "include/header.php";
?>

<body class="d-flex flex-column min-vh-100">

<div class="container-lg py-4">

    <?php if (isset($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= e($_SESSION["success"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <h1 class="my-3">Suggest New</h1>

    <?php if ($type !== "brand" && $type !== "category"): ?>
        <div class="mx-auto" style="max-width: 640px;">
            <div class="bg-light border rounded p-3 mt-3">
                <div class="fw-semibold mb-1">What do you want to suggest?</div>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-sm btn-outline-primary" href="suggestNew.php?type=brand">New brand</a>
                    <a class="btn btn-sm btn-outline-primary" href="suggestNew.php?type=category">New category</a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <form method="POST" class="mx-auto" style="max-width: 640px;">
            <input type="hidden" name="type" value="<?= e($type) ?>">

            <div class="card shadow-sm p-4 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-semibold">
                            Suggest a new <?= $type === "brand" ? "brand" : "category" ?>
                        </div>
                    </div>
                    <a href="suggestNew.php" class="btn btn-sm btn-outline-secondary">Change</a>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">
                        <?= $type === "brand" ? "Brand name" : "Category name" ?>
                    </label>
                    <input name="name" id="name" class="form-control" required maxlength="120"
                           value="<?= e($_POST["name"]) ?>">
                </div>

                <button type="submit" class="btn btn-primary">Submit suggestion</button>
            </div>
        </form>
    <?php endif; ?>

</div>

<?php include "include/footer.php"; ?>
