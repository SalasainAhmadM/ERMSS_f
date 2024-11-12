-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 03:48 PM
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
-- Database: `event_record`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `LastName` varchar(200) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MI` varchar(2) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ContactNo` varchar(15) NOT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Affiliation` varchar(50) NOT NULL,
  `Position` varchar(50) NOT NULL,
  `Image` varchar(200) DEFAULT NULL,
  `Role` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `LastName`, `FirstName`, `MI`, `Gender`, `Email`, `Password`, `ContactNo`, `Address`, `Affiliation`, `Position`, `Image`, `Role`) VALUES
(1, 'Gold', 'Roger', 'D.', 'Male', 'superadmin@gmail.com', '$2y$10$/ATMjC.VAVuAKFQjhh1jCOE.7dr24zsizLOGxiGVpX4p0O5K32XUm', '09709185816', 'Laughtale , Grandline', 'One Piece', 'Pirate King', 'roger.png', 'superadmin'),
(5, 'Casinillo123pppppppp', 'Antonia', 'D.', 'Male', 'Admin2@gmail.com', '$2y$10$NwHhZ1iud/zSr52enuQm5OpKuECWGO02f9B5J7tzizL5Pb/7YdfI2', '09709185816', 'Atis drive, baliwasan,Zamboanga City', 'WMSU2', 'Clerk', 'padwa.png', 'Admin'),
(6, 'Tabotabo', 'Larenz', 'B.', '', 'Admin@gmail.com', '$2y$10$AGJKlslsUWzh6jDVZSAN0u8Xa8U.EXP4QDeQc/Uw6CZ5GfB36RGO.', '09352453795', NULL, 'CCS', 'Lead Programmer', 'mark.png', 'Admin'),
(7, 'Tabotabo12345', 'Larenz', 'B.', 'Male', 'larenz@gmail.com', 'Tabotabo', '123123123', 'Tetutan', 'CCS', 'Lead Programmer', 'mark.png', 'Admin'),
(16, 'Luffy12', 'Monkey', 'D', 'Male', 'salasainahmad@gmail.com', '$2y$10$1ko3A5ZR1pTNVdRf4KLF0O0c3JKJFPmQ2EwWEnhef4OrRZgOvkz7u', '5576676', 'Street', '123', 'CEO', '', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

CREATE TABLE `agency` (
  `agencyID` int(15) NOT NULL,
  `agencyName` varchar(250) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contactNumber` int(15) NOT NULL,
  `designation` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `status` enum('present','absent') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `day` int(11) DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `participant_id`, `event_id`, `attendance_date`, `status`, `created_at`, `day`, `time_in`, `time_out`) VALUES
