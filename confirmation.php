<?php
session_start();

$last_order = null;
if (isset($_SESSION['last_order_details'])) {
    $last_order = $_SESSION['last_order_details'];
    // Optional: unset the details after displaying to prevent refresh issues
    // unset($_SESSION['last_order_details']);
} else {
    // If no order details, redirect to menu or home
    $_SESSION['error'] = "Aucune commande récente à afficher.";
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mon Restaurant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Panier (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="alert alert-success text-center" role="alert">
            <h4 class="alert-heading">Commande Confirmée !</h4>
            <p>Merci pour votre commande. Elle a été enregistrée avec succès.</p>
            <hr>
            <p class="mb-0">Votre numéro de commande est : <strong><?php echo htmlspecialchars($last_order['order_id']); ?></strong></p>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Détails de Votre Commande</h2>
            </div>
            <div class="card-body">
                <p><strong>Adresse de livraison :</strong> <?php echo htmlspecialchars($last_order['address']); ?></p>
                <p><strong>Numéro de téléphone :</strong> <?php echo htmlspecialchars($last_order['phone']); ?></p>

                <h3 class="h6 mt-4">Articles commandés :</h3>
                <ul class="list-group list-group-flush mb-3">
                    <?php
                    $total_items_price = 0;
                    foreach ($last_order['cart'] as $item):
                        $item_subtotal = $item['price'] * $item['quantity'];
                        $total_items_price += $item_subtotal;
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo htmlspecialchars($item['quantity']); ?>)</span>
                            <span><?php echo number_format($item_subtotal, 2); ?> &euro;</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="fw-bold fs-5 text-end">Total payé : <?php echo number_format($last_order['total'], 2); ?> &euro;</p>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-primary me-2">Retour à l'accueil</a>
                    <a href="menu.php" class="btn btn-outline-success">Commander d'autres plats</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>