-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 23 mars 2026 à 14:53
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
-- Base de données : `automarket`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quote_requests`
--

CREATE TABLE `quote_requests` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','contacted','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `options_gps` tinyint(4) DEFAULT 0,
  `options_baby_seat` tinyint(4) DEFAULT 0,
  `options_insurance` tinyint(4) DEFAULT 0,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rental_vehicles`
--

CREATE TABLE `rental_vehicles` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `price_per_week` decimal(10,2) DEFAULT NULL,
  `price_per_month` decimal(10,2) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel` enum('Essence','Diesel','Hybride','Électrique') NOT NULL,
  `transmission` enum('Manuelle','Automatique') NOT NULL,
  `seats` tinyint(4) DEFAULT 5,
  `luggage` tinyint(4) DEFAULT 2,
  `air_conditioning` tinyint(4) DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `featured` tinyint(4) DEFAULT 0,
  `available` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rental_vehicles`
--

INSERT INTO `rental_vehicles` (`id`, `brand`, `model`, `year`, `price_per_day`, `price_per_week`, `price_per_month`, `mileage`, `fuel`, `transmission`, `seats`, `luggage`, `air_conditioning`, `image_url`, `featured`, `available`, `created_at`) VALUES
(1, 'Renault', 'Clio V', 2023, 45.00, 270.00, 900.00, NULL, 'Essence', 'Manuelle', 5, 2, 1, 'https://cdn.pixabay.com/photo/2016/11/18/23/37/renault-1837250_640.jpg', 1, 1, '2026-03-23 12:58:16'),
(2, 'Peugeot', '208', 2023, 49.00, 294.00, 980.00, NULL, 'Essence', 'Manuelle', 5, 2, 1, 'https://cdn.pixabay.com/photo/2021/04/16/10/53/peugeot-6182878_640.jpg', 1, 1, '2026-03-23 12:58:16'),
(3, 'Volkswagen', 'Golf 8', 2023, 65.00, 390.00, 1300.00, NULL, 'Diesel', 'Automatique', 5, 3, 1, 'https://cdn.pixabay.com/photo/2019/07/23/13/51/volkswagen-golf-4356597_640.jpg', 1, 1, '2026-03-23 12:58:16'),
(4, 'BMW', 'Série 3', 2023, 99.00, 594.00, 1980.00, NULL, 'Diesel', 'Automatique', 5, 3, 1, 'https://cdn.pixabay.com/photo/2015/01/19/13/51/auto-604035_640.jpg', 1, 1, '2026-03-23 12:58:16'),
(5, 'Tesla', 'Model 3', 2023, 129.00, 774.00, 2580.00, NULL, 'Électrique', 'Automatique', 5, 2, 1, 'https://cdn.pixabay.com/photo/2021/01/15/17/31/tesla-5920415_640.jpg', 1, 1, '2026-03-23 12:58:16');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `newsletter` tinyint(4) DEFAULT 0,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `newsletter`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'AutoMarket', 'admin@automarket.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0102030405', 0, 'admin', NULL, '2026-03-23 12:58:16', '2026-03-23 12:58:16'),
(2, 'Tamdira', 'Gustave', 'gustave@gmail.com', '$2y$10$rOW/XJMPrI3ZZSW6mEDrVe.6tHaFYr7SI3wQn1Lpy0lFhVEtRAeQW', '656320676', 1, 'user', NULL, '2026-03-23 13:43:37', '2026-03-23 13:43:37');

-- --------------------------------------------------------

--
-- Structure de la table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `mileage` int(11) NOT NULL,
  `fuel` enum('Essence','Diesel','Hybride','Électrique') NOT NULL,
  `transmission` enum('Manuelle','Automatique') NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `doors` tinyint(4) DEFAULT 5,
  `horsepower` int(11) DEFAULT NULL,
  `consumption` varchar(20) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `featured` tinyint(4) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` enum('available','sold','reserved') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicles`
--

INSERT INTO `vehicles` (`id`, `brand`, `model`, `year`, `price`, `original_price`, `mileage`, `fuel`, `transmission`, `color`, `doors`, `horsepower`, `consumption`, `image_url`, `featured`, `description`, `status`, `created_at`) VALUES
(1, 'Renault', 'Clio V', 2023, 18900.00, 21500.00, 8500, 'Essence', 'Manuelle', 'Bleu Métallisé', 5, 90, '5.2L/100km', '', 1, 'Renault Clio V en excellent état, très économique, idéale pour la ville.', 'available', '2026-03-23 12:58:16'),
(2, 'Peugeot', '208', 2023, 19900.00, 22900.00, 6200, 'Essence', 'Manuelle', 'Rouge Passion', 5, 100, '5.0L/100km', 'https://cdn.pixabay.com/photo/2021/04/16/10/53/peugeot-6182878_640.jpg', 1, 'Peugeot 208 récente, faible kilométrage, garantie constructeur.', 'available', '2026-03-23 12:58:16'),
(3, 'Volkswagen', 'Golf 8', 2022, 27900.00, 32900.00, 28450, 'Diesel', 'Automatique', 'Gris Graphite', 5, 115, '4.8L/100km', 'https://cdn.pixabay.com/photo/2019/07/23/13/51/volkswagen-golf-4356597_640.jpg', 1, 'Volkswagen Golf 8, finition Carat, très bien équipée.', 'available', '2026-03-23 12:58:16'),
(4, 'BMW', 'Série 3', 2021, 35900.00, 42900.00, 45200, 'Diesel', 'Automatique', 'Noir Saphir', 4, 190, '5.5L/100km', 'https://cdn.pixabay.com/photo/2015/01/19/13/51/auto-604035_640.jpg', 0, 'BMW Série 3, finition M Sport, cuir noir, jantes alu.', 'available', '2026-03-23 12:58:16'),
(5, 'Mercedes', 'Classe A', 2022, 38900.00, 44900.00, 18900, 'Essence', 'Automatique', 'Blanc Polaire', 5, 163, '6.0L/100km', 'https://cdn.pixabay.com/photo/2020/05/07/18/08/mercedes-benz-5143062_640.jpg', 1, 'Mercedes Classe A, finition AMG Line, très bien optionnée.', 'available', '2026-03-23 12:58:16'),
(6, 'Citroën', 'C3', 2022, 15900.00, 18900.00, 15600, 'Essence', 'Manuelle', 'Orange Cuivre', 5, 83, '5.1L/100km', 'https://cdn.pixabay.com/photo/2019/12/08/21/31/citroen-4682415_640.jpg', 0, 'Citroën C3, coloris original, très bon état général.', 'available', '2026-03-23 12:58:16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Index pour la table `quote_requests`
--
ALTER TABLE `quote_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Index pour la table `rental_vehicles`
--
ALTER TABLE `rental_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `quote_requests`
--
ALTER TABLE `quote_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rental_vehicles`
--
ALTER TABLE `rental_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quote_requests`
--
ALTER TABLE `quote_requests`
  ADD CONSTRAINT `quote_requests_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quote_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `rental_vehicles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
