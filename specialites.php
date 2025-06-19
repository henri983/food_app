<?php

session_start();

require_once 'db_connect.php';



$region = isset($_GET['region']) ? $_GET['region'] : '';

if (empty($region)) {

    header('Location: menu.php');

    exit;

}



// Récupérer tous les plats de la région

$stmt = $pdo->prepare("SELECT * FROM plats WHERE region = ?");

$stmt->execute([$region]);

$plats = $stmt->fetchAll();

?>



<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Plats de la région <?= htmlspecialchars($region) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    

</head>

<body>

<?php require_once 'nav_bar.php'; ?>



<div class="container my-5">

    <a href="menu.php" class="btn btn-sm btn-outline-primary mb-4">&larr; Retour au menu</a>



    <h2 class="mb-4">Plats de la région : <?= ucfirst($region) ?></h2>



    <div class="row">

        <?php if (count($plats) > 0): ?>

            <?php foreach ($plats as $plat): ?>

                <div class="col-md-4 mb-4">

                    <div class="card h-100 shadow-sm">

                        <img src="<?= htmlspecialchars($plat['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($plat['nom']) ?>">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($plat['region']) ?></h5>

                            <h5 class="card-title"><?= htmlspecialchars($plat['nom']) ?></h5>

                            <p class="card-text"><?= htmlspecialchars($plat['description']) ?></p>

                            <p class="fw-bold text-success"><?= number_format($plat['prix'], 2) ?> €</p>
                            
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

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-muted">Aucun plat trouvé pour cette région.</p>

        <?php endif; ?>

    </div>

</div>



<?php require_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>