<?php
session_start();
require_once 'db_connect.php';

// Gérer l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $plat_id = (int) $_POST['plat_id'];
    $quantity = max(1, (int) $_POST['quantity']);

    // Récupération du plat
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([$plat_id]);
    $plat = $stmt->fetch();

    if ($plat) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

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
        header("Location: specialite.php?region=" . urlencode($_GET['region']));
        exit;
    }
}

$region = isset($_GET['region']) ? $_GET['region'] : '';
if (empty($region)) {
    header('Location: menu.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM plats WHERE region = ?");
$stmt->execute([$region]);
$plats = $stmt->fetchAll();

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plats de la région <?= htmlspecialchars($region) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <a href="menu.php" class="btn btn-sm btn-outline-primary mb-4">&larr; Retour au menu</a>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <h2 class="mb-4">Plats de la région : <?= ucfirst($region) ?></h2>

    <div class="row">
        <?php foreach ($plats as $plat): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($plat['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($plat['nom']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($plat['nom']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($plat['description']) ?></p>
                        <p class="fw-bold text-success"><?= number_format($plat['prix'], 2) ?> €</p>

                        <form method="post" class="mt-auto">
                            <input type="hidden" name="plat_id" value="<?= $plat['id'] ?>">
                            <div class="mb-2">
                                <input type="number" name="quantity" value="1" min="1" class="form-control">
                            </div>
                            <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Ajouter au panier</button>
                        </form>

                        <a href="details.php?id=<?= $plat['id'] ?>" class="btn btn-outline-secondary mt-2">Voir les détails</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
