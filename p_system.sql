-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 12:53 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `p_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$0iLpIaZwCvyVNIz9PLktLuBuhDf4kmXnLXpkmYV1iZsi7zfLVEI5m', '2024-11-18 21:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `Id` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Carname` varchar(15) NOT NULL,
  `Brand` varchar(15) NOT NULL,
  `plate` varchar(7) NOT NULL,
  `Color` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`Id`, `Username`, `Carname`, `Brand`, `plate`, `Color`) VALUES
(2, 'user', 'Carnival', 'KIA', '0100-AA', 'Black'),
(3, 'hh', 'Lumina', 'Chevy', '1999-CH', 'White'),
(4, 'user', 'Crssida', 'Toyota', '1011-BA', 'White');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `Id` int(11) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `Emailaddress` varchar(255) NOT NULL,
  `Telephone` varchar(15) DEFAULT NULL,
  `Complaint` text NOT NULL,
  `date_field` date DEFAULT curdate(),
  `state` enum('solved','unsolved') DEFAULT 'unsolved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`Id`, `Fullname`, `Emailaddress`, `Telephone`, `Complaint`, `date_field`, `state`) VALUES
(0, 'komail habib', 'user1@gmail.com', '0505501322', 'Someone takes my parking.', '2024-12-10', 'solved'),
(1, 'Saleh Jassem', 'salehjay@gmail.com', '0505661033', 'There is a blocked parking space.', '2024-10-24', 'solved'),
(3, 'komail habib', 'user1@gmail.com', '0505501322', 'Someone takes my parking.', '2024-12-06', 'unsolved'),
(4, 'Hassan Saleh', 'HassanSal@hotmail.com', '0501124256', 'Someone takes two Parkings.', '2024-10-24', 'unsolved'),
(5, 'Mohammed Ali', 'MuAli@gamil.com', '0566221050', 'Someone hit my car.', '2024-10-26', 'unsolved');

-- --------------------------------------------------------

--
-- Table structure for table `operation_records`
--

CREATE TABLE `operation_records` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `operation_type` enum('booking','auto release','release','release by admin') NOT NULL,
  `operation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `parking_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `operation_records`
--

INSERT INTO `operation_records` (`record_id`, `user_id`, `amount`, `operation_type`, `operation_date`, `parking_id`) VALUES
(3, 1, '0.00', 'auto release', '2024-12-10 18:29:27', 0),
(4, 1, '0.00', 'auto release', '2024-12-10 18:30:31', 0),
(5, 1, '0.00', 'release', '2024-12-10 18:31:14', 0),
(6, 1, '0.00', 'booking', '2024-12-10 18:32:30', 0),
(7, 1, '0.00', 'release', '2024-12-10 18:32:38', 0),
(8, 1, '5.00', 'booking', '2024-12-10 18:33:18', 0),
(9, 1, '0.00', 'release', '2024-12-10 18:33:25', 0),
(10, 1, '5.00', 'booking', '2024-12-10 18:36:04', 0),
(11, 1, '0.00', 'auto release', '2024-12-10 18:37:06', 0),
(12, 1, '5.00', 'booking', '2024-12-10 18:37:18', 0),
(13, 1, '0.00', 'auto release', '2024-12-10 18:38:56', 0),
(14, 1, '5.00', 'booking', '2024-12-10 18:39:09', 0),
(15, 1, '0.00', 'auto release', '2024-12-10 18:39:09', 0),
(16, 1, '50.00', 'booking', '2024-12-10 18:39:26', 0),
(17, 1, '0.00', 'auto release', '2024-12-10 18:39:26', 0),
(18, 1, '10.00', 'booking', '2024-12-10 18:39:38', 0),
(19, 1, '0.00', 'auto release', '2024-12-10 18:39:38', 0),
(20, 1, '10.00', 'booking', '2024-12-10 18:40:06', 0),
(21, 1, '0.00', 'release', '2024-12-10 18:40:17', 0),
(22, 1, '50.00', 'booking', '2024-12-10 18:40:22', 0),
(23, 1, '100.00', 'booking', '2024-12-10 18:40:44', 0),
(24, 1, '0.00', 'release', '2024-12-10 18:41:20', 0),
(25, 1, '0.00', 'release', '2024-12-10 18:41:23', 0),
(26, 1, '5.00', 'booking', '2024-12-10 18:50:25', 11),
(27, 1, '0.00', 'release', '2024-12-10 18:50:41', 0),
(28, 1, '5.00', 'booking', '2024-12-10 18:57:09', 3),
(29, 1, '0.00', 'release', '2024-12-10 18:57:17', 3),
(30, 1, '5.00', 'booking', '2024-12-10 18:57:26', 5),
(31, 1, '0.00', 'auto release', '2024-12-10 18:58:57', 5),
(32, 8, '10.00', 'booking', '2024-12-10 18:59:43', 10),
(33, 8, '0.00', 'release', '2024-12-10 19:00:11', 10),
(34, 8, '10.00', 'booking', '2024-12-10 20:25:25', 11),
(35, 8, '0.00', '', '2024-12-10 20:29:05', 11),
(36, 8, '10.00', 'booking', '2024-12-10 20:31:26', 11),
(37, 6, '10.00', 'booking', '2024-12-10 20:31:37', 12),
(38, 8, '0.00', 'release by admin', '2024-12-10 20:32:49', 11),
(39, 6, '0.00', 'release by admin', '2024-12-10 20:32:52', 12),
(40, 1, '5.00', 'booking', '2024-12-11 00:16:18', 11),
(80, 1, '0.00', 'auto release', '2024-12-11 00:19:06', 12),
(81, 1, '5.00', 'booking', '2024-12-11 00:19:14', 11),
(82, 1, '0.00', 'release', '2024-12-11 00:19:17', 11),
(83, 1, '5.00', 'booking', '2024-12-11 00:19:21', 12),
(84, 1, '0.00', 'release', '2024-12-11 00:19:24', 12),
(85, 1, '5.00', 'booking', '2024-12-11 00:53:13', 301),
(86, 1, '0.00', 'auto release', '2024-12-11 00:55:11', 301),
(87, 1, '5.00', 'booking', '2024-12-11 00:55:15', 43),
(88, 1, '0.00', 'release', '2024-12-11 00:55:20', 43),
(89, 1, '5.00', 'booking', '2024-12-11 00:55:26', 323),
(90, 1, '0.00', 'release', '2024-12-11 00:55:39', 323),
(91, 1, '5.00', 'booking', '2024-12-11 00:57:13', 332),
(92, 1, '0.00', 'auto release', '2024-12-11 00:58:44', 332),
(93, 1, '5.00', 'booking', '2024-12-11 00:58:49', 340),
(94, 1, '0.00', 'release', '2024-12-11 00:58:55', 340),
(95, 1, '5.00', 'booking', '2024-12-11 01:02:30', 365),
(96, 1, '0.00', 'auto release', '2024-12-11 01:03:37', 365),
(97, 1, '5.00', 'booking', '2024-12-11 01:05:23', 387),
(98, 1, '0.00', 'auto release', '2024-12-11 01:07:30', 387),
(99, 1, '5.00', 'booking', '2024-12-11 01:07:35', 388),
(100, 1, '5.00', 'booking', '2024-12-11 01:07:40', 401),
(101, 1, '0.00', 'auto release', '2024-12-11 01:08:36', 388),
(102, 1, '5.00', 'booking', '2024-12-11 01:08:37', 410),
(103, 1, '0.00', 'auto release', '2024-12-11 01:08:40', 401),
(104, 1, '5.00', 'booking', '2024-12-11 01:08:41', 402),
(105, 1, '0.00', 'release by admin', '2024-12-11 01:09:09', 410),
(106, 1, '0.00', 'auto release', '2024-12-11 01:09:49', 402),
(107, 1, '5.00', 'booking', '2024-12-11 02:40:52', 400),
(108, 1, '0.00', 'auto release', '2024-12-11 02:42:46', 400),
(109, 1, '5.00', 'booking', '2024-12-17 12:02:15', 6),
(110, 1, '0.00', 'auto release', '2024-12-17 12:04:43', 6),
(111, 1, '5.00', 'booking', '2024-12-18 23:41:48', 11),
(112, 1, '0.00', 'auto release', '2024-12-18 23:42:55', 11);

