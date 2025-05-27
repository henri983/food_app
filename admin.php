<?php
session_start();
require_once 'db_connect.php';
// --- SECURITY WARNING: This is a very basic check.
// In a real application, you would check $_SESSION['user_role']
// after a successful login to ensure the user is an admin.
// For demonstration, we'll just check if a user is logged in.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Accès non autorisé. Veuillez vous connecter en tant qu'administrateur.";
    header('Location: connexion.php');
    exit;
}

// Simulated admin tasks (in a real app, these would interact with a DB)
$admin_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['manage_products'])) {
        $admin_message = "Gestion des produits : (Redirection vers une page de gestion de produits)";
        // In reality: header('Location: admin_products.php');
    } elseif (isset($_POST['view_orders'])) {
        $admin_message = "Visualisation des commandes : (Redirection vers une page de liste de commandes)";
        // In reality: header('Location: admin_orders.php');
    } elseif (isset($_POST['manage_users'])) {
        $admin_message = "Gestion des utilisateurs : (Redirection vers une page de gestion des utilisateurs)";
        // In reality: header('Location: admin_users.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mon Restaurant - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin.php">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Produits</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Utilisateurs</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="deconnexion.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">Panneau d'Administration</h1>
        <p class="text-center text-muted mb-5">Bienvenue, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>. Gérez votre restaurant ici.</p>

        <?php if ($admin_message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($admin_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Produits</h5>
                        <p class="card-text text-muted">Ajouter, modifier ou supprimer des articles du menu.</p>
                        <form action="" method="post">
                            <button type="submit" name="manage_products" class="btn btn-primary mt-3">Aller à la gestion des produits</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Visualisation des Commandes</h5>
                        <p class="card-text text-muted">Consulter et gérer les commandes passées.</p>
                        <form action="" method="post">
                            <button type="submit" name="view_orders" class="btn btn-success mt-3">Voir les commandes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Utilisateurs</h5>
                        <p class="card-text text-muted">Ajouter ou modifier les informations des utilisateurs.</p>
                        <form action="" method="post">
                            <button type="submit" name="manage_users" class="btn btn-warning mt-3">Gérer les utilisateurs</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>