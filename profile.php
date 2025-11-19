<?php include "include/header.php"; ?>

<?php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$users = include "data/users.php";

$username = $_SESSION['user']['username'];
$user     = $users[$username];

$profilePicture = $user["profile_picture"];

$message = "";
$error   = "";

$uploadFolder = "data/images/profilePicUploads/";


if (isset($_POST["upload_picture"])) {

    if (!empty($_FILES["profile_image"]["name"])) {

        $filename = $username . "_" . time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetPath = $uploadFolder . $filename;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetPath)) {

            if (!empty($user["profile_picture"]) && 
                $user["profile_picture"] !== "data/images/profilePicPlaceholder.png") {
                if (file_exists($user["profile_picture"])) {
                    unlink($user["profile_picture"]);
                }
            }

            $users[$username]["profile_picture"] = $targetPath;

            file_put_contents("data/users.php", "<?php\nreturn " . var_export($users, true) . ";");

            $_SESSION["user"]["profile_picture"] = $targetPath;

            $message = "Profile picture uploaded successfully!";
            $profilePicture = $targetPath;

        } else {
            $error = "Failed to upload file.";
        }
    }
}

if (isset($_POST["delete_picture"])) {

    if (!empty($user["profile_picture"]) &&
        $user["profile_picture"] !== "data/images/profilePicPlaceholder.png") {

        $file = $user["profile_picture"];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    $defaultPic = "data/images/profilePicPlaceholder.png";
    $users[$username]["profile_picture"] = $defaultPic;

    file_put_contents("data/users.php", "<?php\nreturn " . var_export($users, true) . ";");

    $_SESSION["user"]["profile_picture"] = $defaultPic;

    $message = "Profile picture deleted.";
    $profilePicture = $defaultPic;
}


if (isset($_POST["update_details"])) {

    $newUsername = trim($_POST["username"]);

    if ($newUsername === "") {
        $error = "Username cannot be empty.";
    } 
    elseif ($newUsername !== $username && isset($users[$newUsername])) {
        $error = "This username is already taken.";
    } 
    else {

        if ($newUsername !== $username) {

            $users[$newUsername] = $users[$username];
            $users[$newUsername]["username"] = $newUsername; 

            unset($users[$username]);

            $_SESSION['user']['username'] = $newUsername;

            $username = $newUsername;
        }

        file_put_contents("data/users.php", "<?php\nreturn " . var_export($users, true) . ";");

        $message = "Details updated successfully!";
    }
}

if (isset($_POST["change_password"])) {

    $current = trim($_POST["current_password"]);
    $new     = trim($_POST["new_password"]);
    $confirm = trim($_POST["confirm_password"]);

    if (!password_verify($current, $user["password"])) {
        $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {

        $users[$username]["password"] = password_hash($new, PASSWORD_DEFAULT);

        file_put_contents("data/users.php", "<?php\nreturn " . var_export($users, true) . ";");

        $message = "Password updated successfully!";
    }
}

?>

<body class="d-flex flex-column min-vh-100">

<main class="flex-grow-1">
<div class="container py-5">

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <div class="row">

            <div class="col-md-3 text-center border-end">

                <img src="<?= htmlspecialchars($profilePicture) ?>"
                     class="rounded-circle mb-3"
                     width="150" height="150"
                     style="object-fit: cover;">

                <form method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="profile_image" class="form-control mb-2" required>
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
                               value="<?= htmlspecialchars($username) ?>" required>
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

</div>
</main>

<?php include "include/footer.php"; ?>