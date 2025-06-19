<?php
session_start();
require_once 'db_connect.php';

$plat_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($plat_id <= 0) {
    header('Location: menu.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
$stmt->execute([$plat_id]);
$plat = $stmt->fetch();

if (!$plat) {
    $_SESSION['error'] = "Plat non trouvé.";
    header('Location: menu.php');
    exit;
}

// Ajouter au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'])) {
    $quantity = max(1, (int)$_POST['quantity']);

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$plat_id])) {
        $_SESSION['cart'][$plat_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$plat_id] = [
            'id' => $plat['id'],
            'name' => $plat['nom'],
            'price' => $plat['prix'],
            'quantity' => $quantity
        ];
    }

    $_SESSION['message'] = "Produit ajouté au panier !";
    header("Location: details.php?id=" . $plat_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails - <?= htmlspecialchars($plat['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <a href="javascript:history.back()" class="btn btn-outline-primary mb-4">&larr; Retour</a>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($plat['image']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($plat['nom']) ?>">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($plat['nom']) ?></h2>
            <p class="text-muted">Région : <?= ucfirst($plat['region']) ?></p>
            <p><?= htmlspecialchars($plat['description']) ?></p>
            <h4 class="text-success fw-bold"><?= number_format($plat['prix'], 2) ?> €</h4>

            <form method="post" class="mt-4">
                <label for="quantity" class="form-label">Quantité</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control mb-3" style="width:100px;">
                <button type="submit" class="btn btn-primary">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
</body>
</html>
