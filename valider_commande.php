<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    $_SESSION['error'] = "Votre panier est vide.";
    header('Location: menu.php');
    exit;
}

// Simuler un utilisateur connecté (à adapter selon ton système d'authentification)
$user_id = 1;

try {
    // Démarrer une transaction
    $pdo->beginTransaction();

    // Calculer le total de la commande
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insérer la commande principale
    $stmt = $pdo->prepare("INSERT INTO commandes (user_id, date_commande, total) VALUES (?, NOW(), ?)");
    $stmt->execute([$user_id, $total]);

    // Récupérer l'ID de la commande insérée
    $commande_id = $pdo->lastInsertId();

    // Préparer l'insertion des détails
    $detail_stmt = $pdo->prepare("INSERT INTO commande_details (commande_id, plat_id, quantite, prix) VALUES (?, ?, ?, ?)");

    // Insérer chaque détail de la commande
    foreach ($_SESSION['cart'] as $item) {
        $detail_stmt->execute([
            $commande_id,
            $item['id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    // Valider la transaction
    $pdo->commit();

    // Vider le panier
    unset($_SESSION['cart']);
    $_SESSION['message'] = "Commande validée avec succès !";

} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    $pdo->rollBack();
    $_SESSION['error'] = "Erreur lors de la validation de la commande : " . $e->getMessage();
}

header('Location: menu.php');
exit;
