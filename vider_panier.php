<?php
session_start();

// Supprimer le contenu du panier
unset($_SESSION['panier']);

// Message de confirmation (optionnel)
$_SESSION['message'] = "Le panier a été vidé.";

// Redirection vers la page du panier ou d'accueil
header('Location: panier.php');
exit;
?>