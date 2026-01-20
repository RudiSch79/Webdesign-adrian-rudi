<?php
require_once "include/config.php";

$pdo = db();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Invalid username or password";
    } else {
        $stmt = $pdo->prepare("
            SELECT id, username, password_hash, is_admin, is_active
            FROM users
            WHERE username = :u
            LIMIT 1
        ");
        $stmt->execute([":u" => $username]);
        $user = $stmt->fetch();

        if (!$user || (int)$user["is_active"] !== 1) {
            $error = "Invalid username or password";
        } elseif (!password_verify($password, $user["password_hash"])) {
            $error = "Invalid username or password";
        } else {
            login_user($user);
            redirect("index.php");
        }
    }
}

include __DIR__ . "/include/header.php";
?>
<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1 d-flex justify-content-center align-items-center">
  <div class="w-100" style="max-width: 420px;">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h2 class="h4 mb-4 text-center">Welcome back</h2>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="mb-4">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</main>
<?php include "include/footer.php"; ?>