(140, 144, 214, '2024-10-27', 'present', '2024-10-25 15:27:51', NULL, '23:27:51', '23:27:58'),
(141, 145, 214, '2024-10-27', 'present', '2024-10-25 15:27:53', NULL, '23:27:53', '23:27:59'),
(142, 143, 214, '2024-10-28', 'present', '2024-10-25 15:30:47', NULL, '23:30:47', NULL),
(143, 143, 214, '2024-10-27', 'present', '2024-10-26 13:17:55', NULL, '21:17:55', NULL),
(178, 143, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(179, 144, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(180, 145, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(181, 146, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(182, 149, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(183, 152, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(184, 153, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(185, 154, 214, '2024-11-04', 'absent', '2024-11-03 16:04:27', 9, NULL, NULL),
(186, 156, 304, '2024-11-04', 'absent', '2024-11-03 16:04:27', 4, NULL, NULL),
(193, 185, 340, '2024-11-05', 'present', '2024-11-03 17:21:29', NULL, '01:21:29', '01:21:32'),
(194, 186, 340, '2024-11-05', 'present', '2024-11-03 17:21:34', NULL, '01:21:34', NULL),
(195, 187, 340, '2024-11-05', 'present', '2024-11-03 17:21:37', NULL, '01:21:37', NULL),
(196, 185, 340, '2024-11-06', 'present', '2024-11-03 17:21:43', NULL, '01:21:43', NULL),
(197, 185, 340, '2024-11-07', 'present', '2024-11-03 17:21:47', NULL, '01:21:47', NULL),
(198, 143, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(199, 144, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(200, 145, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(201, 146, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(202, 149, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(203, 152, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(204, 153, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(205, 154, 214, '2024-11-05', 'absent', '2024-11-05 01:59:44', 10, NULL, NULL),
(206, 156, 304, '2024-11-05', 'absent', '2024-11-05 01:59:44', 5, NULL, NULL),
(207, 182, 341, '2024-11-05', 'absent', '2024-11-05 01:59:44', 1, NULL, NULL),
(208, 183, 341, '2024-11-05', 'absent', '2024-11-05 01:59:44', 1, NULL, NULL),
(213, 143, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(214, 144, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(215, 145, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(216, 146, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(217, 149, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(218, 152, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(219, 153, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(220, 154, 214, '2024-11-06', 'absent', '2024-11-06 00:11:50', 11, NULL, NULL),
(221, 156, 304, '2024-11-06', 'absent', '2024-11-06 00:11:50', 6, NULL, NULL),
(222, 186, 340, '2024-11-06', 'absent', '2024-11-06 00:11:50', 2, NULL, NULL),
(223, 187, 340, '2024-11-06', 'absent', '2024-11-06 00:11:50', 2, NULL, NULL),
(224, 182, 341, '2024-11-06', 'absent', '2024-11-06 00:11:50', 2, NULL, NULL),
(225, 183, 341, '2024-11-06', 'absent', '2024-11-06 00:11:50', 2, NULL, NULL),
(228, 143, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(229, 144, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(230, 145, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(231, 146, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(232, 149, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(233, 152, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(234, 153, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(235, 154, 214, '2024-11-07', 'absent', '2024-11-07 00:34:16', 12, NULL, NULL),
(236, 156, 304, '2024-11-07', 'absent', '2024-11-07 00:34:16', 7, NULL, NULL),
(237, 184, 339, '2024-11-07', 'absent', '2024-11-07 00:34:16', 1, NULL, NULL),
(238, 186, 340, '2024-11-07', 'absent', '2024-11-07 00:34:16', 3, NULL, NULL),
(239, 187, 340, '2024-11-07', 'absent', '2024-11-07 00:34:16', 3, NULL, NULL),
(240, 182, 341, '2024-11-07', 'absent', '2024-11-07 00:34:16', 3, NULL, NULL),
(241, 183, 341, '2024-11-07', 'absent', '2024-11-07 00:34:16', 3, NULL, NULL),
(243, 156, 304, '2024-11-08', 'absent', '2024-11-07 18:21:32', 8, NULL, NULL),
(244, 184, 339, '2024-11-08', 'absent', '2024-11-07 18:21:32', 2, NULL, NULL),
(246, 156, 304, '2024-11-09', 'absent', '2024-11-09 01:13:57', 9, NULL, NULL),
(247, 184, 339, '2024-11-09', 'absent', '2024-11-09 01:13:57', 3, NULL, NULL),
(249, 153, 214, '2024-11-06', '', '0000-00-00 00:00:00', 0, NULL, NULL),
(250, 143, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(251, 144, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(252, 145, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(253, 146, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(254, 149, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(255, 152, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(256, 153, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(257, 154, 214, '2024-11-10', 'absent', '2024-11-09 23:45:55', 15, NULL, NULL),
(265, 143, 214, '2024-10-29', 'absent', '2024-11-10 00:00:14', 3, NULL, NULL),
(266, 143, 214, '2024-10-30', 'absent', '2024-11-10 00:00:14', 4, NULL, NULL),
(267, 143, 214, '2024-10-31', 'absent', '2024-11-10 00:00:14', 5, NULL, NULL),
(268, 143, 214, '2024-11-01', 'absent', '2024-11-10 00:00:14', 6, NULL, NULL),
(269, 143, 214, '2024-11-02', 'absent', '2024-11-10 00:00:14', 7, NULL, NULL),
(270, 143, 214, '2024-11-03', 'absent', '2024-11-10 00:00:14', 8, NULL, NULL),
(271, 143, 214, '2024-11-08', 'absent', '2024-11-10 00:00:14', 13, NULL, NULL),
(272, 143, 214, '2024-11-09', 'absent', '2024-11-10 00:00:14', 14, NULL, NULL),
(273, 144, 214, '2024-10-28', 'absent', '2024-11-10 00:00:14', 2, NULL, NULL),
(274, 144, 214, '2024-10-29', 'absent', '2024-11-10 00:00:14', 3, NULL, NULL),
(275, 144, 214, '2024-10-30', 'absent', '2024-11-10 00:00:14', 4, NULL, NULL),
(276, 144, 214, '2024-10-31', 'absent', '2024-11-10 00:00:14', 5, NULL, NULL),
(277, 144, 214, '2024-11-01', 'absent', '2024-11-10 00:00:14', 6, NULL, NULL),
(278, 144, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(279, 144, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(280, 144, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(281, 144, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(282, 145, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(283, 145, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(284, 145, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(285, 145, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(286, 145, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(287, 145, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(288, 145, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(289, 145, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(290, 145, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(291, 146, 214, '2024-10-27', 'absent', '2024-11-10 00:00:15', 1, NULL, NULL),
(292, 146, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(293, 146, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(294, 146, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(295, 146, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(296, 146, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(297, 146, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(298, 146, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(299, 146, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(300, 146, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(301, 149, 214, '2024-10-27', 'absent', '2024-11-10 00:00:15', 1, NULL, NULL),
(302, 149, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(303, 149, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(304, 149, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(305, 149, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(306, 149, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(307, 149, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(308, 149, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(309, 149, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(310, 149, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(311, 152, 214, '2024-10-27', 'absent', '2024-11-10 00:00:15', 1, NULL, NULL),
(312, 152, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(313, 152, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(314, 152, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(315, 152, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(316, 152, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(317, 152, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(318, 152, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(319, 152, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(320, 152, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(321, 153, 214, '2024-10-27', 'absent', '2024-11-10 00:00:15', 1, NULL, NULL),
(322, 153, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(323, 153, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(324, 153, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(325, 153, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(326, 153, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(327, 153, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(328, 153, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(329, 153, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(330, 153, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL),
(331, 154, 214, '2024-10-27', 'absent', '2024-11-10 00:00:15', 1, NULL, NULL),
(332, 154, 214, '2024-10-28', 'absent', '2024-11-10 00:00:15', 2, NULL, NULL),
(333, 154, 214, '2024-10-29', 'absent', '2024-11-10 00:00:15', 3, NULL, NULL),
(334, 154, 214, '2024-10-30', 'absent', '2024-11-10 00:00:15', 4, NULL, NULL),
(335, 154, 214, '2024-10-31', 'absent', '2024-11-10 00:00:15', 5, NULL, NULL),
(336, 154, 214, '2024-11-01', 'absent', '2024-11-10 00:00:15', 6, NULL, NULL),
(337, 154, 214, '2024-11-02', 'absent', '2024-11-10 00:00:15', 7, NULL, NULL),
(338, 154, 214, '2024-11-03', 'absent', '2024-11-10 00:00:15', 8, NULL, NULL),
(339, 154, 214, '2024-11-08', 'absent', '2024-11-10 00:00:15', 13, NULL, NULL),
(340, 154, 214, '2024-11-09', 'absent', '2024-11-10 00:00:15', 14, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audience_type`
--

CREATE TABLE `audience_type` (
  `audience_type_id` int(11) NOT NULL,
  `audience_type_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audience_type`
--

INSERT INTO `audience_type` (`audience_type_id`, `audience_type_name`) VALUES
(1, 'All'),
(2, 'Men'),
(3, 'Women'),
(4, 'Students'),
(5, 'Professionals'),
(6, 'Directors'),
(7, 'Staff'),
(8, 'Sponsors');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `userID` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  `dateCreated` varchar(100) NOT NULL,
  `lastUpdated` varchar(100) NOT NULL,
  `updatedBy` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancel_reason`
--

CREATE TABLE `cancel_reason` (
  `cancel_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `UserID` int(15) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancel_reason`
--

INSERT INTO `cancel_reason` (`cancel_id`, `event_id`, `UserID`, `description`) VALUES
(15, 290, 27, 'No Sponsor and Speaker'),
(25, 341, 27, 'No Meattt'),
(26, 341, 21, 'No Meattt'),
(27, 339, 27, 'Floowwwwwwww');

-- --------------------------------------------------------

--
-- Table structure for table `director`
--

CREATE TABLE `director` (
  `DirectorID` int(15) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MI` varchar(2) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ContactNo` varchar(15) NOT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Affiliation` varchar(50) NOT NULL,
  `Image` varchar(250) DEFAULT NULL,
  `Role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `director`
--

INSERT INTO `director` (`DirectorID`, `LastName`, `FirstName`, `MI`, `Email`, `Password`, `ContactNo`, `Address`, `Affiliation`, `Image`, `Role`) VALUES
(1, 'Master', 'Shipu', 'T.', 'Director@gmail.com', '$2y$10$eGxm0xmaQJXh4lV0G6.Suu057nL0JhJgSmqO/RiRIG.ACMU5FIj1C', '0965825394', 'Baliwasan', 'wesmaarrdec', 'faustine.jpg', 'director'),
(2, 'Dumaboc', 'Jaylen', 'J.', 'director@gmail.com', '$2y$10$HnjrjLupHUxvV/8RvqzFkOVV/PUJrTlIUPdHj9Z0hm0VrbLXA.awa', '12312312312', 'sta maria', 'CCS', '', 'Director');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `evaluation_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `evaluation_date` date NOT NULL DEFAULT curdate(),
  `status` enum('approved','declined','no_record') DEFAULT 'no_record',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`evaluation_id`, `participant_id`, `event_id`, `evaluation_date`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(88, 185, 340, '2024-11-04', 'approved', '3 Attended, 0 Absent', '2024-11-03 17:22:00', '2024-11-04 08:28:24'),
(89, 186, 340, '2024-11-04', 'approved', '1 Attended, 0 Absent', '2024-11-03 17:47:12', '2024-11-03 18:04:01'),
(90, 187, 340, '2024-11-04', 'approved', '1 Attended, 0 Absent', '2024-11-03 18:03:29', '2024-11-03 18:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `eventparticipants`
--

CREATE TABLE `eventparticipants` (
  `participant_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventparticipants`
--

INSERT INTO `eventparticipants` (`participant_id`, `event_id`, `UserID`) VALUES
(143, 214, 6),
(144, 214, 15),
(145, 214, 17),
(146, 214, 7),
(149, 214, 16),
(152, 214, 26),
(153, 214, 25),
(154, 214, 23),
(156, 304, 27),
(158, 289, 27),
(160, 322, 27),
(161, 320, 27),
(182, 341, 27),
(183, 341, 21),
(184, 339, 27),
(185, 340, 27),
(186, 340, 16),
(187, 340, 6);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_mode` varchar(50) NOT NULL,
  `event_photo_path` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_link` text DEFAULT NULL,
  `cancelReason` text NOT NULL,
  `event_cancel` varchar(255) NOT NULL,
  `participant_limit` int(11) NOT NULL,
  `audience_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_title`, `event_description`, `event_type`, `event_mode`, `event_photo_path`, `location`, `date_start`, `date_end`, `time_start`, `time_end`, `date_created`, `event_link`, `cancelReason`, `event_cancel`, `participant_limit`, `audience_type`) VALUES
(60, 'Location', 'dasfsdaf sadfasfasd', 'Training Sessions', 'Face-to-Face', '', 'Tugbungan, Zamboanga City', '2024-03-02', '2024-03-02', '19:01:00', '19:01:00', '2024-03-02 11:01:31', '', '', '', 0, ''),
(64, 'loopinggg', 'sdfasfsadf', 'Training Sessions', 'Online', '', '', '2024-03-09', '2024-03-09', '23:42:00', '23:44:00', '2024-03-02 16:46:24', 'https://meet.google.com/xux-xsau-zbn', '', '', 0, ''),
(66, 'hahaha', 'sdfadasd', 'Training Sessions', 'Face-to-Face', '', 'Tugbungan, Zamboanga City', '2021-03-03', '2021-03-12', '01:06:00', '01:06:00', '2024-03-02 17:06:57', '', '', '', 0, ''),
(68, 'Join this event check', 'asdfasdf asdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-08', '2024-03-08', '17:57:00', '18:56:00', '2024-03-08 09:56:54', '', '', '', 0, ''),
(74, 'Join Join Join Event', 'adfasdf asdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-09', '2024-03-09', '22:40:00', '22:51:00', '2024-03-08 10:24:39', '', '', '', 0, ''),
(75, 'WESMAARRDEC EVENT CREATION TRIAL', '', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-02-08', '2022-02-17', '19:15:00', '19:16:00', '2024-03-08 11:16:21', '', '', '', 0, ''),
(76, 'Try if pwede ', 'asdfads asdfasdf ', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-09', '2024-03-09', '22:59:00', '23:00:00', '2024-03-09 14:56:51', '', '', '', 0, ''),
(77, 'Hello Wesmaardec', 'sdfas asdfasdf sdafasd asdfas', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '00:04:00', '00:19:00', '2024-03-09 16:02:48', '', '', '', 0, ''),
(78, 'view participants', 'sdafsad sadfasdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '11:05:00', '11:07:00', '2024-03-10 03:03:54', '', '', '', 0, ''),
(81, 'Heloo', 'sdfasdf', 'Specialized Seminars', 'Hybrid', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '20:43:00', '20:45:00', '2024-03-10 12:42:23', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0, ''),
(82, 'TRy to join this event', 'sdafasd asdfasd asdfas', 'Cluster-specific gathering', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:11:00', '17:14:00', '2024-03-11 07:04:37', '', '', '', 0, ''),
(83, 'Helo helo', 'asdfasd asdfasd', 'Cluster-specific gathering', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:02:00', '17:03:00', '2024-03-11 08:19:18', '', '', '', 0, ''),
(84, 'History', 'sdfas asdfasd adfasf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '16:34:00', '16:36:00', '2024-03-11 08:34:21', '', '', '', 0, ''),
(85, 'sadfsdsdfasd', 'asdasdfasdf', 'Training Sessions', 'Face-to-Face', '../admin/img/eventPhoto/wesmaarrdec-removebg-preview.png', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:34:00', '19:31:00', '2024-03-11 09:31:53', '', '', '', 0, ''),
(86, 'mock defense', 'dasfasd asdfasdf sadfasf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-12', '2024-03-12', '14:13:00', '15:15:00', '2024-03-11 14:09:08', '', '', '', 0, ''),
(87, 'asdfasfasfasfasfasdf', 'fasfs', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '22:20:00', '23:20:00', '2024-03-11 14:20:47', '', '', '', 0, ''),
(88, 'Networking Gala', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo eget magna fermentum, sit amet fermentum sapien tincidunt.', 'Cluster-specific gathering', 'Face-to-Face', '../admin/img/eventPhoto/wesmaarrdec-removebg-preview.png', 'Tetuan, Zamboanga City', '2018-01-04', '2018-01-16', '13:58:00', '17:58:00', '2024-03-11 17:58:58', '', '', '', 0, ''),
(89, 'Conference on Innovation', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo eget magna fermentum, sit amet fermentum sapien tincidunt.', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2018-01-17', '2018-01-18', '14:00:00', '16:00:00', '2024-03-11 18:00:31', '', '', '', 0, ''),
(90, 'Art Exhibition', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu justo a neque viverra posuere.', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2018-01-23', '2018-01-26', '15:02:00', '17:01:00', '2024-03-11 18:01:47', '', '', '', 0, ''),
(92, ' Health and Wellness Expo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget neque vel est imperdiet suscipit eu at leo.', 'Specialized Seminars', 'Online', '', '', '2018-02-02', '2024-03-02', '14:04:00', '15:04:00', '2024-03-11 18:04:28', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0, ''),
(93, 'Literature Symposiu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu tellus et tellus feugiat cursus eget eu mauris.', 'Training Sessions', 'Hybrid', '', 'Tugbungan, Zamboanga City', '2018-02-12', '2018-02-13', '15:05:00', '16:05:00', '2024-03-11 18:05:56', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0, ''),
(119, 'asdfas sadfsf', '', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-06', '2019-01-07', '09:00:00', '11:00:00', '2024-03-11 23:56:54', '', '', '', 0, ''),
(120, 'asdfas fasdfas', 'adfasfa', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-04-02', '2019-04-03', '14:00:00', '15:00:00', '2024-03-11 23:57:53', '', '', '', 0, ''),
(121, 'sadfsadfa', 'sfasdfa\r\n', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-03-02', '2019-03-05', '11:00:00', '13:00:00', '2024-03-11 23:59:15', '', '', '', 0, ''),
(122, 'asdfasdf', 'sadfasd ', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-05-01', '2019-05-02', '12:00:00', '13:00:00', '2024-03-12 00:00:52', '', '', '', 0, ''),
(123, 'safdf asdfasdfas', 'asdfasdfas', 'Training Sessions', 'Online', '', '', '2019-06-01', '2019-06-05', '13:00:00', '14:00:00', '2024-03-12 00:01:32', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0, ''),
(124, 'adsfasdf', 'afasdfa', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-01', '2020-01-02', '13:00:00', '14:00:00', '2024-03-12 00:05:01', '', '', '', 0, ''),
(125, 'asdfasdfasf', 'asdfasfas', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-04', '2020-01-05', '12:00:00', '13:00:00', '2024-03-12 00:05:54', '', '', '', 0, ''),
(126, 'asdfasfasd sadas', 'asdfasf', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-02-02', '2020-02-04', '13:00:00', '14:00:00', '2024-03-12 00:06:37', '', '', '', 0, ''),
(127, 'sdfas', 'asdfasd', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-02-06', '2020-02-08', '13:00:00', '14:00:00', '2024-03-12 00:07:39', '', '', '', 0, ''),
(128, 'asdfas asdf asf', 'asdfas', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-03-01', '2020-03-04', '13:00:00', '14:59:00', '2024-03-12 00:08:23', '', '', '', 0, ''),
(129, 'asdfas', 'sadfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-04-01', '2020-04-02', '13:00:00', '14:00:00', '2024-03-12 00:08:57', '', '', '', 0, ''),
(130, 'afsdfaw fawweasdf ', 'asdfaswe wefwa', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-05-01', '2020-05-03', '13:00:00', '14:00:00', '2024-03-12 00:09:42', '', '', '', 0, ''),
(131, 'asdfa sfwfa', 'sdafasd weaf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-07-01', '2020-07-02', '13:00:00', '14:00:00', '2024-03-12 00:10:18', '', '', '', 0, ''),
(132, 'ggggg', 'ggggg', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-06-03', '2020-06-05', '13:00:00', '14:00:00', '2024-03-12 00:11:24', '', '', '', 0, ''),
(133, 'jhljkhj', 'jhkhlkjb', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-08-13', '2020-08-31', '12:00:00', '13:00:00', '2024-03-12 00:12:10', '', '', '', 0, ''),
(134, 'dafas dasdfasdfas', 'adsfas wefawef', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-09-02', '2020-09-04', '13:00:00', '13:00:00', '2024-03-12 00:13:15', '', '', '', 0, ''),
(135, 'dasf w', 'fww', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-10-02', '2020-10-28', '12:59:00', '13:01:00', '2024-03-12 00:13:54', '', '', '', 0, ''),
(136, 'sdfaswe afawfw', 'faskjkjhrflifhjkdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-11-01', '2020-11-21', '11:00:00', '13:00:00', '2024-03-12 00:14:29', '', '', '', 0, ''),
(137, 'dasfwfwewfw', 'sdfawf wefwa', 'Cluster-specific gathering', 'Hybrid', '', 'Tetuan, Zamboanga City', '2020-12-01', '2020-12-30', '12:59:00', '14:00:00', '2024-03-12 00:15:09', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0, ''),
(139, 'jhjhljk', 'jhkhlk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-02-05', '2022-03-06', '13:00:00', '14:00:00', '2024-03-12 00:19:45', '', '', '', 0, ''),
(140, 'hgggg', 'hhhhhh', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-06-02', '2022-06-03', '13:00:00', '14:59:00', '2024-03-12 00:20:28', '', '', '', 0, ''),
(143, 'jkhlkhkj', 'jhljhk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2023-02-01', '2023-02-04', '13:00:00', '14:59:00', '2024-03-12 00:22:35', '', '', '', 0, ''),
(212, 'NZRO 2015', '', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city city city', '2024-10-26', '2024-10-27', '08:00:00', '17:00:00', '2024-10-25 15:17:55', '', '', '', 2, ''),
(214, 'NZRO NZRO 2099', 'Nzro D Roger', 'Training Sessions', 'Face-to-Face', '', 'zamboanga  citycitycitycitycity', '2024-10-27', '2024-11-11', '08:00:00', '17:00:00', '2024-10-25 15:26:18', '', '', '', 8, ''),
(289, 'dsadasdsakl;klqweq', 'qewew', 'Training Sessions', 'Face-to-Face', '', 'zamboanga citywqeq', '2024-10-30', '2024-11-02', '08:00:00', '17:00:00', '2024-10-31 08:08:21', '', '', '', 1, ''),
(290, 'YOYOY', '23123', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city12312', '2024-11-26', '2024-11-27', '08:00:00', '17:00:00', '2024-10-31 08:08:57', '', '', '', 12, ''),
(291, 'SAMMY DAY', '123', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city12312313', '2024-11-09', '2024-11-10', '08:00:00', '17:00:00', '2024-10-31 08:10:05', '', '', '', 1, ''),
(292, 'Alabasta12345', 'sdasd', 'Training Sessions', 'Face-to-Face', '', '343', '2024-10-31', '2024-11-09', '08:00:00', '17:00:00', '2024-10-31 10:10:42', '', '', '', 3, ''),
(294, 'Sabaodyert', 'sxsda', 'Training Sessions', 'Face-to-Face', '', '333ffff', '2024-11-02', '2024-11-09', '08:00:00', '17:00:00', '2024-10-31 10:32:57', '', '', '', 4, ''),
(303, 'Sabaody123455', 'asdasd', 'Training Sessions', 'Face-to-Face', '', '12344', '2024-11-01', '2024-11-02', '08:00:00', '17:00:00', '2024-10-31 10:14:04', '', '', '', 5, ''),
(304, 'Sabaody', '1212312asd', 'Training Sessions', 'Face-to-Face', '', 'wdqwdqd', '2024-11-01', '2024-11-09', '08:00:00', '17:00:00', '2024-10-31 14:02:57', '', '', '', 7, ''),
(306, 'Slam Dunk The Movie', 'Ang Henyong si Sakuragi', 'Specialized Seminars', 'Face-to-Face', '../admin/img/eventPhoto/event_672397059e5b27.58931434.jpg', 'inter high', '2024-11-09', '2024-11-16', '08:00:00', '17:00:00', '2024-10-31 14:41:09', '', '', '', 10, ''),
(310, 'Paramount War', 'Oyajiiiiiiiiiiiiiiiiiiiiiiiiiiii', 'Training Sessions', 'Face-to-Face', '', 'world government hq', '2024-11-02', '2024-11-04', '08:00:00', '17:00:00', '2024-10-31 14:44:16', '', '', '', 500, ''),
(316, 'Haikyu', 'Dumpster Battle', 'Cluster-specific gathering', 'Face-to-Face', '', 'grandline1111455', '2024-11-09', '2024-11-12', '08:00:00', '17:00:00', '2024-10-31 15:01:52', '', '', '', 5, ''),
(317, 'Sabaody456hgfhhg', 'adsda', 'Training Sessions', 'Online', '', '', '2024-11-22', '2024-11-27', '08:00:00', '17:00:00', '2024-10-31 15:00:36', 'sassdasdad', '', '', 4, ''),
(319, 'Elbaph Arc', 'Ragnarok is Coming?', 'Training Sessions', 'Face-to-Face', '../admin/img/eventPhoto/event_672398b1193da9.49575860.jpg', 'elbaph', '2024-11-06', '2024-11-08', '08:00:00', '17:00:00', '2024-10-31 14:48:17', '', '', '', 23, ''),
(320, 'Dragon Ball Daima', 'Dende has been kidnapped', 'Training Sessions', 'Hybrid', '', 'grandline1111', '2024-11-16', '2024-11-23', '08:00:00', '17:00:00', '2024-10-31 15:17:36', 'sassdasdad', '', '', 4, ''),
(321, 'Beef Pares', 'sssssssssssssssss', 'Specialized Seminars', 'Face-to-Face', '', 'grandline zamboanga', '2024-11-18', '2024-11-30', '08:00:00', '17:00:00', '2024-10-31 15:18:57', '', '', '', 4, ''),
(322, 'NBA Finals', '123456', 'Training Sessions', 'Online', '', '', '2024-11-30', '2024-11-30', '08:00:00', '17:00:00', '2024-10-31 15:20:04', 'sassdasdad', '', '', 10, ''),
(324, 'Sabaody buuuu', 'ssssssssssss', 'Workshop', 'Hybrid', '', 'grandline', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:00:13', 'sassdasdad', '', '', 2, ''),
(325, 'Alabasta Arc QWERT', 'SADASDA', 'Training Sessions', 'Face-to-Face', '', 'grandline1111', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-10-31 23:59:08', '', '', '', 8, ''),
(327, 'Sabaody12311111133333333', 'sdaddddddddddddd', 'Training Sessions', 'Face-to-Face', '', 'grandline qwertsda', '2024-11-17', '2024-11-20', '08:00:00', '17:00:00', '2024-11-01 00:12:39', '', '', '', 3, ''),
(329, 'Sabaody123csacc', 'asdadcdscscsdcs', 'Workshop', 'Face-to-Face', '', 'ascascasc', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:12:09', '', '', '', 9, ''),
(330, 'sasSSsdfdf', 'asAsdfsf', 'Training Sessions', 'Face-to-Face', '', 'grandline 44', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:15:49', '', '', '', 3, ''),
(331, 'asSs', 'SsaSas', 'Training Sessions', 'Face-to-Face', '', 'sasssss', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:15:20', '', '', '', 6, ''),
(332, 'Sabaody11222222222', 'adsdasdasdas', 'Specialized Seminars', 'Face-to-Face', '', 'grandline qwertdasdad', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:14:53', '', '', '', 10, ''),
(333, 'Slam DunkSlam DunkSlam DunkSlam Dunk', 'Slam DunkSlam Dunk', 'Specialized Seminars', 'Face-to-Face', '', 'slam dunkslam dunkslam dunk', '2024-11-05', '2024-11-07', '08:00:00', '17:00:00', '2024-11-01 00:19:35', '', '', '', 4, ''),
(334, 'SabaodySabaodySabaody', 'SabaodySabaody', 'Specialized Seminars', 'Face-to-Face', '', 'sabaodysabaodysabaody', '2024-11-04', '2024-11-06', '08:00:00', '17:00:00', '2024-11-01 00:18:51', '', '', '', 3, ''),
(335, 'BlueLockBlueLockBlueLockBlueLock', 'ssBlueLockBlueLock', 'Training Sessions', 'Face-to-Face', '', 'bluelockbluelockbluelock', '2024-11-16', '2024-11-18', '08:00:00', '17:00:00', '2024-11-01 00:18:09', '', '', '', 3, ''),
(336, 'Pirates Of The Caribean', 'Pirates Of The Caribean', 'Specialized Seminars', 'Face-to-Face', '', 'pirates of the caribean', '2023-11-04', '2023-11-13', '08:00:00', '17:00:00', '2024-11-01 00:35:27', '', '', '', 4, 'Students'),
(338, 'Avengers', 'AvengersAvengersAvengersAvengers', 'Training Sessions', 'Online', '', '', '2024-12-15', '2024-12-16', '08:00:00', '17:00:00', '2024-11-01 00:33:29', 'AvengersAvengersAvengers', '', '', 5, 'Women'),
(339, 'BlueLock1213123', 'wsqwdwd', 'Training Sessions', 'Face-to-Face', '', 'grandline qwertedd', '2024-11-07', '2024-11-09', '08:00:00', '17:00:00', '2024-11-03 08:20:20', '', 'Floowwwwwwww', 'Cancelled', 8, ''),
(340, 'Sabaody123ffff', 'adasdasdasd', 'Training Sessions', 'Face-to-Face', '', 'grandlinedsadas', '2024-11-05', '2024-11-07', '08:00:00', '17:00:00', '2024-11-03 11:23:36', '', '', '', 10, 'Men'),
(341, 'Sabaodyawdadad', 'wdawdawdaw', 'Training Sessions', 'Face-to-Face', '', 'grandline1111212e', '2024-11-05', '2024-11-07', '08:00:00', '17:00:00', '2024-11-03 11:25:31', '', 'No Meattt', 'Cancelled', 10, ''),
(342, 'Sabaody13qwe', 'saxs', 'Training Sessions', 'Face-to-Face', '', 'grandline111112123', '2024-11-05', '2024-11-06', '08:00:00', '17:00:00', '2024-11-03 08:18:07', '', '', '', 10, ''),
(343, 'Sabaody2e3wedesd', 'fsfs', 'Training Sessions', 'Face-to-Face', '', 'grandline', '2024-11-05', '2024-11-06', '08:00:00', '17:00:00', '2024-11-03 11:31:27', '', '123', 'Cancelled', 44, 'Men');

-- --------------------------------------------------------

--
-- Table structure for table `event_agency`
--

CREATE TABLE `event_agency` (
  `agencyID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `purpose` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_facilitator`
--

CREATE TABLE `event_facilitator` (
  `facilitatorID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `task` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_mode`
--

CREATE TABLE `event_mode` (
  `event_mode_id` int(11) NOT NULL,
  `event_mode_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event_mode`
--

INSERT INTO `event_mode` (`event_mode_id`, `event_mode_name`) VALUES
(1, 'Face-to-Face'),
(2, 'Online'),
(4, 'Hybrid'),
(6, 'Punch to Punch');

-- --------------------------------------------------------

--
-- Table structure for table `event_type`
--

CREATE TABLE `event_type` (
  `event_type_id` int(11) NOT NULL,
  `event_type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event_type`
--

INSERT INTO `event_type` (`event_type_id`, `event_type_name`) VALUES
(3, 'Training Sessions'),
(4, 'Specialized Seminars'),
(5, 'Cluster-specific gathering'),
(6, 'General Assembly'),
(7, 'Workshop');

-- --------------------------------------------------------

--
-- Table structure for table `facilitator`
--

CREATE TABLE `facilitator` (
  `facilitatorID` int(15) NOT NULL,
  `facilitatorFname` varchar(100) NOT NULL,
  `facilitatorMname` varchar(100) NOT NULL,
  `facilitatorLname` varchar(100) NOT NULL,
  `Agency` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `expires_at`) VALUES
('Admin@gmail.com', '290fd5eacd165f5b052e49ec6cd0effda924c9e4d31612de4d2463d8ff9d8f8f', '2024-10-24 13:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `pendingevents`
--

CREATE TABLE `pendingevents` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_mode` varchar(50) NOT NULL,
  `event_photo_path` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_link` text DEFAULT NULL,
  `cancelReason` text NOT NULL,
  `event_cancel` varchar(255) NOT NULL,
  `participant_limit` int(11) NOT NULL,
  `audience_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendingspeaker`
--

CREATE TABLE `pendingspeaker` (
  `speaker_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `speaker_firstName` varchar(255) NOT NULL,
  `speaker_MI` varchar(255) DEFAULT NULL,
  `speaker_lastName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendingsponsor`
--

CREATE TABLE `pendingsponsor` (
  `sponsor_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `sponsor_firstName` varchar(255) NOT NULL,
  `sponsor_MI` varchar(255) DEFAULT NULL,
  `sponsor_lastName` varchar(255) DEFAULT NULL,
  `sponsor_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendinguser`
--

CREATE TABLE `pendinguser` (
  `PendingUserID` int(11) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MI` varchar(2) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `ContactNo` varchar(15) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Affiliation` varchar(50) NOT NULL,
  `Position` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Image` varchar(200) NOT NULL,
  `Role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speaker`
--

CREATE TABLE `speaker` (
  `speaker_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `speaker_firstName` varchar(255) NOT NULL,
  `speaker_MI` varchar(255) DEFAULT NULL,
  `speaker_lastName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speaker`
--

INSERT INTO `speaker` (`speaker_id`, `event_id`, `speaker_firstName`, `speaker_MI`, `speaker_lastName`) VALUES
(10, 291, 'Brook', 'D', 'Bone'),
(11, 292, 'Brook', 'D', 'Bone'),
(23, 304, 'Traaffy', 'dqwd', 'qdqw'),
(24, 310, 'Sakazuki', 'A', 'Akainu'),
(25, 310, 'Kuzan', '', 'Aokiji'),
(26, 310, 'Borsalino', '', 'Kizaru'),
(27, 294, 'Brook1', 'D3', 'Bone5'),
(28, 306, 'Takenori', 'A', 'Akagi'),
(29, 306, 'Checheck', 'Ko', 'Yan'),
(34, 317, 'Brook1', 'D3', 'Bone'),
(35, 316, 'Takenori', 'D', 'Bone'),
(36, 320, 'Brook', 'D', 'Bone'),
(37, 320, 'Checheck', 'Ko', 'Yan'),
(38, 320, 'Borsalino', 'a', 'Kizaru'),
(39, 320, 'sda', 'dsas', 'AS'),
(40, 320, 'asdas', 'da', 'asda'),
(41, 321, 'Brook123', 'D23', 'Bone5'),
(42, 321, 'Kuzan', '1', 'Yan'),
(43, 322, 'Brook1', 'D23', 'Bone5'),
(48, 325, 'Brook123', 'D23', 'Bone'),
(49, 325, '123', '1', 'Yan'),
(50, 325, 'Borsalino', 'a', 'Kizaru'),
(51, 325, 'sda', 'dsas', 'AS'),
(52, 325, 'asdas', 'da', 'asda'),
(53, 324, 'Brook1', 'D34', 'Akagi'),
(54, 324, 'Drogy', 'Ko', ' Blue Ogre'),
(55, 324, 'Hadru', 'a', 'Kizaru'),
(63, 329, 'w', 'asAS', 'qdqw'),
(64, 329, '1234', 'the', ' Blue Ogre'),
(65, 327, 'sdas', 'dasd', 'dasd'),
(66, 330, 'dsfsd', 'dfsdf', 'fsdf'),
(67, 335, 'BlueLock', 'BlueLock', 'BlueLock'),
(68, 334, 'Sabaody', 'Sabaody', 'Sabaody'),
(69, 334, 'Sabaody', 'Sabaody', 'Sabaody'),
(70, 333, 'Slam DunkSlam Dunk', 'Slam DunkSlam Dunk', 'Slam DunkSlam Dunk'),
(97, 343, 'sws', 'D34', 'Akagi'),
(98, 341, 'Brook123', 'D', 'Bone'),
(99, 336, 'Pirates Of The Caribean', '', 'Pirates Of The Caribean'),
(100, 336, 'Pirates Of The Caribean', 'Pirates Of The Caribean', ''),
(101, 336, 'Pirates Of The Caribean', '', 'Pirates Of The Caribean'),
(102, 336, 'Pirates Of The Caribean', '', 'Pirates Of The Caribean'),
(103, 336, 'Pirates Of The Caribean', '', 'Pirates Of The Caribean'),
(114, 338, 'Avengers', 'Avengers', 'Avengers'),
(115, 338, 'v', 'Avengers', 'Avengers'),
(116, 338, 'Avengers', 'Avengers', 'Avengers'),
(117, 338, 'Avengers', 'Avengers', 'Avengers'),
(118, 338, 'Avengers', 'Avengers', 'Avengers');

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `sponsor_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `sponsor_firstName` varchar(255) NOT NULL,
  `sponsor_MI` varchar(255) DEFAULT NULL,
  `sponsor_lastName` varchar(255) DEFAULT NULL,
  `sponsor_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`sponsor_id`, `event_id`, `sponsor_firstName`, `sponsor_MI`, `sponsor_lastName`, `sponsor_Name`) VALUES
(102, 340, '', NULL, NULL, 'Monkey D Luffy'),
(111, 343, '', NULL, NULL, 'Monkey D Luffy'),
(112, 341, '', NULL, NULL, 'Monkey D Luffy12'),
(113, 339, '', NULL, NULL, 'Monkey D Luffy12'),
(114, 339, '', NULL, NULL, 'Roronoa Zoro12'),
(115, 339, '', NULL, NULL, 'Vinsmoke Sanji123'),
(116, 336, '', NULL, NULL, 'Pirates Of The Caribean'),
(117, 336, '', NULL, NULL, '123'),
(118, 338, '', NULL, NULL, 'Monkey D Luffy');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(15) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MI` varchar(2) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Age` int(50) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ContactNo` varchar(15) NOT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Affiliation` varchar(50) NOT NULL,
  `Position` varchar(50) NOT NULL,
  `Image` varchar(250) DEFAULT NULL,
  `EducationalAttainment` varchar(200) DEFAULT NULL,
  `Role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `LastName`, `FirstName`, `MI`, `Gender`, `Age`, `Email`, `Password`, `ContactNo`, `Address`, `Affiliation`, `Position`, `Image`, `EducationalAttainment`, `Role`) VALUES
(6, '', 'Wesmaarrdecdasd1234567', 'S.', 'Female', 33223, 'Wesmaarrdec', '$2y$10$JhUGpnj9B0.1/NsMA9nWqOSV1DhncgHKn9vJPtz8m7ONQEmj2aOGy', '09039837486', 'Sta.Maria', 'Wesmaarrdec', 'member', '', NULL, 'User'),
(7, 'Casinillo', 'Antonio Jay III', 'M.', '', NULL, 'ajay@gmail.com', '$2y$10$besPhaSe31BcrfLoeIvQPezM3.kHs3sFsFcGaqHPFzBQdTvJW1IJ.', '09245747382', 'Lamitan', 'Wesmaarrdec', 'Administrative', '', NULL, 'User'),
(15, '', 'padwa@gmail.com', 'L.', 'Male', 9, 'wesmaarrdec2w12w', '$2y$10$igWaNBeQyeOnGjyknSSBU.SzFt3G.zMFPbDl5bwJ5oZiRlyrWipyS', '0997282014', 'Ayala', 'wesmaarrdec', 'member', 'padwa.png', NULL, 'User'),
(16, 'Beligolo', 'Dazai', 'N.', '', NULL, 'dazai@gmail.com', '$2y$10$V6G6INsuRc3bZddU1vP8BO9bOSJOODQ3ydl05.o.OplzegGfNgZnS', '241037198', 'tugbungan', 'PET', 'Boss', NULL, NULL, 'User'),
(17, 'Beligolo', 'Raiza', 'S.', '', NULL, 'raiza@gmail.com', '$2y$10$mRhqGNXI1zU9yyowGLReBO/MyfYOmen7OPV8jS5H72P.JSIPsrm0q', '09776702283', 'tugbungan', 'CCS', 'Project Manager', '', NULL, 'User'),
(19, 'Reyes', 'Ashley', 'S.', '', NULL, 'ashley@gmail.com', '$2y$10$TAdfDA0su/nN.cLEb6wgFuoKlxPaBjjwkoAphNPMTpbKVFFTSMZFi', '0990847987', 'Tetutan', 'WESMAARRDEC', 'Project Manager', 'profilePhotofaustine.jpg', NULL, 'User'),
(21, 'Abule', 'ZIld', 'J.', '', NULL, 'zild@gmail.com', '$2y$10$gVU0jo8xVCKwWqqWOoYbLexyoYqjUyDQ9.6d.RXbrpfGdDFO9Ma4.', '1231231231', NULL, 'CCS', 'tester', NULL, NULL, 'User'),
(23, 'Villares', 'Arp', '', 'Female', NULL, 'arp@gmail.com', '$2y$10$9m6lUl9FXI/Okntab8l8yeRSkyyRGY3vJGD/.YPh.qizNOk.UbY/e', '090909', 'Tetutan, Zamboanga City', 'CCS', 'tester', '', NULL, 'User'),
(25, 'Policarpio', 'Jhong', '', 'male', NULL, 'jhong@gmail.com', '$2y$10$jTSUCUObKu/JYNAOslXJQOFgbYNBGRupssintIWVHjvQKG7Yu3oO2', '123123', NULL, 'CCS', 'Project Manager', NULL, NULL, 'User'),
(26, 'Delica', 'Faustine', '', 'Female', NULL, 'faustine@gmail.com', '$2y$10$wc6onDJlMM8w/29UEhDNU.S22XT77kxk1p8t52.Lnwekm9FmRW5p6', '09090909', 'baliwasan', 'CCS', 'secretary', '', NULL, 'User'),
(27, 'Luffy', 'Monkey', 'D', 'Male', NULL, 'binimaloi352@gmail.com', '$2y$10$gVU0jo8xVCKwWqqWOoYbLexyoYqjUyDQ9.6d.RXbrpfGdDFO9Ma4.', '12131', 'Street', '123', 'CEO', '', NULL, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`agencyID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `participant_id` (`participant_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `audience_type`
--
ALTER TABLE `audience_type`
  ADD PRIMARY KEY (`audience_type_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `cancel_reason`
--
ALTER TABLE `cancel_reason`
  ADD PRIMARY KEY (`cancel_id`),
  ADD KEY `cancel_reason_ibfk_1` (`event_id`),
  ADD KEY `cancel_reason_ibfk_2` (`UserID`);

--
-- Indexes for table `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`DirectorID`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `evaluation_ibfk_1` (`participant_id`),
  ADD KEY `evaluation_ibfk_2` (`event_id`);

--
-- Indexes for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_mode`
--
ALTER TABLE `event_mode`
  ADD PRIMARY KEY (`event_mode_id`);

--
-- Indexes for table `event_type`
--
ALTER TABLE `event_type`
  ADD PRIMARY KEY (`event_type_id`);

--
-- Indexes for table `facilitator`
--
ALTER TABLE `facilitator`
  ADD PRIMARY KEY (`facilitatorID`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- Indexes for table `pendingevents`
--
ALTER TABLE `pendingevents`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  ADD PRIMARY KEY (`speaker_id`),
  ADD KEY `pendingspeaker_ibfk_1` (`event_id`);

--
-- Indexes for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  ADD PRIMARY KEY (`sponsor_id`),
  ADD KEY `pendingsponsor_ibfk_1` (`event_id`);

--
-- Indexes for table `pendinguser`
--
ALTER TABLE `pendinguser`
  ADD PRIMARY KEY (`PendingUserID`);

--
-- Indexes for table `speaker`
--
ALTER TABLE `speaker`
  ADD PRIMARY KEY (`speaker_id`),
  ADD KEY `speaker_ibfk_1` (`event_id`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`sponsor_id`),
  ADD KEY `sponsor_ibfk_1` (`event_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

--
-- AUTO_INCREMENT for table `audience_type`
--
ALTER TABLE `audience_type`
  MODIFY `audience_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cancel_reason`
--
ALTER TABLE `cancel_reason`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `director`
--
ALTER TABLE `director`
  MODIFY `DirectorID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `event_mode`
--
ALTER TABLE `event_mode`
  MODIFY `event_mode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event_type`
--
ALTER TABLE `event_type`
  MODIFY `event_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `facilitator`
--
ALTER TABLE `facilitator`
  MODIFY `facilitatorID` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pendingevents`
--
ALTER TABLE `pendingevents`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  MODIFY `speaker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `pendinguser`
--
ALTER TABLE `pendinguser`
  MODIFY `PendingUserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `speaker`
--
ALTER TABLE `speaker`
  MODIFY `speaker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `eventparticipants` (`participant_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `cancel_reason`
--
ALTER TABLE `cancel_reason`
  ADD CONSTRAINT `cancel_reason_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `cancel_reason_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `eventparticipants` (`participant_id`),
  ADD CONSTRAINT `evaluation_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD CONSTRAINT `eventparticipants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `eventparticipants_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  ADD CONSTRAINT `pendingspeaker_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `pendingevents` (`event_id`);

--
-- Constraints for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  ADD CONSTRAINT `pendingsponsor_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `pendingevents` (`event_id`);

--
-- Constraints for table `speaker`
--
ALTER TABLE `speaker`
  ADD CONSTRAINT `speaker_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD CONSTRAINT `sponsor_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
