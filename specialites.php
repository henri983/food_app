<?php
session_start();
require_once 'db_connect.php';

$region = isset($_GET['region']) ? $_GET['region'] : '';
if (empty($region)) {
    header('Location: menu.php');
    exit;
}

// Récupérer tous les plats de la région
$stmt = $pdo->prepare("SELECT * FROM plats WHERE region = ?");
$stmt->execute([$region]);
$plats = $stmt->fetchAll();

// Traitement ajout panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plat_id'], $_POST['quantity'])) {
    $plat_id = (int)$_POST['plat_id'];
    $quantity = max(1, (int)$_POST['quantity']);

    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([$plat_id]);
    $plat = $stmt->fetch();

    if ($plat) {
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
        header("Location: specialite.php?region=" . urlencode($region));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Spécialités - <?= htmlspecialchars($region) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <a href="menu.php" class="btn btn-outline-secondary mb-3">&larr; Retour</a>
    <h2 class="mb-4">Plats de la région : <?= ucfirst($region) ?></h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($plats as $plat): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($plat['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($plat['nom']) ?>">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($plat['nom']) ?></h5>
                        <p><?= htmlspecialchars($plat['description']) ?></p>
                        <p class="text-success fw-bold"><?= number_format($plat['prix'], 2) ?> €</p>

                        <form method="post" class="d-flex flex-column gap-2">
                            <input type="hidden" name="plat_id" value="<?= $plat['id'] ?>">
                            <a href="details.php?id=<?= $plat['id'] ?>" class="btn btn-outline-secondary w-100">Détails</a>
                        </form>
                    
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
</body>
</html>
