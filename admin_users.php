<?php
session_start();
require_once 'db_connect.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    $_SESSION['error'] = "Accès refusé.";
    header('Location: index.php');
    exit;
}

// Traitement : supprimer utilisateur
if (isset($_POST['delete_user'])) {
    $delete_id = (int) $_POST['delete_user'];
    if ($delete_id !== $_SESSION['user_id']) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$delete_id]);
        $_SESSION['message'] = "Utilisateur supprimé.";
    } else {
        $_SESSION['error'] = "Impossible de supprimer votre propre compte admin.";
    }
}

// Traitement : mise à jour rôle/approuvé
if (isset($_POST['update_user'])) {
    $user_id = (int) $_POST['user_id'];
    $role = $_POST['role'];
    $approuve = isset($_POST['approuve']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE users SET role = ?, approuve = ? WHERE id = ?");
    $stmt->execute([$role, $approuve, $user_id]);

    $_SESSION['message'] = "Utilisateur mis à jour.";
}

// Récupération des utilisateurs
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>
<div class="container my-5">
    <h2>Gestion des Utilisateurs</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Approuvé</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form method="post" class="d-flex flex-column flex-md-row align-items-center">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="role" class="form-select form-select-sm me-2" style="width: 110px;">
                                <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Client</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                    </td>
                    <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="approuve" value="1" <?= $user['approuve'] ? 'checked' : '' ?>>
                            </div>
                    </td>
                    <td><?= $user['created_at'] ?></td>
                    <td class="text-nowrap">
                            <button type="submit" name="update_user" class="btn btn-sm btn-outline-success me-1">Enregistrer</button>
                        </form>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <form method="post" class="d-inline">
                            <button type="submit" name="delete_user" value="<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
