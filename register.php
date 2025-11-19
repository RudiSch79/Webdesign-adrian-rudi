<?php include "include/header.php"; ?>
<?php 
$users = include "data/users.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (isset($users[$username])) {
        $error = "Username already exists";
    } 
    else if ($password !== $confirm) {
        $error = "Passwords do not match";
    }
    else {
        $users[$username] = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "is_admin" => false,
            "profile_picture" => 'images/profilepictures/default-profile-picture.png'
        ];

        $code = "<?php\nreturn " . var_export($users, true) . ";";
        file_put_contents("data/users.php", $code);


        $_SESSION['user'] = [
        "username" => $username,
        "is_admin" => false
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

                <h2 class="h4 mb-4 text-center">Create your account</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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