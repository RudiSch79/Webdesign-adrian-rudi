-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 21. 20:33
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `snackscout`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `brands`
--

INSERT INTO `brands` (`id`, `name`, `created_at`) VALUES
(1, 'Doritos', '2026-01-20 19:43:28'),
(7, 'Aunt Nelly', '2026-01-21 13:36:21'),
(8, 'Haribo', '2026-01-21 13:51:12');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Crisps', '2026-01-20 19:43:37'),
(5, 'Hard Boiled Sweet', '2026-01-21 13:36:21'),
(6, 'Gummy bears', '2026-01-21 13:51:12'),
(7, 'Protein bars', '2026-01-21 19:43:24');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `snack_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(160) NOT NULL,
  `body` text NOT NULL,
  `rating` decimal(3,1) UNSIGNED NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- A tábla adatainak kiíratása `reviews`
--

INSERT INTO `reviews` (`id`, `snack_id`, `user_id`, `title`, `body`, `rating`, `image_path`, `created_at`, `updated_at`) VALUES
(11, 8, 1, 'Very Sweet but delicious', 'I like the taste of the aunt nellie sweet its a bit sour with a good mix of sweetness. I remmoned it to others.', 4.2, 'data/uploads/reviews/87976cafec10dfe2811abee1de19a0c5.jpg', '2026-01-21 13:38:27', '2026-01-21 13:43:47'),
(12, 20, 1, 'Aller Besten Haribo', 'Pico-Balla sind einfach ein klassiker. Ich geniesse sie jedes mal aufs neue, mhmm lecker.', 4.8, 'data/uploads/reviews/b4c093eaa8d429f80d1cf00855447572.jpg', '2026-01-21 13:58:15', '2026-01-21 13:58:46'),
(13, 22, 1, 'Leckerer Snack', 'kann man zwischendurch gut essen', 4.0, 'data/uploads/reviews/98091d59722744bf2a3fa8ed09ef05a1.jpg', '2026-01-21 14:00:06', '2026-01-21 14:00:06');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `review_comments`
--

CREATE TABLE `review_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `snacks`
--

CREATE TABLE `snacks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `snacks`
--

INSERT INTO `snacks` (`id`, `brand_id`, `name`, `description`, `categorie_id`, `image_path`, `created_at`, `updated_at`) VALUES
(8, 7, 'Apple Drops', NULL, 5, 'data/uploads/snacks/eb8d1068f26b21507273c80ed5ff7ddc.jpg', '2026-01-21 13:36:21', '2026-01-21 13:36:21'),
(9, 1, 'Classic Ketchup', NULL, 1, 'data/uploads/snacks/b25e2f0a7510ef76324b25441a77ce40.jpg', '2026-01-21 13:46:33', '2026-01-21 13:46:33'),
(10, 1, 'Sweet Chilli', NULL, 1, 'data/uploads/snacks/09c3c68247226c2bc8210c0a0efa8f40.jpg', '2026-01-21 13:47:08', '2026-01-21 13:47:08'),
(12, 1, 'Poppin Jalapeno', NULL, 1, 'data/uploads/snacks/c3a351e9b4082e2a696ad7bfaf82a5a9.png', '2026-01-21 13:47:50', '2026-01-21 13:47:50'),
(13, 1, 'Original Salted', NULL, 1, 'data/uploads/snacks/14281a459ee13d538aad1a33c13e9c34.jpg', '2026-01-21 13:48:28', '2026-01-21 13:48:28'),
(15, 8, 'Goldbären', NULL, 6, 'data/uploads/snacks/aaea8412735c0985c4323f850619f378.jpg', '2026-01-21 13:51:35', '2026-01-21 13:51:35'),
(16, 8, 'Kinder Schnuller', NULL, 6, 'data/uploads/snacks/ad287a7d115a7c2c6bcb02b2ffab7f3b.jpg', '2026-01-21 13:51:59', '2026-01-21 13:51:59'),
(17, 8, 'Tropifrutti', NULL, 6, 'data/uploads/snacks/66ff8ec99673bd2332e390610bcd4e34.jpg', '2026-01-21 13:52:24', '2026-01-21 13:52:24'),
(18, 8, 'Balla-Balla', NULL, 6, 'data/uploads/snacks/b3708c7f91e737c51be0aa015fefb0c9.jpg', '2026-01-21 13:52:58', '2026-01-21 13:52:58'),
(19, 8, 'Quaxi', NULL, 6, 'data/uploads/snacks/f2fc66aef4f3657d485b62aabd86aa4f.jpg', '2026-01-21 13:53:28', '2026-01-21 13:53:28'),
(20, 8, 'Pico-Balla', NULL, 6, 'data/uploads/snacks/59844850c298441ae256965bbbd9c6db.jpg', '2026-01-21 13:53:56', '2026-01-21 13:53:56'),
(21, 8, 'Pico-Balla Sauer', NULL, 6, 'data/uploads/snacks/2a0cc41b1cb17a4ba0049c2a0261e5de.jpg', '2026-01-21 13:56:04', '2026-01-21 13:56:04'),
(22, 1, 'Cool Ranch', NULL, 1, 'data/uploads/snacks/a4d8347ac6fb44bb3810d73343676426.png', '2026-01-21 13:56:51', '2026-01-21 13:56:51'),
(23, 8, 'fasz', NULL, 6, 'data/uploads/snacks/0251059deaa014cef5a3109675ae089e.jpg', '2026-01-21 17:50:51', '2026-01-21 17:50:51');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `suggestions`
--

CREATE TABLE `suggestions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('brand','category') NOT NULL,
  `name` varchar(120) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- A tábla adatainak kiíratása `suggestions`
