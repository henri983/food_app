<?php
session_start();

// Mettre à jour les quantités
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        if ($qty > 0 && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    $_SESSION['message'] = "Panier mis à jour avec succès.";
    header('Location: panier.php');
    exit;
}

// Supprimer un article
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        $_SESSION['message'] = "Produit supprimé du panier.";
    }
    header('Location: panier.php');
    exit;
}
