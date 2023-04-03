-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 11 May 2021, 02:48:23
-- Sunucu sürümü: 5.6.41-84.1
-- PHP Sürümü: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `diaspome_smartplantcare`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(255) NOT NULL,
  `temp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rain` smallint(10) DEFAULT NULL,
  `hum` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ldr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `temp`, `rain`, `hum`, `ldr`) VALUES
(1, '21.51', 0, '44.48', '794');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
