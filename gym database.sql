-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 04:19 PM
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
-- Database: `gym`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_class`
--

CREATE TABLE `booking_class` (
  `ID_Booking` int(11) NOT NULL,
  `ID_Member` int(11) NOT NULL,
  `ID_Class` int(11) NOT NULL,
  `Tanggal_Booking` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Booked','Cancelled') DEFAULT 'Booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_class`
--

INSERT INTO `booking_class` (`ID_Booking`, `ID_Member`, `ID_Class`, `Tanggal_Booking`, `Status`) VALUES
(1, 2, 3, '2024-12-06 15:22:24', 'Booked'),
(7, 2, 5, '2024-12-07 13:43:53', 'Booked'),
(17, 2, 9, '2024-12-17 10:27:59', 'Booked'),
(18, 27, 11, '2024-12-17 10:41:20', 'Booked');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_membership`
--

CREATE TABLE `daftar_membership` (
  `ID_Record` int(11) NOT NULL,
  `ID_Member` int(11) NOT NULL,
  `Nama_Member` varchar(255) NOT NULL,
  `ID_Membership` int(11) NOT NULL,
  `Jenis_Membership` varchar(255) NOT NULL,
  `Tgl_Berlaku` date NOT NULL,
  `Tgl_Berakhir` date NOT NULL,
  `Harga` int(20) NOT NULL,
  `Pakai_PT` varchar(40) DEFAULT NULL,
  `Bukti_Pembayaran` varchar(255) NOT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_membership`
--

INSERT INTO `daftar_membership` (`ID_Record`, `ID_Member`, `Nama_Member`, `ID_Membership`, `Jenis_Membership`, `Tgl_Berlaku`, `Tgl_Berakhir`, `Harga`, `Pakai_PT`, `Bukti_Pembayaran`, `Status`, `Alasan`) VALUES
(74, 30, 'test', 3, 'Bulanan_Class', '2024-12-15', '2025-01-14', 150000, 'tidak', '1734276198_e99ac21f6df72c88bbab.jpg', 'Aktif', 'mantap valid'),
(75, 30, 'test', 2, 'Bulanan_Gym', '2024-12-15', '2025-01-14', 125000, 'ya', '1734276207_b6e219c40df29ce33681.jpg', 'Non-Aktif', 'okee'),
(76, 30, 'test', 1, 'Harian', '2024-12-15', '2024-12-16', 35000, 'tidak', '1734276215_c89e3456497a0a56622b.jpg', 'Selesai', 'ga'),
(80, 2, 'valen', 3, 'Bulanan_Class', '2024-12-17', '2025-01-16', 150000, 'tidak', '1734431096_919c500ed1414b2fe11d.jpg', 'Aktif', 'ok'),
(81, 2, 'valen', 2, 'Bulanan_Gym', '2024-12-17', '2025-01-16', 575000, 'ya', '1734431896_fe4cb176ce0507d28dd2.jpg', 'Aktif', 'ok'),
(87, 33, 'coba', 2, 'Bulanan_Gym', '2024-12-18', '2025-01-17', 575000, 'ya', '1734537678_98e89b578e4df4fa9506.jpg', 'Aktif', 'oke');

-- --------------------------------------------------------

--
-- Table structure for table `instruktur`
--

