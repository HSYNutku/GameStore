-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 May 2026, 15:40:29
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `gamestore`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `profile_image` varchar(255) DEFAULT 'uploads/profiles/default.jpg',
  `bio` text DEFAULT NULL,
  `favorite_game` varchar(100) DEFAULT NULL,
  `steam_profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `join_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_admin`, `profile_image`, `bio`, `favorite_game`, `steam_profile`, `created_at`, `join_date`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$w6yhvj0IEhhVdUdIa5cwd.4GnLtjF7rHYnrlDbo0XxVJrYKswDK9i', 1, 'uploads/profiles/default.jpg', NULL, NULL, NULL, '2026-05-03 11:18:28', '2026-05-03 14:52:46'),
(2, 'HSYNutku', 'hsynutku@gamestore.com', '$2y$10$83UySy/qCfNpMd/9BNAyi.kgjCDj2gKYaG2CHnCjbYJrVFV5j5oy2', 0, 'uploads/profiles/default.jpg', NULL, NULL, NULL, '2026-05-03 11:47:34', '2026-05-03 14:52:46'),
(3, 'BYKLD', 'bykld@gamestore.com', '$2y$10$ze2spKGFoBpcnO2XmxApwes4pOostAmJWlDdzYIwHaqsXtbNMERqy', 0, 'uploads/profiles/default.jpg', NULL, NULL, NULL, '2026-05-03 11:48:34', '2026-05-03 14:52:46');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
