-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2025 at 01:20 AM
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
  `gambar` varchar(100) DEFAULT NULL,
  `harga` double(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`kod_makanan`, `nama_makanan`, `gambar`, `harga`) VALUES
('R-001', 'Roti canai kosong', 'canaikosong.jpg', 1.20),
('R-002', 'Roti canai telur', 'canaitelur.jpg', 2.50),
('R-003', 'Roti bom', 'rotibom.jpg', 2.00),
('R-004', 'Roti tissue', 'rotitissue.jpg', 2.00),
('R-005', 'Roti Canai bajir', 'roticanaibajir.jpg', 1.50),
('R-006', 'Roti planta', 'rotiplanta.jpg', 2.00),
('R-007', 'Roti telur goyang', 'rotitelurgoyang.jpg', 4.00),
('R-008', 'Roti canai susu', 'roticanaisusu.jpg', 2.00),
('R-009', 'Roti canai cheese', 'roticanaicheese.jpg', 3.00),
('R-010', 'Roti telur jantan', 'rotitelurjantan.webp', 3.00),
('R-011', 'Murtabak', 'murtabak.jpg', 7.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `notel` varchar(15) DEFAULT NULL,
  `tahap` varchar(10) DEFAULT NULL,
  `password` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`email`, `nama`, `notel`, `tahap`, `password`) VALUES
('abdul@gmail.com', 'ABDUL BIN RASYID', '0183838493', 'PELANGGAN', 'Abdul@12'),
('abu@gmail.com', 'ABU BIN RAZAK', '0145565273', 'PELANGGAN', 'Abu@1234'),
('ashman@gmail.com', 'Ashman', '01195382991', 'PELANGGAN', 'Ashman@1'),
('Dzia@gmail.com', 'Dzia', '011485763891', 'PELANGGAN', 'Dzia@123'),
('ikmal@gmail.com', 'Ikmal', '011583672991', 'PELANGGAN', 'Ikmal@12'),
('Ilham@gmail.com', 'Ilham', '01456425273', 'PELANGGAN', 'Ilham@123'),
('izz@gmail.com', 'IZZ BIN RUSDI', '0129383833', 'PELANGGAN', 'Izz@1234'),
('paan@gmail.com', 'Danish Farhan', '01100223991', 'PELANGGAN', 'Paan@123'),
('qaedi@gmail.com', 'QAEDI BIN SHARIZWAN', '0114077852', 'ADMIN', 'Kodi@123'),
('rafiq@gmail.com', 'RAFIQ BIN FARID', '0112348733', 'PELANGGAN', 'Rafiq@12');

-- --------------------------------------------------------

--
-- Table structure for table `tempahan`
--

CREATE TABLE `tempahan` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kod_makanan` varchar(5) NOT NULL,
  `tarikh` datetime NOT NULL,
  `kuantiti` int(3) DEFAULT NULL,
  `jumlah_harga` double(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  ADD KEY `email` (`email`),
  ADD KEY `kod_makanan` (`kod_makanan`),
  ADD KEY `email_2` (`email`,`kod_makanan`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tempahan`
--
ALTER TABLE `tempahan`
  ADD CONSTRAINT `tempahan_ibfk_1` FOREIGN KEY (`email`) REFERENCES `pelanggan` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tempahan_ibfk_2` FOREIGN KEY (`kod_makanan`) REFERENCES `makanan` (`kod_makanan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
