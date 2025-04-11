-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 12:55 PM
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
-- Database: `laundrydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'brian', 'brian@gmail.com', '$2y$10$8lgJbeUSf2a29O3pgZ5J5u/px9gZKJs.ZVfBBsnj77myu0sSJ7DY2', '2025-03-26 09:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `booking_date` date NOT NULL DEFAULT curdate(),
  `provider_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `email`, `phone`, `booking_date`, `provider_id`, `service_id`, `client_name`, `status`, `created_at`, `notes`) VALUES
(1, 0, '', '', '2025-03-25', 1, 0, 'John Doe', 'Pending', '2025-03-14 04:14:05', NULL),
(2, 5, 'scar@gmail.com', '0768123479', '2025-03-25', 11, 0, '', 'Pending', '2025-03-25 13:30:54', 'location karinde'),
(3, 5, 'scar@gmail.com', '0768123479', '2025-03-25', 11, 0, '', '', '2025-03-25 13:33:13', 'location karinde'),
(4, 5, 'scar@gmail.com', '0768123479', '2025-03-25', 12, 0, '', 'Pending', '2025-03-25 13:33:53', 'location karinde'),
(6, 5, 'scar@gmail.com', '0768123479', '2025-03-26', 15, 0, '', '', '2025-03-26 05:13:16', 'location ngong'),
(7, 5, 'scar@gmail.com', '0768123479', '2025-03-26', 15, 0, '', '', '2025-03-26 06:39:25', 'location');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `provider_id`, `message`, `is_read`, `created_at`) VALUES
(1, 11, 'You have a new booking request from a client.', 0, '2025-03-25 13:33:13'),
(2, 12, 'You have a new booking request from a client.', 0, '2025-03-25 13:33:53'),
(3, 15, 'You have a new booking request from a client.', 0, '2025-03-25 13:35:10'),
(4, 15, 'You have a new booking request from a client.', 0, '2025-03-26 05:13:16'),
(5, 15, 'You have a new booking request from a client.', 0, '2025-03-26 06:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL,
  `services` text NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `rating` float DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `name`, `email`, `password`, `experience`, `services`, `hourly_rate`, `rating`, `status`) VALUES
(1, '0', 'njoroge@gmail.com', '$2y$10$ZKnZlwif9e4voFqBeN8.9ut.S8AIOw1pxhypO0N4BKf/Xs8soHjK.', 3, '0', 10.00, 4.4, 'Approved'),
(4, '0', 'maina@gmail.com', '$2y$10$Z/OxjcYEwXFR/qH36ZkwQure3iDcLlhjx.1GOSKynZNhMkqstsZa2', 3, '0', 10.00, 4.4, 'Rejected'),
(7, '0', 'mine@gmail.com', '$2y$10$bP5lVBA020D5n9F.Eq0O2ePNXC8LR7XVoFjmaevfDt69UgpNVbBn.', 5, '0', 3.00, 5, 'Rejected'),
(11, 'Hillton white', 'white@gmail.com', '$2y$10$44QyJH8xwddyz90E6WXb2.ID/PKclaDzVa.VXYLXKFmZtBK86SMLe', 5, 'Dry Cleaning, Ironing, Fold & Pack, landscaping', 7.00, 0, 'Approved'),
(12, 'Ann ', 'ann@gmail.com', '$2y$10$pPYiGwOmQYDx08E0EQx.VOKFrw7iFdG6U1Yiv6tUrtVWVx3I6rKue', 5, 'Dry Cleaning, Ironing, Stain Removal, Fold & Pack, sanitization', 6.00, 0, 'Approved'),
(13, 'Mary Jane', 'jane@gmail.com', '$2y$10$0DDRE/HoG7scUkkiJWp7JOPJxKTJi26JQDa6iMYOfZq1TbXL/xQlm', 4, 'Dry Cleaning, Ironing, Fold & Pack, general cleaning', 5.00, 0, 'Pending'),
(14, 'alan ken', 'alan@gmail.com', '$2y$10$V21dHu3yY.7Ue33XsLd4RO0AW7ibrD5U4ZpuC6bvuefoqAYfgFn9G', 10, 'Dry Cleaning, Ironing, Stain Removal, Fold & Pack, genaral cleaning', 10.00, 0, 'Approved'),
(15, 'Sarah omah', 'sara@gmail.com', '$2y$10$2wj4J6NupW..KnlCaX01v.7Vf1LgsSTFpPWCCAOBTdDhrdwoDI7Yq', 2, 'Dry Cleaning, Ironing, Fold & Pack, ', 4.00, 0, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rating` float DEFAULT 0,
  `services` text NOT NULL,
  `availability` enum('Available','Busy') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`) VALUES
(1, 'phoebe', 'phoebe@gmail.com', '$2y$10$FZoGeRWX0htdcchYKnEfzOzb0BpAcZly8TVJpOXUIy6A0JXEWYz3G', 'Active'),
(2, 'linda', 'linda@gmail.com', '$2y$10$xMt5sEhsHIrDY4KHUdb3ousJIva/AvB8jC2FcV0G32cIkOfv2OGVO', 'Active'),
(3, 'john', 'john@gmail.com', '$2y$10$34eYGQ1vFo9hSoCv6w0S3uAntpbCDGqwLCZGS4UgKQJZ9/an6A3um', 'Active'),
(4, 'ndungu', 'ngungu@gmail.com', '$2y$10$gr4Bwdj87pKTR5oT7M5.CevNtyJbN4FQQfia7cGEOg3j0IU7QOoc.', 'Active'),
(5, 'scar', 'scar@gmail.com', '$2y$10$Tjp29hevFeuTccRI38TeAORydJCPGChX6majSY3ZIKC5c.DaR1Xa.', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
