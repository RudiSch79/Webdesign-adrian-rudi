<?php include "../include/header.php"; ?>
<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="w-100" style="max-width: 420px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h2 class="h4 mb-4 text-center">Welcome back</h2>

                <form>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Username">
                    </div>

                    <div class="mb-4">
                        <input type="password" class="form-control" placeholder="Password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Sign in</button>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include "../include/footer.php"; ?>
