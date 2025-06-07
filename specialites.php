<?php
require_once 'db_connect.php'; // fichier de connexion à la base
$region = $_GET['region'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM plats WHERE region = ?");
$stmt->execute([$region]);
$plats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Spécialités <?php echo ucfirst(htmlspecialchars($region)); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once 'nav_bar.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Spécialités <?php echo ucfirst(htmlspecialchars($region)); ?></h1>
    <p class="text-center mb-4">Découvrez les plats traditionnels de cette région.</p>

    <?php if (count($plats) === 0): ?>
        <div class="alert alert-warning text-center">Aucun plat trouvé pour cette région.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($plats as $plat): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo htmlspecialchars($plat['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($plat['nom']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($plat['nom']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($plat['description']); ?></p>
                            <p class="fw-bold text-success"><?php echo number_format($plat['prix'], 2); ?> &euro;</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
