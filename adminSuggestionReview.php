<?php
require_once "include/config.php";

require_login();
require_admin();

$pdo = db();

$message = "";
$error = "";

$confirmId = isset($_GET["confirm"]) ? (int)$_GET["confirm"] : 0;
$confirmSuggestion = null;

if ($confirmId > 0) {
    $stmt = $pdo->prepare("
        SELECT s.id, s.type, s.name, s.status, s.created_at, u.username
        FROM suggestions s
        JOIN users u ON u.id = s.user_id
        WHERE s.id = :id
        LIMIT 1
    ");
    $stmt->execute([":id" => $confirmId]);
    $confirmSuggestion = $stmt->fetch();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"];
    $sid = (int)($_POST["suggestion_id"]);

            $stmt = $pdo->prepare("
                SELECT id, user_id, type, name, status
                FROM suggestions
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute([":id" => $sid]);
            $sug = $stmt->fetch();


                $type = $sug["type"];
                $name = trim($sug["name"]);

                if ($action === "approve") {

                    $pdo->beginTransaction();

                    if ($type === "brand") {
                        $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = :n LIMIT 1");
                        $stmt->execute([":n" => $name]);
                        $exists = $stmt->fetchColumn();

                        if (!$exists) {
                            $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (:n)");
                            $stmt->execute([":n" => $name]);
                        }

                    } elseif ($type === "category") {
                        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = :n LIMIT 1");
                        $stmt->execute([":n" => $name]);
                        $exists = $stmt->fetchColumn();

                        if (!$exists) {
                            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:n)");
                            $stmt->execute([":n" => $name]);
                        }

                    } else {
                        throw new RuntimeException("Unknown suggestion type.");
                    }

                    $stmt = $pdo->prepare("UPDATE suggestions SET status = 'approved' WHERE id = :id");
                    $stmt->execute([":id" => $sid]);

                    $pdo->commit();

                    $message = "Suggestion approved.";

                } elseif ($action === "reject") {

                    $stmt = $pdo->prepare("
                        UPDATE suggestions
                        SET status = 'rejected', admin_note = :note
                        WHERE id = :id
                    ");
                    $stmt->execute([":note" => $note, ":id" => $sid]);

                    $message = "Suggestion rejected.";

                } else {
                    $error = "Unknown action.";
                }
            }

$stmt = $pdo->query("
    SELECT s.id, s.type, s.name, s.status, s.created_at, u.username
    FROM suggestions s
    JOIN users u ON u.id = s.user_id
    ORDER BY
        CASE s.status WHEN 'pending' THEN 0 WHEN 'approved' THEN 1 ELSE 2 END,
        s.created_at DESC
");
$suggestions = $stmt->fetchAll();

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

    <?php if ($confirmSuggestion): ?>
        <div class="card shadow-sm p-4 mb-4 border-danger">
            <h5 class="mb-2">Confirm reject</h5>
            <p class="mb-3">
                Reject this suggestion?
                <br><strong><?= e($confirmSuggestion["type"]) ?>:</strong> <?= e($confirmSuggestion["name"]) ?>
                <br><span class="text-muted small">Suggested by <?= e($confirmSuggestion["username"]) ?></span>
            </p>

            <form method="POST" class="mb-2">
                <input type="hidden" name="action" value="reject">
                <input type="hidden" name="suggestion_id" value="<?= (int)$confirmSuggestion["id"] ?>">

                <div class="mb-3">
                    <label class="form-label">Admin note (optional)</label>
                    <input type="text" name="admin_note" class="form-control" maxlength="255"
                           placeholder="Reason for rejection (optional)">
                </div>

                <div class="d-flex gap-2">
                    <a href="adminSuggestions.php" class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <div class="row">

            <div class="col-md-3 text-center border-end">
                <h5 class="mt-2">Admin Panel</h5>
                <p class="text-muted mb-4">Suggestions</p>

                <div class="d-grid gap-2">
                    <a href="adminUserManagement.php" class="btn btn-outline-secondary">User management</a>
                    <a href="adminPanel.php" class="btn btn-outline-secondary">Review moderation</a>
                    <a href="index.php" class="btn btn-outline-secondary">Back to homepage</a>
                </div>

            </div>

            <div class="col-md-9 ps-4">
                <h5 class="mt-2 mb-3">All suggestions</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($suggestions as $s): ?>
                            <?php
                                $isPending = ($s["status"] === "pending");
                            ?>
                            <tr>
                                <td><span class="badge text-bg-light border"><?= e($s["type"]) ?></span></td>
                                <td><?= e($s["name"]) ?></td>
                                <td><?= e($s["username"]) ?></td>
                                <td>
                                    <?php if ($s["status"] === "pending"): ?>
                                        <span class="badge bg-secondary">pending</span>
                                    <?php elseif ($s["status"] === "approved"): ?>
                                        <span class="badge bg-success">approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= e($s["created_at"]) ?></td>

                                <td class="text-end">
                                    <?php if ($isPending): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="suggestion_id" value="<?= (int)$s["id"] ?>">
                                            <button class="btn btn-sm btn-outline-success" name="action" value="approve">
                                                Approve
                                            </button>
                                        </form>

                                        <a class="btn btn-sm btn-outline-danger"
                                           href="adminSuggestions.php?confirm=<?= (int)$s["id"] ?>">
                                            Reject
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">No actions</span>
                                    <?php endif; ?>
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
