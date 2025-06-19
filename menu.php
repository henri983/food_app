<?php
session_start();

// --- Food Items avec prix ---
$food_items = [
    [
        'id' => 1,
        'name' => 'Spécialités Camerounaises',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique camerounaise.',
        'image' => 'images_drp/CAmeroun.png',
        'price' => 12.50,
        'regionName' => 'cameroun'
    ],
    [
        'id' => 2,
        'name' => 'Spécialités Centrafricaines',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique centrafricaine.',
        'image' => 'images_drp/Centrafrique.png',
        'price' => 10.00,
        'regionName' => 'centrafrique'
    ],
    [
        'id' => 3,
        'name' => 'Spécialités Maliennes',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique malienne.',
        'image' => 'images_drp/Mali.png',
        'price' => 9.00,
        'regionName' => 'mali'
    ],
    [
        'id' => 4,
        'name' => 'Spécialités Ivoiriennes',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique ivoirienne.',
        'image' => 'images_drp/Cote_d_Ivoire.png',
        'price' => 11.00,
        'regionName' => 'cote_divoire'
    ],
    [
        'id' => 5,
        'name' => 'Spécialités Sénégalaises',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique sénégalaise.',
        'image' => 'images_drp/Senegal.png',
        'price' => 13.50,
        'regionName' => 'senegal'
    ],
];

// --- Gestion ajout au panier ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

    if ($product_id && $quantity > 0) {
        // Trouver l'article dans la liste
        $found_item = null;
        foreach ($food_items as $item) {
            if ($item['id'] == $product_id) {
                $found_item = $item;
                break;
            }
        }
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Menu - Notre Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Découvrez Notre Menu Complet</h1>
    <p class="text-center mb-5">Commandez vos plats préférés directement ici !</p>

    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($food_items as $item): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($item['description']) ?></p>
                    <form action="" method="post" class="mt-auto d-flex align-items-center gap-2">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>" />
                        <button class="btn btn-outline-primary" class="btn btn-outline-secondary mt-2"><a href="specialites.php?region=<?= htmlspecialchars($item['regionName']) ?>">Voir les plats</a></button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
