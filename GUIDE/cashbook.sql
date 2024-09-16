-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2024 at 01:02 PM
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
-- Database: `cashbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `income_expense`
--

CREATE TABLE `income_expense` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT curdate(),
  `details` varchar(255) DEFAULT NULL,
  `credit` decimal(15,0) DEFAULT NULL,
  `debit` decimal(15,0) DEFAULT NULL,
  `balance` decimal(15,0) DEFAULT NULL,
  `expense_type` enum('Office','Personal') DEFAULT NULL,
  `edited` enum('yes','no') DEFAULT 'no',
  `reporting` enum('yes','no') DEFAULT 'yes',
  `time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('SU','Admin','Manager','Dealer','User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `reg_date`, `username`, `email`, `password`, `role`) VALUES
(1, '2024-09-10 10:18:15', 'dawigner', 'info@dawigner.com', '$2y$10$sSwtT7EVYvGSWfCew07bUOuJG9dFJWku3FfsJfxWG9KZQCNL.x0UW', 'User'),
(2, '2024-09-10 10:19:38', 'adreesch', 'info@adrees.com', '$2y$10$f0tv/HMh7NOY62sVLh/M7uzCvBRao.ryQut6kNQqYnmXFWTpPy26y', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `income_expense`
--
ALTER TABLE `income_expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `income_expense`
--
ALTER TABLE `income_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
