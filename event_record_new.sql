-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 09:12 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