--

INSERT INTO `suggestions` (`id`, `user_id`, `type`, `name`, `status`, `created_at`) VALUES
(1, 4, 'category', 'Protein bars', 'approved', '2026-01-21 19:30:17');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `avatar_path`, `is_admin`, `is_active`, `created_at`) VALUES
(1, 'r', '$2y$10$mN5tWG28it2URe/CguRzMe0SSrODDBAxnl3B7Or7gKT8fIWAOUXxO', 'data/images/profilePicPlaceholder.png', 1, 1, '2026-01-20 20:36:58'),
(2, '11', '$2y$10$Eb45dJ.nIV2fyCexFITWHeKjnmKY2njsY5SI1RKeQXphfCZu2H4hG', 'data/images/profilePicPlaceholder.png', 0, 1, '2026-01-20 21:30:00'),
(4, '123', '$2y$10$qNLjrkped15erJ2d544SQOPIv4O2Y1DVlPVEcTM0WUqL27BbEh9Xy', 'data/images/profilePicPlaceholder.png', 0, 1, '2026-01-21 16:00:35');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_brands_name` (`name`);

--
-- A tábla indexei `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_name` (`name`);

--
-- A tábla indexei `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reviews_snack_user` (`snack_id`,`user_id`),
  ADD KEY `idx_reviews_snack` (`snack_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_created` (`created_at`);

--
-- A tábla indexei `review_comments`
--
ALTER TABLE `review_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_comments_review` (`review_id`),
  ADD KEY `idx_review_comments_user` (`user_id`),
  ADD KEY `idx_review_comments_created` (`created_at`);

--
-- A tábla indexei `snacks`
--
ALTER TABLE `snacks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_snacks_brand_name` (`brand_id`,`name`),
  ADD KEY `fk_snacks_brand` (`categorie_id`),
  ADD KEY `idx_snacks_name` (`name`),
  ADD KEY `idx_snacks_brand` (`brand_id`);

--
-- A tábla indexei `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_suggestions_status` (`status`),
  ADD KEY `idx_suggestions_type` (`type`),
  ADD KEY `idx_suggestions_user` (`user_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT a táblához `review_comments`
--
ALTER TABLE `review_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `snacks`
--
ALTER TABLE `snacks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT a táblához `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_snack` FOREIGN KEY (`snack_id`) REFERENCES `snacks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `review_comments`
--
ALTER TABLE `review_comments`
  ADD CONSTRAINT `fk_review_comments_review` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `snacks`
--
ALTER TABLE `snacks`
  ADD CONSTRAINT `fk_snacks_brand` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_snacks_categorie` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Megkötések a táblához `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `fk_suggestions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
