-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 09:41 PM
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
-- Database: `calbayog_go`
--

-- --------------------------------------------------------

--
-- Table structure for table `visited_places`
--

CREATE TABLE `visited_places` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `visited_date` datetime DEFAULT NULL,
  `first_visited_date` datetime DEFAULT NULL,
  `last_visited_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visited_places`
--

INSERT INTO `visited_places` (`id`, `user_id`, `content_id`, `visited_date`, `first_visited_date`, `last_visited_date`) VALUES
(47, 11, 14, NULL, '2024-11-14 04:40:29', '2024-11-14 04:41:04'),
(48, 11, 10, NULL, '2024-11-14 04:40:29', '2024-11-14 04:41:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visited_places`
--
ALTER TABLE `visited_places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_visit` (`user_id`,`content_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visited_places`
--
ALTER TABLE `visited_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `visited_places`
--
ALTER TABLE `visited_places`
  ADD CONSTRAINT `visited_places_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `visited_places_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
