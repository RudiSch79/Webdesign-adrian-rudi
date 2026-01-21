<?php
require_once __DIR__ . "/include/config.php";

require_login();
require_admin();

$pdo = db();

$message = "";
$error = "";

$confirmId = isset($_GET["confirm"]) ? (int)$_GET["confirm"] : 0;
$confirmUser = null;

if ($confirmId > 0) {
    $stmt = $pdo->prepare("
        SELECT id, username, is_admin, is_active, created_at
        FROM users
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([":id" => $confirmId]);
    $confirmUser = $stmt->fetch();

    if (!$confirmUser) {
        $error = "User not found.";
        $confirmId = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action   = $_POST["action"] ?? "";
    $targetId = (int)($_POST["user_id"] ?? 0);
    $me       = (int)current_user()["id"];

    if ($targetId <= 0) {
        $error = "Invalid user.";
    } elseif ($targetId === $me && in_array($action, ["delete_user", "deactivate", "demote"], true)) {
        $error = "You can't perform that action on your own account.";
    } else {
        try {
            if ($action === "deactivate") {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 0 WHERE id = :id");
                $stmt->execute([":id" => $targetId]);
                $message = "User deactivated.";

            } elseif ($action === "activate") {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = :id");
                $stmt->execute([":id" => $targetId]);
                $message = "User activated.";

            } elseif ($action === "promote") {
                $stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE id = :id");
                $stmt->execute([":id" => $targetId]);
                $message = "User promoted to admin.";

            } elseif ($action === "demote") {
                $stmt = $pdo->prepare("UPDATE users SET is_admin = 0 WHERE id = :id");
                $stmt->execute([":id" => $targetId]);
                $message = "Admin rights removed.";

            } elseif ($action === "delete_user") {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                $stmt->execute([":id" => $targetId]);
                $message = "User deleted.";

            } else {
                $error = "Unknown action.";
            }
        } catch (Throwable $e) {
            $error = "Action failed.";
        }
    }

    redirect("adminUserManagement.php");
}

$stmt = $pdo->query("
    SELECT id, username, avatar_path, is_admin, is_active, created_at
    FROM users
    ORDER BY created_at DESC
");
$users = $stmt->fetchAll();

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

    <?php if ($confirmUser): ?>
        <div class="card shadow-sm p-4 mb-4 border-danger">
            <h5 class="mb-2">Confirm delete</h5>
            <p class="mb-3">
                You are about to delete user:
                <br><strong><?= e($confirmUser["username"]) ?></strong>
                <br><span class="text-muted small">This will also delete their reviews, comments, and forum posts.</span>
            </p>

            <div class="d-flex gap-2">
                <a href="adminUserManagement.php" class="btn btn-outline-secondary">Cancel</a>

                <form method="POST" class="m-0">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" value="<?= (int)$confirmUser["id"] ?>">
                    <button class="btn btn-danger">Yes, delete</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <div class="row">

            <div class="col-md-3 text-center border-end">
                <h5 class="mt-2">Admin Panel</h5>
                <p class="text-muted mb-4">User management</p>

                <div class="d-grid gap-2">
                    <a href="admin_reviews.php" class="btn btn-outline-secondary">Review moderation</a>
                    <a href="index.php" class="btn btn-outline-secondary">Back to homepage</a>
                </div>

                <hr>

                <div class="text-start small text-muted">
                    Actions:
                    <ul class="mb-0">
                        <li>Activate / deactivate</li>
                        <li>Promote / demote</li>
                        <li>Delete (with confirmation)</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-9 ps-4">
                <h5 class="mt-2 mb-3">Users</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $u): ?>
                            <?php
                                $avatar = $u["avatar_path"] ?: "data/images/profilePicPlaceholder.png";
                                $isActive = (int)$u["is_active"] === 1;
                                $isAdmin  = (int)$u["is_admin"] === 1;
                                $isMe     = (int)$u["id"] === (int)current_user()["id"];
                            ?>
                            <tr>
                                <td style="width:70px;">
                                    <img src="<?= e($avatar) ?>" alt="avatar"
                                         width="40" height="40"
                                         class="rounded-circle"
                                         style="object-fit:cover;">
                                </td>
                                <td>
                                    <?= e($u["username"]) ?>
                                    <?php if ($isMe): ?>
                                        <span class="badge bg-info text-dark ms-2">You</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isActive): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isAdmin): ?>
                                        <span class="badge bg-warning text-dark">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border">User</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= e($u["created_at"]) ?></td>

                                <td class="text-end">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= (int)$u["id"] ?>">

                                        <?php if ($isActive): ?>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    name="action" value="deactivate"
                                                    <?= $isMe ? "disabled" : "" ?>>
                                                Deactivate
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-success"
                                                    name="action" value="activate">
                                                Activate
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($isAdmin): ?>
                                            <button class="btn btn-sm btn-outline-warning"
                                                    name="action" value="demote"
                                                    <?= $isMe ? "disabled" : "" ?>>
                                                Demote
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-warning"
                                                    name="action" value="promote">
                                                Promote
                                            </button>
                                        <?php endif; ?>

                                        <a class="btn btn-sm btn-outline-danger <?= $isMe ? "disabled" : "" ?>"
                                           href="adminUserManagement.php?confirm=<?= (int)$u["id"] ?>">
                                            Delete
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</div>
</main>

<?php include "include/footer.php"; ?>
