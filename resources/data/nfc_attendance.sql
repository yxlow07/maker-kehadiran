-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2024 at 02:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nfc_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` varchar(256) NOT NULL,
  `namaA` varchar(256) NOT NULL,
  `kLAdmin` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`idAdmin`, `namaA`, `kLAdmin`) VALUES
('A1234', 'Admin No. 1', '$2y$10$SHqHEi.aU/RHEbUjlxy1tu65XhJC1bZeH5UPAP.caJQBeuPTCn8iS');

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran`
--

CREATE TABLE `kehadiran` (
  `idMurid` varchar(256) NOT NULL,
  `idAdmin` varchar(256) NOT NULL,
  `kehadiran` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kehadiran`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kehadiran`
--

INSERT INTO `kehadiran` (`idMurid`, `idAdmin`, `kehadiran`) VALUES
('D5343', 'A1234', '[true,true,true,false,true,false,true,false,true]'),
('D53432', 'A1234', '[true,true,false,true,false,true,true]'),
('D511111', 'A1234', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `murid`
--

CREATE TABLE `murid` (
  `idMurid` varchar(256) NOT NULL,
  `noTel` varchar(15) NOT NULL,
  `kLMurid` varchar(256) NOT NULL,
  `infoLogMasuk` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `murid`
--

INSERT INTO `murid` (`idMurid`, `noTel`, `kLMurid`, `infoLogMasuk`) VALUES
('D511111', '0123456789', '$2y$10$DU9xI.KMn3twfBXpHqkZs.KlDsLHp4w9JI4LzFehmVo/y6fUdW0My', NULL),
('D5343', '60129087237', '$2y$10$1p2M.bq.rwbtHf7FchHyweH6ixhcjvwaPk0uAytqnKjcHtBBLw1WK', '[]'),
('D53432', '0123456789', '$2y$10$Lh5l12o.c4hVL.qpX.yn4u6Bt0i1I748k4wSk7TxQFSCBOGnyW6XW', '{\"sessionID\":\"b1e1b165fd49636eaba7805206f9b2ea5193fa1f318956d818a6c278ace8919d\"}');

-- --------------------------------------------------------

--
-- Table structure for table `telefon`
--

CREATE TABLE `telefon` (
  `noTel` varchar(15) NOT NULL,
  `namaM` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `telefon`
--

INSERT INTO `telefon` (`noTel`, `namaM`) VALUES
('0123456789', 'Test User 2'),
('60129087237', 'Yu Xuan XX');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indexes for table `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD KEY `idMurid` (`idMurid`,`idAdmin`),
  ADD KEY `kehadiran_ibfk_1` (`idAdmin`);

--
-- Indexes for table `murid`
--
ALTER TABLE `murid`
  ADD PRIMARY KEY (`idMurid`),
  ADD KEY `noTel` (`noTel`);

--
-- Indexes for table `telefon`
--
ALTER TABLE `telefon`
  ADD UNIQUE KEY `noTel` (`noTel`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD CONSTRAINT `kehadiran_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admin` (`idAdmin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kehadiran_ibfk_2` FOREIGN KEY (`idMurid`) REFERENCES `murid` (`idMurid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `telefon`
--
ALTER TABLE `telefon`
  ADD CONSTRAINT `telefon_ibfk_1` FOREIGN KEY (`noTel`) REFERENCES `murid` (`noTel`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
