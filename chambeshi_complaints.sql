-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 08:00 PM
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
