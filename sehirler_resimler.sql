-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 09 Tem 2023, 04:23:15
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `sehirler_resimler`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `resimler`
--

CREATE TABLE `resimler` (
  `id` int(11) NOT NULL,
  `il` text NOT NULL,
  `ilce` text NOT NULL,
  `koy` text NOT NULL,
  `tarla` text NOT NULL,
  `resim` text NOT NULL,
  `ada` text NOT NULL,
  `parsel` text NOT NULL,
  `m2` int(11) NOT NULL,
  `no` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `resimler`
--

INSERT INTO `resimler` (`id`, `il`, `ilce`, `koy`, `tarla`, `resim`, `ada`, `parsel`, `m2`, `no`) VALUES
(3, 'istanbul', 'Yıldırım', 'Kadıköy', 'tarla25', 'img/jack.jpg', 'Lavanta', '200', 20, '55411449452'),
(6, 'Bursa', 'osmangazi', 'yenişehir', 'Lavaa', 'img/resimler-resim.bin', 'riva', '41', 15, '55411449452'),
(7, 'Bursa', 'Yıldırım', 'Hacivat', 'tarla7', 'img/FAVDAZ7GM9QLU3U.webp', 'riva', '40', 15, '55411449452');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `resimler`
--
ALTER TABLE `resimler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `resimler`
--
ALTER TABLE `resimler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
