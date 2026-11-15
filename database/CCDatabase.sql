-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2026 at 08:12 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `community_connect`
--
CREATE DATABASE IF NOT EXISTS `community_connect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `community_connect`;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `saved_at` datetime NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(254) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `age` int NOT NULL,
  `parish` varchar(50) NOT NULL,
  `activity` varchar(60) NOT NULL,
  `participation_date` date NOT NULL,
  `skills` varchar(500) NOT NULL,
  `contact_method` varchar(30) DEFAULT NULL,
  `volunteer_experience` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `saved_at`, `full_name`, `email`, `telephone`, `age`, `parish`, `activity`, `participation_date`, `skills`, `contact_method`, `volunteer_experience`, `availability`) VALUES
('00001', '2026-10-21 22:00:05', 'Daniel Richards', 'dddd2676@aol.com', '87629104657', 28, 'St. Ann', 'Food Drive', '2026-12-19', 'Elderly Support; Photography', 'WhatsApp', 'First-time volunteer', 'Flexible'),
('00002', '2026-10-21 22:17:29', 'Patrice Thomas', 'thomaspat@yahoo.com', '8769309287', 60, 'Westmoreland', 'Beach Cleanup', '2027-04-10', 'First Aid; Event Planning', 'Telephone', 'Experienced volunteer', 'Weekday afternoons'),
('00003', '2026-10-21 20:43:25', 'Stewart Kirk', 'skirk@gmail.com', '8768993222', 46, 'St. Andrew', 'Food Drive', '2026-11-21', 'Cooking; Organising', NULL, NULL, NULL),
('00004', '2026-10-21 20:42:16', 'Ashley Evans', 'ashevans@gmail.com', '8765329876', 35, 'Trelawny', 'Food Drive', '2026-11-21', 'Teaching; Organising', NULL, NULL, NULL),
('00005', '2026-10-21 21:10:52', 'Stephan Lewis', 'slewis@yahoo.com', '876-888-2560', 51, 'Trelawny', 'Youth Mentoring', '2027-11-22', 'Gardening; Cooking', NULL, NULL, NULL),
('00006', '2026-10-21 21:38:33', 'Simone Walters', 'simwalter@yahoo.com', '8763904882', 20, 'St. Catherine', 'Beach Cleanup', '2026-10-22', 'Gardening; Teaching; Cooking', 'Telephone', 'First-time volunteer', 'Weekday mornings'),
('f55d13e92e', '2026-11-09 19:39:31', 'Dana Bowley', 'bowley@gmail.com', '8762038639', 16, 'Hanover', 'Youth Mentoring', '2027-03-13', 'Childcare,Youth Mentoring,General Volunteering', 'Telephone', 'Some volunteering experience', 'Flexible');
--
-- Database: `univesity_db`
--
CREATE DATABASE IF NOT EXISTS `univesity_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `univesity_db`;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `programme` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_of_study` int DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `programme`, `year_of_study`) VALUES
(1, 'John', 'Brown', 'john@example.com', 'Computer Science', 1),
(2, 'Mary', 'Smith', 'mary@example.com', 'Computer Information Systems', 3),
(3, 'David', 'Williams', 'david@example.com', 'Information Technology', 3),
(4, 'Sarah', 'Johnson', 'sarah@example.com', 'Computer Science', 1),
(5, 'Peter', 'Clarke', 'peter@example.com', 'Computer Information Systems', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
