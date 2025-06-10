<?php
session_start();
require_once 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: menu.php");
    exit;
}

$plat_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
$stmt->execute([$plat_id]);
$plat = $stmt->fetch();

if (!$plat) {
    $_SESSION['error'] = "Plat introuvable.";
    header("Location: menu.php");
    exit;
}

// Gérer l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, (int)$_POST['quantity']);

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
    header("Location: menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($plat['nom']); ?> - Détails du plat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="images/plats/<?php echo htmlspecialchars($plat['image']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo htmlspecialchars($plat['nom']); ?></h1>
            <p class="text-muted"><?php echo htmlspecialchars($plat['description']); ?></p>
            <h3 class="text-success"><?php echo number_format($plat['prix'], 2); ?> €</h3>

            <form action="" method="post" class="mt-4">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantité</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="10">
                </div>
                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