CREATE TABLE `instruktur` (
  `ID_Instruktur` int(11) NOT NULL,
  `Nama_Instruktur` varchar(255) NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Spesialisasi` varchar(255) NOT NULL,
  `Status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruktur`
--

INSERT INTO `instruktur` (`ID_Instruktur`, `Nama_Instruktur`, `Foto`, `Spesialisasi`, `Status`) VALUES
(1, 'Melani Ricardo', '1733411834_b86fbac7c9ee0fdc392c.jpeg', 'Yoga', 'Aktif'),
(3, 'Renatta Wardana', '1733412610_80d9cb61da5a908591f5.jpg', 'Aerobik', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_class`
--

CREATE TABLE `jadwal_class` (
  `ID_Class` int(11) NOT NULL,
  `Nama_Class` varchar(255) NOT NULL,
  `ID_Instruktur` int(11) NOT NULL,
  `Nama_Instruktur` varchar(255) NOT NULL,
  `Tanggal` date NOT NULL,
  `Jam` varchar(255) DEFAULT NULL,
  `Kuota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_class`
--

INSERT INTO `jadwal_class` (`ID_Class`, `Nama_Class`, `ID_Instruktur`, `Nama_Instruktur`, `Tanggal`, `Jam`, `Kuota`) VALUES
(3, 'Zumba', 3, 'Renatta Wardana', '2024-12-06', '08:00 - 09:30', 0),
(5, 'Yoga', 3, 'Renatta Wardana', '2024-12-07', '11:00 - 12:30', 4),
(6, 'Aerobik', 1, 'Melani Ricardo', '2024-12-08', '08:00 - 09:30', 5),
(7, 'Aerobik', 1, 'Melani Ricardo', '2024-12-08', '08:00 - 09:30', 4),
(8, 'Aerobik', 1, 'Melani Ricardo', '2024-12-16', '17:00 - 18:30', 5),
(9, 'Zumba', 1, 'Melani Ricardo', '2024-12-16', '08:00 - 09:30', 4),
(10, 'Aerobik', 1, 'Melani Ricardo', '2024-12-16', '14:00 - 15:30', 5),
(11, 'Aerobik', 1, 'Melani Ricardo', '2024-12-17', '11:00 - 12:30', 0),
(12, 'Aerobik', 1, 'Melani Ricardo', '2024-12-18', '14:00 - 15:30', 5),
(13, 'Aerobik', 1, 'Melani Ricardo', '2024-12-19', '08:00 - 09:30', 5),
(14, 'Aerobik', 1, 'Melani Ricardo', '2024-12-19', '14:00 - 15:30', 5);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pt`
--

CREATE TABLE `jadwal_pt` (
  `ID_Jadwal` int(11) NOT NULL,
  `ID_PT` int(11) DEFAULT NULL,
  `Nama_PT` varchar(255) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `Sesi1` enum('tersedia','tidak tersedia') DEFAULT 'tidak tersedia',
  `Sesi2` enum('tersedia','tidak tersedia') DEFAULT 'tidak tersedia',
  `Sesi3` enum('tersedia','tidak tersedia') DEFAULT 'tidak tersedia',
  `Sesi4` enum('tersedia','tidak tersedia') DEFAULT 'tidak tersedia',
  `Sesi5` enum('tersedia','tidak tersedia') DEFAULT 'tidak tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_pt`
--

INSERT INTO `jadwal_pt` (`ID_Jadwal`, `ID_PT`, `Nama_PT`, `Tanggal`, `Sesi1`, `Sesi2`, `Sesi3`, `Sesi4`, `Sesi5`) VALUES
(59, 8, 'Matthew Carlos', '2024-11-26', 'tersedia', 'tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(60, 8, 'Matthew Carlos', '2024-11-25', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(61, 8, 'Matthew Carlos', '2024-11-27', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(62, 8, 'Matthew Carlos', '2024-11-28', 'tidak tersedia', 'tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(63, 8, 'Matthew Carlos', '2024-11-29', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(64, 8, 'Matthew Carlos', '2024-11-30', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tersedia'),
(65, 8, 'Matthew Carlos', '2024-12-01', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(66, 8, 'Matthew Carlos', '2024-12-03', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(67, 8, 'Matthew Carlos', '2024-12-05', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(68, 8, 'Matthew Carlos', '2024-12-07', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(69, 8, 'Matthew Carlos', '2024-12-09', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tidak tersedia'),
(70, 8, 'Matthew Carlos', '2024-12-11', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tidak tersedia'),
(71, 8, 'Matthew Carlos', '2024-12-13', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(72, 8, 'Matthew Carlos', '2024-12-15', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(73, 8, 'Matthew Carlos', '2024-12-17', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(74, 8, 'Matthew Carlos', '2024-12-19', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(75, 8, 'Matthew Carlos', '2024-12-25', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(76, 8, 'Matthew Carlos', '2024-12-26', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(77, 8, 'Matthew Carlos', '2024-12-24', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(78, 8, 'Matthew Carlos', '2024-12-27', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(79, 9, 'Andrew Garfield', '2024-11-28', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tidak tersedia'),
(80, 9, 'Andrew Garfield', '2024-12-06', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(81, 9, 'Andrew Garfield', '2024-12-07', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(82, 9, 'Andrew Garfield', '2024-12-09', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(83, 9, 'Andrew Garfield', '2024-12-10', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(84, 9, 'Andrew Garfield', '2024-12-12', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tidak tersedia'),
(85, 9, 'Andrew Garfield', '2024-12-20', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(86, 9, 'Andrew Garfield', '2025-01-01', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(87, 9, 'Andrew Garfield', '2024-12-18', 'tidak tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(88, 9, 'Andrew Garfield', '2024-12-21', 'tidak tersedia', 'tersedia', 'tersedia', 'tersedia', 'tidak tersedia'),
(89, 9, 'Andrew Garfield', '2024-12-26', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tersedia', 'tersedia'),
(90, 8, 'Matthew Carlos', '2025-01-01', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(91, 8, 'Matthew Carlos', '2025-01-02', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(92, 8, 'Matthew Carlos', '2024-12-10', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(93, 8, 'Matthew Carlos', '2024-12-12', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(94, 8, 'Matthew Carlos', '2024-12-14', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(95, 8, 'Matthew Carlos', '2025-01-03', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(96, 8, 'Matthew Carlos', '2024-12-18', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(97, 9, 'Andrew Garfield', '2024-12-11', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(98, 8, 'Matthew Carlos', '2025-01-04', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(99, 8, 'Matthew Carlos', '2025-01-05', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(100, 9, 'Andrew Garfield', '2024-12-13', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(101, 9, 'Andrew Garfield', '2024-12-14', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(102, 8, 'Matthew Carlos', '2025-01-07', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(103, 8, 'Matthew Carlos', '2024-12-20', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tersedia', 'tersedia'),
(104, 8, 'Matthew Carlos', '2024-12-21', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(105, 8, 'Matthew Carlos', '2024-12-28', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia'),
(106, 8, 'Matthew Carlos', '2025-01-17', 'tidak tersedia', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia'),
(107, 8, 'Matthew Carlos', '2025-01-15', 'tidak tersedia', 'tersedia', 'tidak tersedia', 'tidak tersedia', 'tidak tersedia'),
(108, 8, 'Matthew Carlos', '2024-12-23', 'tersedia', 'tersedia', 'tersedia', 'tersedia', 'tersedia'),
(109, 8, 'Matthew Carlos', '2024-12-22', 'tidak tersedia', 'tersedia', 'tersedia', 'tersedia', 'tidak tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `ID_Member` int(40) NOT NULL,
  `Nama_Member` varchar(50) NOT NULL,
  `NoHP` varchar(20) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Foto_Member` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`ID_Member`, `Nama_Member`, `NoHP`, `Email`, `Password`, `Foto_Member`) VALUES
(2, 'valen', '12344', 'valen@gmail.com', '$2y$10$hokiUF4qCB/EcHrKOt59O.8PD.9efUP4XnJCxNgobKG6mxbTp1WMa', NULL),
(20, 'celin', '12123123321', 'celin@gmail.com', '$2y$10$Q4UwMBp0jZr4KtUbVFqO7e4IN7An2tBSifGBDSnff7F/B7IZL7wDG', NULL),
(22, 'admin', '1243435353', 'admin@gmail.com', '$2y$10$Afdwa.gz8KDbvtNP4jOHkOprLC2kpJSi/EwqpBUk944jLUCKOfkDG', NULL),
(23, 'trainer', '3943748327328', 'trainer@gmail.com', '$2y$10$fXO3frGT7h5ixJTBB.O5XOag5KVoKEbacwlPn81BtRe8UshXVOj4O', NULL),
(27, 'subjek', '1233435', 'subjek@gmail.com', '$2y$10$VfFw3HGMO1NMVNMOfcWL0O0tBsILF9ki9j1n82twVITuoz5X7TgU2', NULL),
(28, 'tomas', '1233435', 'thomas@gmail.com', '$2y$10$EVUq7IzZ6/oD8oFHNQG2g.ADRXgbdCdhmTgsxNgCFaGV/Om2M8kuC', NULL),
(30, 'test', '1233435', 'test@gmail.com', '$2y$10$NlPRXg4aU/I3ZqhtmNyrT.hebi2TMPVY9M5lXD8xUstDUDvTHCwpu', NULL),
(33, 'coba', '1233435', 'coba@gmail.com', '$2y$10$5n2SzVAzS35kkDzm42xrGuVMRpybaxHaV89RtOXRwEzWvJf7fUWUq', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `ID_Membership` int(40) NOT NULL,
  `Jenis_Membership` varchar(50) NOT NULL,
  `Durasi` int(20) NOT NULL,
  `Harga` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`ID_Membership`, `Jenis_Membership`, `Durasi`, `Harga`) VALUES
(1, 'Harian', 1, 35000),
(2, 'Bulanan_Gym', 30, 125000),
(3, 'Bulanan_Class', 30, 150000);

-- --------------------------------------------------------

--
-- Table structure for table `personal_trainer`
--

CREATE TABLE `personal_trainer` (
  `ID_PT` int(40) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Nama_PT` varchar(50) NOT NULL,
  `Foto_PT` varchar(255) NOT NULL,
  `Prestasi` varchar(50) NOT NULL,
  `Spesialisasi` varchar(50) NOT NULL,
  `Harga_Sesi` int(20) NOT NULL,
  `Rating` float DEFAULT NULL,
  `Reset_Token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_trainer`
--

INSERT INTO `personal_trainer` (`ID_PT`, `Email`, `Password`, `Nama_PT`, `Foto_PT`, `Prestasi`, `Spesialisasi`, `Harga_Sesi`, `Rating`, `Reset_Token`) VALUES
(8, 'matthew@gmail.com', '$2y$10$kani.H8W0LmWhQN/0xkD4eAxE5e2.2d4OFigD5z/eeY4nsanNfBjO', 'Matthew Carlos', '1734431817_ed854f792728609c68b3.jpg', 'Juara 1 Lomba Body Building Banten 2021', 'Body Building', 450000, 3.4, NULL),
(9, 'andrew@gmail.com', '$2y$10$LvpT/jsFT8dGF7sxRRgEc.X/c2W.AqSNf7odRs7Zdcvd75UNU6Pn6', 'Andrew Garfield', '1730984643_9c28d85eb8e8eda2f9cd.jpg', 'Juara 1 Lomba Body Building Tangerang 2021', 'Body Building', 500000, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_training`
--

CREATE TABLE `personal_training` (
  `ID_Sesi` int(11) NOT NULL,
  `ID_PT` int(11) NOT NULL,
  `Nama_PT` varchar(255) DEFAULT NULL,
  `ID_Member` int(11) NOT NULL,
  `Nama_Member` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `session_time` varchar(20) NOT NULL,
  `status` enum('booked','paid') DEFAULT 'booked',
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `Latihan` text DEFAULT NULL,
  `Confirm` varchar(40) DEFAULT NULL,
  `Pesan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_training`
--

INSERT INTO `personal_training` (`ID_Sesi`, `ID_PT`, `Nama_PT`, `ID_Member`, `Nama_Member`, `date`, `session_time`, `status`, `rating`, `review`, `Latihan`, `Confirm`, `Pesan`) VALUES
(572, 8, 'Matthew Carlos', 30, 'test', '2025-01-02', '19:00 - 21:00', 'paid', NULL, NULL, NULL, NULL, ''),
(573, 8, 'Matthew Carlos', 30, 'test', '2025-01-02', '15:00 - 17:00', 'paid', 1, 'ga', NULL, NULL, ''),
(574, 8, 'Matthew Carlos', 30, 'test', '2025-01-05', '07:00 - 09:00', 'paid', NULL, NULL, NULL, NULL, ''),
(575, 8, 'Matthew Carlos', 30, 'test', '2025-01-05', '11:00 - 13:00', 'paid', NULL, NULL, NULL, NULL, ''),
(576, 8, 'Matthew Carlos', 30, 'test', '2025-01-05', '15:00 - 17:00', 'paid', NULL, NULL, NULL, NULL, ''),
(577, 8, 'Matthew Carlos', 30, 'test', '2025-01-05', '19:00 - 21:00', 'paid', NULL, NULL, NULL, NULL, ''),
(578, 8, 'Matthew Carlos', 30, 'test', '2024-12-27', '11:00 - 13:00', 'paid', 1, 'ga', NULL, NULL, ''),
(580, 8, 'Matthew Carlos', 30, 'test', '2024-12-26', '11:00 - 13:00', 'paid', 5, 'oke', NULL, NULL, ''),
(634, 8, 'Matthew Carlos', 33, 'coba', '2024-12-18', '11:00 - 13:00', 'paid', NULL, NULL, NULL, NULL, ''),
(636, 8, 'Matthew Carlos', 33, 'coba', '2024-12-19', '09:00 - 11:00', 'paid', NULL, NULL, NULL, NULL, ''),
(637, 8, 'Matthew Carlos', 33, 'coba', '2024-12-20', '11:00 - 13:00', 'paid', NULL, NULL, NULL, NULL, ''),
(638, 8, 'Matthew Carlos', 33, 'coba', '2024-12-21', '07:00 - 09:00', 'paid', NULL, NULL, NULL, NULL, ''),
(639, 8, 'Matthew Carlos', 33, 'coba', '2024-12-21', '09:00 - 11:00', 'paid', NULL, NULL, NULL, NULL, ''),
(640, 8, 'Matthew Carlos', 33, 'coba', '2024-12-22', '09:00 - 11:00', 'paid', NULL, NULL, NULL, NULL, ''),
(641, 8, 'Matthew Carlos', 33, 'coba', '2024-12-23', '07:00 - 09:00', 'paid', NULL, NULL, NULL, NULL, ''),
(642, 8, 'Matthew Carlos', 33, 'coba', '2024-12-23', '09:00 - 11:00', 'paid', NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `tambah_pt`
--

CREATE TABLE `tambah_pt` (
  `ID_Tambah_PT` int(11) NOT NULL,
  `ID_Record` int(11) DEFAULT NULL,
  `ID_PT` int(11) DEFAULT NULL,
  `Harga_PT` decimal(10,2) DEFAULT NULL,
  `Bukti_TambahPT` varchar(255) DEFAULT NULL,
  `StatusPT` varchar(255) NOT NULL DEFAULT 'Pending',
  `Reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_class`
--
ALTER TABLE `booking_class`
  ADD PRIMARY KEY (`ID_Booking`),
  ADD KEY `ID_Member` (`ID_Member`),
  ADD KEY `ID_Class` (`ID_Class`);

--
-- Indexes for table `daftar_membership`
--
ALTER TABLE `daftar_membership`
  ADD PRIMARY KEY (`ID_Record`),
  ADD KEY `ID_Member` (`ID_Member`),
  ADD KEY `ID_Membership` (`ID_Membership`);

--
-- Indexes for table `instruktur`
--
ALTER TABLE `instruktur`
  ADD PRIMARY KEY (`ID_Instruktur`);

--
-- Indexes for table `jadwal_class`
--
ALTER TABLE `jadwal_class`
  ADD PRIMARY KEY (`ID_Class`),
  ADD KEY `ID_Instruktur` (`ID_Instruktur`);

--
-- Indexes for table `jadwal_pt`
--
ALTER TABLE `jadwal_pt`
  ADD PRIMARY KEY (`ID_Jadwal`),
  ADD KEY `ID_PT` (`ID_PT`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`ID_Member`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Nama_Member` (`Nama_Member`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`ID_Membership`);

--
-- Indexes for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  ADD PRIMARY KEY (`ID_PT`);

--
-- Indexes for table `personal_training`
--
ALTER TABLE `personal_training`
  ADD PRIMARY KEY (`ID_Sesi`),
  ADD KEY `ID_PT` (`ID_PT`),
  ADD KEY `ID_Member` (`ID_Member`);

--
-- Indexes for table `tambah_pt`
--
ALTER TABLE `tambah_pt`
  ADD PRIMARY KEY (`ID_Tambah_PT`),
  ADD KEY `ID_Record` (`ID_Record`),
  ADD KEY `ID_PT` (`ID_PT`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_class`
--
ALTER TABLE `booking_class`
  MODIFY `ID_Booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `daftar_membership`
--
ALTER TABLE `daftar_membership`
  MODIFY `ID_Record` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `instruktur`
--
ALTER TABLE `instruktur`
  MODIFY `ID_Instruktur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal_class`
--
ALTER TABLE `jadwal_class`
  MODIFY `ID_Class` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jadwal_pt`
--
ALTER TABLE `jadwal_pt`
  MODIFY `ID_Jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `ID_Member` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `ID_Membership` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_trainer`
--
ALTER TABLE `personal_trainer`
  MODIFY `ID_PT` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_training`
--
ALTER TABLE `personal_training`
  MODIFY `ID_Sesi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=643;

--
-- AUTO_INCREMENT for table `tambah_pt`
--
ALTER TABLE `tambah_pt`
  MODIFY `ID_Tambah_PT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_class`
--
ALTER TABLE `booking_class`
  ADD CONSTRAINT `booking_class_ibfk_1` FOREIGN KEY (`ID_Member`) REFERENCES `member` (`ID_Member`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_class_ibfk_2` FOREIGN KEY (`ID_Class`) REFERENCES `jadwal_class` (`ID_Class`) ON DELETE CASCADE;

--
-- Constraints for table `daftar_membership`
--
ALTER TABLE `daftar_membership`
  ADD CONSTRAINT `daftar_membership_ibfk_1` FOREIGN KEY (`ID_Member`) REFERENCES `member` (`ID_Member`),
  ADD CONSTRAINT `daftar_membership_ibfk_2` FOREIGN KEY (`ID_Membership`) REFERENCES `membership` (`ID_Membership`);

--
-- Constraints for table `jadwal_class`
--
ALTER TABLE `jadwal_class`
  ADD CONSTRAINT `jadwal_class_ibfk_1` FOREIGN KEY (`ID_Instruktur`) REFERENCES `instruktur` (`ID_Instruktur`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_pt`
--
ALTER TABLE `jadwal_pt`
  ADD CONSTRAINT `jadwal_pt_ibfk_1` FOREIGN KEY (`ID_PT`) REFERENCES `personal_trainer` (`ID_PT`);

--
-- Constraints for table `personal_training`
--
ALTER TABLE `personal_training`
  ADD CONSTRAINT `personal_training_ibfk_1` FOREIGN KEY (`ID_PT`) REFERENCES `personal_trainer` (`ID_PT`) ON DELETE CASCADE,
  ADD CONSTRAINT `personal_training_ibfk_2` FOREIGN KEY (`ID_Member`) REFERENCES `member` (`ID_Member`) ON DELETE CASCADE;

--
-- Constraints for table `tambah_pt`
--
ALTER TABLE `tambah_pt`
  ADD CONSTRAINT `tambah_pt_ibfk_1` FOREIGN KEY (`ID_Record`) REFERENCES `daftar_membership` (`ID_Record`),
  ADD CONSTRAINT `tambah_pt_ibfk_2` FOREIGN KEY (`ID_PT`) REFERENCES `personal_trainer` (`ID_PT`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
