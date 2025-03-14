-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 14 mars 2025 à 11:30
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

-- Insertion des nouveaux produits
INSERT INTO `products` (`id`, `name`, `image`, `video`, `price`, `description`, `duration`, `upload_date`, `type`) VALUES
(1, 'Smartphone Galaxy S21', 'images/galaxy_s21.jpg', 'videos/galaxy_s21.mp4', 799.00, 'Smartphone performant avec écran AMOLED', '30s', '2025-03-14', 'Électronique'),
(2, 'Ordinateur Portable Dell XPS 15', 'images/dell_xps_15.jpg', 'videos/dell_xps_15.mp4', 1499.00, 'PC portable haute performance', '45s', '2025-03-14', 'Électronique'),
(3, 'Casque Bluetooth Sony WH-1000XM4', 'images/sony_wh1000xm4.jpg', 'videos/sony_wh1000xm4.mp4', 349.00, 'Casque à réduction de bruit active', '20s', '2025-03-14', 'Électronique'),
(4, 'Montre Connectée Apple Watch Series 7', 'images/apple_watch_7.jpg', 'videos/apple_watch_7.mp4', 429.00, 'Montre connectée avec capteurs avancés', '35s', '2025-03-14', 'Électronique'),

(5, 'Sneakers Nike Air Max 270', 'images/nike_air_max_270.jpg', 'videos/nike_air_max_270.mp4', 129.00, 'Chaussures de sport confortables', '25s', '2025-03-14', 'Mode & Accessoires'),
(6, 'Jean Levi’s 501', 'images/levis_501.jpg', 'videos/levis_501.mp4', 79.00, 'Jean classique et intemporel', '15s', '2025-03-14', 'Mode & Accessoires'),
(7, 'Sac à dos Herschel Little America', 'images/herschel_little_america.jpg', 'videos/herschel_little_america.mp4', 99.00, 'Sac à dos tendance et pratique', '20s', '2025-03-14', 'Mode & Accessoires'),
(8, 'Montre Fossil Homme', 'images/fossil_homme.jpg', 'videos/fossil_homme.mp4', 159.00, 'Montre élégante en acier inoxydable', '30s', '2025-03-14', 'Mode & Accessoires'),

(9, 'Robot Cuiseur Moulinex Companion', 'images/moulinex_companion.jpg', 'videos/moulinex_companion.mp4', 699.00, 'Robot multifonction pour la cuisine', '40s', '2025-03-14', 'Maison & Cuisine'),
(10, 'Aspirateur Dyson V15 Detect', 'images/dyson_v15.jpg', 'videos/dyson_v15.mp4', 649.00, 'Aspirateur sans fil ultra puissant', '35s', '2025-03-14', 'Maison & Cuisine'),
(11, 'Machine à café Nespresso Vertuo', 'images/nespresso_vertuo.jpg', 'videos/nespresso_vertuo.mp4', 159.00, 'Cafetière à capsules avec mousseur', '25s', '2025-03-14', 'Maison & Cuisine'),
(12, 'Grille-pain Philips HD2581', 'images/philips_hd2581.jpg', 'videos/philips_hd2581.mp4', 29.00, 'Grille-pain compact et efficace', '15s', '2025-03-14', 'Maison & Cuisine'),

(13, 'Parfum Dior Sauvage (100ml)', 'images/dior_sauvage.jpg', 'videos/dior_sauvage.mp4', 89.00, 'Parfum boisé et épicé pour homme', '20s', '2025-03-14', 'Beauté & Santé'),
(14, 'Crème hydratante La Roche-Posay', 'images/la_roche_posay.jpg', 'videos/la_roche_posay.mp4', 14.00, 'Crème apaisante pour peau sensible', '15s', '2025-03-14', 'Beauté & Santé'),
(15, 'Tondeuse à barbe Philips OneBlade', 'images/philips_oneblade.jpg', 'videos/philips_oneblade.mp4', 39.00, 'Tondeuse de précision pour barbe', '20s', '2025-03-14', 'Beauté & Santé'),
(16, 'Sérum Vitamine C Garnier', 'images/garnier_vitamine_c.jpg', 'videos/garnier_vitamine_c.mp4', 12.00, 'Sérum éclat pour une peau lumineuse', '10s', '2025-03-14', 'Beauté & Santé'),

(17, 'Vélo de route Btwin Ultra 920', 'images/btwin_ultra_920.jpg', 'videos/btwin_ultra_920.mp4', 1299.00, 'Vélo de route pour cyclistes exigeants', '50s', '2025-03-14', 'Sport & Loisirs'),
(18, 'Tapis de Yoga Liforme', 'images/liforme_yoga.jpg', 'videos/liforme_yoga.mp4', 89.00, 'Tapis de yoga avec repères intégrés', '20s', '2025-03-14', 'Sport & Loisirs'),
(19, 'Montre GPS Garmin Forerunner 245', 'images/garmin_forerunner_245.jpg', 'videos/garmin_forerunner_245.mp4', 299.00, 'Montre GPS pour coureurs', '40s', '2025-03-14', 'Sport & Loisirs'),
(20, 'Haltères ajustables Bowflex 24kg', 'images/bowflex_24kg.jpg', 'videos/bowflex_24kg.mp4', 399.00, 'Haltères ajustables pour musculation', '30s', '2025-03-14', 'Sport & Loisirs');

-- Mise à jour de l'auto-incrémentation (au cas où)
ALTER TABLE `products` AUTO_INCREMENT = 21;
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
