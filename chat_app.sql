-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2024 at 03:45 PM
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
-- Database: `chat_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `password`, `email`, `status`) VALUES
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'mhshihab.official@gmail.com', 'Active'),
(4, 'abed', '3cc7f617b8b11d87e00fc1a8ac025b06', 'abedshadin@outlook.com', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `message`, `created_at`, `file_path`, `read_status`) VALUES
(287, 2, 'MFZjR1llTDJTckFyUHArTnVFNGF5QT09OjpHTWNLN25yd0s5QXNUWkJMVXN6Y1hnPT0=', '2024-07-27 13:32:36', '', 0),
(288, 2, 'SHZQRSs2ZUFENG4rKzBVdWJlU3YyZz09Ojpwd1hia21vZldhM0RSOUdDUzFzVHNBPT0=', '2024-07-27 13:33:10', '', 0),
(289, 2, 'MTdITGV5d0RXRG0zZGF3ZVA2c0g4Zz09OjoyYVlYekVDMTNnTnlhOHhmaEZQZUNRPT0=', '2024-07-27 13:35:16', '', 0),
(290, 2, 'Mzh2MENmUjRSaU8vOGlzdnVHMkx1dz09OjphV29FVlpGWUtrSnNFQjIvbWdsUm1RPT0=', '2024-07-27 13:35:18', '', 0),
(291, 2, 'd09kQStIVDQ2TTVDUmdRTXdGYk9tQT09OjpoaWxUc1NLL1RMQzVlMk1Lc2dlNW1BPT0=', '2024-07-27 13:35:21', '', 0),
(292, 2, 'cG1Fc0oxSTlsekVLeDc2bjh1Um1DUT09Ojp4VHlHdGhCNVJtUkNoNWlER2xVTXRBPT0=', '2024-07-27 13:35:24', '', 0),
(293, 2, 'NjN4VlU1YzhsdUtBcXEydnIwUjBaRWNFWUVMU0xVaGlMMGNVTzFhSGZoWT06OmQrL0xDbllTWGEramRhcTdqbXRxaWc9PQ==', '2024-07-27 13:35:32', '', 0),
(294, 2, 'dHRIajNteW5TQzh1RVFzYUVnb016dz09OjpFUXE4RHdEeFFRR0xtampRM3JMaWdRPT0=', '2024-07-27 13:35:40', 'uploads/66a4f7ac61ad0_Rock.png', 0),
(295, 1, 'ZWR6MnlzcUdHZXdxZGlxTXZJU1gyUT09OjpMS294UEF1Zmc4dDVJMzNGYkxzaWNBPT0=', '2024-07-27 13:36:40', '', 0),
(296, 2, 'cHVFNjJRQU9USCtac2twTkp0aHJtZz09OjpQUDc4S2NVczc4cGZLQjNpSWpTbFVRPT0=', '2024-07-27 13:36:52', '', 0),
(297, 1, 'aWVWWFJwbWdlOEFLUGhNSEdrdEFoUT09OjpYdzRRVXFoWmlzUjZXcTNrM01BUW13PT0=', '2024-07-27 13:36:53', '', 0),
(298, 1, 'T3pFZjZ5QmsyRHdnU2N4dU93bHAvQT09Ojo5a0RnK1RvWmZwcGZKRWtkaGFGVDRnPT0=', '2024-07-27 13:37:01', '', 0),
(299, 2, 'a1pSQ1VuT2tLSE9odXo1eDBuVlBldz09Ojpob0l1UGV3SzA1NnljRTZVcGRTR3FnPT0=', '2024-07-27 13:37:05', '', 0),
(300, 2, 'VnAvT1V6ZEs3ZEhTVlVyMk1MVGg4dz09OjpRR2pzZ1EzdjM1OXFFS1g2d01nK0ZBPT0=', '2024-07-27 13:37:54', '', 0),
(301, 2, 'U1dUUEs0MFROM1IzaTVZOHRNb0tFUT09OjpOeThlUythTGpKblpHUGNzaG9zN3h3PT0=', '2024-07-27 13:38:42', '', 0),
(302, 2, 'a2VMckNab2RRbWx1SFVma1BlZXNBdz09Ojozemc1YXgrWHp2SkNXMTJsKzBISWtRPT0=', '2024-07-27 13:43:05', '', 0),
(303, 2, 'V3QxeHo0R0lncnRyUXFJNnFBRWNSQT09OjpTbDNtSDIyY3JrUDR2UDlMcDZ1cXZRPT0=', '2024-07-27 13:43:07', 'uploads/66a4f96b1deb6_rapid.png', 0),
(304, 2, 'SENLbjZlVXFyYnRRVk5RUzV1aHBZZz09OjplODVGbExlVDNSQy9RTHZ0aUNzNzZBPT0=', '2024-07-27 13:44:47', 'uploads/66a4f9cfe800c_Proxifier.exe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `u_status` varchar(255) NOT NULL,
  `online_status` enum('online','offline') DEFAULT 'offline',
  `last_activity` datetime DEFAULT current_timestamp(),
  `typing_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `u_status`, `online_status`, `last_activity`, `typing_status`) VALUES
(1, 'abcd', '$2y$10$VsumVh3d4fIzIndolp4Rf.493qKhSNLcemu9c1si/R1zTrvjLz3xK', 'Active', 'offline', '2024-07-27 19:37:31', 0),
(2, 'abed', '$2y$10$GQLL1ECJs9hw14b74pQ7Q.MdAxS3ojpucy7iSLiiheEK.Pwmoy3XK', 'Active', 'offline', '2024-07-27 19:44:58', 0),
(4, 'Shihab', '$2y$10$o559VlfX88J2jr2DArSstOiYlT5igKHqzH5.08fZ/i0FNp6qWTipK', 'Active', 'offline', '2024-07-26 13:03:49', 0),
(7, 'Rahim', '$2y$10$Mi2ZQyHrcGiZjger4cGSeuG2giW6QVGev8/rteNwMxxy1Y1euut56', 'Active', 'offline', '2024-07-26 13:57:54', 0),
(8, 'Mahdi', '$2y$10$7nvS6brEDjJ3DVQoz.vhXuj1bfbGdsy8kT1aRcGlkcRkjUuv44BB.', 'Active', 'offline', '2024-07-26 14:59:32', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
