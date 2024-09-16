-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2024 at 12:43 PM
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

--
-- Dumping data for table `income_expense`
--

INSERT INTO `income_expense` (`id`, `date`, `details`, `credit`, `debit`, `balance`, `expense_type`, `edited`, `reporting`, `time`) VALUES
(1, '2024-09-10', 'Paid Electricty Bill', NULL, 14000, -20000, 'Office', 'yes', 'yes', '14:37:47'),
(2, '2024-09-10', 'Office Rent', NULL, 46000, -66000, 'Office', 'yes', 'yes', '14:38:02'),
(5, '2024-09-10', 'payment from SSK consultants', 70000, NULL, 4000, 'Office', 'yes', 'yes', '16:01:48'),
(6, '2024-08-29', 'Paid Office rent', NULL, 46000, -6000, 'Office', 'no', 'yes', '16:08:58'),
(7, '2024-08-23', 'Payment from Al Anas', 40000, NULL, 40000, 'Office', 'no', 'yes', '16:10:14'),
(8, '2024-09-11', 'new test income', 26000, NULL, 30000, 'Office', 'yes', 'yes', '16:02:00'),
(9, '2024-09-11', 'new test expense', NULL, 21000, 9000, 'Personal', 'yes', 'yes', '16:02:28'),
(10, '2024-09-12', 'new', NULL, NULL, 9000, 'Office', 'no', 'yes', '11:36:35'),
(11, '2024-09-14', 'Received from sardar estate', 150000, NULL, 159000, NULL, 'no', 'yes', '13:26:33'),
(12, '2024-09-14', 'paid PTCL bill', NULL, 3000, 156000, 'Office', 'yes', 'yes', '13:29:12'),
(13, '2024-09-14', 'Lunch', NULL, 200, 155800, 'Personal', 'no', 'yes', '13:29:34'),
(15, '2024-09-14', 'Payment received From France 40 EUR', 12000, NULL, 167800, '', 'yes', 'yes', '13:30:53'),
(16, '2024-09-14', 'Payment received From Global Lights', 12000, NULL, 179800, '', 'no', 'yes', '13:31:53'),
(17, '2024-09-14', 'Payment received From France 100 EUR', 30000, NULL, 209800, '', 'no', 'yes', '13:32:28');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
