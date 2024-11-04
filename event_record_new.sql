-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 09:14 AM
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
(5, 'Casinillo', 'Antonia', 'D.', 'Female', 'Admin2@gmail.com', '$2y$10$NwHhZ1iud/zSr52enuQm5OpKuECWGO02f9B5J7tzizL5Pb/7YdfI2', '09709185816', 'Atis drive, baliwasan,Zamboanga City', 'WMSU', 'Clerk', 'padwa.png', 'Admin'),
(6, 'Tabotabo', 'Larenz', 'B.', '', 'Admin@gmail.com', '$2y$10$AGJKlslsUWzh6jDVZSAN0u8Xa8U.EXP4QDeQc/Uw6CZ5GfB36RGO.', '09352453795', NULL, 'CCS', 'Lead Programmer', 'mark.png', 'Admin'),
(7, 'Tabotabo', 'Larenz', 'B.', '', 'larenz@gmail.com', 'Tabotabo', '123123123', 'Tetutan', 'CCS', 'Lead Programmer', 'mark.png', 'Admin'),
(10, 'Dela Cruz', 'Juan', 'l', 'Male', 'director@gmail.com', '$2y$10$EpXTOqerQtUKQUab..FJLe8o5Nm6RgJxTGxuR48ImC.qyC033u5Ve', '09873526627', 'Normal Road, Baliwasan, Zamboanga City', 'Wesmaarrdec', 'Director', 'wesmaarrdec.jpg', 'Admin'),
(11, 'Beligolo', 'Raiza', '', 'Female', 'ziara@gmail.com', '$2y$10$XW3ZM3iJepGa6B5lX5Z1QenAAkjbLMdIfChQjzHpk7cu2aIr43Yvu', '09776702283', 'Tetutan, Zamboanga City', 'CCS', 'Project Manager', '', 'Admin'),
(12, 'Beligolo', 'Raiza', 'S.', 'Female', 'ziara@gmail.com', '$2y$10$jW5AUsW.ahrO/i8E7ILRoOe5/2n54/D5uyhY.SMw.8CYt7kaHt19O', '09776702283', 'Tetutan, Zamboanga City', 'CCS', 'Project Manager', '', 'Admin'),
(13, 'Beligolo', 'Raiza', 'S.', 'Female', 'ziara@gmail.com', '$2y$10$iz/yw.bG41NEliY4jH9cO.UTWfSff47odNRZ7t8t0FCrPqCttw/YK', '09776702283', 'Tetutan, Zamboanga City', 'CCS', 'Project Manager', '', 'Admin'),
(14, 'Beligolo', 'Raiza', '', 'Female', 'ziara@gmail.com', '$2y$10$bzgljQmztu6jojx.K1CUHe/AurC4aIizgmvHFJQoY2WmZGzjb4pzS', '09776702283', 'Tetuan, Zamboanga City', 'CCS', 'Project Manager', '', 'Admin'),
(15, 'qwe', 'qwe', 'e', 'Male', 'nnadad@gmail.com', '$2y$10$n6F0Y4h2N4BBMXxx/Njgaudh95l1xYdtoKy3aIY9DcRb4/FW7WltW', '213123', 'wqe', 'qweqw', 'e', '', 'Admin');

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
(136, 141, 211, '2024-10-28', 'present', '2024-10-25 14:28:13', NULL, '22:28:13', '22:36:34'),
(137, 141, 211, '2024-10-29', 'present', '2024-10-25 14:37:33', NULL, '22:37:33', '22:37:44'),
(138, 141, 211, '2024-10-30', 'present', '2024-10-25 14:37:56', NULL, '22:37:56', '22:38:03'),
(140, 144, 214, '2024-10-27', 'present', '2024-10-25 15:27:51', NULL, '23:27:51', '23:27:58'),
(141, 145, 214, '2024-10-27', 'present', '2024-10-25 15:27:53', NULL, '23:27:53', '23:27:59'),
(142, 143, 214, '2024-10-28', 'present', '2024-10-25 15:30:47', NULL, '23:30:47', NULL),
(143, 143, 214, '2024-10-27', 'present', '2024-10-26 13:17:55', NULL, '21:17:55', NULL);

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
(5, 50, 26, 'good job'),
(6, 211, 26, 'good job'),
(7, 211, 26, 'g00d job!213'),
(8, 211, 26, 'walang kwenta'),
(9, 211, 26, 'good job'),
(10, 211, 26, 'good job'),
(11, 211, 26, 'sammy d'),
(12, 211, 26, 'yessir'),
(13, 210, 26, 'good job!');

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
  `evaluation_id` INT(11) NOT NULL,
  `participant_id` INT(11) NOT NULL,
  `event_id` INT(11) NOT NULL,
  `evaluation_date` DATE NOT NULL DEFAULT CURRENT_DATE(), 
  `status` ENUM('approved', 'declined', 'no_record') DEFAULT 'no_record',
  `remarks` VARCHAR(255) DEFAULT NULL, 
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 66, 10),
(3, 68, 10),
(4, 64, 10),
(7, 60, 10),
(8, 50, 10),
(9, 58, 10),
(11, 77, 10),
(12, 78, 10),
(13, 79, 10),
(15, 82, 10),
(19, 86, 10),
(117, 209, 6),
(141, 211, 26),
(143, 214, 6),
(144, 214, 15),
(145, 214, 17),
(146, 214, 7),
(147, 214, 10),
(148, 214, 12),
(149, 214, 16),
(150, 214, 18),
(151, 214, 27),
(152, 214, 26),
(153, 214, 25),
(154, 214, 23);

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
  `participant_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_title`, `event_description`, `event_type`, `event_mode`, `event_photo_path`, `location`, `date_start`, `date_end`, `time_start`, `time_end`, `date_created`, `event_link`, `cancelReason`, `event_cancel`, `participant_limit`) VALUES
