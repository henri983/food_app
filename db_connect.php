<?php
// db_connect.php

$host = 'localhost'; // Ou l'adresse IP de votre serveur de base de données
$db   = 'food_app_db';
$user = 'root';      // Votre nom d'utilisateur MySQL
$pass = '';          // Votre mot de passe MySQL (laissez vide si pas de mot de passe)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Rapporte les erreurs sous forme d'exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les lignes comme des tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactive l'émulation des requêtes préparées pour plus de sécurité
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En cas d'erreur de connexion, affiche un message et arrête le script
    // En production, vous ne devriez pas afficher l'erreur brute à l'utilisateur
    // mais plutôt un message générique et logguer l'erreur.
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

?>
