-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 28, 2025 at 02:52 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kafelip`
--

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `kod_makanan` varchar(5) NOT NULL,
  `nama_makanan` varchar(30) DEFAULT NULL,
  `gambar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `harga` double(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`kod_makanan`, `nama_makanan`, `gambar`, `harga`) VALUES
('R-001', 'Roti Canai', 'canaikosong.jpg', 1.20),
('R-002', 'Roti Telur', 'canaitelur.jpg', 2.50),
('R-003', 'Roti Bom', 'rotibom.jpg', 2.00),
('R-004', 'Roti Tisu', 'rotitisu.jpg', 2.00),
('R-005', 'Roti Banjir', 'roticanaibajir.jpg', 1.50),
('R-006', 'Roti Planta', 'rotiplanta_20250213_223306.jpg', 2.00),
('R-007', 'Roti Telur Goyang', 'rotitelurgoyang.jpg', 4.00),
('R-008', 'Roti Susu', 'roticanaisusu.jpg', 2.00),
('R-009', 'Roti Cheese', 'roticanaicheese.jpg', 3.00),
('R-010', 'Roti Sarang Burung', 'rotisarangburung_20250222_160040.jpg', 3.00),
('R-011', 'Murtabak', 'murtabak_20250213_223137.jpg', 4.00),
('R-012', 'Roti Boy', 'rotiboy.jpg', 2.50),
('R-013', 'Roti Hitam', NULL, 4.00),
('R-014', 'Roti Putih', NULL, 1.00),
('R-015', 'Roti Hitam Putih', NULL, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `notel` varchar(11) NOT NULL,
  `tahap` varchar(10) DEFAULT NULL,
  `password` varchar(12) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`email`, `nama`, `notel`, `tahap`, `password`) VALUES
('Dzia@gmail.com', 'Dzia', '01147483647', 'PELANGGAN', 'Dzia@123'),
('Ilham@gmail.com', 'Ilham', '01456425273', 'PELANGGAN', 'Ilham@123'),
('abdul@gmail.com', 'ABDUL BIN RASYID', '01838384933', 'PELANGGAN', 'Abdul@12'),
('abu@gmail.com', 'ABU BIN RAZAK', '01455652738', 'PELANGGAN', 'Abu@1234'),
('ashman@gmail.com', 'Ashman', '01195382991', 'PELANGGAN', 'Ashman@1'),
('ikmal@gmail.com', 'Ikmal', '01147489647', 'PELANGGAN', 'Ikmal@12'),
('izz@gmail.com', 'IZZ BIN RUSDI', '0129383833', 'ADMIN', 'Izz@1234'),
('mualip@gmail.com', 'Muhammad Aliff ', '0119551602', 'PELANGGAN', 'Aliff@123'),
('muhaaliff@admin.kafelip.com', 'Muhammad Aliff Bin Ramli', '01158962991', 'ADMIN', 'Alipje29'),
('paan@gmail.com', 'Danish Farhan', '01100223991', 'PELANGGAN', 'Paan@123'),
('qaedi@gmail.com', 'QAEDI BIN SHARIZWAN', '01140778528', 'ADMIN', 'Kodi@123'),
('rafiq@gmail.com', 'RAFIQ BIN FARID', '0112348733', 'PELANGGAN', 'Rafiq@12'),
('staf1@staf.kafelip.com', 'Pekerja 1', '01155662992', 'ADMIN', 'Staf@123'),
('staf2@staf.kafelip.com', 'Pekerja 2', '01177962900', 'ADMIN', 'Staf@123'),
('staf3@staf.kafelip.com', 'Pekerja 3', '01988992980', 'ADMIN', 'Staf@123'),
('syaheed@gmail.com', 'Syaheed', '0123424535', 'PELANGGAN', 'Syaheed@123');

-- --------------------------------------------------------

--
-- Table structure for table `tempahan`
--