-- --------------------------------------------------------

--
-- Table structure for table `parkings`
--

CREATE TABLE `parkings` (
  `parking_id` int(11) NOT NULL,
  `id_sen` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `parking_number` int(11) NOT NULL,
  `status` enum('available','booked') DEFAULT 'available',
  `status_light` enum('0','1') DEFAULT '0',
  `booking_time` int(11) NOT NULL DEFAULT 0,
  `booked_by` int(11) DEFAULT NULL,
  `expiry_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parkings`
--

INSERT INTO `parkings` (`parking_id`, `id_sen`, `floor`, `parking_number`, `status`, `status_light`, `booking_time`, `booked_by`, `expiry_time`) VALUES
(1, 1001, 1, 1, 'available', '0', 0, NULL, NULL),
(2, 1002, 1, 2, 'available', '0', 0, NULL, NULL),
(3, 1003, 1, 3, 'available', '0', 0, NULL, NULL),
(4, 1004, 1, 4, 'available', '0', 0, NULL, NULL),
(5, 1005, 1, 5, 'available', '0', 0, NULL, NULL),
(6, 1006, 1, 6, 'available', '0', 0, NULL, NULL),
(7, 1007, 1, 7, 'available', '0', 0, NULL, NULL),
(8, 1008, 1, 8, 'available', '0', 0, NULL, NULL),
(9, 1009, 1, 9, 'available', '0', 0, NULL, NULL),
(10, 1010, 1, 10, 'available', '0', 0, NULL, NULL),
(11, 1011, 1, 11, 'available', '0', 0, NULL, NULL),
(12, 1012, 1, 12, 'available', '0', 0, NULL, NULL),
(13, 1013, 1, 13, 'available', '0', 0, NULL, NULL),
(14, 1014, 1, 14, 'available', '0', 0, NULL, NULL),
(15, 1015, 1, 15, 'available', '0', 0, NULL, NULL),
(16, 1016, 1, 16, 'available', '0', 0, NULL, NULL),
(17, 1017, 1, 17, 'available', '0', 0, NULL, NULL),
(18, 1018, 1, 18, 'available', '0', 0, NULL, NULL),
(19, 1019, 1, 19, 'available', '0', 0, NULL, NULL),
(20, 1020, 1, 20, 'available', '0', 0, NULL, NULL),
(21, 1021, 1, 21, 'available', '0', 0, NULL, NULL),
(22, 1022, 1, 22, 'available', '0', 0, NULL, NULL),
(23, 1023, 1, 23, 'available', '0', 0, NULL, NULL),
(24, 1024, 1, 24, 'available', '0', 0, NULL, NULL),
(25, 1025, 1, 25, 'available', '0', 0, NULL, NULL),
(26, 1026, 1, 26, 'available', '0', 0, NULL, NULL),
(27, 1027, 1, 27, 'available', '0', 0, NULL, NULL),
(28, 1028, 1, 28, 'available', '0', 0, NULL, NULL),
(29, 1029, 1, 29, 'available', '0', 0, NULL, NULL),
(30, 1030, 1, 30, 'available', '0', 0, NULL, NULL),
(31, 1031, 1, 31, 'available', '0', 0, NULL, NULL),
(32, 1032, 1, 32, 'available', '0', 0, NULL, NULL),
(33, 1033, 1, 33, 'available', '0', 0, NULL, NULL),
(34, 1034, 1, 34, 'available', '0', 0, NULL, NULL),
(35, 1035, 1, 35, 'available', '0', 0, NULL, NULL),
(36, 1036, 1, 36, 'available', '0', 0, NULL, NULL),
(37, 1037, 1, 37, 'available', '0', 0, NULL, NULL),
(38, 1038, 1, 38, 'available', '0', 0, NULL, NULL),
(39, 1039, 1, 39, 'available', '0', 0, NULL, NULL),
(40, 1040, 1, 40, 'available', '0', 0, NULL, NULL),
(41, 1041, 1, 41, 'available', '0', 0, NULL, NULL),
(42, 1042, 1, 42, 'available', '0', 0, NULL, NULL),
(43, 1043, 1, 43, 'available', '0', 0, NULL, NULL),
(44, 1044, 1, 44, 'available', '0', 0, NULL, NULL),
(45, 1045, 1, 45, 'available', '0', 0, NULL, NULL),
(46, 1046, 1, 46, 'available', '0', 0, NULL, NULL),
(47, 1047, 1, 47, 'available', '0', 0, NULL, NULL),
(48, 1048, 1, 48, 'available', '0', 0, NULL, NULL),
(49, 1049, 1, 49, 'available', '0', 0, NULL, NULL),
(50, 1050, 1, 50, 'available', '0', 0, NULL, NULL),
(51, 1051, 1, 51, 'available', '0', 0, NULL, NULL),
(52, 1052, 1, 52, 'available', '0', 0, NULL, NULL),
(53, 1053, 1, 53, 'available', '0', 0, NULL, NULL),
(54, 1054, 1, 54, 'available', '0', 0, NULL, NULL),
(55, 1055, 1, 55, 'available', '0', 0, NULL, NULL),
(56, 1056, 1, 56, 'available', '0', 0, NULL, NULL),
(57, 1057, 1, 57, 'available', '0', 0, NULL, NULL),
(58, 1058, 1, 58, 'available', '0', 0, NULL, NULL),
(59, 1059, 1, 59, 'available', '0', 0, NULL, NULL),
(60, 1060, 1, 60, 'available', '0', 0, NULL, NULL),
(61, 1061, 1, 61, 'available', '0', 0, NULL, NULL),
(62, 1062, 1, 62, 'available', '0', 0, NULL, NULL),
(63, 1063, 1, 63, 'available', '0', 0, NULL, NULL),
(64, 1064, 1, 64, 'available', '0', 0, NULL, NULL),
(65, 1065, 1, 65, 'available', '0', 0, NULL, NULL),
(66, 1066, 1, 66, 'available', '0', 0, NULL, NULL),
(67, 1067, 1, 67, 'available', '0', 0, NULL, NULL),
(68, 1068, 1, 68, 'available', '0', 0, NULL, NULL),
(69, 1069, 1, 69, 'available', '0', 0, NULL, NULL),
(70, 1070, 1, 70, 'available', '0', 0, NULL, NULL),
(71, 1071, 1, 71, 'available', '0', 0, NULL, NULL),
(72, 1072, 1, 72, 'available', '0', 0, NULL, NULL),
(73, 1073, 1, 73, 'available', '0', 0, NULL, NULL),
(74, 1074, 1, 74, 'available', '0', 0, NULL, NULL),
(75, 1075, 1, 75, 'available', '0', 0, NULL, NULL),
(76, 1076, 1, 76, 'available', '0', 0, NULL, NULL),
(77, 1077, 1, 77, 'available', '0', 0, NULL, NULL),
(78, 1078, 1, 78, 'available', '0', 0, NULL, NULL),
(79, 1079, 1, 79, 'available', '0', 0, NULL, NULL),
(80, 1080, 1, 80, 'available', '0', 0, NULL, NULL),
(81, 1081, 1, 81, 'available', '0', 0, NULL, NULL),
(82, 1082, 1, 82, 'available', '0', 0, NULL, NULL),
(83, 1083, 1, 83, 'available', '0', 0, NULL, NULL),
(84, 1084, 1, 84, 'available', '0', 0, NULL, NULL),
(85, 1085, 1, 85, 'available', '0', 0, NULL, NULL),
(86, 1086, 1, 86, 'available', '0', 0, NULL, NULL),
(87, 1087, 1, 87, 'available', '0', 0, NULL, NULL),
(88, 1088, 1, 88, 'available', '0', 0, NULL, NULL),
(89, 1089, 1, 89, 'available', '0', 0, NULL, NULL),
(90, 1090, 1, 90, 'available', '0', 0, NULL, NULL),
(91, 1091, 1, 91, 'available', '0', 0, NULL, NULL),
(92, 1092, 1, 92, 'available', '0', 0, NULL, NULL),
(93, 1093, 1, 93, 'available', '0', 0, NULL, NULL),
(94, 1094, 1, 94, 'available', '0', 0, NULL, NULL),
(95, 1095, 1, 95, 'available', '0', 0, NULL, NULL),
(96, 1096, 1, 96, 'available', '0', 0, NULL, NULL),
(97, 1097, 1, 97, 'available', '0', 0, NULL, NULL),
(98, 1098, 1, 98, 'available', '0', 0, NULL, NULL),
(99, 1099, 1, 99, 'available', '0', 0, NULL, NULL),
(100, 1100, 1, 100, 'available', '0', 0, NULL, NULL),
(101, 1101, 1, 101, 'available', '0', 0, NULL, NULL),
(102, 1102, 1, 102, 'available', '0', 0, NULL, NULL),
(103, 1103, 1, 103, 'available', '0', 0, NULL, NULL),
(104, 1104, 1, 104, 'available', '0', 0, NULL, NULL),
(105, 1105, 1, 105, 'available', '0', 0, NULL, NULL),
(106, 1106, 1, 106, 'available', '0', 0, NULL, NULL),
(107, 1107, 1, 107, 'available', '0', 0, NULL, NULL),
(108, 1108, 1, 108, 'available', '0', 0, NULL, NULL),
(109, 1109, 1, 109, 'available', '0', 0, NULL, NULL),
(110, 1110, 1, 110, 'available', '0', 0, NULL, NULL),
(111, 1111, 1, 111, 'available', '0', 0, NULL, NULL),
(112, 1112, 1, 112, 'available', '0', 0, NULL, NULL),
(113, 1113, 1, 113, 'available', '0', 0, NULL, NULL),
(114, 1114, 1, 114, 'available', '0', 0, NULL, NULL),
(115, 1115, 1, 115, 'available', '0', 0, NULL, NULL),
(116, 1116, 1, 116, 'available', '0', 0, NULL, NULL),
(117, 1117, 1, 117, 'available', '0', 0, NULL, NULL),
(118, 1118, 1, 118, 'available', '0', 0, NULL, NULL),
(119, 1119, 1, 119, 'available', '0', 0, NULL, NULL),
(120, 1120, 1, 120, 'available', '0', 0, NULL, NULL),
(121, 1121, 1, 121, 'available', '0', 0, NULL, NULL),
(122, 1122, 1, 122, 'available', '0', 0, NULL, NULL),
(123, 1123, 1, 123, 'available', '0', 0, NULL, NULL),
(124, 1124, 1, 124, 'available', '0', 0, NULL, NULL),
(125, 1125, 1, 125, 'available', '0', 0, NULL, NULL),
(126, 1126, 1, 126, 'available', '0', 0, NULL, NULL),
(127, 1127, 1, 127, 'available', '0', 0, NULL, NULL),
(128, 1128, 1, 128, 'available', '0', 0, NULL, NULL),
(129, 1129, 1, 129, 'available', '0', 0, NULL, NULL),
(130, 1130, 1, 130, 'available', '0', 0, NULL, NULL),
(133, 2001, 2, 1, 'available', '0', 0, NULL, NULL),
(134, 2002, 2, 2, 'available', '0', 0, NULL, NULL),
(135, 2003, 2, 3, 'available', '0', 0, NULL, NULL),
(136, 2004, 2, 4, 'available', '0', 0, NULL, NULL),
(137, 2005, 2, 5, 'available', '0', 0, NULL, NULL),
(138, 2006, 2, 6, 'available', '0', 0, NULL, NULL),
(139, 2007, 2, 7, 'available', '0', 0, NULL, NULL),
(140, 2008, 2, 8, 'available', '0', 0, NULL, NULL),
(141, 2009, 2, 9, 'available', '0', 0, NULL, NULL),
(142, 2010, 2, 10, 'available', '0', 0, NULL, NULL),
(143, 2011, 2, 11, 'available', '0', 0, NULL, NULL),
(144, 2012, 2, 12, 'available', '0', 0, NULL, NULL),
(145, 2013, 2, 13, 'available', '0', 0, NULL, NULL),
(146, 2014, 2, 14, 'available', '0', 0, NULL, NULL),
(147, 2015, 2, 15, 'available', '0', 0, NULL, NULL),
(148, 2016, 2, 16, 'available', '0', 0, NULL, NULL),
(149, 2017, 2, 17, 'available', '0', 0, NULL, NULL),
(150, 2018, 2, 18, 'available', '0', 0, NULL, NULL),
(151, 2019, 2, 19, 'available', '0', 0, NULL, NULL),
(152, 2020, 2, 20, 'available', '0', 0, NULL, NULL),
(153, 2021, 2, 21, 'available', '0', 0, NULL, NULL),
(154, 2022, 2, 22, 'available', '0', 0, NULL, NULL),
(155, 2023, 2, 23, 'available', '0', 0, NULL, NULL),
(156, 2024, 2, 24, 'available', '0', 0, NULL, NULL),
(157, 2025, 2, 25, 'available', '0', 0, NULL, NULL),
(158, 2026, 2, 26, 'available', '0', 0, NULL, NULL),
(159, 2027, 2, 27, 'available', '0', 0, NULL, NULL),
(160, 2028, 2, 28, 'available', '0', 0, NULL, NULL),
(161, 2029, 2, 29, 'available', '0', 0, NULL, NULL),
(162, 2030, 2, 30, 'available', '0', 0, NULL, NULL),
(163, 2031, 2, 31, 'available', '0', 0, NULL, NULL),
(164, 2032, 2, 32, 'available', '0', 0, NULL, NULL),
(165, 2033, 2, 33, 'available', '0', 0, NULL, NULL),
(166, 2034, 2, 34, 'available', '0', 0, NULL, NULL),
(167, 2035, 2, 35, 'available', '0', 0, NULL, NULL),
(168, 2036, 2, 36, 'available', '0', 0, NULL, NULL),
(169, 2037, 2, 37, 'available', '0', 0, NULL, NULL),
(170, 2038, 2, 38, 'available', '0', 0, NULL, NULL),
(171, 2039, 2, 39, 'available', '0', 0, NULL, NULL),
(172, 2040, 2, 40, 'available', '0', 0, NULL, NULL),
(173, 2041, 2, 41, 'available', '0', 0, NULL, NULL),
(174, 2042, 2, 42, 'available', '0', 0, NULL, NULL),
(175, 2043, 2, 43, 'available', '0', 0, NULL, NULL),
(176, 2044, 2, 44, 'available', '0', 0, NULL, NULL),
(177, 2045, 2, 45, 'available', '0', 0, NULL, NULL),
(178, 2046, 2, 46, 'available', '0', 0, NULL, NULL),
(179, 2047, 2, 47, 'available', '0', 0, NULL, NULL),
(180, 2048, 2, 48, 'available', '0', 0, NULL, NULL),
(181, 2049, 2, 49, 'available', '0', 0, NULL, NULL),
(182, 2050, 2, 50, 'available', '0', 0, NULL, NULL),
(183, 2051, 2, 51, 'available', '0', 0, NULL, NULL),
(184, 2052, 2, 52, 'available', '0', 0, NULL, NULL),
(185, 2053, 2, 53, 'available', '0', 0, NULL, NULL),
(186, 2054, 2, 54, 'available', '0', 0, NULL, NULL),
(187, 2055, 2, 55, 'available', '0', 0, NULL, NULL),
(188, 2056, 2, 56, 'available', '0', 0, NULL, NULL),
(189, 2057, 2, 57, 'available', '0', 0, NULL, NULL),
(190, 2058, 2, 58, 'available', '0', 0, NULL, NULL),
(191, 2059, 2, 59, 'available', '0', 0, NULL, NULL),
(192, 2060, 2, 60, 'available', '0', 0, NULL, NULL),
(193, 2061, 2, 61, 'available', '0', 0, NULL, NULL),
(194, 2062, 2, 62, 'available', '0', 0, NULL, NULL),
(195, 2063, 2, 63, 'available', '0', 0, NULL, NULL),
(196, 2064, 2, 64, 'available', '0', 0, NULL, NULL),
(197, 2065, 2, 65, 'available', '0', 0, NULL, NULL),
(198, 2066, 2, 66, 'available', '0', 0, NULL, NULL),
(199, 2067, 2, 67, 'available', '0', 0, NULL, NULL),
(200, 2068, 2, 68, 'available', '0', 0, NULL, NULL),
(201, 2069, 2, 69, 'available', '0', 0, NULL, NULL),
(202, 2070, 2, 70, 'available', '0', 0, NULL, NULL),
(203, 2071, 2, 71, 'available', '0', 0, NULL, NULL),
(204, 2072, 2, 72, 'available', '0', 0, NULL, NULL),
(205, 2073, 2, 73, 'available', '0', 0, NULL, NULL),
(206, 2074, 2, 74, 'available', '0', 0, NULL, NULL),
(207, 2075, 2, 75, 'available', '0', 0, NULL, NULL),
(208, 2076, 2, 76, 'available', '0', 0, NULL, NULL),
(209, 2077, 2, 77, 'available', '0', 0, NULL, NULL),
(210, 2078, 2, 78, 'available', '0', 0, NULL, NULL),
(211, 2079, 2, 79, 'available', '0', 0, NULL, NULL),
(212, 2080, 2, 80, 'available', '0', 0, NULL, NULL),
(213, 2081, 2, 81, 'available', '0', 0, NULL, NULL),
(214, 2082, 2, 82, 'available', '0', 0, NULL, NULL),
(215, 2083, 2, 83, 'available', '0', 0, NULL, NULL),
(216, 2084, 2, 84, 'available', '0', 0, NULL, NULL),
(217, 2085, 2, 85, 'available', '0', 0, NULL, NULL),
(218, 2086, 2, 86, 'available', '0', 0, NULL, NULL),
(219, 2087, 2, 87, 'available', '0', 0, NULL, NULL),
(220, 2088, 2, 88, 'available', '0', 0, NULL, NULL),
(221, 2089, 2, 89, 'available', '0', 0, NULL, NULL),
(222, 2090, 2, 90, 'available', '0', 0, NULL, NULL),
(223, 2091, 2, 91, 'available', '0', 0, NULL, NULL),
(224, 2092, 2, 92, 'available', '0', 0, NULL, NULL),
(225, 2093, 2, 93, 'available', '0', 0, NULL, NULL),
(226, 2094, 2, 94, 'available', '0', 0, NULL, NULL),
(227, 2095, 2, 95, 'available', '0', 0, NULL, NULL),
(228, 2096, 2, 96, 'available', '0', 0, NULL, NULL),
(229, 2097, 2, 97, 'available', '0', 0, NULL, NULL),
(230, 2098, 2, 98, 'available', '0', 0, NULL, NULL),
(231, 2099, 2, 99, 'available', '0', 0, NULL, NULL),
(232, 2100, 2, 100, 'available', '0', 0, NULL, NULL),
(233, 2101, 2, 101, 'available', '0', 0, NULL, NULL),
(234, 2102, 2, 102, 'available', '0', 0, NULL, NULL),
(235, 2103, 2, 103, 'available', '0', 0, NULL, NULL),
(236, 2104, 2, 104, 'available', '0', 0, NULL, NULL),
(237, 2105, 2, 105, 'available', '0', 0, NULL, NULL),
(238, 2106, 2, 106, 'available', '0', 0, NULL, NULL),
(239, 2107, 2, 107, 'available', '0', 0, NULL, NULL),
(240, 2108, 2, 108, 'available', '0', 0, NULL, NULL),
(241, 2109, 2, 109, 'available', '0', 0, NULL, NULL),
(242, 2110, 2, 110, 'available', '0', 0, NULL, NULL),
(243, 2111, 2, 111, 'available', '0', 0, NULL, NULL),
(244, 2112, 2, 112, 'available', '0', 0, NULL, NULL),
(245, 2113, 2, 113, 'available', '0', 0, NULL, NULL),
(246, 2114, 2, 114, 'available', '0', 0, NULL, NULL),
(247, 2115, 2, 115, 'available', '0', 0, NULL, NULL),
(248, 2116, 2, 116, 'available', '0', 0, NULL, NULL),
(249, 2117, 2, 117, 'available', '0', 0, NULL, NULL),
(250, 2118, 2, 118, 'available', '0', 0, NULL, NULL),
(251, 2119, 2, 119, 'available', '0', 0, NULL, NULL),
(252, 2120, 2, 120, 'available', '0', 0, NULL, NULL),
(253, 2121, 2, 121, 'available', '0', 0, NULL, NULL),
(254, 2122, 2, 122, 'available', '0', 0, NULL, NULL),
(255, 2123, 2, 123, 'available', '0', 0, NULL, NULL),
(256, 2124, 2, 124, 'available', '0', 0, NULL, NULL),
(257, 2125, 2, 125, 'available', '0', 0, NULL, NULL),
(258, 2126, 2, 126, 'available', '0', 0, NULL, NULL),
(259, 2127, 2, 127, 'available', '0', 0, NULL, NULL),
(260, 2128, 2, 128, 'available', '0', 0, NULL, NULL),
(261, 2129, 2, 129, 'available', '0', 0, NULL, NULL),
(262, 2130, 2, 130, 'available', '0', 0, NULL, NULL),
(263, 2131, 2, 131, 'available', '0', 0, NULL, NULL),
(264, 2132, 2, 132, 'available', '0', 0, NULL, NULL),
(265, 2133, 2, 133, 'available', '0', 0, NULL, NULL),
(266, 2134, 2, 134, 'available', '0', 0, NULL, NULL),
(267, 2135, 2, 135, 'available', '0', 0, NULL, NULL),
(268, 2136, 2, 136, 'available', '0', 0, NULL, NULL),
(269, 2137, 2, 137, 'available', '0', 0, NULL, NULL),
(270, 2138, 2, 138, 'available', '0', 0, NULL, NULL),
(273, 3001, 3, 1, 'available', '0', 0, NULL, NULL),
(274, 3002, 3, 2, 'available', '0', 0, NULL, NULL),
(275, 3003, 3, 3, 'available', '0', 0, NULL, NULL),
(276, 3004, 3, 4, 'available', '0', 0, NULL, NULL),
(277, 3005, 3, 5, 'available', '0', 0, NULL, NULL),
(278, 3006, 3, 6, 'available', '0', 0, NULL, NULL),
(279, 3007, 3, 7, 'available', '0', 0, NULL, NULL),
(280, 3008, 3, 8, 'available', '0', 0, NULL, NULL),
(281, 3009, 3, 9, 'available', '0', 0, NULL, NULL),
(282, 3010, 3, 10, 'available', '0', 0, NULL, NULL),
(283, 3011, 3, 11, 'available', '0', 0, NULL, NULL),
(284, 3012, 3, 12, 'available', '0', 0, NULL, NULL),
(285, 3013, 3, 13, 'available', '0', 0, NULL, NULL),
(286, 3014, 3, 14, 'available', '0', 0, NULL, NULL),
(287, 3015, 3, 15, 'available', '0', 0, NULL, NULL),
(288, 3016, 3, 16, 'available', '0', 0, NULL, NULL),
(289, 3017, 3, 17, 'available', '0', 0, NULL, NULL),
(290, 3018, 3, 18, 'available', '0', 0, NULL, NULL),
(291, 3019, 3, 19, 'available', '0', 0, NULL, NULL),
(292, 3020, 3, 20, 'available', '0', 0, NULL, NULL),
(293, 3021, 3, 21, 'available', '0', 0, NULL, NULL),
(294, 3022, 3, 22, 'available', '0', 0, NULL, NULL),
(295, 3023, 3, 23, 'available', '0', 0, NULL, NULL),
(296, 3024, 3, 24, 'available', '0', 0, NULL, NULL),
(297, 3025, 3, 25, 'available', '0', 0, NULL, NULL),
(298, 3026, 3, 26, 'available', '0', 0, NULL, NULL),
(299, 3027, 3, 27, 'available', '0', 0, NULL, NULL),
(300, 3028, 3, 28, 'available', '0', 0, NULL, NULL),
(301, 3029, 3, 29, 'available', '0', 0, NULL, NULL),
(302, 3030, 3, 30, 'available', '0', 0, NULL, NULL),
(303, 3031, 3, 31, 'available', '0', 0, NULL, NULL),
(304, 3032, 3, 32, 'available', '0', 0, NULL, NULL),
(305, 3033, 3, 33, 'available', '0', 0, NULL, NULL),
(306, 3034, 3, 34, 'available', '0', 0, NULL, NULL),
(307, 3035, 3, 35, 'available', '0', 0, NULL, NULL),
(308, 3036, 3, 36, 'available', '0', 0, NULL, NULL),
(309, 3037, 3, 37, 'available', '0', 0, NULL, NULL),
(310, 3038, 3, 38, 'available', '0', 0, NULL, NULL),
(311, 3039, 3, 39, 'available', '0', 0, NULL, NULL),
(312, 3040, 3, 40, 'available', '0', 0, NULL, NULL),
(313, 3041, 3, 41, 'available', '0', 0, NULL, NULL),
(314, 3042, 3, 42, 'available', '0', 0, NULL, NULL),
(315, 3043, 3, 43, 'available', '0', 0, NULL, NULL),
(316, 3044, 3, 44, 'available', '0', 0, NULL, NULL),
(317, 3045, 3, 45, 'available', '0', 0, NULL, NULL),
(318, 3046, 3, 46, 'available', '0', 0, NULL, NULL),
(319, 3047, 3, 47, 'available', '0', 0, NULL, NULL),
(320, 3048, 3, 48, 'available', '0', 0, NULL, NULL),
(321, 3049, 3, 49, 'available', '0', 0, NULL, NULL),
(322, 3050, 3, 50, 'available', '0', 0, NULL, NULL),
(323, 3051, 3, 51, 'available', '0', 0, NULL, NULL),
(324, 3052, 3, 52, 'available', '0', 0, NULL, NULL),
(325, 3053, 3, 53, 'available', '0', 0, NULL, NULL),
(326, 3054, 3, 54, 'available', '0', 0, NULL, NULL),
(327, 3055, 3, 55, 'available', '0', 0, NULL, NULL),
(328, 3056, 3, 56, 'available', '0', 0, NULL, NULL),
(329, 3057, 3, 57, 'available', '0', 0, NULL, NULL),
(330, 3058, 3, 58, 'available', '0', 0, NULL, NULL),
(331, 3059, 3, 59, 'available', '0', 0, NULL, NULL),
(332, 3060, 3, 60, 'available', '0', 0, NULL, NULL),
(333, 3061, 3, 61, 'available', '0', 0, NULL, NULL),
(334, 3062, 3, 62, 'available', '0', 0, NULL, NULL),
(335, 3063, 3, 63, 'available', '0', 0, NULL, NULL),
(336, 3064, 3, 64, 'available', '0', 0, NULL, NULL),
(337, 3065, 3, 65, 'available', '0', 0, NULL, NULL),
(338, 3066, 3, 66, 'available', '0', 0, NULL, NULL),
(339, 3067, 3, 67, 'available', '0', 0, NULL, NULL),
(340, 3068, 3, 68, 'available', '0', 0, NULL, NULL),
(341, 3069, 3, 69, 'available', '0', 0, NULL, NULL),
(342, 3070, 3, 70, 'available', '0', 0, NULL, NULL),
(343, 3071, 3, 71, 'available', '0', 0, NULL, NULL),
(344, 3072, 3, 72, 'available', '0', 0, NULL, NULL),
(345, 3073, 3, 73, 'available', '0', 0, NULL, NULL),
(346, 3074, 3, 74, 'available', '0', 0, NULL, NULL),
(347, 3075, 3, 75, 'available', '0', 0, NULL, NULL),
(348, 3076, 3, 76, 'available', '0', 0, NULL, NULL),
(349, 3077, 3, 77, 'available', '0', 0, NULL, NULL),
(350, 3078, 3, 78, 'available', '0', 0, NULL, NULL),
(351, 3079, 3, 79, 'available', '0', 0, NULL, NULL),
(352, 3080, 3, 80, 'available', '0', 0, NULL, NULL),
(353, 3081, 3, 81, 'available', '0', 0, NULL, NULL),
(354, 3082, 3, 82, 'available', '0', 0, NULL, NULL),
(355, 3083, 3, 83, 'available', '0', 0, NULL, NULL),
(356, 3084, 3, 84, 'available', '0', 0, NULL, NULL),
(357, 3085, 3, 85, 'available', '0', 0, NULL, NULL),
(358, 3086, 3, 86, 'available', '0', 0, NULL, NULL),
(359, 3087, 3, 87, 'available', '0', 0, NULL, NULL),
(360, 3088, 3, 88, 'available', '0', 0, NULL, NULL),
(361, 3089, 3, 89, 'available', '0', 0, NULL, NULL),
(362, 3090, 3, 90, 'available', '0', 0, NULL, NULL),
(363, 3091, 3, 91, 'available', '0', 0, NULL, NULL),
(364, 3092, 3, 92, 'available', '0', 0, NULL, NULL),
(365, 3093, 3, 93, 'available', '0', 0, NULL, NULL),
(366, 3094, 3, 94, 'available', '0', 0, NULL, NULL),
(367, 3095, 3, 95, 'available', '0', 0, NULL, NULL),
(368, 3096, 3, 96, 'available', '0', 0, NULL, NULL),
(369, 3097, 3, 97, 'available', '0', 0, NULL, NULL),
(370, 3098, 3, 98, 'available', '0', 0, NULL, NULL),
(371, 3099, 3, 99, 'available', '0', 0, NULL, NULL),
(372, 3100, 3, 100, 'available', '0', 0, NULL, NULL),
(373, 3101, 3, 101, 'available', '0', 0, NULL, NULL),
(374, 3102, 3, 102, 'available', '0', 0, NULL, NULL),
(375, 3103, 3, 103, 'available', '0', 0, NULL, NULL),
(376, 3104, 3, 104, 'available', '0', 0, NULL, NULL),
(377, 3105, 3, 105, 'available', '0', 0, NULL, NULL),
(378, 3106, 3, 106, 'available', '0', 0, NULL, NULL),
(379, 3107, 3, 107, 'available', '0', 0, NULL, NULL),
(380, 3108, 3, 108, 'available', '0', 0, NULL, NULL),
(381, 3109, 3, 109, 'available', '0', 0, NULL, NULL),
(382, 3110, 3, 110, 'available', '0', 0, NULL, NULL),
(383, 3111, 3, 111, 'available', '0', 0, NULL, NULL),
(384, 3112, 3, 112, 'available', '0', 0, NULL, NULL),
(385, 3113, 3, 113, 'available', '0', 0, NULL, NULL),
(386, 3114, 3, 114, 'available', '0', 0, NULL, NULL),
(387, 3115, 3, 115, 'available', '0', 0, NULL, NULL),
(388, 3116, 3, 116, 'available', '0', 0, NULL, NULL),
(389, 3117, 3, 117, 'available', '0', 0, NULL, NULL),
(390, 3118, 3, 118, 'available', '0', 0, NULL, NULL),
(391, 3119, 3, 119, 'available', '0', 0, NULL, NULL),
(392, 3120, 3, 120, 'available', '0', 0, NULL, NULL),
(393, 3121, 3, 121, 'available', '0', 0, NULL, NULL),
(394, 3122, 3, 122, 'available', '0', 0, NULL, NULL),
(395, 3123, 3, 123, 'available', '0', 0, NULL, NULL),
(396, 3124, 3, 124, 'available', '0', 0, NULL, NULL),
(397, 3125, 3, 125, 'available', '0', 0, NULL, NULL),
(398, 3126, 3, 126, 'available', '0', 0, NULL, NULL),
(399, 3127, 3, 127, 'available', '0', 0, NULL, NULL),
(400, 3128, 3, 128, 'available', '0', 0, NULL, NULL),
(401, 3129, 3, 129, 'available', '0', 0, NULL, NULL),
(402, 3130, 3, 130, 'available', '0', 0, NULL, NULL),
(403, 3131, 3, 131, 'available', '0', 0, NULL, NULL),
(404, 3132, 3, 132, 'available', '0', 0, NULL, NULL),
(405, 3133, 3, 133, 'available', '0', 0, NULL, NULL),
(406, 3134, 3, 134, 'available', '0', 0, NULL, NULL),
(407, 3135, 3, 135, 'available', '0', 0, NULL, NULL),
(408, 3136, 3, 136, 'available', '0', 0, NULL, NULL),
(409, 3137, 3, 137, 'available', '0', 0, NULL, NULL),
(410, 3138, 3, 138, 'available', '0', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('top_up','booking') NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `amount`, `transaction_type`, `transaction_date`) VALUES
(1, 1, '15.00', 'top_up', '2024-11-18 21:29:44'),
(2, 1, '10.00', 'booking', '2024-11-18 21:35:43'),
(3, 1, '50.00', 'top_up', '2024-11-18 21:43:48'),
(4, 1, '10.00', 'booking', '2024-11-18 21:43:54'),
(5, 1, '10.00', 'booking', '2024-11-18 21:50:10'),
(6, 1, '10.00', 'booking', '2024-11-18 21:50:14'),
(7, 1, '10.00', 'booking', '2024-11-18 22:00:42'),
(8, 1, '10.00', 'booking', '2024-11-18 22:01:28'),
(9, 1, '100.00', 'top_up', '2024-11-18 22:02:01'),
(10, 1, '10.00', 'booking', '2024-11-18 22:02:04'),
(11, 1, '50.00', 'booking', '2024-11-18 22:46:54'),
(12, 1, '5.00', 'top_up', '2024-11-19 00:02:34'),
(13, 1, '15.00', 'top_up', '2024-11-19 00:33:12'),
(14, 1, '10.00', 'booking', '2024-11-19 01:01:22'),
(15, 1, '100.00', 'top_up', '2024-11-19 01:05:06'),
(16, 1, '10.00', 'booking', '2024-11-19 01:11:02'),
(17, 1, '10.00', 'booking', '2024-11-19 01:14:07'),
(18, 1, '10.00', 'booking', '2024-11-19 01:14:42'),
(19, 1, '10.00', 'booking', '2024-11-19 01:19:38'),
(20, 1, '10.00', 'booking', '2024-11-19 01:21:20'),
(21, 8, '60.00', 'top_up', '2024-11-19 01:22:32'),
(22, 8, '10.00', 'booking', '2024-11-19 01:22:35'),
(23, 1, '10.00', 'booking', '2024-11-26 21:40:25'),
(24, 1, '100.00', 'top_up', '2024-11-26 21:51:08'),
(25, 1, '100.00', 'top_up', '2024-11-26 22:08:12'),
(26, 1, '100.00', 'top_up', '2024-11-26 22:10:47'),
(27, 1, '10.00', 'top_up', '2024-11-26 22:11:55'),
(28, 1, '10.00', 'booking', '2024-11-26 22:17:46'),
(29, 1, '50.00', 'booking', '2024-11-26 22:19:03'),
(30, 1, '10.00', 'booking', '2024-11-26 22:38:57'),
(31, 1, '10.00', 'booking', '2024-11-26 22:53:08'),
(32, 1, '10.00', 'top_up', '2024-11-26 22:53:34'),
(33, 1, '10.00', 'top_up', '2024-11-26 23:04:16'),
(34, 1, '50.00', 'top_up', '2024-11-26 23:04:45'),
(35, 1, '50.00', 'top_up', '2024-11-26 23:06:41'),
(36, 1, '10.00', 'top_up', '2024-11-26 23:15:42'),
(37, 1, '10.00', 'top_up', '2024-11-26 23:16:26'),
(38, 1, '10.00', 'booking', '2024-11-26 23:18:51'),
(39, 1, '10.00', 'booking', '2024-11-27 00:46:54'),
(40, 1, '10.00', 'booking', '2024-11-27 23:54:38'),
(41, 1, '10.00', 'top_up', '2024-11-27 23:55:10'),
(42, 1, '10.00', 'booking', '2024-12-01 23:26:45'),
(43, 1, '10.00', 'booking', '2024-12-01 23:27:30'),
(44, 1, '10.00', 'booking', '2024-12-01 23:29:22'),
(45, 1, '10.00', 'booking', '2024-12-03 21:00:31'),
(46, 1, '10.00', 'booking', '2024-12-03 21:04:02'),
(47, 1, '10.00', 'booking', '2024-12-03 21:04:07'),
(48, 1, '10.00', 'booking', '2024-12-03 21:04:11'),
(49, 1, '10.00', 'booking', '2024-12-03 21:04:17'),
(50, 1, '10.00', 'booking', '2024-12-03 21:04:22'),
(51, 1, '10.00', 'booking', '2024-12-03 21:04:43'),
(52, 1, '10.00', 'booking', '2024-12-03 21:05:46'),
(53, 1, '10.00', 'booking', '2024-12-03 21:07:43'),
(54, 1, '10.00', 'booking', '2024-12-03 21:12:53'),
(55, 1, '10.00', 'booking', '2024-12-03 21:21:06'),
(56, 1, '10.00', 'booking', '2024-12-03 21:23:45'),
(57, 1, '10.00', 'booking', '2024-12-03 21:27:04'),
(58, 1, '10.00', 'booking', '2024-12-03 21:29:55'),
(59, 1, '10.00', 'booking', '2024-12-03 21:33:02'),
(60, 1, '10.00', 'booking', '2024-12-03 21:34:51'),
(61, 1, '10.00', 'booking', '2024-12-03 21:35:36'),
(62, 1, '10.00', 'booking', '2024-12-03 21:47:26'),
(63, 1, '10.00', 'booking', '2024-12-03 21:53:26'),
(64, 1, '10.00', 'booking', '2024-12-03 23:18:23'),
(65, 1, '10.00', 'booking', '2024-12-03 23:18:28'),
(66, 8, '50.00', 'top_up', '2024-12-04 02:35:24'),
(67, 1, '10.00', 'booking', '2024-12-04 02:35:48'),
(68, 8, '10.00', 'booking', '2024-12-04 02:40:40'),
(69, 8, '10.00', 'booking', '2024-12-04 02:44:14'),
(70, 8, '10.00', 'booking', '2024-12-04 02:44:49'),
(71, 1, '10.00', 'top_up', '2024-12-06 04:03:33'),
(72, 1, '10.00', 'top_up', '2024-12-06 04:28:10'),
(73, 1, '10.00', 'top_up', '2024-12-06 04:29:43'),
(74, 1, '10.00', 'top_up', '2024-12-06 04:32:22'),
(75, 1, '10.00', 'top_up', '2024-12-06 04:35:43'),
(76, 8, '10.00', 'booking', '2024-12-07 03:14:34'),
(77, 1, '10.00', 'booking', '2024-12-07 18:34:29'),
(78, 6, '100.00', 'top_up', '2024-12-07 18:42:23'),
(79, 6, '10.00', 'booking', '2024-12-07 18:42:36'),
(80, 6, '50.00', 'top_up', '2024-12-07 19:03:42'),
(81, 6, '10.00', 'booking', '2024-12-07 19:04:03'),
(82, 6, '10.00', 'booking', '2024-12-10 00:07:15'),
(83, 6, '10.00', 'booking', '2024-12-10 00:12:10'),
(84, 1, '10.00', 'booking', '2024-12-10 00:47:50'),
(85, 1, '10.00', 'booking', '2024-12-10 01:04:15'),
(86, 8, '40.00', 'top_up', '2024-12-10 01:05:40'),
(87, 1, '110.00', 'booking', '2024-12-10 20:38:10'),
(88, 1, '90.00', 'top_up', '2024-12-10 20:56:33'),
(89, 1, '10.00', 'booking', '2024-12-10 20:57:56'),
(90, 1, '90.00', 'booking', '2024-12-10 20:59:54'),
(91, 1, '100.00', 'top_up', '2024-12-10 21:03:15'),
(92, 1, '5.00', 'booking', '2024-12-10 21:03:23'),
(93, 1, '5.00', 'booking', '2024-12-10 21:07:24'),
(94, 1, '5.00', 'booking', '2024-12-10 21:10:41'),
(95, 1, '5.00', 'booking', '2024-12-10 21:12:45'),
(96, 1, '5.00', 'booking', '2024-12-10 21:16:24'),
(97, 1, '5.00', 'booking', '2024-12-10 21:20:20'),
(98, 1, '5.00', 'booking', '2024-12-10 21:24:42'),
(99, 1, '5.00', 'booking', '2024-12-10 21:28:21'),
(100, 1, '5.00', 'booking', '2024-12-10 21:30:25'),
(101, 1, '5.00', 'booking', '2024-12-10 21:31:11'),
(102, 1, '5.00', 'booking', '2024-12-10 21:32:30'),
(103, 1, '5.00', 'booking', '2024-12-10 21:33:18'),
(104, 1, '5.00', 'booking', '2024-12-10 21:36:04'),
(105, 1, '5.00', 'booking', '2024-12-10 21:37:18'),
(106, 1, '5.00', 'booking', '2024-12-10 21:39:08'),
(107, 1, '100.00', 'top_up', '2024-12-10 21:39:19'),
(108, 1, '50.00', 'booking', '2024-12-10 21:39:26'),
(109, 1, '10.00', 'booking', '2024-12-10 21:39:37'),
(110, 1, '10.00', 'booking', '2024-12-10 21:40:06'),
(111, 1, '50.00', 'booking', '2024-12-10 21:40:22'),
(112, 1, '100.00', 'top_up', '2024-12-10 21:40:38'),
(113, 1, '100.00', 'booking', '2024-12-10 21:40:44'),
(114, 1, '5.00', 'booking', '2024-12-10 21:50:25'),
(115, 1, '100.00', 'top_up', '2024-12-10 21:57:04'),
(116, 1, '5.00', 'booking', '2024-12-10 21:57:09'),
(117, 1, '5.00', 'booking', '2024-12-10 21:57:26'),
(118, 8, '10.00', 'booking', '2024-12-10 21:59:43'),
(119, 8, '10.00', 'booking', '2024-12-10 23:25:25'),
(120, 8, '10.00', 'booking', '2024-12-10 23:31:26'),
(121, 6, '10.00', 'booking', '2024-12-10 23:31:37'),
(122, 1, '5.00', 'booking', '2024-12-11 00:16:18'),
(123, 1, '5.00', 'booking', '2024-12-11 00:16:40'),
(124, 1, '5.00', 'booking', '2024-12-11 00:19:14'),
(125, 1, '5.00', 'booking', '2024-12-11 00:19:21'),
(126, 1, '5.00', 'booking', '2024-12-11 00:53:13'),
(127, 1, '5.00', 'booking', '2024-12-11 00:55:15'),
(128, 1, '5.00', 'booking', '2024-12-11 00:55:26'),
(129, 1, '5.00', 'booking', '2024-12-11 00:57:13'),
(130, 1, '5.00', 'booking', '2024-12-11 00:58:49'),
(131, 1, '5.00', 'booking', '2024-12-11 01:02:30'),
(132, 1, '5.00', 'booking', '2024-12-11 01:05:23'),
(133, 1, '5.00', 'booking', '2024-12-11 01:07:35'),
(134, 1, '5.00', 'booking', '2024-12-11 01:07:40'),
(135, 1, '5.00', 'booking', '2024-12-11 01:08:37'),
(136, 1, '5.00', 'booking', '2024-12-11 01:08:41'),
(137, 1, '5.00', 'booking', '2024-12-11 02:40:52'),
(138, 1, '5.00', 'booking', '2024-12-17 12:02:15'),
(139, 1, '10.00', 'top_up', '2024-12-17 12:41:08'),
(140, 1, '100.00', 'top_up', '2024-12-17 12:41:58'),
(141, 1, '5.00', 'booking', '2024-12-18 23:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `building` varchar(5) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `first_name`, `last_name`, `phone`, `password`, `building`, `balance`, `created_at`) VALUES
(1, 'user', 'user1@gmail.com', 'komail', 'habib', '0505501322', '$2y$10$SDV0ytqkm9OKoKp2UIkO1uqomQHdOmcLQWlfhh2mAz3M7v6f7cKTW', '17', '110.00', '2024-11-19 00:31:01'),
(6, 'Hassan', 'HassanSal@hotmail.com', 'Hassan', 'Saleh', '0501124256', '$2y$10$k6aTy28zieRfujiM6r9GCOMobUd22uViMTCqOFNi0i0nQDzitPKN6', '17', '100.00', '2024-11-19 00:31:01'),
(8, 'hh', 'mu3li@gmail.com', 'Mu', 'Ali', '0566221050', '$2y$10$sn/96FqeA4nQJO0LqOsVjOPGntEa2tH81Gp.2utIUlV2dhsS6q8da', '17', '70.00', '2024-11-19 00:31:01'),
(9, 'SalehJ', 'salehjay@gmail.com', 'Saleh', 'Jassim', '0505661033', '$2y$10$/gOKGV17MdTIIN.4eJzepe.xje5JOxGHNRVcQhwU4SsrCNuMGiVX2', NULL, '0.00', '2024-12-04 01:52:40'),
(11, 'SalehJj', 'salehY1@gmail.com', 'Saleh', 'Yousef', '0588817568', '$2y$10$7WbIeZ/q5Lhm.qfFdkHt0eIzmfx8QwZ.lrp68gGl5cel8b8Tzh43.', NULL, '0.00', '2024-12-04 01:56:26'),
(13, 'yousef', 'usef_A5@gmail.com', 'Yousef', 'ab', '0552321445', '$2y$10$05KFqNjELDwYVh4zU165F.poqcWES7/Zkq9HFWh5MpGAte.uE3./.', '20', '0.00', '2024-12-07 02:38:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `plate` (`plate`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `operation_records`
--
ALTER TABLE `operation_records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `parkings`
--
ALTER TABLE `parkings`
  ADD PRIMARY KEY (`parking_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `operation_records`
--
ALTER TABLE `operation_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
