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
    <title>Menu des Plats Africains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">   
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Découvrez les Plats Africains</h1>
    <p class="text-center mb-5">Commandez vos plats traditionnels préférés par pays !</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php
    $regions = $pdo->query("SELECT DISTINCT region FROM plats")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($regions as $region):
        $stmtPlats = $pdo->prepare("SELECT * FROM plats WHERE region = ?");
        $stmtPlats->execute([$region]);
        $plats = $stmtPlats->fetchAll();
        if (count($plats) === 0) continue;
    ?>
        <h3 class="mt-5 mb-3 text-capitalize"><?= ucfirst(str_replace('_', ' ', $region)) ?></h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($plats as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nom']) ?>" style="height:200px; object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($item['nom']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($item['description']) ?></p>
                            <p class="fw-bold text-success"><?= number_format($item['prix'], 2) ?> &euro;</p>
                            <form method="post" class="d-flex align-items-center mt-auto">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control me-2" style="width:80px;">
                                <button type="submit" name="add_to_cart" class="btn btn-primary flex-grow-1">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