(50, 'zoom link', 's', 'Training Sessions', 'Face-to-Face', '../admin/img/eventPhoto/wesmaarrdec-removebg-preview.png', 'Tetuan, Zamboanga City', '2024-03-01', '2024-03-01', '20:18:00', '20:21:00', '2024-03-01 12:17:01', 'https://meet.google.com/xux-xsau-zbn', '', '', 0),
(58, 'Pilar&#039;s event', 'asfads asdfasdf', 'Specialized Seminars', 'Online', '../admin/img/eventPhoto/mark.png', '', '2024-01-02', '2024-01-11', '18:30:00', '18:31:00', '2024-03-02 10:24:53', 'https://meet.google.com/xux-xsau-zbn', '', '', 0),
(60, 'Location', 'dasfsdaf sadfasfasd', 'Training Sessions', 'Face-to-Face', '', 'Tugbungan, Zamboanga City', '2024-03-02', '2024-03-02', '19:01:00', '19:01:00', '2024-03-02 11:01:31', '', '', '', 0),
(64, 'loopinggg', 'sdfasfsadf', 'Training Sessions', 'Online', '', '', '2024-03-09', '2024-03-09', '23:42:00', '23:44:00', '2024-03-02 16:46:24', 'https://meet.google.com/xux-xsau-zbn', '', '', 0),
(66, 'hahaha', 'sdfadasd', 'Training Sessions', 'Face-to-Face', '', 'Tugbungan, Zamboanga City', '2021-03-03', '2021-03-12', '01:06:00', '01:06:00', '2024-03-02 17:06:57', '', '', '', 0),
(68, 'Join this event check', 'asdfasdf asdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-08', '2024-03-08', '17:57:00', '18:56:00', '2024-03-08 09:56:54', '', '', '', 0),
(74, 'Join Join Join Event', 'adfasdf asdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-09', '2024-03-09', '22:40:00', '22:51:00', '2024-03-08 10:24:39', '', '', '', 0),
(75, 'WESMAARRDEC EVENT CREATION TRIAL', '', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-02-08', '2022-02-17', '19:15:00', '19:16:00', '2024-03-08 11:16:21', '', '', '', 0),
(76, 'Try if pwede ', 'asdfads asdfasdf ', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-09', '2024-03-09', '22:59:00', '23:00:00', '2024-03-09 14:56:51', '', '', '', 0),
(77, 'Hello Wesmaardec', 'sdfas asdfasdf sdafasd asdfas', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '00:04:00', '00:19:00', '2024-03-09 16:02:48', '', '', '', 0),
(78, 'view participants', 'sdafsad sadfasdfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '11:05:00', '11:07:00', '2024-03-10 03:03:54', '', '', '', 0),
(79, 'Join to view', 'asdfasfda asdfasdf as', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '13:46:00', '13:50:00', '2024-03-10 05:44:46', '', '', '', 0),
(81, 'Heloo', 'sdfasdf', 'Specialized Seminars', 'Hybrid', '', 'Tetuan, Zamboanga City', '2024-03-10', '2024-03-10', '20:43:00', '20:45:00', '2024-03-10 12:42:23', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(82, 'TRy to join this event', 'sdafasd asdfasd asdfas', 'Cluster-specific gathering', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:11:00', '17:14:00', '2024-03-11 07:04:37', '', '', '', 0),
(83, 'Helo helo', 'asdfasd asdfasd', 'Cluster-specific gathering', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:02:00', '17:03:00', '2024-03-11 08:19:18', '', '', '', 0),
(84, 'History', 'sdfas asdfasd adfasf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '16:34:00', '16:36:00', '2024-03-11 08:34:21', '', '', '', 0),
(85, 'sadfsdsdfasd', 'asdasdfasdf', 'Training Sessions', 'Face-to-Face', '../admin/img/eventPhoto/wesmaarrdec-removebg-preview.png', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '17:34:00', '19:31:00', '2024-03-11 09:31:53', '', '', '', 0),
(86, 'mock defense', 'dasfasd asdfasdf sadfasf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-12', '2024-03-12', '14:13:00', '15:15:00', '2024-03-11 14:09:08', '', '', '', 0),
(87, 'asdfasfasfasfasfasdf', 'fasfs', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2024-03-11', '2024-03-11', '22:20:00', '23:20:00', '2024-03-11 14:20:47', '', '', '', 0),
(88, 'Networking Gala', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo eget magna fermentum, sit amet fermentum sapien tincidunt.', 'Cluster-specific gathering', 'Face-to-Face', '../admin/img/eventPhoto/wesmaarrdec-removebg-preview.png', 'Tetuan, Zamboanga City', '2018-01-04', '2018-01-16', '13:58:00', '17:58:00', '2024-03-11 17:58:58', '', '', '', 0),
(89, 'Conference on Innovation', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo eget magna fermentum, sit amet fermentum sapien tincidunt.', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2018-01-17', '2018-01-18', '14:00:00', '16:00:00', '2024-03-11 18:00:31', '', '', '', 0),
(90, 'Art Exhibition', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu justo a neque viverra posuere.', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2018-01-23', '2018-01-26', '15:02:00', '17:01:00', '2024-03-11 18:01:47', '', '', '', 0),
(91, 'Music Festival', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Vivamus fringilla tortor ut risus sagittis, ac tincidunt purus dictum.', 'Cluster-specific gathering', 'Online', '', '', '2018-01-29', '2018-01-31', '14:03:00', '16:03:00', '2024-03-11 18:03:15', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(92, ' Health and Wellness Expo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget neque vel est imperdiet suscipit eu at leo.', 'Specialized Seminars', 'Online', '', '', '2018-02-02', '2024-03-02', '14:04:00', '15:04:00', '2024-03-11 18:04:28', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(93, 'Literature Symposiu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu tellus et tellus feugiat cursus eget eu mauris.', 'Training Sessions', 'Hybrid', '', 'Tugbungan, Zamboanga City', '2018-02-12', '2018-02-13', '15:05:00', '16:05:00', '2024-03-11 18:05:56', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(119, 'asdfas sadfsf', '', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-06', '2019-01-07', '09:00:00', '11:00:00', '2024-03-11 23:56:54', '', '', '', 0),
(120, 'asdfas fasdfas', 'adfasfa', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-04-02', '2019-04-03', '14:00:00', '15:00:00', '2024-03-11 23:57:53', '', '', '', 0),
(121, 'sadfsadfa', 'sfasdfa\r\n', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-03-02', '2019-03-05', '11:00:00', '13:00:00', '2024-03-11 23:59:15', '', '', '', 0),
(122, 'asdfasdf', 'sadfasd ', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2019-05-01', '2019-05-02', '12:00:00', '13:00:00', '2024-03-12 00:00:52', '', '', '', 0),
(123, 'safdf asdfasdfas', 'asdfasdfas', 'Training Sessions', 'Online', '', '', '2019-06-01', '2019-06-05', '13:00:00', '14:00:00', '2024-03-12 00:01:32', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(124, 'adsfasdf', 'afasdfa', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-01', '2020-01-02', '13:00:00', '14:00:00', '2024-03-12 00:05:01', '', '', '', 0),
(125, 'asdfasdfasf', 'asdfasfas', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-01-04', '2020-01-05', '12:00:00', '13:00:00', '2024-03-12 00:05:54', '', '', '', 0),
(126, 'asdfasfasd sadas', 'asdfasf', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-02-02', '2020-02-04', '13:00:00', '14:00:00', '2024-03-12 00:06:37', '', '', '', 0),
(127, 'sdfas', 'asdfasd', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-02-06', '2020-02-08', '13:00:00', '14:00:00', '2024-03-12 00:07:39', '', '', '', 0),
(128, 'asdfas asdf asf', 'asdfas', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-03-01', '2020-03-04', '13:00:00', '14:59:00', '2024-03-12 00:08:23', '', '', '', 0),
(129, 'asdfas', 'sadfasdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-04-01', '2020-04-02', '13:00:00', '14:00:00', '2024-03-12 00:08:57', '', '', '', 0),
(130, 'afsdfaw fawweasdf ', 'asdfaswe wefwa', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-05-01', '2020-05-03', '13:00:00', '14:00:00', '2024-03-12 00:09:42', '', '', '', 0),
(131, 'asdfa sfwfa', 'sdafasd weaf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-07-01', '2020-07-02', '13:00:00', '14:00:00', '2024-03-12 00:10:18', '', '', '', 0),
(132, 'ggggg', 'ggggg', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-06-03', '2020-06-05', '13:00:00', '14:00:00', '2024-03-12 00:11:24', '', '', '', 0),
(133, 'jhljkhj', 'jhkhlkjb', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-08-13', '2020-08-31', '12:00:00', '13:00:00', '2024-03-12 00:12:10', '', '', '', 0),
(134, 'dafas dasdfasdfas', 'adsfas wefawef', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-09-02', '2020-09-04', '13:00:00', '13:00:00', '2024-03-12 00:13:15', '', '', '', 0),
(135, 'dasf w', 'fww', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-10-02', '2020-10-28', '12:59:00', '13:01:00', '2024-03-12 00:13:54', '', '', '', 0),
(136, 'sdfaswe afawfw', 'faskjkjhrflifhjkdf', 'Training Sessions', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2020-11-01', '2020-11-21', '11:00:00', '13:00:00', '2024-03-12 00:14:29', '', '', '', 0),
(137, 'dasfwfwewfw', 'sdfawf wefwa', 'Cluster-specific gathering', 'Hybrid', '', 'Tetuan, Zamboanga City', '2020-12-01', '2020-12-30', '12:59:00', '14:00:00', '2024-03-12 00:15:09', 'https://meet.google.com/sgm-jdfr-ucn?authuser=2', '', '', 0),
(138, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(139, 'jhjhljk', 'jhkhlk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-02-05', '2022-03-06', '13:00:00', '14:00:00', '2024-03-12 00:19:45', '', '', '', 0),
(140, 'hgggg', 'hhhhhh', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-06-02', '2022-06-03', '13:00:00', '14:59:00', '2024-03-12 00:20:28', '', '', '', 0),
(141, 'kkkkkkkkkkk', 'kkkkkkkk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-02-02', '2020-02-02', '22:00:00', '23:00:00', '2024-03-12 00:21:11', '', '', '', 0),
(142, 'asdfasdf weff', 'ddddd', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2023-01-03', '2023-01-04', '13:00:00', '14:00:00', '2024-03-12 00:22:01', '', '', '', 0),
(143, 'jkhlkhkj', 'jhljhk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2023-02-01', '2023-02-04', '13:00:00', '14:59:00', '2024-03-12 00:22:35', '', '', '', 0),
(207, 'try create event', 'sdfasd', 'Specialized Seminars', 'Online', '', '', '2025-09-23', '2025-09-27', '09:43:00', '10:43:00', '2024-04-30 06:44:05', '', 'ikuze', 'Cancelled', 1),
(208, 'data analytics for the future', 'chu chuc chu', 'Specialized Seminars', 'Face-to-Face', '', 'wmsu social hall', '2026-09-26', '2026-10-01', '08:00:00', '17:00:00', '2024-04-30 07:25:11', '', 'Try cancel', 'Cancelled', 3),
(209, 'workshop', 'asdfasdf asdfasdf asdfasd', 'Specialized Seminars', 'Face-to-Face', '', 'wmsu social hall', '2025-12-25', '2025-12-26', '08:44:00', '17:44:00', '2024-04-30 23:45:04', '', 'ikuze', 'Cancelled', 1),
(210, 'NZRO NZRO', '', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city213231', '2028-01-25', '2028-01-26', '08:00:00', '17:00:00', '2024-10-25 12:16:20', '', '', '', 2),
(211, 'NZRO 0505033333', '', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city13', '2024-10-28', '2024-10-30', '08:00:00', '17:00:00', '2024-10-25 14:00:17', '', '', '', 4),
(212, 'NZRO 2015', '', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city city city', '2024-10-26', '2024-10-27', '08:00:00', '17:00:00', '2024-10-25 15:17:55', '', '', '', 2),
(213, 'NZRO NZRO 2020', '', 'Training Sessions', 'Face-to-Face', '', '123333', '2024-10-27', '2024-10-30', '08:00:00', '17:00:00', '2024-10-25 15:21:39', '', '', '', 2),
(214, 'NZRO NZRO 2099', 'Nzro D Roger', 'Training Sessions', 'Face-to-Face', '', 'zamboanga  citycitycitycitycity', '2024-10-27', '2024-11-07', '08:00:00', '17:00:00', '2024-10-25 15:26:18', '', '', '', 5),
(269, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(270, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(271, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(272, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(273, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(274, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(275, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(276, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(277, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(278, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(279, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(280, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(281, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(282, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(283, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(284, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(285, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(286, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(287, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(288, 'kjljkhkjhkjbhb', 'jhjkhjk', 'Specialized Seminars', 'Face-to-Face', '', 'Tetuan, Zamboanga City', '2022-03-01', '2022-03-02', '15:00:00', '16:00:00', '2024-03-12 00:19:10', '', '', '', 0),
(289, 'dsadasdsakl;klqweq', 'qewew', 'Training Sessions', 'Face-to-Face', '', 'zamboanga citywqeq', '2024-11-01', '2024-11-02', '08:00:00', '17:00:00', '2024-10-31 08:08:21', '', '', '', 1),
(290, 'YOYOY', '23123', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city12312', '2024-11-26', '2024-11-27', '08:00:00', '17:00:00', '2024-10-31 08:08:57', '', '', '', 12),
(291, 'SAMMY DAY', '123', 'Training Sessions', 'Face-to-Face', '', 'zamboanga city12312313', '2024-11-09', '2024-11-10', '08:00:00', '17:00:00', '2024-10-31 08:10:05', '', '', '', 1);

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
(4, 'Hybrid');

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
  `participant_limit` int(11) NOT NULL
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
  `sponsor_lastName` varchar(255) DEFAULT NULL
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
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `sponsor_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `sponsor_Name` varchar(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`sponsor_id`, `event_id`, `sponsor_Name`) VALUES
(8, 214, 'Gol Roger'),
(9, 214, 'Monkey Garp');

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
(6, 'Allanah', 'Princess', 'S.', 'Male', 3, 'alyana@gmail.com', '$2y$10$JhUGpnj9B0.1/NsMA9nWqOSV1DhncgHKn9vJPtz8m7ONQEmj2aOGy', '09039837486', 'Sta.Maria', 'Wesmaarrdec', 'member', '', NULL, 'User'),
(7, 'Casinillo', 'Antonio Jay III', 'M.', '', NULL, 'ajay@gmail.com', '$2y$10$besPhaSe31BcrfLoeIvQPezM3.kHs3sFsFcGaqHPFzBQdTvJW1IJ.', '09245747382', 'Lamitan', 'Wesmaarrdec', 'Administrative', '', NULL, 'User'),
(10, 'Tabotabo', 'Mark', 'B.', '', 22, 'mark@gmail.com', '$2y$10$0sFeEwnE5Tj9TjHVL9bbceHKTdRTDx.qtcdmBXKWbLsvwKcwCa2Ai', '0935253795', 'Tetuan, Alvarez Drive Zamboanga City', 'CCS', 'Lead Programmer', 'mark.png', '', 'User'),
(12, 'Balan', 'John', 'B.', '', NULL, 'balan@gmail.com', '$2y$10$PfuN6U07gI5EA12EjNFz0.gzKubsQ/MVCezG9INt8xb8fg0l.pqtW', '412341241', NULL, 'CCS', 'Project Manager', NULL, NULL, 'User'),
(15, 'Tingkasan', 'Padwa', 'L.', '', NULL, 'padwa@gmail.com', '$2y$10$igWaNBeQyeOnGjyknSSBU.SzFt3G.zMFPbDl5bwJ5oZiRlyrWipyS', '0997282014', 'Ayala', 'wesmaarrdec', 'member', 'padwa.png', NULL, 'User'),
(16, 'Beligolo', 'Dazai', 'N.', '', NULL, 'dazai@gmail.com', '$2y$10$V6G6INsuRc3bZddU1vP8BO9bOSJOODQ3ydl05.o.OplzegGfNgZnS', '241037198', 'tugbungan', 'PET', 'Boss', NULL, NULL, 'User'),
(17, 'Beligolo', 'Raiza', 'S.', '', NULL, 'raiza@gmail.com', '$2y$10$mRhqGNXI1zU9yyowGLReBO/MyfYOmen7OPV8jS5H72P.JSIPsrm0q', '09776702283', 'tugbungan', 'CCS', 'Project Manager', '', NULL, 'User'),
(18, 'Tabotabo', 'Haidee', 'N.', '', NULL, 'haidee@gmail.com', '$2y$10$VmprzI/plQ7rg9WAQEz6tOYkWPHks337uGyjCUIHwJ/Qttqevmxwy', '090909', NULL, 'WESMAARRDEC', 'Project Manager', 'padwa.png', NULL, 'User'),
(19, 'Reyes', 'Ashley', 'S.', '', NULL, 'ashley@gmail.com', '$2y$10$TAdfDA0su/nN.cLEb6wgFuoKlxPaBjjwkoAphNPMTpbKVFFTSMZFi', '0990847987', 'Tetutan', 'WESMAARRDEC', 'Project Manager', 'profilePhotofaustine.jpg', NULL, 'User'),
(20, 'Parker', 'Peter', 'B.', '', NULL, 'peter@gmail.com', '$2y$10$GDEYEbgeDwgwB8UGRKUi0u8mSnAZxeFxLCoBkY34xvRtAXGErMYtG', '09352453795', NULL, 'WESMAARRDEC', 'Project Manager', NULL, NULL, 'User'),
(21, 'Abule', 'ZIld', 'J.', '', NULL, 'zild@gmail.com', '$2y$10$27kBwrDT1MM97LIrQZ16l.TJS8eQipj.rnp6SzP.EN5vs.2SmN2aq', '1231231231', NULL, 'CCS', 'tester', NULL, NULL, 'User'),
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
  ADD PRIMARY KEY `evaluation_id` (`evaluation_id`);

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
-- Indexes for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  ADD PRIMARY KEY (`sponsor_id`),
  ADD KEY `pendingsponsor_ibfk_1` (`event_id`);

--
-- Indexes for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  ADD PRIMARY KEY (`speaker_id`),
  ADD KEY `pendingspeaker_ibfk_1` (`event_id`);

--
-- Indexes for table `pendinguser`
--
ALTER TABLE `pendinguser`
  ADD PRIMARY KEY (`PendingUserID`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`sponsor_id`),
  ADD KEY `sponsor_ibfk_1` (`event_id`);

--
-- Indexes for table `speaker`
--
ALTER TABLE `speaker`
  ADD PRIMARY KEY (`speaker_id`),
  ADD KEY `speaker_ibfk_1` (`event_id`);

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
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `cancel_reason`
--
ALTER TABLE `cancel_reason`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `director`
--
ALTER TABLE `director`
  MODIFY `DirectorID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluation`
--

ALTER TABLE `evaluation`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `event_mode`
--
ALTER TABLE `event_mode`
  MODIFY `event_mode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  MODIFY `speaker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pendinguser`
--
ALTER TABLE `pendinguser`
  MODIFY `PendingUserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `speaker`
--
ALTER TABLE `speaker`
  MODIFY `speaker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `pendingsponsor`
--
ALTER TABLE `pendingsponsor`
  ADD CONSTRAINT `pendingsponsor_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `pendingevents` (`event_id`);

--
-- Constraints for table `pendingspeaker`
--
ALTER TABLE `pendingspeaker`
  ADD CONSTRAINT `pendingspeaker_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `pendingevents` (`event_id`);

--
-- Constraints for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD CONSTRAINT `sponsor_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
  
--
-- Constraints for table `speaker`
--
ALTER TABLE `speaker`
  ADD CONSTRAINT `speaker_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
