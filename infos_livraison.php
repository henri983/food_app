<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez vous connecter.";
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // Simple validation
    if (!$nom || !$prenom || !$adresse || !$telephone || !$email) {
        $error = "Tous les champs sont obligatoires et doivent être valides.";
    } else {
        $_SESSION['livraison'] = compact('nom', 'prenom', 'adresse', 'telephone', 'email');
        header('Location: valider_commande.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Informations de livraison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="mb-4">Informations de livraison</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required value="<?= htmlspecialchars($_SESSION['livraison']['nom'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" required value="<?= htmlspecialchars($_SESSION['livraison']['prenom'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse complète</label>
            <textarea name="adresse" id="adresse" class="form-control" required><?= htmlspecialchars($_SESSION['livraison']['adresse'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" required value="<?= htmlspecialchars($_SESSION['livraison']['telephone'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_SESSION['livraison']['email'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
