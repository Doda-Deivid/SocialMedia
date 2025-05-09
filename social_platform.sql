-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 12:02 PM
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
-- Database: `social_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `loves` int(11) DEFAULT 0,
  `hahas` int(11) DEFAULT 0,
  `skull` int(11) DEFAULT 0,
  `mad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `media_path`, `media_type`, `created_at`, `likes`, `loves`, `hahas`, `skull`, `mad`) VALUES
(1, 2, 'xd', NULL, NULL, '2025-04-27 18:56:07', 0, 0, 0, 0, 0),
(2, 2, 'c', 'uploads/1745780175_166585336_257756646075819_4920076266207011374_n.jpg', 'image/jpeg', '2025-04-27 18:56:16', 0, 0, 0, 0, 0),
(3, 2, 'Ta proga isgerkim', NULL, NULL, '2025-04-27 18:57:14', 0, 0, 0, 0, 0),
(4, 2, '😂😂😂😂😂😂 Žiauru šaika', 'uploads/1745781402_El Risitas - Funniest Laugh Ever!!.mp4', 'video/mp4', '2025-04-27 19:16:42', 0, 0, 0, 0, 0),
(7, 2, 'labas xd', NULL, NULL, '2025-04-28 09:53:49', 3, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_picture`, `bio`, `created_at`) VALUES
(2, 'zdarova', 'matasmaksimas@gmail.com', '$2y$10$jMJ4sCTuzc0jhL/Udr4iWOFyetvPq.mH2xuy4qsFrpfGMlINEr3sO', 'uploads/166585336_257756646075819_4920076266207011374_n.jpg', 'stumiu belekas', '2025-04-26 19:41:28'),
(4, 'Matumboz', 'sdarov665@gmail.com', '$2y$10$vCO64ZKSseC8FZKvTjgVo.71csLhQ1oE/6NOPJOR7IViLADQsJWW2', 'uploads/166585336_257756646075819_4920076266207011374_n.jpg', 'xdxd', '2025-04-27 18:33:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
