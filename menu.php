<?php
session_start();
require_once 'db_connect.php';
// --- Food Item Data (usually fetched from a database) ---
$food_items = [
    [
        'id' => 1,
        'name' => 'Specialités Camerounaises',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique camerounaise.',
        'image' => 'images/burger.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Spécialités Centrafricaines',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique centrafricaine.',
        'image' => 'images/pizza.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Spécialités Maliennes',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique malienne.',
        'image' => 'images/salad.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Spécialités Ivoiriennes',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique ivoirienne.',
        'image' => 'images/sushi.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Spécialités Sénégalaises',
        'description' => 'Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique sénégalaise.',
        'image' => 'images/pasta.jpg'
    ],
];

// --- Handle adding to cart (same as index.php) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

    if ($product_id && $quantity > 0) {
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
    header('Location: menu.php'); // Redirect back to menu page
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notre Menu Complet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require_once 'nav_bar.php'?>
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mon Restaurant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Panier (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->
<!-- 
    <div class="container my-5">
        <h1 class="text-center mb-4">Découvrez Notre Menu Complet</h1>
        <p class="text-center mb-5">Commandez vos plats préférés directement ici !</p>

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

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($food_items as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="card-text fs-4 fw-bold text-success"><?php echo number_format($item['price'], 2); ?> &euro;</p>
                            <form action="" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                <input type="number" name="quantity" value="1" min="1" max="10" class="form-control me-2" style="width: 80px;">
                                <button type="submit" name="add_to_cart" class="btn btn-primary flex-grow-1">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div> -->
     <div class="container my-5">
        <h1 class="text-center mb-4">Découvrez Notre Menu Complet</h1>
        <p class="text-center mb-5">Commandez vos plats préférés directement ici !</p>
 <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">

   <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="images drp/Cameroun.png">
  <div class="card-body">
    <div class="col">
        <div class="card h-100 mb-4" >

    <h5 class="card-title">Spécialités Camerounaises</h5>
    <p class="card-text">Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique Camerounaise.</p>
    </div>
    </div>
 </div>
 
  <div class="card-body">
    <!-- <a href="#" class="card-link">Card link</a> -->
    <a href="specialite.php?region=Cameroun" class="card-link">Voir le menu...</a>
  </div>
</div>
  <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="images drp/Centrafrique.png">
  <div class="card-body">
    <h5 class="card-title">Spécialités Centrafricaines</h5>
    <p class="card-text">Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique Centrafricaines.</p>
  </div>
 
  <div class="card-body">
    <!-- <a href="#" class="card-link">Card link</a> -->
    <a href="specialite.php?region=Centrafrique" class="card-link">Voir le menu...</a>
  </div>
</div>
  <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="images drp/Mali.png">
  <div class="card-body">
    <h5 class="card-title">Spécialités Maliennes</h5>
    <p class="card-text">Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique Malienne.</p>
  </div>
 
  <div class="card-body">
    <!-- <a href="#" class="card-link">Card link</a> -->
    <a href="specialite.php?region=Mali" class="card-link">Voir le menu...</a>
  </div>
</div>
  <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="images drp/Côte_d'Ivoire.png">
  <div class="card-body">
    <h5 class="card-title">Spécialités Ivoiriennes</h5>
    <p class="card-text">Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique Ivoirienne.</p>
  </div>
 
  <div class="card-body">
    <!-- <a href="#" class="card-link">Card link</a> -->
    <a href="specialite.php?region=Cote_d_Ivoire" class="card-link">Voir le menu...</a>
  </div>
</div>
 <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="images drp/Sénégal.png">
  <div class="card-body">
    <h5 class="card-title">Spécialités Sénégalaises</h5>
    <p class="card-text">Chaque jour nous préparons les produits du marché pour vous offrir une cuisine authentique Sénégalaise.</p>
  </div>
 
  <div class="card-body">
    <!-- <a href="#" class="card-link">Card link</a> -->
    <a href="specialite.php?region=Senegal" class="card-link">Voir le menu...</a>
  </div>
</div>
</div>
</div>
<?php require_once 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>