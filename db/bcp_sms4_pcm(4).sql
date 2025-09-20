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
  `asset_id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `property_tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('In-Use','In-Storage','Damaged','Disposed','Lost') COLLATE utf8mb4_general_ci DEFAULT 'In-Use',
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `assigned_to` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_registered` date NOT NULL,
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `property_tag` (`property_tag`),
  UNIQUE KEY `property_tag_2` (`property_tag`),
  KEY `fk_asset_item` (`item_id`),
  CONSTRAINT `fk_asset_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_asset_audit`;
CREATE TABLE `bcp_sms4_asset_audit` (
  `audit_id` int NOT NULL AUTO_INCREMENT,
  `asset_id` int NOT NULL,
  `audit_date` date NOT NULL,
  `auditor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `findings` text COLLATE utf8mb4_general_ci,
  `result` enum('Match','Mismatch','Pending') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `bcp_sms4_asset_audit_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `bcp_sms4_asset` (`asset_id`) ON DELETE CASCADE
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

DROP TABLE IF EXISTS `bcp_sms4_audit`;
CREATE TABLE `bcp_sms4_audit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `audit_date` date NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `custodian` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Scheduled','Ongoing','Completed') COLLATE utf8mb4_general_ci DEFAULT 'Scheduled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_audit_discrepancies`;
CREATE TABLE `bcp_sms4_audit_discrepancies` (
  `discrepancy_id` int NOT NULL AUTO_INCREMENT,
  `audit_type` enum('Asset','Consumable') COLLATE utf8mb4_general_ci NOT NULL,
  `audit_id` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `resolved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`discrepancy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_consumable`;
CREATE TABLE `bcp_sms4_consumable` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `status` enum('Available','Low-Stock','Out-of-Stock','Expired') COLLATE utf8mb4_general_ci DEFAULT 'Available',
  `expiration` date NOT NULL,
  `date_received` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_consumable_item` (`item_id`),
  CONSTRAINT `fk_consumable_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_consumable_audit`;
CREATE TABLE `bcp_sms4_consumable_audit` (
  `audit_id` int NOT NULL AUTO_INCREMENT,
  `consumable_id` int NOT NULL,
  `audit_date` date NOT NULL,
  `auditor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `findings` text COLLATE utf8mb4_general_ci,
  `result` enum('OK','Expired','Low Stock','Mismatch','Pending') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_id`),
  KEY `consumable_id` (`consumable_id`),
  CONSTRAINT `bcp_sms4_consumable_audit_ibfk_1` FOREIGN KEY (`consumable_id`) REFERENCES `bcp_sms4_consumable` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_items`;
CREATE TABLE `bcp_sms4_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `item_type` enum('Asset','Consumable') NOT NULL,
  `unit` varchar(50) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `bcp_sms4_procurement`;
CREATE TABLE `bcp_sms4_procurement` (
  `procurement_id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `request_date` date NOT NULL,
  `requested_by` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `approved_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `reason` text COLLATE utf8mb4_general_ci,
  `status` enum('Pending','Approved','Rejected','Completed') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`procurement_id`),
  KEY `fk_procurement_item` (`item_id`),
  CONSTRAINT `fk_procurement_item` FOREIGN KEY (`item_id`) REFERENCES `bcp_sms4_items` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `bcp_sms4_reports`;
CREATE TABLE `bcp_sms4_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_type` enum('Lost','Damaged','Repair/Replacement') COLLATE utf8mb4_general_ci NOT NULL,
  `reported_by` int NOT NULL,
  `assigned_to` int DEFAULT NULL,
  `date_reported` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('Pending','In-Progress','Resolved','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `description` text COLLATE utf8mb4_general_ci,
  `evidence` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asset_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reported_by` (`reported_by`),
  KEY `assigned_to` (`assigned_to`),
  KEY `fk_reports_asset` (`asset_id`),
  CONSTRAINT `bcp_sms4_reports_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `bcp_sms4_admins` (`id`),
  CONSTRAINT `bcp_sms4_reports_ibfk_7` FOREIGN KEY (`reported_by`) REFERENCES `bcp_sms4_admins` (`id`),
  CONSTRAINT `fk_reports_asset` FOREIGN KEY (`asset_id`) REFERENCES `bcp_sms4_asset` (`asset_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'teacher', 'Senka', 'Senka', '$2y$10$WHJtGH2A7l3ZEKron3u.WOT6zUR6bGHGhssU0vIWqeo/pm7yUTxma', 'aceplayer81218@gmail.com'),
(3, 'admin', 'Admin', 'Administrator', '$2y$10$nP8qd/wMRvtqHtc.ukF5d.zkDOQK9DJKLo.p4RaP3EgAMezJkwewS', 'notyujisandesu2@gmail.com');
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
(211, 14, 'ASSET-2025-0207', 'In-Use', NULL, NULL, '2025-09-20');

