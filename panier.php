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
