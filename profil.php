<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    if ($prenom && $adresse && $telephone) {
        $stmt = $pdo->prepare("UPDATE users SET prenom = ?, adresse = ?, telephone = ? WHERE id = ?");
        $stmt->execute([$prenom, $adresse, $telephone, $user_id]);
        $_SESSION['message'] = "Profil mis à jour avec succès.";
        header('Location: mon_profil.php');
        exit;
    } else {
        $error = "Tous les champs sont requis.";
    }
}

// Charger les infos existantes
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>
<div class="container my-5">
    <h2>Modifier mes informations</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea class="form-control" name="adresse" required><?= htmlspecialchars($user['adresse']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
