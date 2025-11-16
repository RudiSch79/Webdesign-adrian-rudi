<?php include "include/header.php"; ?>

<body class="d-flex flex-column min-vh-100">

<main class="flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="w-100" style="max-width: 420px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h2 class="h4 mb-4 text-center">Create your account</h2>

                <form method="POST" action="register_handler.php">

                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="mb-4">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

                </form>

            </div>
        </div>
    </div>
</main>

<?php include "include/footer.php"; ?>
