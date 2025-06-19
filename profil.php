<?php
session_start();
require_once 'db_connect.php';

if (empty($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $ancien_mdp = $_POST['ancien_mdp'] ?? '';
    $nouveau_mdp = $_POST['nouveau_mdp'] ?? '';
    $confirm_mdp = $_POST['confirm_mdp'] ?? '';
    $photo_name = null;

    // Photo de profil (upload)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['photo']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo_name);
        } else {
            $error = "Format de photo non supporté.";
        }
    }

    if ($prenom && $adresse && $telephone) {
        // MAJ infos de base
        $sql = "UPDATE users SET prenom = ?, adresse = ?, telephone = ?";
        $params = [$prenom, $adresse, $telephone];

        if ($photo_name) {
            $sql .= ", photo = ?";
            $params[] = $photo_name;
        }

        $sql .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = "Profil mis à jour avec succès.";

        // Changement de mot de passe
        if (!empty($ancien_mdp) && !empty($nouveau_mdp) && !empty($confirm_mdp)) {
            if ($nouveau_mdp !== $confirm_mdp) {
                $error = "Les nouveaux mots de passe ne correspondent pas.";
            } else {
                $stmt = $pdo->prepare("SELECT mot_de_passe FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user_pass = $stmt->fetchColumn();

                if (password_verify($ancien_mdp, $user_pass)) {
                    $new_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET mot_de_passe = ? WHERE id = ?");
                    $stmt->execute([$new_hash, $user_id]);
                    $message .= " Mot de passe changé.";
                } else {
                    $error = "Ancien mot de passe incorrect.";
                }
            }
        }

        if (!$error) {
            $_SESSION['message'] = $message;
            header('Location: mon_profil.php');
            exit;
        }
    } else {
        $error = "Tous les champs sont requis.";
    }
}

// Récupération des données utilisateur
$stmt = $pdo->prepare("SELECT prenom, adresse, telephone, photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Mon profil</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne profil -->
        <div class="col-md-4 text-center mb-4">
            <?php if (!empty($user['photo'])): ?>
                <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" class="img-thumbnail" alt="Photo de profil" style="max-width: 200px;">
            <?php else: ?>
                <img src="https://via.placeholder.com/200x200?text=Photo" class="img-thumbnail" alt="Photo de profil">
            <?php endif; ?>
            <p class="mt-3"><strong><?= htmlspecialchars($user['prenom']) ?></strong></p>
            <p><?= htmlspecialchars($user['adresse']) ?></p>
            <p><?= htmlspecialchars($user['telephone']) ?></p>
        </div>

        <!-- Colonne formulaire -->
        <div class="col-md-8">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control"
                           value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea id="adresse" name="adresse" class="form-control" required><?= htmlspecialchars($user['adresse'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" class="form-control"
                           value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Photo de profil</label>
                    <input type="file" name="photo" id="photo" accept="image/png, image/jpeg" class="form-control">
                </div>

                <hr>

                <h5>Changer le mot de passe</h5>
                <div class="mb-3">
                    <label for="ancien_mdp" class="form-label">Ancien mot de passe</label>
                    <input type="password" name="ancien_mdp" id="ancien_mdp" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="nouveau_mdp" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="nouveau_mdp" id="nouveau_mdp" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="confirm_mdp" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_mdp" id="confirm_mdp" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

