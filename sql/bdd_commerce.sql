-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 29 mars 2025 à 18:14
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
-- Base de données : `bdd_commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `video_id` int(11) NOT NULL,
  `date_added` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,0) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL COMMENT 'stripe, paypal',
  `status` int(11) NOT NULL DEFAULT 0,
  `email_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `upload_date` date NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `video`, `price`, `description`, `duration`, `upload_date`, `type`) VALUES
(1, 'Smartphone Galaxy S21', 'images/galaxy_s21.jpg', '../videos/video.mp4', 799, 'Smartphone performant avec écran AMOLED', '30s', '2025-03-14', 'Électronique'),
(2, 'Ordinateur Portable Dell XPS 15', 'images/dell_xps_15.jpg', '../videos/video.mp4', 1499, 'PC portable haute performance', '45s', '2025-03-14', 'Électronique'),
(3, 'Casque Bluetooth Sony WH-1000XM4', 'images/sony_wh1000xm4.jpg', '../videos/video.mp4', 349, 'Casque à réduction de bruit active', '20s', '2025-03-14', 'Électronique'),
(4, 'Montre Connectée Apple Watch Series 7', 'images/apple_watch_7.jpg', '../videos/video.mp4', 429, 'Montre connectée avec capteurs avancés', '35s', '2025-03-14', 'Électronique'),
(5, 'Sneakers Nike Air Max 270', 'images/nike_air_max_270.jpg', '../videos/video.mp4', 129, 'Chaussures de sport confortables', '25s', '2025-03-14', 'Mode & Accessoires'),
(6, 'Jean Levi’s 501', 'images/levis_501.jpg', '../videos/video.mp4', 79, 'Jean classique et intemporel', '15s', '2025-03-14', 'Mode & Accessoires'),
(7, 'Sac à dos Herschel Little America', 'images/herschel_little_america.jpg', '../videos/video.mp4', 99, 'Sac à dos tendance et pratique', '20s', '2025-03-14', 'Mode & Accessoires'),
(8, 'Montre Fossil Homme', 'images/fossil_homme.jpg', '../videos/video.mp4', 159, 'Montre élégante en acier inoxydable', '30s', '2025-03-14', 'Mode & Accessoires'),
(9, 'Robot Cuiseur Moulinex Companion', 'images/moulinex_companion.jpg', '../videos/video.mp4', 699, 'Robot multifonction pour la cuisine', '40s', '2025-03-14', 'Maison & Cuisine'),
(10, 'Aspirateur Dyson V15 Detect', 'images/dyson_v15.jpg', '../videos/video.mp4', 649, 'Aspirateur sans fil ultra puissant', '35s', '2025-03-14', 'Maison & Cuisine'),
(11, 'Machine à café Nespresso Vertuo', 'images/nespresso_vertuo.jpg', '../videos/video.mp4', 159, 'Cafetière à capsules avec mousseur', '25s', '2025-03-14', 'Maison & Cuisine'),
(12, 'Grille-pain Philips HD2581', 'images/philips_hd2581.jpg', '../videos/video.mp4', 29, 'Grille-pain compact et efficace', '15s', '2025-03-14', 'Maison & Cuisine'),
(13, 'Parfum Dior Sauvage (100ml)', 'images/dior_sauvage.jpg', '../videos/video.mp4', 89, 'Parfum boisé et épicé pour homme', '20s', '2025-03-14', 'Beauté & Santé'),
(14, 'Crème hydratante La Roche-Posay', 'images/la_roche_posay.jpg', '../videos/video.mp4', 14, 'Crème apaisante pour peau sensible', '15s', '2025-03-14', 'Beauté & Santé'),
(15, 'Tondeuse à barbe Philips OneBlade', 'images/philips_oneblade.jpg', '../videos/video.mp4', 39, 'Tondeuse de précision pour barbe', '20s', '2025-03-14', 'Beauté & Santé'),
(16, 'Sérum Vitamine C Garnier', 'images/garnier_vitamine_c.jpg', '../videos/video.mp4', 12, 'Sérum éclat pour une peau lumineuse', '10s', '2025-03-14', 'Beauté & Santé'),
(17, 'Vélo de route Btwin Ultra 920', 'images/btwin_ultra_920.jpg', '../videos/video.mp4', 1299, 'Vélo de route pour cyclistes exigeants', '50s', '2025-03-14', 'Sport & Loisirs'),
(18, 'Tapis de Yoga Liforme', 'images/liforme_yoga.jpg', '../videos/video.mp4', 89, 'Tapis de yoga avec repères intégrés', '20s', '2025-03-14', 'Sport & Loisirs'),
(19, 'Montre GPS Garmin Forerunner 245', 'images/garmin_forerunner_245.jpg', '../videos/video.mp4', 299, 'Montre GPS pour coureurs', '40s', '2025-03-14', 'Sport & Loisirs'),
(20, 'Haltères ajustables Bowflex 24kg', 'images/bowflex_24kg.jpg', '../videos/video.mp4', 399, 'Haltères ajustables pour musculation', '30s', '2025-03-14', 'Sport & Loisirs');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(0, 'Administrateur', 'titi.pharell77420@gmail.com', '$2y$10$W9N4tyWvWNkDj2XRFGwnQumrHrKJM.8iT9Db2QTsadHyqT3FJpupO'),
(1, 'test', 'test@email.com', '$2y$10$iYNU6it2bdR8iBCSsDHdbOdXDSUKb5/nASBkgDpsUsfn3R67.8GY2');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT pour la table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
