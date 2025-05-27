<?php
session_start();
require_once 'db_connect.php';
// Simuler des frais de livraison
$delivery_fee = 5.00;

$message = '';
$error = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Check if cart is empty before proceeding
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Votre panier est vide. Veuillez ajouter des articles avant de passer commande.";
    header('Location: menu.php');
    exit;
}

$total_cart_price = 0;
foreach ($_SESSION['cart'] as $cart_item) {
    $total_cart_price += $cart_item['price'] * $cart_item['quantity'];
}
$final_total = $total_cart_price + $delivery_fee;

// Handle order submission (simplified)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    if (empty($address) || empty($phone)) {
        $_SESSION['error'] = "Veuillez remplir votre adresse et numéro de téléphone.";
    } else {
        // --- In a real application, you would: ---
        // 1. Save the order details (user_id, items, total, address, phone) to a 'orders' table in the database.
        // 2. Clear the session cart.
        // 3. Redirect to a confirmation page.
        // 4. Integrate with a payment gateway (e.g., Stripe, PayPal). This example just simulates success.

        $_SESSION['last_order_details'] = [
            'cart' => $_SESSION['cart'],
            'address' => $address,
            'phone' => $phone,
            'total' => $final_total,
            'order_id' => uniqid('ORDER_') // Simulate an order ID
        ];

        unset($_SESSION['cart']); // Clear the cart after placing order
        $_SESSION['message'] = "Votre commande a été passée avec succès !";
        header('Location: confirmation.php');
        exit;
    }
    header('Location: checkout.php'); // Redirect to refresh messages
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la Commande</title>
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
                        <a class="nav-link active" aria-current="page" href="#">Panier (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">Passer Votre Commande</h1>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h2 class="h5 mb-0">Récapitulatif du Panier</h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo htmlspecialchars($item['quantity']); ?>)</span>
                                    <span><?php echo number_format($item['price'] * $item['quantity'], 2); ?> &euro;</span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                                <span>Frais de livraison</span>
                                <span><?php echo number_format($delivery_fee, 2); ?> &euro;</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5 bg-light">
                                <span>Total Final</span>
                                <span><?php echo number_format($final_total, 2); ?> &euro;</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h2 class="h5 mb-0">Informations de Livraison</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse de livraison</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Numéro de téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="place_order" class="btn btn-primary btn-lg">Confirmer la Commande</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>