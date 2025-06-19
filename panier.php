<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plat_id'], $_POST['quantite'])) {
    $plat_id = (int) $_POST['plat_id'];
    $quantite = (int) $_POST['quantite'];

    // Vérifier si le plat existe
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([$plat_id]);
    $plat = $stmt->fetch();

    if ($plat) {
        // Initialiser le panier si vide
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Vérifier si le plat est déjà dans le panier
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $plat['id']) {
                $item['quantite'] += $quantite;
                $found = true;
                break;
            }
        }
        unset($item);

        // Ajouter le plat s'il n'est pas encore dans le panier
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $plat['id'],
                'name' => $plat['nom'],
                'price' => $plat['prix'],
                'quantite' => $quantite
            ];
        }

        // Redirection vers le panier
        header('Location: panier.php');
        exit;
    }
}

header('Location: specialites.php');
exit;
?>
<<<<<<< HEAD

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Votre Panier</h1>

    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <p class="text-center fs-4">Votre panier est vide.</p>
        <div class="text-center mt-4">
            <a href="menu.php" class="btn btn-primary">Retour au menu</a>
        </div>
    <?php else: ?>
        <form action="update_cart.php" method="post">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalGeneral = 0; ?>
                    <?php foreach ($cart as $product_id => $item): ?>
                        <?php $total = $item['price'] * $item['quantity']; ?>
                        <?php $totalGeneral += $total; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded">
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> €</td>
                            <td style="width:120px;">
                                <input type="number" name="quantities[<?= $product_id ?>]" value="<?= $item['quantity'] ?>" min="1" class="form-control">
                            </td>
                            <td><?= number_format($total, 2) ?> €</td>
                            <td>
                                <a href="update_cart.php?action=delete&id=<?= $product_id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total général :</th>
                        <th colspan="2"><?= number_format($totalGeneral, 2) ?> €</th>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex justify-content-between">
                <div>
                    <a href="vider_panier.php" class="btn btn-danger" onclick="return confirm('Vider tout le panier ?')">Vider le panier</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-warning">Mettre à jour le panier</button>
                    <a href="valider_commande.php" class="btn btn-success ms-2">Valider la commande</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
>>>>>>> 40e615eef858301fd66a7e2049b45ea52ca3da4f
