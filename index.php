<?php
session_start();
require_once 'db_connect.php';

// Traitement ajout panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

    if ($product_id && $quantity > 0) {
        $stmt = $pdo->prepare("SELECT id, nom AS name, prix AS price FROM plats WHERE id = ?");
        $stmt->execute([$product_id]);
        $found_item = $stmt->fetch();

        if ($found_item) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $found_item['id'],
                    'name' => $found_item['name'],
                    'price' => $found_item['price'],
                    'quantity' => $quantity
                ];
            }
            $_SESSION['message'] = "Produit ajouté au panier !";
        } else {
            $_SESSION['error'] = "Produit introuvable.";
        }
    } else {
        $_SESSION['error'] = "Quantité invalide.";
    }
    header('Location: menu.php');
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
    <title>Restaurant Africain - Saveurs d'Afrique</title>
    <link rel="stylesheet" href="style.css">

   <title>Restaurant Africain - Saveurs d'Afrique</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="overlay">
        <h1>Saveurs d'Afrique</h1>
        <p>Bienvenue dans notre univers culinaire, où chaque plat raconte une histoire du continent africain.</p>
        <a href="menu.php"><button class="btn">Voir le Menu</button></a>
    </div>
<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
