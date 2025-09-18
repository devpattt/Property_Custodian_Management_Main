/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `bcp_sms4_activity`;
CREATE TABLE `bcp_sms4_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_admins`;
CREATE TABLE `bcp_sms4_admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','teacher','custodian') COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_asset`;
CREATE TABLE `bcp_sms4_asset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_tag` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `active` int NOT NULL,
  `in_repair` int NOT NULL,
  `disposed` int NOT NULL,
  `purchase_date` date NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_assign_history`;
CREATE TABLE `bcp_sms4_assign_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `equipment_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `equipment_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `custodian_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custodian_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `department_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `assigned_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_general_ci,
  `assigned_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_consumable`;
CREATE TABLE `bcp_sms4_consumable` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_tag` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `box` int NOT NULL,
  `quantity` int NOT NULL,
  `expiration` date NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_reports`;
CREATE TABLE `bcp_sms4_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `report_type` enum('Lost','Damaged','Repair/Replacement') COLLATE utf8mb4_general_ci NOT NULL,
  `reported_by` int NOT NULL,
  `assigned_to` int DEFAULT NULL,
  `date_reported` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('Pending','In-Progress','Resolved','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `description` text COLLATE utf8mb4_general_ci,
  `evidence` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reported_by` (`reported_by`),
  KEY `assigned_to` (`assigned_to`),
  CONSTRAINT `bcp_sms4_reports_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `bcp_sms4_admins` (`id`),
  CONSTRAINT `bcp_sms4_reports_ibfk_7` FOREIGN KEY (`reported_by`) REFERENCES `bcp_sms4_admins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_scheduling`;
CREATE TABLE `bcp_sms4_scheduling` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `frequency` enum('Daily','Weekly','Monthly','Quarterly','Yearly') COLLATE utf8mb4_general_ci NOT NULL,
  `personnel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Scheduled','In Progress','Completed','Overdue') COLLATE utf8mb4_general_ci DEFAULT 'Scheduled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `bcp_sms4_admins` (`id`, `user_type`, `username`, `fullname`, `password`, `email`) VALUES
(2, 'custodian', 'Senka', 'Senka', '$2y$10$WHJtGH2A7l3ZEKron3u.WOT6zUR6bGHGhssU0vIWqeo/pm7yUTxma', 'aceplayer81218@gmail.com'),
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com');
INSERT INTO `bcp_sms4_asset` (`id`, `asset_tag`, `name`, `category`, `quantity`, `active`, `in_repair`, `disposed`, `purchase_date`, `created_at`) VALUES
(1, 'ASSET-2025-0001', 'Table', 'Furniture', 2, 1, 1, 0, '2025-09-04', '0000-00-00'),
(2, 'ASSET-2025-0002', 'yuyu', 'yuyuy', 65, 0, 0, 0, '0000-00-00', '2025-09-12'),
(3, 'ASSET-2025-0003', 'marijana', 'shabu', 5, 0, 0, 0, '0000-00-00', '2025-09-12'),
(4, 'ASSET-2025-0004', 'marijana', 'fresh', 2356, 0, 0, 0, '0000-00-00', '2025-09-12');
INSERT INTO `bcp_sms4_assign_history` (`id`, `reference_no`, `equipment_id`, `equipment_name`, `quantity`, `custodian_id`, `custodian_name`, `department_code`, `assigned_date`, `end_date`, `remarks`, `assigned_by`) VALUES
(15, 'REF-20250912-4BE7C', '1234', 'Table', 1, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:39:15', NULL, 'Ewan ko | Merged on 2025-09-12 21:24:33', 'admin'),
(20, 'REF-20250912-6B465', '42424', 'Table', 5, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:58', NULL, 'sdfgdsg', 'admin'),
(21, 'REF-20250912-6B3A7', '42424', 'Table', 2, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:51:23', NULL, 'ISA PA TANGINA MO KA', 'admin'),
(22, 'REF-20250912-5F88A', 'REF-20250912-6B3A7', 'Table', 3, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:04', NULL, 'iuadghajidgn', 'admin');
INSERT INTO `bcp_sms4_consumable` (`id`, `asset_tag`, `name`, `category`, `box`, `quantity`, `expiration`, `add_date`) VALUES
(1, 'ASSET-2025-5815', 'Paints', 'Shabu', 4, 5, '2026-09-11', '2025-09-11 23:26:30'),
(3, 'ASSET-2025-7124', 'marijanads', 'sdsds', 6, 6, '2025-09-20', '2025-09-12 00:22:20'),
(4, 'ASSET-2025-216E', 'marijana', '232323', 5, 5, '2025-09-27', '2025-09-12 00:22:55'),
(5, 'ASSET-2025-3E4E', 'ewe', 'ewew', 2, 2, '2025-09-20', '2025-09-12 00:24:15'),
(7, 'ASSET-2025-5779', 'yyy', 'yy', 4, 4, '2025-09-27', '2025-09-12 00:30:04');
INSERT INTO `bcp_sms4_reports` (`id`, `asset`, `report_type`, `reported_by`, `assigned_to`, `date_reported`, `status`, `description`, `evidence`) VALUES
(1, 'Projector - Room 201', 'Lost', 3, 2, '2025-09-16 21:30:04', 'Resolved', 'Projector went missing after class.', NULL),
(2, 'Library Chair - ID123', 'Damaged', 3, 2, '2025-09-17 06:04:23', 'Resolved', 'Chair leg broken, unsafe to use.', 'chair_damage.jpg'),
(3, 'Laptop - SN456', 'Repair/Replacement', 3, NULL, '2025-09-03 00:00:00', 'Resolved', 'Laptop not booting, replaced with new unit.', NULL),
(4, 'Whiteboard Marker Set', 'Lost', 3, NULL, '2025-09-05 00:00:00', 'Rejected', 'Reported lost, later found.', NULL),
(5, 'Projector - Room 201', 'Lost', 3, NULL, '2025-09-01 00:00:00', 'Pending', 'Projector went missing after class.', NULL),
(6, 'Library Chair', 'Damaged', 3, 2, '2025-09-16 21:29:43', 'Pending', 'One leg is broken and unstable.', NULL),
(7, 'Whiteboard Marker Set', 'Lost', 3, 2, '2025-09-16 21:29:43', 'Pending', 'Reported lost, later found.', NULL),
(8, 'Air Conditioner', '', 3, 2, '2025-09-16 21:29:43', 'Pending', 'Not cooling properly, needs replacement.', NULL),
(9, 'Fire Extinguisher', 'Damaged', 3, 2, '2025-09-08 00:00:00', 'Resolved', 'Pressure gauge is broken.', NULL),
(10, 'aw', 'Damaged', 2, 2, '2025-09-15 10:15:06', 'Resolved', 'aa', NULL),
(11, 'aw', 'Lost', 2, 2, '2025-09-15 10:15:10', 'Resolved', 'aw', NULL),
(12, 'Marijuana', 'Repair/Replacement', 2, 2, '2025-09-15 10:15:13', 'Resolved', 'aw', NULL);
INSERT INTO `bcp_sms4_scheduling` (`id`, `asset`, `type`, `frequency`, `personnel`, `start_date`, `created_at`, `status`) VALUES
(5, 'Air Conditioner', 'Cleaning', 'Weekly', 'Juan Delacruz', '2025-09-10', '2025-09-09 03:24:14', 'Scheduled'),
(6, 'Air Conditioner', 'Inspection', 'Weekly', 'sdsd', '2025-09-10', '2025-09-09 03:30:24', 'Scheduled'),
(7, 'Computer', 'Calibration', 'Monthly', 'Pat', '2025-09-12', '2025-09-09 16:58:11', 'Scheduled');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;