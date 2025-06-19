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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, (int) $_POST['quantity']);

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
    header("Location: details.php?id=" . $plat_id);
    exit;
}

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du plat - <?= htmlspecialchars($plat['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <a href="menu.php" class="btn btn-sm btn-outline-primary mb-3">&larr; Retour au menu</a>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($plat['nom']) ?></h2>
            <p class="text-muted"><strong>Région :</strong> <?= ucfirst(str_replace('_', ' ', $plat['region'])) ?></p>
            <p><?= htmlspecialchars($plat['description']) ?></p>
            <p class="fs-4 fw-bold text-success"><?= number_format($plat['prix'], 2) ?> &euro;</p>

            <form method="post" class="d-flex align-items-center mt-4">
                <input type="number" name="quantity" value="1" min="1" class="form-control me-2" style="width:100px;">
                <button type="submit" name="add_to_cart" class="btn btn-primary">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