INSERT INTO `bcp_sms4_assign_history` (`id`, `reference_no`, `equipment_id`, `equipment_name`, `quantity`, `custodian_id`, `custodian_name`, `department_code`, `assigned_date`, `end_date`, `remarks`, `assigned_by`) VALUES
(15, 'REF-20250912-4BE7C', '1234', 'Table', 1, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:39:15', NULL, 'Ewan ko | Merged on 2025-09-12 21:24:33', 'admin'),
(20, 'REF-20250912-6B465', '42424', 'Table', 5, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:58', NULL, 'sdfgdsg', 'admin'),
(21, 'REF-20250912-6B3A7', '42424', 'Table', 2, 'EMP0069', 'Patrick', 'Information Technology', '2025-09-12 21:51:23', NULL, 'ISA PA TANGINA MO KA', 'admin'),
(22, 'REF-20250912-5F88A', 'REF-20250912-6B3A7', 'Table', 3, 'EMP010', 'Isabella Thomas', 'Hospitality Management', '2025-09-12 22:09:04', NULL, 'iuadghajidgn', 'admin');
INSERT INTO `bcp_sms4_audit` (`id`, `audit_date`, `department`, `custodian`, `status`) VALUES
(1, '2025-09-30', 'IT', 'Custodian 1', 'Ongoing'),
(2, '2025-09-26', 'IT', 'Custodian 2', 'Ongoing'),
(3, '2025-09-30', 'IT', 'Custodian 3', 'Ongoing'),
(4, '2025-09-22', 'Information Technology', 'Custodian 4', 'Scheduled'),
(5, '2025-09-29', 'Information Technology', 'Custodian 5', 'Completed');

INSERT INTO `bcp_sms4_consumable` (`id`, `item_id`, `unit`, `quantity`, `status`, `expiration`, `date_received`) VALUES
(10, 11, 'pcs', 100, 'Available', '2026-09-20', '2025-09-20 14:41:46');

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
INSERT INTO `bcp_sms4_procurement` (`procurement_id`, `item_id`, `quantity`, `request_date`, `requested_by`, `approved_by`, `expected_date`, `reason`, `status`, `created_at`) VALUES
(1, 1, 5, '2025-09-01', 'Faculty Office', NULL, NULL, 'For new faculty computers', 'Completed', '2025-09-20 13:39:38'),
(2, 2, 20, '2025-09-02', 'Registrar', NULL, NULL, 'Replacement of broken chairs', 'Completed', '2025-09-20 13:39:38'),
(3, 3, 10, '2025-09-03', 'Library', NULL, NULL, 'Additional tables for study area', 'Completed', '2025-09-20 13:39:38'),
(4, 4, 2, '2025-09-05', 'IT Department', NULL, NULL, 'Backup projector units', 'Completed', '2025-09-20 13:39:38'),
(5, 5, 50, '2025-09-06', 'Accounting', NULL, NULL, 'Monthly supply of bond paper', 'Completed', '2025-09-20 13:39:38'),
(6, 6, 100, '2025-09-07', 'Faculty Office', NULL, NULL, 'Whiteboard markers for classrooms', 'Completed', '2025-09-20 13:39:38'),
(7, 7, 30, '2025-09-08', 'Clinic', NULL, NULL, 'Alcohol bottles for disinfection', 'Completed', '2025-09-20 13:39:38'),
(8, 8, 10, '2025-09-09', 'Admin Office', NULL, NULL, 'Printer ink cartridges', 'Completed', '2025-09-20 13:39:38'),
(9, 9, 50, '2025-09-10', 'Registrar', NULL, NULL, 'Printing of student forms', 'Completed', '2025-09-20 16:00:00'),
(10, 10, 200, '2025-09-11', 'Faculty Office', NULL, NULL, 'For classroom use', 'Completed', '2025-09-20 16:10:00'),
(11, 11, 100, '2025-09-12', 'Faculty Office', NULL, NULL, 'Refill for teachers', 'Completed', '2025-09-20 16:20:00'),
(12, 13, 10, '2025-09-13', 'Clinic', NULL, NULL, 'For cleaning and sanitation', 'Completed', '2025-09-20 16:30:00'),
(13, 14, 30, '2025-09-14', 'Admin Office', NULL, NULL, 'Printer ink refills', 'Completed', '2025-09-20 16:40:00'),
(14, 15, 15, '2025-09-15', 'Accounting', NULL, NULL, 'Toner replacement for reports', 'Completed', '2025-09-20 16:50:00'),
(15, 16, 5, '2025-09-16', 'Clinic', NULL, NULL, 'Restock of medical supplies', 'Completed', '2025-09-20 17:00:00');
INSERT INTO `bcp_sms4_reports` (`id`, `report_type`, `reported_by`, `assigned_to`, `date_reported`, `status`, `description`, `evidence`, `asset_id`) VALUES
(13, 'Lost', 2, NULL, '2025-09-20 17:36:23', 'Pending', 'aw', NULL, 5),
(14, 'Damaged', 2, NULL, '2025-09-20 17:39:11', 'Pending', 'aw', NULL, 7);
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