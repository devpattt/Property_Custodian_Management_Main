-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 05:59 PM
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
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_asset`
--

CREATE TABLE `bcp_sms4_asset` (
  `id` int(11) NOT NULL,
  `asset_tag` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `in_repair` int(11) NOT NULL,
  `disposed` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_asset`
--

INSERT INTO `bcp_sms4_asset` (`id`, `asset_tag`, `name`, `category`, `quantity`, `active`, `in_repair`, `disposed`, `purchase_date`, `created_at`) VALUES
(1, 'ASSET-2025-0001', 'Table', 'Furniture', 2, 1, 1, 0, '2025-09-04', '0000-00-00'),
(2, 'ASSET-2025-0002', 'yuyu', 'yuyuy', 65, 0, 0, 0, '0000-00-00', '2025-09-12'),
(3, 'ASSET-2025-0003', 'marijana', 'shabu', 5, 0, 0, 0, '0000-00-00', '2025-09-12'),
(4, 'ASSET-2025-0004', 'marijana', 'fresh', 2356, 0, 0, 0, '0000-00-00', '2025-09-12');

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
-- Table structure for table `bcp_sms4_consumable`
--

CREATE TABLE `bcp_sms4_consumable` (
  `id` int(11) NOT NULL,
  `asset_tag` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `box` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiration` date NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_consumable`
--

INSERT INTO `bcp_sms4_consumable` (`id`, `asset_tag`, `name`, `category`, `box`, `quantity`, `expiration`, `add_date`) VALUES
(1, 'ASSET-2025-5815', 'Paints', 'Shabu', 4, 5, '2026-09-11', '2025-09-11 23:26:30'),
(3, 'ASSET-2025-7124', 'marijanads', 'sdsds', 6, 6, '2025-09-20', '2025-09-12 00:22:20'),
(4, 'ASSET-2025-216E', 'marijana', '232323', 5, 5, '2025-09-27', '2025-09-12 00:22:55'),
(5, 'ASSET-2025-3E4E', 'ewe', 'ewew', 2, 2, '2025-09-20', '2025-09-12 00:24:15'),
(7, 'ASSET-2025-5779', 'yyy', 'yy', 4, 4, '2025-09-27', '2025-09-12 00:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `bcp_sms4_reports`
--

CREATE TABLE `bcp_sms4_reports` (
  `id` int(11) NOT NULL,
  `asset` varchar(255) NOT NULL,
  `report_type` enum('Lost','Damaged','Repair/Replacement') NOT NULL,
  `reported_by` varchar(100) NOT NULL,
  `date_reported` date NOT NULL,
  `status` enum('Pending','In Progress','Resolved','Rejected') DEFAULT 'Pending',
  `description` text DEFAULT NULL,
  `evidence` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_sms4_reports`
--

INSERT INTO `bcp_sms4_reports` (`id`, `asset`, `report_type`, `reported_by`, `date_reported`, `status`, `description`, `evidence`) VALUES
(1, 'Projector - Room 201', 'Lost', 'Teacher A', '2025-09-01', 'Pending', 'Projector went missing after class.', NULL),
(2, 'Library Chair - ID123', 'Damaged', 'Teacher B', '2025-09-02', 'In Progress', 'Chair leg broken, unsafe to use.', 'chair_damage.jpg'),
(3, 'Laptop - SN456', 'Repair/Replacement', 'Teacher C', '2025-09-03', 'Resolved', 'Laptop not booting, replaced with new unit.', NULL),
(4, 'Whiteboard Marker Set', 'Lost', 'Teacher D', '2025-09-05', 'Rejected', 'Reported lost, later found.', NULL),
(5, 'Projector - Room 201', 'Lost', 'Teacher A', '2025-09-01', NULL, 'Projector went missing after class.', NULL),
(6, 'Library Chair', 'Damaged', 'Custodian 1', '2025-09-03', NULL, 'One leg is broken and unstable.', NULL),
(7, 'Whiteboard Marker Set', 'Lost', 'Teacher D', '2025-09-05', NULL, 'Reported lost, later found.', NULL),
(8, 'Air Conditioner', '', 'Teacher B', '2025-09-07', NULL, 'Not cooling properly, needs replacement.', NULL),
(9, 'Fire Extinguisher', 'Damaged', 'Custodian 2', '2025-09-08', NULL, 'Pressure gauge is broken.', NULL);

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
-- Indexes for table `bcp_sms4_asset`
--
ALTER TABLE `bcp_sms4_asset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_assign_history`
--
ALTER TABLE `bcp_sms4_assign_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_consumable`
--
ALTER TABLE `bcp_sms4_consumable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcp_sms4_reports`
--
ALTER TABLE `bcp_sms4_reports`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bcp_sms4_asset`
--
ALTER TABLE `bcp_sms4_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bcp_sms4_assign_history`
--
ALTER TABLE `bcp_sms4_assign_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bcp_sms4_consumable`
--
ALTER TABLE `bcp_sms4_consumable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bcp_sms4_reports`
--
ALTER TABLE `bcp_sms4_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bcp_sms4_scheduling`
--
ALTER TABLE `bcp_sms4_scheduling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
