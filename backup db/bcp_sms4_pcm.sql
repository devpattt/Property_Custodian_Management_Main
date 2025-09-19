-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 02:53 PM
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
-- Database: `bcp_sms4_pcm`
--

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_admins`
--

CREATE TABLE `bcp_sms4_admins` (
  `id` int(11) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_admins`
--

INSERT INTO `bcp_sms4_admins` (`id`, `user_type`, `username`, `fullname`, `password`, `email`) VALUES
(2, 'admin', 'Senka', 'Senka', '$2y$10$WHJtGH2A7l3ZEKron3u.WOT6zUR6bGHGhssU0vIWqeo/pm7yUTxma', 'aceplayer81218@gmail.com'),
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com'),
(4, 'admin', 'yuji', 'burat', '$2y$10$a4y/te38BWq/VZ/hBWsBx.XFaSsuLKZjVnWb9beJcq6nR6CDIkIlO', 'notyujiiiii@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bcp_sms4_admins`
--
ALTER TABLE `bcp_sms4_admins`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bcp_sms4_admins`
--
ALTER TABLE `bcp_sms4_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
