-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2026 at 12:03 AM
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
-- Database: `chambeshi_complaints`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Given', 'givennkonde535@gmail.com', '$2y$10$YL0qK3.Y5uzYecOKmadOVOkQ2JtH1AcSdv.4tGuS8l9A5d1Ule382', '2026-01-20 21:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `tracking_id` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `issue` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `previous_issue` enum('yes','no') DEFAULT 'no',
  `status` enum('submitted','in review','in progress','resolved') DEFAULT 'submitted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_note` text DEFAULT NULL,
  `resolution_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `tracking_id`, `user_id`, `room_number`, `issue`, `image_path`, `previous_issue`, `status`, `created_at`, `updated_at`, `admin_note`, `resolution_message`) VALUES
(1, 'CHMB-2026-957895', 1, 'CHA 55', 'Socket problem', 'uploads/issue_1768939177_9216.png', 'no', 'in review', '2026-01-20 19:59:37', '2026-01-20 21:39:49', NULL, NULL),
(2, 'CHMB-2026-711883', 2, 'CHA 60', 'water problem', 'uploads/issue_1768942386_4675.png', 'no', 'in progress', '2026-01-20 20:53:06', '2026-01-20 21:53:17', NULL, NULL),
(3, 'CHMB-2026-250168', 1, 'CHA 55', 'door problem', 'uploads/issue_1768947686_1859.png', 'no', 'submitted', '2026-01-20 22:21:26', '2026-01-20 22:21:26', NULL, NULL),
(4, 'CHMB-2026-898051', 1, 'CHA 55', 'door problem', 'uploads/issue_1768947717_1258.png', 'no', 'resolved', '2026-01-20 22:21:57', '2026-01-20 22:23:15', NULL, NULL),
(5, 'CHMB-2026-931757', 1, 'CHA 55', 'WINDOW', 'uploads/issue_1768949106_9721.png', 'no', 'submitted', '2026-01-20 22:45:06', '2026-01-20 22:45:06', NULL, NULL),
(6, 'CHMB-2026-708349', 1, 'CHA 55', 'WINDOW', 'uploads/issue_1768949120_6120.png', 'no', 'in progress', '2026-01-20 22:45:20', '2026-01-20 22:54:12', 'We\'ll send someone to fix them', 'don\'t tempa with anything');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Nkonde given', 'givennkonde535@gmail.com', '$2y$10$gwlMz12AYGxSBjaHZtmlgu1DqOKpzXjxeIPH1Dt/aFApOEOT0UXr2', '2026-01-19 22:36:19'),
(2, 'Chawa', 'chawa123@gmail.com', '$2y$10$nE1WorQM/dGrmRu3O1DLF.Rkzk99Rq/GcRvflTz2pE8Qvw5uwfsnm', '2026-01-19 22:37:46'),
(3, 'christopher', 'chris123@gmail.com', '$2y$10$CCNVdyvArZYOhblnM2.z0uKxWiw7ngY0QRCnS1fgvD/s9/7vcqHHe', '2026-01-19 23:02:13'),
(4, 'nyirenda', 'nyirenda123@gmail.com', '$2y$10$8ettO8oo91klQAGr7j62W.oGoKr7IJfFlNKK71ihAb0SvOTInnc/u', '2026-01-20 18:44:48');

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
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_id` (`tracking_id`),
  ADD KEY `fk_complaints_user` (`user_id`);

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
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
