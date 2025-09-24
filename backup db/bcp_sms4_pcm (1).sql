-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 02:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `bcp_sms4_activity`
--

CREATE TABLE `bcp_sms4_activity` (
  `id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_admins`
--

CREATE TABLE `bcp_sms4_admins` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','teacher','custodian') NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_admins`
--

INSERT INTO `bcp_sms4_admins` (`id`, `user_type`, `username`, `fullname`, `password`, `email`) VALUES
(2, 'teacher', 'Teacher', 'Teacher', '$2y$10$WHJtGH2A7l3ZEKron3u.WOT6zUR6bGHGhssU0vIWqeo/pm7yUTxma', 'aceplayer81218@gmail.com'),
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com'),
(4, 'custodian', 'Custodian', 'Custodian', '$2y$10$a4y/te38BWq/VZ/hBWsBx.XFaSsuLKZjVnWb9beJcq6nR6CDIkIlO', 'notyujiiiii@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_adminsss`
--

CREATE TABLE `bcp_sms4_adminsss` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','teacher','custodian') NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_adminsss`
--

INSERT INTO `bcp_sms4_adminsss` (`id`, `user_type`, `username`, `fullname`, `password`, `email`) VALUES
(2, 'teacher', 'Senka', 'Senka', '$2y$10$WHJtGH2A7l3ZEKron3u.WOT6zUR6bGHGhssU0vIWqeo/pm7yUTxma', 'aceplayer81218@gmail.com'),
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_asset`
--

CREATE TABLE `bcp_sms4_asset` (
  `asset_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `property_tag` varchar(255) NOT NULL,
  `status` enum('In-Use','In-Storage','Damaged','Disposed','Lost') DEFAULT 'In-Use',
  `location` varchar(100) DEFAULT NULL,
  `assigned_to` varchar(100) DEFAULT NULL,
  `date_registered` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_asset`
--

INSERT INTO `bcp_sms4_asset` (`asset_id`, `item_id`, `property_tag`, `status`, `location`, `assigned_to`, `date_registered`) VALUES
(5, 2, 'ASSET-2025-0001', 'In-Use', NULL, NULL, '2025-09-20'),
(6, 2, 'ASSET-2025-0002', 'In-Use', NULL, NULL, '2025-09-20'),
(7, 2, 'ASSET-2025-0003', 'In-Use', NULL, NULL, '2025-09-20'),
(8, 2, 'ASSET-2025-0004', 'In-Use', NULL, NULL, '2025-09-20'),
(9, 2, 'ASSET-2025-0005', 'In-Use', NULL, NULL, '2025-09-20'),
(10, 2, 'ASSET-2025-0006', 'In-Use', NULL, NULL, '2025-09-20'),
(11, 2, 'ASSET-2025-0007', 'In-Use', NULL, NULL, '2025-09-20'),
(12, 2, 'ASSET-2025-0008', 'In-Use', NULL, NULL, '2025-09-20'),
(13, 2, 'ASSET-2025-0009', 'In-Use', NULL, NULL, '2025-09-20'),
(14, 2, 'ASSET-2025-0010', 'In-Use', NULL, NULL, '2025-09-20'),
(15, 2, 'ASSET-2025-0011', 'In-Use', NULL, NULL, '2025-09-20'),
(16, 2, 'ASSET-2025-0012', 'In-Use', NULL, NULL, '2025-09-20'),
(17, 2, 'ASSET-2025-0013', 'In-Use', NULL, NULL, '2025-09-20'),
(18, 2, 'ASSET-2025-0014', 'In-Use', NULL, NULL, '2025-09-20'),
(19, 2, 'ASSET-2025-0015', 'In-Use', NULL, NULL, '2025-09-20'),
(20, 2, 'ASSET-2025-0016', 'In-Use', NULL, NULL, '2025-09-20'),
(21, 2, 'ASSET-2025-0017', 'In-Use', NULL, NULL, '2025-09-20'),
(22, 2, 'ASSET-2025-0018', 'In-Use', NULL, NULL, '2025-09-20'),
(23, 2, 'ASSET-2025-0019', 'In-Use', NULL, NULL, '2025-09-20'),
(24, 2, 'ASSET-2025-0020', 'In-Use', NULL, NULL, '2025-09-20'),
(25, 4, 'ASSET-2025-0021', 'In-Use', NULL, NULL, '2025-09-20'),
(26, 4, 'ASSET-2025-0022', 'In-Use', NULL, NULL, '2025-09-20'),
(27, 6, 'ASSET-2025-0023', 'In-Use', NULL, NULL, '2025-09-20'),
(28, 6, 'ASSET-2025-0024', 'In-Use', NULL, NULL, '2025-09-20'),
(29, 6, 'ASSET-2025-0025', 'In-Use', NULL, NULL, '2025-09-20'),
(30, 6, 'ASSET-2025-0026', 'In-Use', NULL, NULL, '2025-09-20'),
(31, 6, 'ASSET-2025-0027', 'In-Use', NULL, NULL, '2025-09-20'),
(32, 6, 'ASSET-2025-0028', 'In-Use', NULL, NULL, '2025-09-20'),
(33, 6, 'ASSET-2025-0029', 'In-Use', NULL, NULL, '2025-09-20'),
(34, 6, 'ASSET-2025-0030', 'In-Use', NULL, NULL, '2025-09-20'),
(35, 6, 'ASSET-2025-0031', 'In-Use', NULL, NULL, '2025-09-20'),
(36, 6, 'ASSET-2025-0032', 'In-Use', NULL, NULL, '2025-09-20'),
(37, 6, 'ASSET-2025-0033', 'In-Use', NULL, NULL, '2025-09-20'),
(38, 6, 'ASSET-2025-0034', 'In-Use', NULL, NULL, '2025-09-20'),
(39, 6, 'ASSET-2025-0035', 'In-Use', NULL, NULL, '2025-09-20'),
(40, 6, 'ASSET-2025-0036', 'In-Use', NULL, NULL, '2025-09-20'),
(41, 6, 'ASSET-2025-0037', 'In-Use', NULL, NULL, '2025-09-20'),
(42, 6, 'ASSET-2025-0038', 'In-Use', NULL, NULL, '2025-09-20'),
(43, 6, 'ASSET-2025-0039', 'In-Use', NULL, NULL, '2025-09-20'),
(44, 6, 'ASSET-2025-0040', 'In-Use', NULL, NULL, '2025-09-20'),
(45, 6, 'ASSET-2025-0041', 'In-Use', NULL, NULL, '2025-09-20'),
(46, 6, 'ASSET-2025-0042', 'In-Use', NULL, NULL, '2025-09-20'),
(47, 6, 'ASSET-2025-0043', 'In-Use', NULL, NULL, '2025-09-20'),
(48, 6, 'ASSET-2025-0044', 'In-Use', NULL, NULL, '2025-09-20'),
(49, 6, 'ASSET-2025-0045', 'In-Use', NULL, NULL, '2025-09-20'),
(50, 6, 'ASSET-2025-0046', 'In-Use', NULL, NULL, '2025-09-20'),
(51, 6, 'ASSET-2025-0047', 'In-Use', NULL, NULL, '2025-09-20'),
(52, 6, 'ASSET-2025-0048', 'In-Use', NULL, NULL, '2025-09-20'),
(53, 6, 'ASSET-2025-0049', 'In-Use', NULL, NULL, '2025-09-20'),
(54, 6, 'ASSET-2025-0050', 'In-Use', NULL, NULL, '2025-09-20'),
(55, 6, 'ASSET-2025-0051', 'In-Use', NULL, NULL, '2025-09-20'),
(56, 6, 'ASSET-2025-0052', 'In-Use', NULL, NULL, '2025-09-20'),
(57, 6, 'ASSET-2025-0053', 'In-Use', NULL, NULL, '2025-09-20'),
(58, 6, 'ASSET-2025-0054', 'In-Use', NULL, NULL, '2025-09-20'),
(59, 6, 'ASSET-2025-0055', 'In-Use', NULL, NULL, '2025-09-20'),
(60, 6, 'ASSET-2025-0056', 'In-Use', NULL, NULL, '2025-09-20'),
(61, 6, 'ASSET-2025-0057', 'In-Use', NULL, NULL, '2025-09-20'),
(62, 6, 'ASSET-2025-0058', 'In-Use', NULL, NULL, '2025-09-20'),
(63, 6, 'ASSET-2025-0059', 'In-Use', NULL, NULL, '2025-09-20'),
(64, 6, 'ASSET-2025-0060', 'In-Use', NULL, NULL, '2025-09-20'),
(65, 6, 'ASSET-2025-0061', 'In-Use', NULL, NULL, '2025-09-20'),
(66, 6, 'ASSET-2025-0062', 'In-Use', NULL, NULL, '2025-09-20'),
(67, 6, 'ASSET-2025-0063', 'In-Use', NULL, NULL, '2025-09-20'),
(68, 6, 'ASSET-2025-0064', 'In-Use', NULL, NULL, '2025-09-20'),
(69, 6, 'ASSET-2025-0065', 'In-Use', NULL, NULL, '2025-09-20'),
(70, 6, 'ASSET-2025-0066', 'In-Use', NULL, NULL, '2025-09-20'),
(71, 6, 'ASSET-2025-0067', 'In-Use', NULL, NULL, '2025-09-20'),
(72, 6, 'ASSET-2025-0068', 'In-Use', NULL, NULL, '2025-09-20'),
(73, 6, 'ASSET-2025-0069', 'In-Use', NULL, NULL, '2025-09-20'),
(74, 6, 'ASSET-2025-0070', 'In-Use', NULL, NULL, '2025-09-20'),
(75, 6, 'ASSET-2025-0071', 'In-Use', NULL, NULL, '2025-09-20'),
(76, 6, 'ASSET-2025-0072', 'In-Use', NULL, NULL, '2025-09-20'),
(77, 6, 'ASSET-2025-0073', 'In-Use', NULL, NULL, '2025-09-20'),
(78, 6, 'ASSET-2025-0074', 'In-Use', NULL, NULL, '2025-09-20'),
(79, 6, 'ASSET-2025-0075', 'In-Use', NULL, NULL, '2025-09-20'),
(80, 6, 'ASSET-2025-0076', 'In-Use', NULL, NULL, '2025-09-20'),
(81, 6, 'ASSET-2025-0077', 'In-Use', NULL, NULL, '2025-09-20'),
(82, 6, 'ASSET-2025-0078', 'In-Use', NULL, NULL, '2025-09-20'),
(83, 6, 'ASSET-2025-0079', 'In-Use', NULL, NULL, '2025-09-20'),
(84, 6, 'ASSET-2025-0080', 'In-Use', NULL, NULL, '2025-09-20'),
(85, 6, 'ASSET-2025-0081', 'In-Use', NULL, NULL, '2025-09-20'),
(86, 6, 'ASSET-2025-0082', 'In-Use', NULL, NULL, '2025-09-20'),
(87, 6, 'ASSET-2025-0083', 'In-Use', NULL, NULL, '2025-09-20'),
(88, 6, 'ASSET-2025-0084', 'In-Use', NULL, NULL, '2025-09-20'),
(89, 6, 'ASSET-2025-0085', 'In-Use', NULL, NULL, '2025-09-20'),
(90, 6, 'ASSET-2025-0086', 'In-Use', NULL, NULL, '2025-09-20'),
(91, 6, 'ASSET-2025-0087', 'In-Use', NULL, NULL, '2025-09-20'),
(92, 6, 'ASSET-2025-0088', 'In-Use', NULL, NULL, '2025-09-20'),
(93, 6, 'ASSET-2025-0089', 'In-Use', NULL, NULL, '2025-09-20'),
(94, 6, 'ASSET-2025-0090', 'In-Use', NULL, NULL, '2025-09-20'),
(95, 6, 'ASSET-2025-0091', 'In-Use', NULL, NULL, '2025-09-20'),
(96, 6, 'ASSET-2025-0092', 'In-Use', NULL, NULL, '2025-09-20'),
(97, 6, 'ASSET-2025-0093', 'In-Use', NULL, NULL, '2025-09-20'),
(98, 6, 'ASSET-2025-0094', 'In-Use', NULL, NULL, '2025-09-20'),
(99, 6, 'ASSET-2025-0095', 'In-Use', NULL, NULL, '2025-09-20'),
(100, 6, 'ASSET-2025-0096', 'In-Use', NULL, NULL, '2025-09-20'),
(101, 6, 'ASSET-2025-0097', 'In-Use', NULL, NULL, '2025-09-20'),
(102, 6, 'ASSET-2025-0098', 'In-Use', NULL, NULL, '2025-09-20'),
(103, 6, 'ASSET-2025-0099', 'In-Use', NULL, NULL, '2025-09-20'),
(104, 6, 'ASSET-2025-0100', 'In-Use', NULL, NULL, '2025-09-20'),
(105, 6, 'ASSET-2025-0101', 'In-Use', NULL, NULL, '2025-09-20'),
(106, 6, 'ASSET-2025-0102', 'In-Use', NULL, NULL, '2025-09-20'),
(107, 6, 'ASSET-2025-0103', 'In-Use', NULL, NULL, '2025-09-20'),
(108, 6, 'ASSET-2025-0104', 'In-Use', NULL, NULL, '2025-09-20'),
(109, 6, 'ASSET-2025-0105', 'In-Use', NULL, NULL, '2025-09-20'),
(110, 6, 'ASSET-2025-0106', 'In-Use', NULL, NULL, '2025-09-20'),
(111, 6, 'ASSET-2025-0107', 'In-Use', NULL, NULL, '2025-09-20'),
(112, 6, 'ASSET-2025-0108', 'In-Use', NULL, NULL, '2025-09-20'),
(113, 6, 'ASSET-2025-0109', 'In-Use', NULL, NULL, '2025-09-20'),
(114, 6, 'ASSET-2025-0110', 'In-Use', NULL, NULL, '2025-09-20'),
(115, 6, 'ASSET-2025-0111', 'In-Use', NULL, NULL, '2025-09-20'),
(116, 6, 'ASSET-2025-0112', 'In-Use', NULL, NULL, '2025-09-20'),
(117, 6, 'ASSET-2025-0113', 'In-Use', NULL, NULL, '2025-09-20'),
(118, 6, 'ASSET-2025-0114', 'In-Use', NULL, NULL, '2025-09-20'),
(119, 6, 'ASSET-2025-0115', 'In-Use', NULL, NULL, '2025-09-20'),
(120, 6, 'ASSET-2025-0116', 'In-Use', NULL, NULL, '2025-09-20'),
(121, 6, 'ASSET-2025-0117', 'In-Use', NULL, NULL, '2025-09-20'),
(122, 6, 'ASSET-2025-0118', 'In-Use', NULL, NULL, '2025-09-20'),
(123, 6, 'ASSET-2025-0119', 'In-Use', NULL, NULL, '2025-09-20'),
(124, 6, 'ASSET-2025-0120', 'In-Use', NULL, NULL, '2025-09-20'),
(125, 6, 'ASSET-2025-0121', 'In-Use', NULL, NULL, '2025-09-20'),
(126, 6, 'ASSET-2025-0122', 'In-Use', NULL, NULL, '2025-09-20'),
(127, 5, 'ASSET-2025-0123', 'In-Use', NULL, NULL, '2025-09-20'),
(128, 5, 'ASSET-2025-0124', 'In-Use', NULL, NULL, '2025-09-20'),
(129, 5, 'ASSET-2025-0125', 'In-Use', NULL, NULL, '2025-09-20'),
(130, 5, 'ASSET-2025-0126', 'In-Use', NULL, NULL, '2025-09-20'),
(131, 5, 'ASSET-2025-0127', 'In-Use', NULL, NULL, '2025-09-20'),
(132, 5, 'ASSET-2025-0128', 'In-Use', NULL, NULL, '2025-09-20'),
(133, 5, 'ASSET-2025-0129', 'In-Use', NULL, NULL, '2025-09-20'),
(134, 5, 'ASSET-2025-0130', 'In-Use', NULL, NULL, '2025-09-20'),
(135, 5, 'ASSET-2025-0131', 'In-Use', NULL, NULL, '2025-09-20'),
(136, 5, 'ASSET-2025-0132', 'In-Use', NULL, NULL, '2025-09-20'),
(137, 5, 'ASSET-2025-0133', 'In-Use', NULL, NULL, '2025-09-20'),
(138, 5, 'ASSET-2025-0134', 'In-Use', NULL, NULL, '2025-09-20'),
(139, 5, 'ASSET-2025-0135', 'In-Use', NULL, NULL, '2025-09-20'),
(140, 5, 'ASSET-2025-0136', 'In-Use', NULL, NULL, '2025-09-20'),
(141, 5, 'ASSET-2025-0137', 'In-Use', NULL, NULL, '2025-09-20'),
(142, 5, 'ASSET-2025-0138', 'In-Use', NULL, NULL, '2025-09-20'),
(143, 5, 'ASSET-2025-0139', 'In-Use', NULL, NULL, '2025-09-20'),
(144, 5, 'ASSET-2025-0140', 'In-Use', NULL, NULL, '2025-09-20'),
(145, 5, 'ASSET-2025-0141', 'In-Use', NULL, NULL, '2025-09-20'),
(146, 5, 'ASSET-2025-0142', 'In-Use', NULL, NULL, '2025-09-20'),
(147, 5, 'ASSET-2025-0143', 'In-Use', NULL, NULL, '2025-09-20'),
(148, 5, 'ASSET-2025-0144', 'In-Use', NULL, NULL, '2025-09-20'),
(149, 5, 'ASSET-2025-0145', 'In-Use', NULL, NULL, '2025-09-20'),
(150, 5, 'ASSET-2025-0146', 'In-Use', NULL, NULL, '2025-09-20'),
(151, 5, 'ASSET-2025-0147', 'In-Use', NULL, NULL, '2025-09-20'),
(152, 5, 'ASSET-2025-0148', 'In-Use', NULL, NULL, '2025-09-20'),
(153, 5, 'ASSET-2025-0149', 'In-Use', NULL, NULL, '2025-09-20'),
(154, 5, 'ASSET-2025-0150', 'In-Use', NULL, NULL, '2025-09-20'),
(155, 5, 'ASSET-2025-0151', 'In-Use', NULL, NULL, '2025-09-20'),
(156, 5, 'ASSET-2025-0152', 'In-Use', NULL, NULL, '2025-09-20'),
(157, 5, 'ASSET-2025-0153', 'In-Use', NULL, NULL, '2025-09-20'),
(158, 5, 'ASSET-2025-0154', 'In-Use', NULL, NULL, '2025-09-20'),
(159, 5, 'ASSET-2025-0155', 'In-Use', NULL, NULL, '2025-09-20'),
(160, 5, 'ASSET-2025-0156', 'In-Use', NULL, NULL, '2025-09-20'),
(161, 5, 'ASSET-2025-0157', 'In-Use', NULL, NULL, '2025-09-20'),
(162, 5, 'ASSET-2025-0158', 'In-Use', NULL, NULL, '2025-09-20'),
(163, 5, 'ASSET-2025-0159', 'In-Use', NULL, NULL, '2025-09-20'),
(164, 5, 'ASSET-2025-0160', 'In-Use', NULL, NULL, '2025-09-20'),
(165, 5, 'ASSET-2025-0161', 'In-Use', NULL, NULL, '2025-09-20'),
(166, 5, 'ASSET-2025-0162', 'In-Use', NULL, NULL, '2025-09-20'),
(167, 5, 'ASSET-2025-0163', 'In-Use', NULL, NULL, '2025-09-20'),
(168, 5, 'ASSET-2025-0164', 'In-Use', NULL, NULL, '2025-09-20'),
(169, 5, 'ASSET-2025-0165', 'In-Use', NULL, NULL, '2025-09-20'),
(170, 5, 'ASSET-2025-0166', 'In-Use', NULL, NULL, '2025-09-20'),
(171, 5, 'ASSET-2025-0167', 'In-Use', NULL, NULL, '2025-09-20'),
(172, 5, 'ASSET-2025-0168', 'In-Use', NULL, NULL, '2025-09-20'),
(173, 5, 'ASSET-2025-0169', 'In-Use', NULL, NULL, '2025-09-20'),
(174, 5, 'ASSET-2025-0170', 'In-Use', NULL, NULL, '2025-09-20'),
(175, 5, 'ASSET-2025-0171', 'In-Use', NULL, NULL, '2025-09-20'),
(176, 5, 'ASSET-2025-0172', 'In-Use', NULL, NULL, '2025-09-20'),
(177, 16, 'ASSET-2025-0173', 'In-Use', NULL, NULL, '2025-09-20'),
(178, 16, 'ASSET-2025-0174', 'In-Use', NULL, NULL, '2025-09-20'),
(179, 16, 'ASSET-2025-0175', 'In-Use', NULL, NULL, '2025-09-20'),
(180, 16, 'ASSET-2025-0176', 'In-Use', NULL, NULL, '2025-09-20'),
(181, 16, 'ASSET-2025-0177', 'In-Use', NULL, NULL, '2025-09-20'),
(182, 14, 'ASSET-2025-0178', 'In-Use', NULL, NULL, '2025-09-20'),
(183, 14, 'ASSET-2025-0179', 'In-Use', NULL, NULL, '2025-09-20'),
(184, 14, 'ASSET-2025-0180', 'In-Use', NULL, NULL, '2025-09-20'),
(185, 14, 'ASSET-2025-0181', 'In-Use', NULL, NULL, '2025-09-20'),
(186, 14, 'ASSET-2025-0182', 'In-Use', NULL, NULL, '2025-09-20'),
(187, 14, 'ASSET-2025-0183', 'In-Use', NULL, NULL, '2025-09-20'),
(188, 14, 'ASSET-2025-0184', 'In-Use', NULL, NULL, '2025-09-20'),
(189, 14, 'ASSET-2025-0185', 'In-Use', NULL, NULL, '2025-09-20'),
(190, 14, 'ASSET-2025-0186', 'In-Use', NULL, NULL, '2025-09-20'),
(191, 14, 'ASSET-2025-0187', 'In-Use', NULL, NULL, '2025-09-20'),
(192, 14, 'ASSET-2025-0188', 'In-Use', NULL, NULL, '2025-09-20'),
(193, 14, 'ASSET-2025-0189', 'In-Use', NULL, NULL, '2025-09-20'),
(194, 14, 'ASSET-2025-0190', 'In-Use', NULL, NULL, '2025-09-20'),
(195, 14, 'ASSET-2025-0191', 'In-Use', NULL, NULL, '2025-09-20'),
(196, 14, 'ASSET-2025-0192', 'In-Use', NULL, NULL, '2025-09-20'),
(197, 14, 'ASSET-2025-0193', 'In-Use', NULL, NULL, '2025-09-20'),
(198, 14, 'ASSET-2025-0194', 'In-Use', NULL, NULL, '2025-09-20'),
(199, 14, 'ASSET-2025-0195', 'In-Use', NULL, NULL, '2025-09-20'),
(200, 14, 'ASSET-2025-0196', 'In-Use', NULL, NULL, '2025-09-20'),
(201, 14, 'ASSET-2025-0197', 'In-Use', NULL, NULL, '2025-09-20'),
(202, 14, 'ASSET-2025-0198', 'In-Use', NULL, NULL, '2025-09-20'),
(203, 14, 'ASSET-2025-0199', 'In-Use', NULL, NULL, '2025-09-20'),
(204, 14, 'ASSET-2025-0200', 'In-Use', NULL, NULL, '2025-09-20'),
(205, 14, 'ASSET-2025-0201', 'In-Use', NULL, NULL, '2025-09-20'),
(206, 14, 'ASSET-2025-0202', 'In-Use', NULL, NULL, '2025-09-20'),
(207, 14, 'ASSET-2025-0203', 'In-Use', NULL, NULL, '2025-09-20'),
(208, 14, 'ASSET-2025-0204', 'In-Use', NULL, NULL, '2025-09-20'),
(209, 14, 'ASSET-2025-0205', 'In-Use', NULL, NULL, '2025-09-20'),
(210, 14, 'ASSET-2025-0206', 'In-Use', NULL, NULL, '2025-09-20'),
(211, 14, 'ASSET-2025-0207', 'In-Use', NULL, NULL, '2025-09-20'),
(212, 1, 'ASSET-2025-0208', 'In-Use', NULL, NULL, '2025-09-22'),
(213, 1, 'ASSET-2025-0209', 'In-Use', NULL, NULL, '2025-09-22'),
(214, 1, 'ASSET-2025-0210', 'In-Use', NULL, NULL, '2025-09-22'),
(215, 1, 'ASSET-2025-0211', 'In-Use', NULL, NULL, '2025-09-22'),
(216, 1, 'ASSET-2025-0212', 'In-Use', NULL, NULL, '2025-09-22'),
(217, 3, 'ASSET-2025-0213', 'In-Use', NULL, NULL, '2025-09-22'),
(218, 3, 'ASSET-2025-0214', 'In-Use', NULL, NULL, '2025-09-22'),
(219, 3, 'ASSET-2025-0215', 'In-Use', NULL, NULL, '2025-09-22'),
(220, 3, 'ASSET-2025-0216', 'In-Use', NULL, NULL, '2025-09-22'),
(221, 3, 'ASSET-2025-0217', 'In-Use', NULL, NULL, '2025-09-22'),
(222, 3, 'ASSET-2025-0218', 'In-Use', NULL, NULL, '2025-09-22'),
(223, 3, 'ASSET-2025-0219', 'In-Use', NULL, NULL, '2025-09-22'),
(224, 3, 'ASSET-2025-0220', 'In-Use', NULL, NULL, '2025-09-22'),
(225, 3, 'ASSET-2025-0221', 'In-Use', NULL, NULL, '2025-09-22'),
(226, 3, 'ASSET-2025-0222', 'In-Use', NULL, NULL, '2025-09-22'),
(227, 1, 'ASSET-2025-0223', 'In-Use', NULL, NULL, '2025-09-22'),
(228, 1, 'ASSET-2025-0224', 'In-Use', NULL, NULL, '2025-09-22'),
(229, 1, 'ASSET-2025-0225', 'In-Use', NULL, NULL, '2025-09-22'),
(230, 1, 'ASSET-2025-0226', 'In-Use', NULL, NULL, '2025-09-22'),
(231, 1, 'ASSET-2025-0227', 'In-Use', NULL, NULL, '2025-09-22'),
(232, 1, 'ASSET-2025-0228', 'In-Use', NULL, NULL, '2025-09-22'),
(233, 1, 'ASSET-2025-0229', 'In-Use', NULL, NULL, '2025-09-22'),
(234, 1, 'ASSET-2025-0230', 'In-Use', NULL, NULL, '2025-09-22'),
(235, 1, 'ASSET-2025-0231', 'In-Use', NULL, NULL, '2025-09-22'),
(236, 1, 'ASSET-2025-0232', 'In-Use', NULL, NULL, '2025-09-22'),
(237, 2, 'ASSET-2025-0233', 'In-Use', NULL, NULL, '2025-09-22'),
(238, 2, 'ASSET-2025-0234', 'In-Use', NULL, NULL, '2025-09-22'),
(239, 2, 'ASSET-2025-0235', 'In-Use', NULL, NULL, '2025-09-22'),
(240, 2, 'ASSET-2025-0236', 'In-Use', NULL, NULL, '2025-09-22'),
(241, 2, 'ASSET-2025-0237', 'In-Use', NULL, NULL, '2025-09-22'),
(242, 2, 'ASSET-2025-0238', 'In-Use', NULL, NULL, '2025-09-22'),
(243, 2, 'ASSET-2025-0239', 'In-Use', NULL, NULL, '2025-09-22'),
(244, 2, 'ASSET-2025-0240', 'In-Use', NULL, NULL, '2025-09-22'),
(245, 2, 'ASSET-2025-0241', 'In-Use', NULL, NULL, '2025-09-22'),
(246, 2, 'ASSET-2025-0242', 'In-Use', NULL, NULL, '2025-09-22'),
(247, 2, 'ASSET-2025-0243', 'In-Use', NULL, NULL, '2025-09-22'),
(248, 2, 'ASSET-2025-0244', 'In-Use', NULL, NULL, '2025-09-22'),
(249, 2, 'ASSET-2025-0245', 'In-Use', NULL, NULL, '2025-09-22'),
(250, 2, 'ASSET-2025-0246', 'In-Use', NULL, NULL, '2025-09-22'),
(251, 2, 'ASSET-2025-0247', 'In-Use', NULL, NULL, '2025-09-22'),
(252, 2, 'ASSET-2025-0248', 'In-Use', NULL, NULL, '2025-09-22'),
(253, 2, 'ASSET-2025-0249', 'In-Use', NULL, NULL, '2025-09-22'),
(254, 2, 'ASSET-2025-0250', 'In-Use', NULL, NULL, '2025-09-22'),
(255, 2, 'ASSET-2025-0251', 'In-Use', NULL, NULL, '2025-09-22'),
(256, 2, 'ASSET-2025-0252', 'In-Use', NULL, NULL, '2025-09-22');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_asset_audit`
--

CREATE TABLE `bcp_sms4_asset_audit` (
  `audit_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `audit_date` date NOT NULL,
  `auditor` varchar(100) NOT NULL,
  `findings` text DEFAULT NULL,
  `result` enum('Match','Mismatch','Pending') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_assign_consumable`
--

CREATE TABLE `bcp_sms4_assign_consumable` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `equipment_id` varchar(255) NOT NULL,
  `equipment_name` varchar(100) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `expiration` date NOT NULL,
  `box` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `per_box` int(11) DEFAULT NULL,
  `custodian_id` varchar(50) DEFAULT NULL,
  `custodian_name` varchar(100) DEFAULT NULL,
  `department_code` varchar(255) NOT NULL,
  `assigned_date` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL,
  `remarks` text DEFAULT NULL,
  `assigned_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_assign_consumable`
--

INSERT INTO `bcp_sms4_assign_consumable` (`id`, `reference_no`, `equipment_id`, `equipment_name`, `category`, `expiration`, `box`, `quantity`, `per_box`, `custodian_id`, `custodian_name`, `department_code`, `assigned_date`, `end_date`, `remarks`, `assigned_by`) VALUES
(6, 'REF-20250916-D6685', 'ASSET-2025-EF56', 'Eraser (dust-free)', 'Classroom Supplies', '0000-00-00', 8, 10, 20, 'EMP009', 'William Taylor', 'Architecture', '2025-09-17 04:51:36', '0000-00-00 00:00:00', 'aaaaaaaa', 'admin'),
(10, 'REF-20250916-4C75A', 'ASSET-2025-EF56', 'Eraser (dust-free)', 'Classroom Supplies', '0000-00-00', 2, 5, 20, 'EMP005', 'Daniel Wilson', 'Civil Engineering', '2025-09-17 04:51:36', '0000-00-00 00:00:00', 'aaaaaaaa', 'admin'),
(11, 'REF-20250919-DE956', 'ASSET-2025-CD34', 'Whiteboard Marker (pack of 10)', 'Writing Supplies', '2025-11-30', 4, 5, 10, 'EMP003', 'Michael Johnson', 'Business Administration', '2025-09-19 20:53:53', '0000-00-00 00:00:00', 'sss', 'admin'),
(12, 'REF-20250919-B3A9C', 'ASSET-2025-CD34', 'Whiteboard Marker (pack of 10)', 'Writing Supplies', '2025-11-30', 5, 5, 10, 'EMP002', 'Jane Doe', 'Information Technology', '2025-09-19 20:57:20', '0000-00-00 00:00:00', 'dddddddd', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_assign_history`
--

CREATE TABLE `bcp_sms4_assign_history` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `equipment_id` varchar(255) NOT NULL,
  `equipment_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `custodian_id` varchar(50) DEFAULT NULL,
  `custodian_name` varchar(100) DEFAULT NULL,
  `department_code` varchar(255) NOT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `end_date` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `assigned_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_assign_history`
--

INSERT INTO `bcp_sms4_assign_history` (`id`, `reference_no`, `equipment_id`, `equipment_name`, `quantity`, `custodian_id`, `custodian_name`, `department_code`, `assigned_date`, `end_date`, `remarks`, `assigned_by`) VALUES
(15, 'REF-20250912-4BE7C', '1234', 'Table', 1, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:39:15', NULL, 'Ewan ko | Merged on 2025-09-12 21:24:33', 'admin'),
(20, 'REF-20250912-6B465', '42424', 'Table', 5, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:58', NULL, 'sdfgdsg', 'admin'),
(21, 'REF-20250912-6B3A7', '42424', 'Table', 2, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:51:23', NULL, 'ISA PA TANGINA MO KA', 'admin'),
(22, 'REF-20250912-5F88A', 'REF-20250912-6B3A7', 'Table', 3, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:04', NULL, 'iuadghajidgn', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_audit`
--

CREATE TABLE `bcp_sms4_audit` (
  `id` int(11) NOT NULL,
  `audit_date` date NOT NULL,
  `department` varchar(100) NOT NULL,
  `custodian` varchar(100) NOT NULL,
  `status` enum('Scheduled','Ongoing','Completed') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_audit`
--

INSERT INTO `bcp_sms4_audit` (`id`, `audit_date`, `department`, `custodian`, `status`) VALUES
(1, '2025-09-30', 'IT', 'Custodian 1', 'Ongoing'),
(2, '2025-09-26', 'IT', 'Custodian 2', 'Ongoing'),
(3, '2025-09-30', 'IT', 'Custodian 3', 'Ongoing'),
(4, '2025-09-22', 'Information Technology', 'Custodian 4', 'Scheduled'),
(5, '2025-09-29', 'Information Technology', 'Custodian 5', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_audit_discrepancies`
--

CREATE TABLE `bcp_sms4_audit_discrepancies` (
  `discrepancy_id` int(11) NOT NULL,
  `audit_type` enum('Asset','Consumable') NOT NULL,
  `audit_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `resolved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_consumable`
--

CREATE TABLE `bcp_sms4_consumable` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Available','Low-Stock','Out-of-Stock','Expired') DEFAULT 'Available',
  `expiration` date NOT NULL,
  `date_received` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_consumable`
--

INSERT INTO `bcp_sms4_consumable` (`id`, `item_id`, `unit`, `quantity`, `status`, `expiration`, `date_received`) VALUES
(10, 11, 'pcs', 100, 'Available', '2026-09-20', '2025-09-20 14:41:46'),
(16, 9, 'ream', 5, 'Available', '2026-09-22', '2025-09-22 20:56:43'),
(17, 10, 'pcs', 50, 'Available', '2026-09-22', '2025-09-22 20:56:47'),
(18, 11, 'pcs', 20, 'Available', '2026-09-22', '2025-09-22 20:56:52'),
(19, 12, 'pcs', 10, 'Available', '2026-09-22', '2025-09-22 20:56:55'),
(20, 14, 'bottle', 3, 'Available', '2026-09-22', '2025-09-22 20:57:02'),
(21, 15, 'pcs', 2, 'Available', '2026-09-22', '2025-09-22 20:57:06'),
(22, 16, 'set', 1, 'Available', '2026-09-22', '2025-09-22 20:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_consumable_audit`
--

CREATE TABLE `bcp_sms4_consumable_audit` (
  `audit_id` int(11) NOT NULL,
  `consumable_id` int(11) NOT NULL,
  `audit_date` date NOT NULL,
  `auditor` varchar(100) NOT NULL,
  `findings` text DEFAULT NULL,
  `result` enum('OK','Expired','Low Stock','Mismatch','Pending') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_items`
--

CREATE TABLE `bcp_sms4_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `item_type` enum('Asset','Consumable') NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_items`
--

INSERT INTO `bcp_sms4_items` (`item_id`, `item_name`, `category`, `item_type`, `unit`) VALUES
(1, 'Teacher’s Desk', 'Furniture', 'Asset', 'pcs'),
(2, 'Student Armchair', 'Furniture', 'Asset', 'pcs'),
(3, 'Desktop Computer', 'Electronics', 'Asset', 'pcs'),
(4, 'Projector', 'Electronics', 'Asset', 'pcs'),
(5, 'Electric Fan', 'Appliances', 'Asset', 'pcs'),
(6, 'Air Conditioner', 'Appliances', 'Asset', 'pcs'),
(7, 'School Service Van', 'Vehicles', 'Asset', 'unit'),
(8, 'Microscope', 'Lab Equipment', 'Asset', 'pcs'),
(9, 'Bond Paper A4', 'Office Supply', 'Consumable', 'ream'),
(10, 'Ballpoint Pen (Blue)', 'Office Supply', 'Consumable', 'pcs'),
(11, 'Whiteboard Marker (Black)', 'Office Supply', 'Consumable', 'pcs'),
(12, 'Mop', 'Janitorial', 'Consumable', 'pcs'),
(13, 'Disinfectant', 'Janitorial', 'Consumable', 'gallon'),
(14, 'Printer Ink Epson 003 (Black)', 'Printing', 'Consumable', 'bottle'),
(15, 'Toner Cartridge HP 85A', 'Printing', 'Consumable', 'pcs'),
(16, 'First Aid Kit', 'Medical Supply', 'Consumable', 'set');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_procurement`
--

CREATE TABLE `bcp_sms4_procurement` (
  `procurement_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_procurement`
--

INSERT INTO `bcp_sms4_procurement` (`procurement_id`, `item_id`, `quantity`, `request_date`, `requested_by`, `approved_by`, `expected_date`, `reason`, `status`, `created_at`) VALUES
(1, 1, 5, '2025-09-01', 'Faculty Office', NULL, NULL, 'For new faculty computers', 'Completed', '2025-09-20 05:39:38'),
(2, 2, 20, '2025-09-02', 'Registrar', NULL, NULL, 'Replacement of broken chairs', 'Completed', '2025-09-20 05:39:38'),
(3, 3, 10, '2025-09-03', 'Library', NULL, NULL, 'Additional tables for study area', 'Pending', '2025-09-20 05:39:38'),
(4, 4, 2, '2025-09-05', 'IT Department', NULL, NULL, 'Backup projector units', 'Rejected', '2025-09-20 05:39:38'),
(5, 5, 50, '2025-09-06', 'Accounting', NULL, NULL, 'Monthly supply of bond paper', 'Rejected', '2025-09-20 05:39:38'),
(6, 6, 100, '2025-09-07', 'Faculty Office', NULL, NULL, 'Whiteboard markers for classrooms', 'Completed', '2025-09-20 05:39:38'),
(7, 7, 30, '2025-09-08', 'Clinic', NULL, NULL, 'Alcohol bottles for disinfection', 'Completed', '2025-09-20 05:39:38'),
(8, 8, 10, '2025-09-09', 'Admin Office', NULL, NULL, 'Printer ink cartridges', 'Completed', '2025-09-20 05:39:38'),
(9, 9, 50, '2025-09-10', 'Registrar', NULL, NULL, 'Printing of student forms', 'Completed', '2025-09-20 08:00:00'),
(10, 10, 200, '2025-09-11', 'Faculty Office', NULL, NULL, 'For classroom use', 'Completed', '2025-09-20 08:10:00'),
(11, 11, 100, '2025-09-12', 'Faculty Office', NULL, NULL, 'Refill for teachers', 'Completed', '2025-09-20 08:20:00'),
(12, 13, 10, '2025-09-13', 'Clinic', NULL, NULL, 'For cleaning and sanitation', 'Completed', '2025-09-20 08:30:00'),
(13, 14, 30, '2025-09-14', 'Admin Office', NULL, NULL, 'Printer ink refills', 'Completed', '2025-09-20 08:40:00'),
(14, 15, 15, '2025-09-15', 'Accounting', NULL, NULL, 'Toner replacement for reports', 'Completed', '2025-09-20 08:50:00'),
(15, 16, 5, '2025-09-16', 'Clinic', NULL, NULL, 'Restock of medical supplies', 'Completed', '2025-09-20 09:00:00'),
(21, 9, 5, '2025-09-20', 'Alice', 'Bob', '2025-09-25', 'Restock bond papers', 'Completed', '2025-09-20 06:41:46'),
(22, 10, 50, '2025-09-21', 'Charlie', 'Dana', '2025-09-26', 'Blue ballpoint pens for classroom', 'Completed', '2025-09-21 01:30:00'),
(23, 11, 20, '2025-09-22', 'Eve', NULL, '2025-09-28', 'Whiteboard markers for meetings', 'Completed', '2025-09-22 02:15:00'),
(24, 12, 10, '2025-09-23', 'Frank', 'Grace', '2025-09-29', 'Mops for janitorial team', 'Completed', '2025-09-23 03:20:00'),
(25, 13, 5, '2025-09-24', 'Heidi', 'Ivan', '2025-09-30', 'Disinfectants for sanitization', 'Completed', '2025-09-24 04:00:00'),
(26, 14, 3, '2025-09-25', 'Jack', 'Kate', '2025-10-01', 'Printer ink for office printers', 'Completed', '2025-09-25 00:45:00'),
(27, 15, 2, '2025-09-26', 'Leo', NULL, '2025-10-02', 'Toner cartridges for printer', 'Completed', '2025-09-26 01:15:00'),
(28, 16, 1, '2025-09-27', 'Mia', 'Nina', '2025-10-03', 'First aid kit for clinic', 'Completed', '2025-09-27 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_reports`
--

CREATE TABLE `bcp_sms4_reports` (
  `id` int(11) NOT NULL,
  `report_type` enum('Lost','Damaged','Repair/Replacement') NOT NULL,
  `reported_by` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `date_reported` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Pending','In-Progress','Resolved','Rejected') DEFAULT 'Pending',
  `description` text DEFAULT NULL,
  `evidence` varchar(255) DEFAULT NULL,
  `asset_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_reports`
--

INSERT INTO `bcp_sms4_reports` (`id`, `report_type`, `reported_by`, `assigned_to`, `date_reported`, `status`, `description`, `evidence`, `asset_id`) VALUES
(13, 'Lost', 2, NULL, '2025-09-23 17:35:36', 'In-Progress', 'aw', NULL, 5),
(14, 'Damaged', 2, NULL, '2025-09-20 09:39:11', 'Pending', 'aw', NULL, 7),
(15, 'Damaged', 2, NULL, '2025-09-23 17:35:41', 'Resolved', 'Sirang sira na body clock ko, jusko', NULL, 246),
(16, 'Repair/Replacement', 2, NULL, '2025-09-23 17:18:02', 'Pending', '', NULL, 46),
(17, 'Repair/Replacement', 2, NULL, '2025-09-23 17:35:45', 'Resolved', '', NULL, 198),
(18, 'Lost', 2, NULL, '2025-09-23 17:35:59', 'Pending', '', NULL, 7),
(19, 'Damaged', 2, NULL, '2025-09-23 17:36:04', 'Pending', '', NULL, 73),
(20, 'Repair/Replacement', 2, NULL, '2025-09-23 17:36:11', 'Pending', '', NULL, 165),
(21, 'Repair/Replacement', 2, NULL, '2025-09-23 18:49:50', 'Pending', '', NULL, 97);

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_scheduling`
--

CREATE TABLE `bcp_sms4_scheduling` (
  `id` int(11) NOT NULL,
  `asset` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `frequency` enum('Daily','Weekly','Monthly','Quarterly','Yearly') NOT NULL,
  `personnel` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Scheduled','In Progress','Completed','Overdue') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_scheduling`
--

INSERT INTO `bcp_sms4_scheduling` (`id`, `asset`, `type`, `frequency`, `personnel`, `start_date`, `created_at`, `status`) VALUES
(5, 'Air Conditioner', 'Cleaning', 'Weekly', 'Juan Delacruz', '2025-09-10', '2025-09-08 19:24:14', 'Scheduled'),
(6, 'Air Conditioner', 'Inspection', 'Weekly', 'sdsd', '2025-09-10', '2025-09-08 19:30:24', 'Scheduled'),
(7, 'Computer', 'Calibration', 'Monthly', 'Pat', '2025-09-12', '2025-09-09 08:58:11', 'Scheduled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bcp_sms4_activity`
--
ALTER TABLE `bcp_sms4_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_admins`
--
ALTER TABLE `bcp_sms4_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_adminsss`
--
ALTER TABLE `bcp_sms4_adminsss`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_asset`
--
ALTER TABLE `bcp_sms4_asset`
  ADD PRIMARY KEY (`asset_id`),
  ADD UNIQUE KEY `property_tag` (`property_tag`),
  ADD UNIQUE KEY `property_tag_2` (`property_tag`),
  ADD KEY `fk_asset_item` (`item_id`);

--
-- Indexes for table `bcp_sms4_asset_audit`
--
ALTER TABLE `bcp_sms4_asset_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `bcp_sms4_assign_history`
--
ALTER TABLE `bcp_sms4_assign_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_audit`
--
ALTER TABLE `bcp_sms4_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_audit_discrepancies`
--
ALTER TABLE `bcp_sms4_audit_discrepancies`
  ADD PRIMARY KEY (`discrepancy_id`);

--
-- Indexes for table `bcp_sms4_consumable`
--
ALTER TABLE `bcp_sms4_consumable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_consumable_item` (`item_id`);

--
-- Indexes for table `bcp_sms4_consumable_audit`
--
ALTER TABLE `bcp_sms4_consumable_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `consumable_id` (`consumable_id`);

--
-- Indexes for table `bcp_sms4_items`
--
ALTER TABLE `bcp_sms4_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `bcp_sms4_procurement`
--
ALTER TABLE `bcp_sms4_procurement`
  ADD PRIMARY KEY (`procurement_id`),
  ADD KEY `fk_procurement_item` (`item_id`);

--
-- Indexes for table `bcp_sms4_reports`
--
ALTER TABLE `bcp_sms4_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `fk_reports_asset` (`asset_id`);

--
-- Indexes for table `bcp_sms4_scheduling`
--
ALTER TABLE `bcp_sms4_scheduling`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bcp_sms4_activity`
--
ALTER TABLE `bcp_sms4_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bcp_sms4_admins`
--
ALTER TABLE `bcp_sms4_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bcp_sms4_adminsss`
--
ALTER TABLE `bcp_sms4_adminsss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bcp_sms4_asset`
--
ALTER TABLE `bcp_sms4_asset`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `bcp_sms4_asset_audit`
--
ALTER TABLE `bcp_sms4_asset_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bcp_sms4_assign_history`
--
ALTER TABLE `bcp_sms4_assign_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bcp_sms4_audit`
--
ALTER TABLE `bcp_sms4_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bcp_sms4_audit_discrepancies`
--
ALTER TABLE `bcp_sms4_audit_discrepancies`
  MODIFY `discrepancy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bcp_sms4_consumable`
--
ALTER TABLE `bcp_sms4_consumable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bcp_sms4_consumable_audit`
--
ALTER TABLE `bcp_sms4_consumable_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bcp_sms4_items`
--
ALTER TABLE `bcp_sms4_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bcp_sms4_procurement`
--
ALTER TABLE `bcp_sms4_procurement`
  MODIFY `procurement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `bcp_sms4_reports`
--
ALTER TABLE `bcp_sms4_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `bcp_sms4_scheduling`
--
ALTER TABLE `bcp_sms4_scheduling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bcp_sms4_asset`
--
ALTER TABLE `bcp_sms4_asset`
  ADD CONSTRAINT `fk_asset_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`);

--
-- Constraints for table `bcp_sms4_asset_audit`
--
ALTER TABLE `bcp_sms4_asset_audit`
  ADD CONSTRAINT `bcp_sms4_asset_audit_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `bcp_sms4_asset` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `bcp_sms4_consumable`
--
ALTER TABLE `bcp_sms4_consumable`
  ADD CONSTRAINT `fk_consumable_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`);

--
-- Constraints for table `bcp_sms4_consumable_audit`
--
ALTER TABLE `bcp_sms4_consumable_audit`
  ADD CONSTRAINT `bcp_sms4_consumable_audit_ibfk_1` FOREIGN KEY (`consumable_id`) REFERENCES `bcp_sms4_consumable` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bcp_sms4_procurement`
--
ALTER TABLE `bcp_sms4_procurement`
  ADD CONSTRAINT `fk_procurement_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`);

--
-- Constraints for table `bcp_sms4_reports`
--
ALTER TABLE `bcp_sms4_reports`
  ADD CONSTRAINT `bcp_sms4_reports_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `bcp_sms4_adminsss` (`id`),
  ADD CONSTRAINT `bcp_sms4_reports_ibfk_7` FOREIGN KEY (`reported_by`) REFERENCES `bcp_sms4_adminsss` (`id`),
  ADD CONSTRAINT `fk_reports_asset` FOREIGN KEY (`asset_id`) REFERENCES `bcp_sms4_asset` (`asset_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
