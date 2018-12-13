-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2018 at 06:22 PM
-- Server version: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scouting`
--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int(11) NOT NULL,
  `blue_1` int(11) NOT NULL,
  `blue_2` int(11) NOT NULL,
  `blue_3` int(11) NOT NULL,
  `red_1` int(11) NOT NULL,
  `red_2` int(11) NOT NULL,
  `red_3` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_text` varchar(200) NOT NULL,
  `question_type` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `question_category` enum('Autonomous','Teleop','EndGame','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scouting_data`
--

CREATE TABLE `scouting_data` (
  `scouting_data_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `team_number` int(11) NOT NULL,
  `scout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scouts`
--

CREATE TABLE `scouts` (
  `scout_id` int(11) NOT NULL,
  `scout_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `teamnumber` int(11) NOT NULL,
  `teamname` varchar(100) NOT NULL,
  `team_comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `scouting_data`
--
ALTER TABLE `scouting_data`
  ADD PRIMARY KEY (`scouting_data_id`),
  ADD KEY `match_id` (`match_id`),
  ADD KEY `scout_id` (`scout_id`),
  ADD KEY `team_number` (`team_number`);

--
-- Indexes for table `scouts`
--
ALTER TABLE `scouts`
  ADD PRIMARY KEY (`scout_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`teamnumber`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scouting_data`
--
ALTER TABLE `scouting_data`
  ADD CONSTRAINT `match_id` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`),
  ADD CONSTRAINT `scout_id` FOREIGN KEY (`scout_id`) REFERENCES `scouts` (`scout_id`),
  ADD CONSTRAINT `team_number` FOREIGN KEY (`team_number`) REFERENCES `teams` (`teamnumber`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
