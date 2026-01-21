<?php
require_once "include/config.php";

require_login();

$pdo = db();

$message = "";
$error   = "";

// Load fresh user row from DB (don’t trust session for everything)
$sessionUser = current_user();
$userId = (int)$sessionUser["id"];

$stmt = $pdo->prepare("SELECT id, username, password_hash, avatar_path FROM users WHERE id = :id LIMIT 1");
$stmt->execute([":id" => $userId]);
$dbUser = $stmt->fetch();

if (!$dbUser) {
    // Session is stale (user deleted/deactivated, etc.)
    logout_user();
    redirect("login.php");
}

$username = $dbUser["username"];
$defaultPic = "data/images/profilePicPlaceholder.png";
$profilePicture = $dbUser["avatar_path"] ?: $defaultPic;

// Keep uploads in one folder
$uploadFolderFs = ROOT_PATH . "/data/uploads/avatars";
$uploadFolderWeb = "data/uploads/avatars";

if (isset($_POST["upload_picture"])) {

    if (!isset($_FILES["profile_image"]) || $_FILES["profile_image"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Failed to upload file.";
    } else {

        $tmpPath = $_FILES["profile_image"]["tmp_name"];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmpPath);

        $allowed = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
        ];

        if (!isset($allowed[$mime])) {
            $error = "Only JPG, PNG, or WEBP images are allowed.";
        } else {

            if (!is_dir($uploadFolderFs)) {
                mkdir($uploadFolderFs, 0777, true);
            }

            $ext = $allowed[$mime];

            $filename = "u{$userId}_" . time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
            $targetFs = $uploadFolderFs . "/" . $filename;
            $targetWeb = $uploadFolderWeb . "/" . $filename;

            if (!move_uploaded_file($tmpPath, $targetFs)) {
                $error = "Failed to save uploaded file.";
            } else {

                $old = $dbUser["avatar_path"];
                if ($old && $old !== $defaultPic) {
                    $oldFs = ROOT_PATH . "/" . ltrim($old, "/");
                    if (is_file($oldFs)) {
                        @unlink($oldFs);
                    }
                }

                // Update DB
                $stmt = $pdo->prepare("UPDATE users SET avatar_path = :p WHERE id = :id");
                $stmt->execute([":p" => $targetWeb, ":id" => $userId]);

                // Update session (optional, but handy for header nav/avatar)
                $_SESSION["user"]["username"] = $username;
                $_SESSION["user"]["avatar_path"] = $targetWeb;

                $message = "Profile picture uploaded successfully!";
                $profilePicture = $targetWeb;
            }
        }
    }
}

if (isset($_POST["delete_picture"])) {

    // Delete old avatar file if it was real
    $old = $dbUser["avatar_path"];
    if ($old && $old !== $defaultPic) {
        $oldFs = ROOT_PATH . "/" . ltrim($old, "/");
        if (is_file($oldFs)) {
            @unlink($oldFs);
        }
    }

    // Update DB to placeholder (or NULL, if you prefer)
    $stmt = $pdo->prepare("UPDATE users SET avatar_path = :p WHERE id = :id");
    $stmt->execute([":p" => $defaultPic, ":id" => $userId]);

    $_SESSION["user"]["avatar_path"] = $defaultPic;

    $message = "Profile picture deleted.";
    $profilePicture = $defaultPic;
}

if (isset($_POST["update_details"])) {

    $newUsername = trim($_POST["username"] ?? "");

    if ($newUsername === "") {
        $error = "Username cannot be empty.";
    } elseif ($newUsername !== $username) {

        // Check uniqueness
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([":u" => $newUsername]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $error = "This username is already taken.";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = :u WHERE id = :id");
            $stmt->execute([":u" => $newUsername, ":id" => $userId]);

            $_SESSION["user"]["username"] = $newUsername;

            $message = "Details updated successfully!";
            $username = $newUsername;
        }
    } else {
        $message = "Details updated successfully!";
    }
}

if (isset($_POST["change_password"])) {

    $current = trim($_POST["current_password"] ?? "");
    $new     = trim($_POST["new_password"] ?? "");
    $confirm = trim($_POST["confirm_password"] ?? "");

    if (!password_verify($current, $dbUser["password_hash"])) {
        $error = "Current password is incorrect.";
    } elseif ($new === "" || $new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash = :h WHERE id = :id");
        $stmt->execute([":h" => $newHash, ":id" => $userId]);

        $message = "Password updated successfully!";
    }
}

include "include/header.php";
?>

<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1">
<div class="container py-5">

    <?php if ($message): ?>
        <div class="alert alert-success"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <div class="row">

            <div class="col-md-3 text-center border-end">

                <img src="<?= e($profilePicture) ?>"
                     class="rounded-circle mb-3"
                     width="150" height="150"
                     style="object-fit: cover;">

                <form method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="profile_image" class="form-control mb-2" accept="image/*" required>
                    <button class="btn btn-primary w-100" name="upload_picture">Upload new profile picture</button>
                </form>

                <form method="POST">
                    <button class="btn btn-outline-danger w-100" name="delete_picture">Delete profile picture</button>
                </form>

            </div>

            <div class="col-md-9 ps-4">

                <h5 class="mt-2 mb-3">Personal details</h5>

                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="<?= e($username) ?>" required>
                    </div>

                    <button class="btn btn-secondary" name="update_details">Change details</button>
                </form>

                <h5 class="mb-3">Change password</h5>

                <form method="POST">
                    <div class="mb-3">
                        <input type="password" name="current_password" class="form-control"
                               placeholder="Current password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="new_password" class="form-control"
                               placeholder="New password" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" name="confirm_password" class="form-control"
                               placeholder="Confirm new password" required>
                    </div>

                    <button class="btn btn-secondary" name="change_password">Change password</button>
                </form>

            </div>

        </div>
    </div>
    
    <h1 class="display-4 fw-bold">My Reviews:</h1>
    <div class="container-fluid d-flex flex-wrap justify-content-center">
        <?php
        $reviews = db_fetch_all("SELECT * FROM reviews WHERE user_id = " .$userId);
        foreach ($reviews as $review):
            include "include/reviewCard.php"; ?>
        <?php endforeach; ?>
    </div>

</div>
</main>

<?php include "include/footer.php"; ?>
