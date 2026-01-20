<?php
require_once "include/config.php";

$pdo = db();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $confirm  = trim($_POST['confirm_password'] ?? "");

    if ($username === "" || $password === "" || $confirm === "") {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match";
    } else {

        // Optional: basic username length check to match schema (VARCHAR 50)
        if (mb_strlen($username) > 50) {
            $error = "Username is too long (max 50 characters).";
        } else {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
            $stmt->execute([":u" => $username]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $error = "Username already exists";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $defaultAvatar = "data/images/profilePicPlaceholder.png";

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO users (username, password_hash, avatar_path, is_admin, is_active)
                        VALUES (:u, :p, :a, 0, 1)
                    ");
                    $stmt->execute([
                        ":u" => $username,
                        ":p" => $hash,
                        ":a" => $defaultAvatar,
                    ]);

                    $newUserId = (int)$pdo->lastInsertId();

                    // Log user in (same session shape as your login.php)
                    login_user([
                        "id" => $newUserId,
                        "username" => $username,
                        "is_admin" => 0,
                    ]);

                    redirect("index.php");

                } catch (Throwable $e) {
                    $error = "Could not create account. Please try a different username.";
                }
            }
        }
    }
}

include "include/header.php";
?>

<body class="d-flex flex-column min-vh-100">

<main class="flex-grow-1 d-flex justify-content-center align-items-center">

    <div class="w-100" style="max-width: 420px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h2 class="h4 mb-4 text-center">Create your account</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="mb-4">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

            </div>
        </div>
    </div>

</main>

<?php include "include/footer.php"; ?>
