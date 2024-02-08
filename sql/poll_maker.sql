-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 05:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poll_maker`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(20) NOT NULL,
  `answer` varchar(250) NOT NULL,
  `num_votes` int(50) NOT NULL,
  `poll_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `answer`, `num_votes`, `poll_id`) VALUES
(88, 'ITCS 333', 6, 64),
(89, 'ITCS 444', 1, 64),
(90, 'ITCS 222', 0, 64),
(91, 'JAVA', 1, 65),
(92, 'JavaScript', 1, 65),
(93, 'PHP', 2, 65),
(94, 'Python', 2, 65),
(95, 'Lionel Messi', 5, 66),
(96, 'Cristiano Ronaldo', 1, 66),
(97, 'YES', 4, 67),
(98, 'NO', 0, 67),
(99, 'Happy', 4, 68),
(100, 'Sad', 1, 68),
(101, 'Instagram', 1, 69),
(102, 'Snapchat', 1, 69),
(103, 'Youtube', 0, 69),
(104, 'TikTok', 3, 69),
(105, 'Samsung', 4, 70),
(106, 'Apple', 1, 70),
(107, 'Bahrain', 3, 71),
(108, 'UAE', 1, 71),
(109, 'KSA', 1, 71),
(110, 'Football', 4, 72),
(111, 'Basketball', 1, 72),
(112, 'overwatch', 2, 73),
(113, 'black ops1', 3, 73),
(114, 'Andrés Iniesta', 3, 74),
(115, 'Luka Modrić', 0, 74),
(136, 'Yes', 0, 85),
(137, 'No', 0, 85);

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE `poll` (
  `poll_id` int(20) NOT NULL,
  `question` varchar(250) NOT NULL,
  `total_votes` int(50) NOT NULL,
  `user_id` int(20) NOT NULL,
  `poll_status` varchar(15) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll`
--

INSERT INTO `poll` (`poll_id`, `question`, `total_votes`, `user_id`, `poll_status`, `start_date`, `end_date`) VALUES
(64, 'What course do you prefer?', 7, 19, 'active', '2023-12-23', '2023-12-28'),
(65, 'Best programming language?', 6, 19, 'inactive', '2023-12-23', '2023-12-25'),
(66, 'Greatest player of all time?', 6, 20, 'active', '0000-00-00', '0000-00-00'),
(67, 'Do you love UOB?', 4, 21, 'active', '0000-00-00', '0000-00-00'),
(68, 'How do you feel when you code?', 5, 21, 'active', '2023-12-23', '2023-12-30'),
(69, 'Best social media platform?', 5, 22, 'active', '2023-12-23', '2023-12-30'),
(70, 'Samsung or Apple?', 5, 22, 'inactive', '0000-00-00', '0000-00-00'),
(71, 'Where are you from?', 5, 21, 'active', '0000-00-00', '0000-00-00'),
(72, 'The best sport?', 5, 24, 'active', '0000-00-00', '0000-00-00'),
(73, 'Your favorite game?', 5, 24, 'active', '0000-00-00', '0000-00-00'),
(74, 'The best?', 3, 24, 'active', '0000-00-00', '0000-00-00'),
(85, 'Test', 0, 29, 'active', '2023-12-26', '2023-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(19, 'Mahmood Husain', 'mahmood123@gmail.com', '$2y$10$gWUhAi37eMgsi4NiQkG7/uqd.llCUtBfxPUhe/QyAgoQNLbMqpCJS', ''),
(20, 'Hesham', 'heshamahmed243@gmail.com', '$2y$10$NfsRhuSiNlyU2WkIs6gKjuoXDQGXY3TuYUWiyZ70aFA/hDr9ULxZ6', ''),
(21, 'Zeshan', 'zeshanbh@gmail.com', '$2y$10$eirclXwgfXKzHL7qU/G.zO8a6UbwLcfiMzYFmqFPX7IqMX9TdcKsK', ''),
(22, 'Ali Ebrahim', 'aliEbrahim@gmail.com', '$2y$10$WnmoT6Xe3wwYJudhn/ynCeIG3wXrxtpYDHUzCXJ0OJx8gE9R8rneS', ''),
(24, 'Moayad', 'moayad44@gmail.com', '$2y$10$kIcapi2eCdd3qHbq6tEGguxdW28n/oO7O.6K5.4ekgDXzQfUzhHiW', ''),
(29, 'Mahmood HUsain', 'mahmood12@gmail.com', '$2y$10$1vOVYvCWwdPV0yIkgw69wuDEgtfP7SomDQ6ycaF/5bFDaf0zuMpyO', '');

-- --------------------------------------------------------

--
-- Table structure for table `voted`
--

CREATE TABLE `voted` (
  `vote_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `answer_id` int(20) NOT NULL,
  `poll_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voted`
--

INSERT INTO `voted` (`vote_id`, `user_id`, `answer_id`, `poll_id`) VALUES
(19, 19, 88, 64),
(20, 20, 88, 64),
(21, 21, 88, 64),
(22, 19, 93, 65),
(23, 19, 95, 66),
(24, 20, 95, 66),
(25, 20, 94, 65),
(26, 22, 97, 67),
(27, 22, 89, 64),
(28, 22, 92, 65),
(29, 22, 96, 66),
(30, 21, 99, 68),
(31, 20, 100, 68),
(32, 21, 93, 65),
(33, 21, 95, 66),
(34, 21, 97, 67),
(35, 22, 101, 69),
(36, 22, 99, 68),
(37, 21, 104, 69),
(38, 22, 105, 70),
(39, 19, 99, 68),
(40, 19, 102, 69),
(41, 21, 107, 71),
(42, 19, 106, 70),
(43, 19, 107, 71),
(45, 22, 108, 71),
(50, 21, 105, 70),
(55, 24, 112, 73),
(57, 24, 91, 65),
(58, 24, 110, 72),
(59, 24, 95, 66),
(60, 20, 110, 72),
(61, 20, 112, 73),
(62, 20, 105, 70),
(63, 19, 110, 72),
(64, 20, 109, 71),
(65, 20, 104, 69),
(66, 19, 113, 73),
(67, 20, 97, 67),
(68, 22, 111, 72),
(69, 22, 113, 73),
(70, 24, 114, 74),
(71, 22, 114, 74),
(72, 24, 88, 64),
(88, 29, 114, 74),
(89, 29, 88, 64);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `Test1` (`poll_id`);

--
-- Indexes for table `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`poll_id`),
  ADD KEY `Test` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `voted`
--
ALTER TABLE `voted`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `test3` (`user_id`),
  ADD KEY `test4` (`answer_id`),
  ADD KEY `test5` (`poll_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `poll`
--
ALTER TABLE `poll`
  MODIFY `poll_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `voted`
--
ALTER TABLE `voted`
  MODIFY `vote_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `Test1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`poll_id`);

--
-- Constraints for table `poll`
--
ALTER TABLE `poll`
  ADD CONSTRAINT `Test` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `voted`
--
ALTER TABLE `voted`
  ADD CONSTRAINT `test3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `test4` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`answer_id`),
  ADD CONSTRAINT `test5` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`poll_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