CREATE TABLE `tempahan` (
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `kod_makanan` varchar(5) NOT NULL,
  `tarikh` datetime NOT NULL,
  `kuantiti` int(3) DEFAULT NULL,
  `jumlah_harga` double(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tempahan`
--

INSERT INTO `tempahan` (`email`, `kod_makanan`, `tarikh`, `kuantiti`, `jumlah_harga`) VALUES
('Dzia@gmail.com', 'R-002', '2025-03-19 15:53:14', 2, 5.00),
('qaedi@gmail.com', 'R-001', '2025-02-05 11:26:30', 30, 36.00),
('qaedi@gmail.com', 'R-001', '2025-02-16 13:27:50', 4, 4.80),
('qaedi@gmail.com', 'R-001', '2025-02-17 20:34:26', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-02-18 15:45:17', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-02-18 15:47:51', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-02-18 15:48:13', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-03-10 14:53:30', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-03-10 15:05:30', 2, 2.40),
('qaedi@gmail.com', 'R-001', '2025-04-10 14:52:15', 4, 4.80),
('qaedi@gmail.com', 'R-001', '2025-04-12 11:29:52', 1, 1.20),
('qaedi@gmail.com', 'R-001', '2025-04-19 08:42:36', 2, 2.40),
('qaedi@gmail.com', 'R-001', '2025-04-23 16:52:21', 3, 3.60),
('qaedi@gmail.com', 'R-001', '2025-04-23 16:53:24', 4, 4.80),
('qaedi@gmail.com', 'R-002', '2025-01-27 20:24:42', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-01-27 20:25:25', 3, 7.50),
('qaedi@gmail.com', 'R-002', '2025-01-27 20:26:37', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-07 23:05:39', 5, 12.50),
('qaedi@gmail.com', 'R-002', '2025-02-08 20:38:56', 10, 25.00),
('qaedi@gmail.com', 'R-002', '2025-02-10 20:27:06', 5, 12.50),
('qaedi@gmail.com', 'R-002', '2025-02-11 12:45:11', 5, 12.50),
('qaedi@gmail.com', 'R-002', '2025-02-12 08:36:20', 2, 5.00),
('qaedi@gmail.com', 'R-002', '2025-02-16 13:27:50', 3, 7.50),
('qaedi@gmail.com', 'R-002', '2025-02-16 20:36:02', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-17 20:30:45', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-17 20:34:26', 7, 17.50),
('qaedi@gmail.com', 'R-002', '2025-02-18 15:24:53', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-18 15:44:38', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-18 15:47:51', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-19 20:52:19', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-19 22:49:49', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-20 15:26:40', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-02-20 16:04:53', 1, 2.50),
('qaedi@gmail.com', 'R-002', '2025-03-10 14:53:16', 3, 7.50),
('qaedi@gmail.com', 'R-002', '2025-03-10 15:03:41', 3, 7.50),
('qaedi@gmail.com', 'R-002', '2025-03-10 15:05:03', 3, 7.50),
('qaedi@gmail.com', 'R-002', '2025-03-19 15:45:06', 5, 12.50),
('qaedi@gmail.com', 'R-002', '2025-04-12 11:29:11', 6, 15.00),
('qaedi@gmail.com', 'R-002', '2025-04-12 11:29:52', 4, 10.00),
('qaedi@gmail.com', 'R-002', '2025-04-19 08:42:36', 1, 2.50),
('qaedi@gmail.com', 'R-003', '2025-01-27 20:24:52', 4, 8.00),
('qaedi@gmail.com', 'R-003', '2025-01-27 20:25:36', 3, 6.00),
('qaedi@gmail.com', 'R-003', '2025-01-27 20:26:08', 3, 6.00),
('qaedi@gmail.com', 'R-003', '2025-01-27 20:27:31', 29, 58.00),
('qaedi@gmail.com', 'R-003', '2025-02-16 13:27:50', 3, 6.00),
('qaedi@gmail.com', 'R-003', '2025-02-17 20:34:26', 1, 2.00),
('qaedi@gmail.com', 'R-003', '2025-02-18 15:26:02', 1, 2.00),
('qaedi@gmail.com', 'R-003', '2025-02-18 15:44:59', 1, 2.00),
('qaedi@gmail.com', 'R-003', '2025-02-18 15:45:30', 1, 2.00),
('qaedi@gmail.com', 'R-003', '2025-02-18 21:32:19', 3, 6.00),
('qaedi@gmail.com', 'R-003', '2025-02-19 20:52:19', 3, 6.00),
('qaedi@gmail.com', 'R-003', '2025-02-22 15:57:25', 6, 12.00),
('qaedi@gmail.com', 'R-003', '2025-04-12 11:29:52', 3, 6.00),
('qaedi@gmail.com', 'R-004', '2025-01-27 16:53:17', 6, 12.00),
('qaedi@gmail.com', 'R-004', '2025-01-27 20:25:02', 1, 2.00),
('qaedi@gmail.com', 'R-004', '2025-02-01 07:19:12', 15, 30.00),
('qaedi@gmail.com', 'R-004', '2025-02-16 13:27:50', 1, 2.00),
('qaedi@gmail.com', 'R-004', '2025-02-17 20:34:26', 1, 2.00),
('qaedi@gmail.com', 'R-004', '2025-02-18 15:47:51', 103, 206.00),
('qaedi@gmail.com', 'R-004', '2025-02-19 15:59:55', 5, 10.00),
('qaedi@gmail.com', 'R-004', '2025-02-19 20:52:19', 1, 2.00),
('qaedi@gmail.com', 'R-004', '2025-02-19 22:49:49', 1, 2.00),
('qaedi@gmail.com', 'R-004', '2025-02-20 16:07:48', 7, 14.00),
('qaedi@gmail.com', 'R-004', '2025-04-10 15:11:36', 2, 4.00),
('qaedi@gmail.com', 'R-005', '2025-02-16 13:27:50', 1, 1.50),
('qaedi@gmail.com', 'R-005', '2025-02-16 17:44:47', 20, 30.00),
('qaedi@gmail.com', 'R-005', '2025-02-17 20:34:26', 2, 3.00),
('qaedi@gmail.com', 'R-005', '2025-02-18 15:23:01', 4, 6.00),
('qaedi@gmail.com', 'R-005', '2025-02-18 15:26:29', 1, 1.50),
('qaedi@gmail.com', 'R-005', '2025-02-18 15:44:38', 1, 1.50),
('qaedi@gmail.com', 'R-005', '2025-02-18 15:47:51', 1, 1.50),
('qaedi@gmail.com', 'R-005', '2025-04-12 11:29:52', 5, 7.50),
('qaedi@gmail.com', 'R-005', '2025-04-23 16:53:24', 2, 3.00),
('qaedi@gmail.com', 'R-006', '2025-01-27 20:25:56', 3, 6.00),
('qaedi@gmail.com', 'R-006', '2025-01-27 20:27:31', 40, 80.00),
('qaedi@gmail.com', 'R-006', '2025-02-16 13:27:50', 6, 12.00),
('qaedi@gmail.com', 'R-006', '2025-02-17 20:34:26', 1, 2.00),
('qaedi@gmail.com', 'R-006', '2025-02-18 15:25:43', 4, 8.00),
('qaedi@gmail.com', 'R-006', '2025-02-18 15:46:08', 1, 2.00),
('qaedi@gmail.com', 'R-006', '2025-02-18 15:47:51', 1, 2.00),
('qaedi@gmail.com', 'R-006', '2025-04-12 11:29:52', 2, 4.00),
('qaedi@gmail.com', 'R-007', '2025-02-17 20:34:26', 1, 4.00),
('qaedi@gmail.com', 'R-007', '2025-02-18 15:25:17', 1, 4.00),
('qaedi@gmail.com', 'R-007', '2025-02-18 15:47:51', 1, 4.00),
('qaedi@gmail.com', 'R-007', '2025-04-12 11:29:11', 3, 12.00),
('qaedi@gmail.com', 'R-008', '2025-01-27 20:25:14', 5, 10.00),
('qaedi@gmail.com', 'R-008', '2025-01-27 20:25:45', 3, 6.00),
('qaedi@gmail.com', 'R-008', '2025-02-17 20:34:26', 1, 2.00),
('qaedi@gmail.com', 'R-008', '2025-02-18 15:47:51', 1, 2.00),
('qaedi@gmail.com', 'R-008', '2025-02-19 22:49:49', 1, 2.00),
('qaedi@gmail.com', 'R-008', '2025-02-23 11:55:46', 3, 6.00),
('qaedi@gmail.com', 'R-008', '2025-04-10 14:52:15', 6, 12.00),
('qaedi@gmail.com', 'R-009', '2025-01-27 20:27:31', 21, 63.00),
('qaedi@gmail.com', 'R-009', '2025-02-16 13:27:50', 3, 9.00),
('qaedi@gmail.com', 'R-009', '2025-02-17 20:34:26', 1, 3.00),
('qaedi@gmail.com', 'R-009', '2025-02-18 15:47:51', 1, 3.00),
('qaedi@gmail.com', 'R-009', '2025-03-19 15:45:06', 3, 9.00),
('qaedi@gmail.com', 'R-009', '2025-04-23 16:53:24', 1, 3.00),
('qaedi@gmail.com', 'R-010', '2025-02-17 20:34:26', 1, 3.00),
('qaedi@gmail.com', 'R-010', '2025-02-18 15:47:51', 1, 3.00),
('qaedi@gmail.com', 'R-010', '2025-02-19 20:52:19', 1, 3.00),
('qaedi@gmail.com', 'R-010', '2025-04-23 16:52:21', 1, 3.00),
('qaedi@gmail.com', 'R-011', '2025-01-27 16:53:17', 1, 7.00),
('qaedi@gmail.com', 'R-011', '2025-02-11 12:45:11', 4, 28.00),
('qaedi@gmail.com', 'R-011', '2025-02-12 08:37:27', 1, 1000.50),
('qaedi@gmail.com', 'R-011', '2025-02-16 20:46:58', 25, 100.00),
('qaedi@gmail.com', 'R-011', '2025-02-17 20:34:26', 1, 4.00),
('qaedi@gmail.com', 'R-011', '2025-02-18 15:47:51', 1, 4.00),
('qaedi@gmail.com', 'R-011', '2025-02-20 16:01:00', 8, 32.00),
('qaedi@gmail.com', 'R-011', '2025-04-12 11:29:11', 4, 16.00),
('qaedi@gmail.com', 'R-012', '2025-01-27 20:41:57', 100, 250.00),
('qaedi@gmail.com', 'R-012', '2025-01-28 20:48:20', 10, 25.00),
('qaedi@gmail.com', 'R-012', '2025-02-08 20:45:37', 8, 20.00),
('qaedi@gmail.com', 'R-012', '2025-02-11 12:45:11', 6, 15.00),
('qaedi@gmail.com', 'R-012', '2025-02-16 13:27:50', 11, 27.50),
('qaedi@gmail.com', 'R-012', '2025-02-17 20:34:26', 1, 2.50),
('qaedi@gmail.com', 'R-012', '2025-02-18 15:47:51', 1, 2.50),
('qaedi@gmail.com', 'R-012', '2025-02-19 15:59:55', 1, 2.50),
('qaedi@gmail.com', 'R-012', '2025-02-19 22:49:49', 1, 2.50),
('qaedi@gmail.com', 'R-012', '2025-02-19 22:50:26', 1, 2.50),
('qaedi@gmail.com', 'R-012', '2025-02-20 20:37:14', 5, 12.50),
('qaedi@gmail.com', 'R-012', '2025-04-12 11:29:11', 5, 12.50),
('qaedi@gmail.com', 'R-012', '2025-04-25 16:49:48', 3, 7.50),
('rafiq@gmail.com', 'R-003', '2025-03-04 22:18:14', 10, 20.00),
('rafiq@gmail.com', 'R-004', '2025-02-06 23:19:39', 11, 22.00),
('rafiq@gmail.com', 'R-006', '2025-02-06 23:19:39', 8, 16.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`kod_makanan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `tempahan`
--
ALTER TABLE `tempahan`
  ADD PRIMARY KEY (`email`,`kod_makanan`,`tarikh`),
  ADD KEY `kod_makanan` (`kod_makanan`),
  ADD KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tempahan`
--
ALTER TABLE `tempahan`
  ADD CONSTRAINT `fk_tempahan_pelanggan` FOREIGN KEY (`email`) REFERENCES `pelanggan` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tempahan_ibfk_2` FOREIGN KEY (`kod_makanan`) REFERENCES `makanan` (`kod_makanan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
