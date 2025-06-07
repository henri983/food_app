<?php
session_start();
require_once 'db_connect.php';

// Sécurité : Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: connexion.php');
    exit;
}

// Vérification du rôle admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    $_SESSION['error'] = "Accès réservé à l'administrateur.";
    header('Location: index.php');
    exit;
}

$admin_message = '';

// Gestion des redirections vers les pages admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['manage_products'])) {
        header('Location: admin_products.php'); // à créer
        exit;
    } elseif (isset($_POST['view_orders'])) {
        header('Location: admin_orders.php'); // à créer
        exit;
    } elseif (isset($_POST['manage_users'])) {
        header('Location: admin_users.php');
        exit;
    } elseif (isset($_POST['approuver_utilisateur'])) {
        $user_id = (int) $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE users SET approuve = 1 WHERE id = ?");
        $stmt->execute([$user_id]);
        $admin_message = "Utilisateur approuvé avec succès.";
    }
}

// Récupération des utilisateurs non approuvés
$stmt_pending = $pdo->query("SELECT * FROM users WHERE role = 'customer' AND approuve = 0");
$utilisateurs_non_valides = $stmt_pending->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau d'Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Panneau d'Administration</h1>
    <p class="text-center text-muted mb-4">Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>.</p>

    <?php if ($admin_message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($admin_message) ?></div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Gestion des Produits</h5>
                    <p class="card-text text-muted">Ajouter, modifier ou supprimer des articles du menu.</p>
                    <form method="post">
                        <button type="submit" name="manage_products" class="btn btn-primary mt-3">Gérer les produits</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Visualisation des Commandes</h5>
                    <p class="card-text text-muted">Consulter et gérer les commandes passées.</p>
                    <form method="post">
                        <button type="submit" name="view_orders" class="btn btn-success mt-3">Voir les commandes</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Gestion des Utilisateurs</h5>
                    <p class="card-text text-muted">Approuver ou modifier les comptes.</p>
                    <form method="post">
                        <button type="submit" name="manage_users" class="btn btn-warning mt-3">Gérer les utilisateurs</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs à approuver -->
    <h3 class="mb-3">Utilisateurs en attente de validation</h3>
    <?php if (count($utilisateurs_non_valides) === 0): ?>
        <p class="text-muted">Aucun utilisateur à approuver pour le moment.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs_non_valides as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['created_at'] ?></td>
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="approuver_utilisateur" class="btn btn-sm btn-success">Approuver</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
