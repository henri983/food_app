-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 19 juin 2025 à 13:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `food_app_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_commande` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `date_commande`, `total`, `nom`, `prenom`, `adresse`, `telephone`, `email`) VALUES
(1, 1, '2025-06-09 16:37:02', 10.50, NULL, NULL, NULL, NULL, NULL),
(2, 1, '2025-06-10 10:52:07', 43.00, NULL, NULL, NULL, NULL, NULL),
(3, 1, '2025-06-10 10:52:46', 45.00, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande_details`
--

CREATE TABLE `commande_details` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `plat_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_details`
--

INSERT INTO `commande_details` (`id`, `commande_id`, `plat_id`, `quantite`, `prix`) VALUES
(1, 1, 1, 1, 10.50),
(2, 2, 1, 2, 12.50),
(3, 2, 3, 2, 9.00),
(4, 3, 3, 5, 9.00);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `status` enum('pending','preparing','out_for_delivery','delivered','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plats`
--

CREATE TABLE `plats` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(6,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plats`
--

INSERT INTO `plats` (`id`, `nom`, `description`, `prix`, `image`, `region`) VALUES
(1, 'Ndole', 'Feuilles de Ndole aux arachides avec viande ou poisson et les crevettes', 10.50, 'images/ndole.jpg', 'cameroun'),
(2, 'Poulet DG', 'Poulet aux bananes plantains et légumes sautés', 10.00, 'images/poulet_dg.jpg', 'cameroun'),
(3, 'Koki', 'Gâteau de haricots cuits à la vapeur dans des feuilles', 7.50, 'images/koki.jpg', 'cameroun'),
(4, 'Tô au gombo', 'Pâte de mil avec sauce au gombo et viande', 8.50, 'images/to_gombo.jpg', 'mali'),
(5, 'Riz gras malien', 'Riz parfumé au gras de viande et épices', 9.00, 'images/riz_gras.jpg', 'mali'),
(6, 'Capitaine braisé', 'Poisson capitaine grillé aux épices', 12.00, 'images/capitaine.jpg', 'mali'),
(7, 'Thieboudienne', 'Riz au poisson, plat emblématique du Sénégal', 10.00, 'images/thieb.jpg', 'senegal'),
(8, 'Yassa poulet', 'Poulet mariné au citron et oignons sautés', 9.50, 'images/yassa.jpg', 'senegal'),
(9, 'Mafe', 'Ragoût de viande à la sauce d’arachide', 9.00, 'images/mafe.jpg', 'senegal'),
(10, 'Garba', 'Attiéké avec thon frit et piment', 6.50, 'images/garba.jpg', 'cote_divoire'),
(11, 'Sauce graine', 'Sauce à base de graines de palme avec viande', 8.00, 'images/sauce_graine.jpg', 'cote_divoire'),
(12, 'Alloco', 'Bananes plantains frites accompagnées de sauce', 5.50, 'images/alloco.jpg', 'cote_divoire'),
(13, 'koko', 'Feuille de Yetûn avec de la viande', 7.00, 'images/koko.jpg', 'centrafrique'),
(14, 'Ngounza', 'Feuilles de manioc pilées en sauce', 6.50, 'images/ngounza.jpg', 'centrafrique'),
(15, 'Gozo', 'Pâte de manioc avec sauce viande ou poisson', 7.00, 'images/gozo.jpg', 'centrafrique');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `stock`, `category`, `created_at`) VALUES
(1, 'Burger Classique', 'Un délicieux burger avec viande de bœuf, fromage, salade, tomate et oignon.', 9.99, 'images/burger.jpg', 50, 'Burgers', '2025-05-22 12:11:55'),
(2, 'Pizza Margherita', 'La classique pizza Margherita avec sauce tomate, mozzarella fraîche et basilic.', 12.50, 'images/pizza.jpg', 40, 'Pizzas', '2025-05-22 12:11:55'),
(3, 'Salade César', 'Salade croquante avec poulet grillé, croûtons, parmesan et sauce César.', 8.75, 'images/salad.jpg', 30, 'Salades', '2025-05-22 12:11:55'),
(4, 'Sushi Assorti', 'Sélection de sushis et makis frais du jour.', 18.00, 'images/sushi.jpg', 25, 'Sushis', '2025-05-22 12:11:55');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approuve` tinyint(1) DEFAULT 1,
  `photo` varchar(255) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `approuve`, `photo`, `prenom`, `adresse`, `telephone`) VALUES
(10, 'momo', 'momo@gmail.com', '$2y$10$W0Uhmslu1wtO.gcyZxr5SOimLx5Lf5HVvBJaIx/5N/8fSrTcwWsnG', 'customer', '2025-06-18 13:00:23', 1, NULL, NULL, NULL, NULL),
(11, 'admin', 'admin@foodapp.com', '$2y$10$KmUjNBhm8KOYbKGOEb9h1.IstHBH3p2Ye/VS6km9R8DjX8ysuvgQ2', 'admin', '2025-06-18 18:18:31', 1, NULL, NULL, NULL, NULL),
(12, 'user', 'user@foodapp.com', '$2y$10$hxVvXF3L0XoV4CmtKSm2nux8SiG1ORIkBb9JKS3upEjPs8qaXwBR6', 'customer', '2025-06-18 18:20:14', 1, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `plat_id` (`plat_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `plats`
--
ALTER TABLE `plats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commande_details`
--
ALTER TABLE `commande_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plats`
--
ALTER TABLE `plats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD CONSTRAINT `commande_details_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`),
  ADD CONSTRAINT `commande_details_ibfk_2` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
