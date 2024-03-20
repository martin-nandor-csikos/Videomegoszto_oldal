-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 20, 2024 at 02:50 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `videomegoszto`
--
CREATE DATABASE IF NOT EXISTS `videomegoszto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `videomegoszto`;

-- --------------------------------------------------------

--
-- Table structure for table `cimke`
--

CREATE TABLE IF NOT EXISTS `cimke` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'A címke azonosítója (kulcs)',
  `cim` int NOT NULL COMMENT 'A címke címe (egyedi)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cim` (`cim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók lehetséges címkéit tartalmazó tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `eredet`
--

CREATE TABLE IF NOT EXISTS `eredet` (
  `komment_id` int NOT NULL COMMENT 'A komment azonosítója (kulcs)',
  `video_id` int NOT NULL COMMENT 'A videó azonosítója (kulcs)',
  PRIMARY KEY (`komment_id`,`video_id`),
  KEY `komment_id` (`komment_id`,`video_id`),
  KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A kommentek eredet videóját jelölő tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `felhasznalo`
--

CREATE TABLE IF NOT EXISTS `felhasznalo` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'A felhasználó azonosítója (kulcs)',
  `nev` varchar(30) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A felhasználónév',
  `email` varchar(50) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A felhaszánló email címe (jelszóval együtt\r\negyedi)',
  `jelszo` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A felhasználó jelszava titkosítva (emaillel\r\negyütt egyedi)',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Igaz, ha a felhasználó adminisztrátor',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_jelszo` (`email`,`jelszo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A felhasználók adatait tartalmazó tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `feltolto`
--

CREATE TABLE IF NOT EXISTS `feltolto` (
  `felhasznalo_id` int NOT NULL COMMENT 'A feltöltő felhasználó azonosítója (kulcs)',
  `video_id` int NOT NULL COMMENT 'A videó azonosítója (kulcs)',
  `datum` date NOT NULL COMMENT 'A videó feltöltésének dátuma',
  PRIMARY KEY (`felhasznalo_id`,`video_id`),
  KEY `video_id` (`video_id`),
  KEY `felhasznalo_id` (`felhasznalo_id`,`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók feltöltését leíró tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `iro`
--

CREATE TABLE IF NOT EXISTS `iro` (
  `felhasznalo_id` int NOT NULL COMMENT 'A feltöltő felhasználó azonosítója (kulcs)',
  `komment_id` int NOT NULL COMMENT 'A komment azonosítója (kulcs)',
  `ido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'A komment kiírásának dátuma és időpontja',
  PRIMARY KEY (`felhasznalo_id`,`komment_id`),
  KEY `felhasznalo_id` (`felhasznalo_id`,`komment_id`),
  KEY `komment_id` (`komment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A kommentek kiírását leíró tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `kategoria`
--

CREATE TABLE IF NOT EXISTS `kategoria` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'A kategória azonosítója (kulcs)',
  `cim` varchar(60) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A kategória címe (egyedi)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cim` (`cim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók lehetséges kategóriáit tartalmazó tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `kedvenc`
--

CREATE TABLE IF NOT EXISTS `kedvenc` (
  `felhasznalo_id` int NOT NULL COMMENT 'A felhasználó azonosítója (kulcs)',
  `video_id` int NOT NULL COMMENT 'A kedvelt videó azonosítója (kulcs)',
  PRIMARY KEY (`felhasznalo_id`,`video_id`),
  KEY `felhasznalo_id` (`felhasznalo_id`,`video_id`),
  KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A felhasználók kedvelt videóit jelölő tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `komment`
--

CREATE TABLE IF NOT EXISTS `komment` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'A komment azonosítója (kulcs)',
  `szoveg` varchar(1000) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A komment szövege',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A kommentek szövegét tartalmazó tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'A videó azonosítója (kulcs)',
  `cim` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A videó címe',
  `leiras` varchar(1000) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A videó leírása',
  `path` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL COMMENT 'A videó elérési útvonala (egyedi)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók adatait tartalmazó tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `video_cimke`
--

CREATE TABLE IF NOT EXISTS `video_cimke` (
  `video_id` int NOT NULL COMMENT 'A videó azonosítója (kulcs)',
  `cimke_id` int NOT NULL COMMENT 'Egy címke azonosítója (kulcs)',
  PRIMARY KEY (`video_id`,`cimke_id`),
  KEY `video_id` (`video_id`,`cimke_id`),
  KEY `cimke_id` (`cimke_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók egy-egy címkéjét jelölő tábla.';

-- --------------------------------------------------------

--
-- Table structure for table `video_kategoria`
--

CREATE TABLE IF NOT EXISTS `video_kategoria` (
  `video_id` int NOT NULL COMMENT 'A videó azonosítója (kulcs)',
  `kategoria_id` int NOT NULL COMMENT 'A kategória azonosítója (kulcs)',
  PRIMARY KEY (`video_id`,`kategoria_id`),
  KEY `video_id` (`video_id`,`kategoria_id`),
  KEY `kategoria_id` (`kategoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci COMMENT='A videók kategoriáját jelölő tábla.';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eredet`
--
ALTER TABLE `eredet`
  ADD CONSTRAINT `eredet_ibfk_1` FOREIGN KEY (`komment_id`) REFERENCES `komment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `eredet_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `feltolto`
--
ALTER TABLE `feltolto`
  ADD CONSTRAINT `feltolto_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `feltolto_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `iro`
--
ALTER TABLE `iro`
  ADD CONSTRAINT `iro_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `iro_ibfk_2` FOREIGN KEY (`komment_id`) REFERENCES `komment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `kedvenc`
--
ALTER TABLE `kedvenc`
  ADD CONSTRAINT `kedvenc_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `kedvenc_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `video_cimke`
--
ALTER TABLE `video_cimke`
  ADD CONSTRAINT `video_cimke_ibfk_1` FOREIGN KEY (`cimke_id`) REFERENCES `cimke` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `video_cimke_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `video_kategoria`
--
ALTER TABLE `video_kategoria`
  ADD CONSTRAINT `video_kategoria_ibfk_1` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoria` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `video_kategoria_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
