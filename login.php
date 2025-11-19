<?php include "include/header.php";

$users = include "data/users.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!isset($users[$username])) {
        $error = "Invalid username or password";
    }
    else if (!password_verify($password, $users[$username]["password"])) {
        $error = "Invalid username or password";
    }
    else {
        $_SESSION["user"] = [
            "username" => $username,
            "is_admin" => $users[$username]["is_admin"] ?? false
        ];
        header("Location: index.php");
        exit;
    }
}
?>

<body class="d-flex flex-column min-vh-100">

<main class="flex-grow-1 d-flex justify-content-center align-items-center">

    <div class="w-100" style="max-width: 420px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h2 class="h4 mb-4 text-center">Welcome back</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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