-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2022 at 09:17 PM
-- Server version: 10.6.11-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wlh4dh_a`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`wlh4dh`@`%` PROCEDURE `get_bucket_list_items_count` (IN `list_ID` INT)   SELECT COUNT(*) FROM bucket_list_items NATURAL JOIN item_in NATURAL JOIN bucket_lists WHERE bucket_list_ID = list_ID$$

CREATE DEFINER=`wlh4dh`@`%` PROCEDURE `get_num_users` (OUT `num` INT)   SELECT COUNT(*) FROM users INTO num$$

CREATE DEFINER=`wlh4dh`@`%` PROCEDURE `get_users_count` ()   SELECT COUNT(*) FROM users$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bucket_lists`
--

CREATE TABLE `bucket_lists` (
  `bucket_list_ID` int(11) NOT NULL,
  `bucket_list_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bucket_lists`
--

INSERT INTO `bucket_lists` (`bucket_list_ID`, `bucket_list_title`) VALUES
(1, 'My goals for the future'),
(2, 'Places I want to travel'),
(3, 'Food to eat'),
(5, 'Things to Do Before I Graduate'),
(6, 'Summer 2023'),
(9, 'Winter Break'),
(14, 'Spring'),
(95, 'Beaches'),
(96, 'College Visits'),
(97, 'Concerts'),
(98, 'Restaurants'),
(100, 'Travel stuff :)'),
(101, 'Travel everywhere'),
(102, 'Summer 2023'),
(104, 'Winter Break'),
(105, 'new'),
(107, 'bucee list v2'),
(108, 'Concerts'),
(109, 'hi'),
(110, 'testing'),
(111, 'Fun Stuff');

-- --------------------------------------------------------

--
-- Table structure for table `bucket_list_items`
--

CREATE TABLE `bucket_list_items` (
  `bucket_list_item_ID` int(11) NOT NULL,
  `bucket_list_item_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bucket_list_items`
--

INSERT INTO `bucket_list_items` (`bucket_list_item_ID`, `bucket_list_item_name`, `description`, `cost`, `completed`) VALUES
(1, 'Eat great food', 'I can’t wait', NULL, 1),
(2, 'K2', '2nd tallest mountain', NULL, 0),
(3, 'Kangchenjunga', '3rd tallest mountain', NULL, 0),
(4, 'Lhotse', '4th tallest mountain', NULL, 0),
(5, 'Makalu', '5th tallest mountain', NULL, 0),
(6, 'Italy Trip', 'Traveling', 10001, 0),
(7, 'Spain', 'Coolio', 2, 1),
(9, 'Pizza', 'From anywhere', NULL, 0),
(10, 'Graduate!', NULL, NULL, 0),
(20, 'Ed Sheeran Concert', 'Attend Ed Sheeran concert in TX', 100.25, 0),
(21, 'Apple', 'HEHe', 12001200000, 0),
(22, 'Hamilton', 'Go see Hamilton', 50, 1),
(27, 'Lion King', '', 90, 0),
(28, 'Taylor Swift', 'Midnights', 104, 1),
(29, 'Katy Perry', 'Go on her tour', 30000, 0),
(30, 'Germany', 'See castles', 5000, 0),
(31, 'Germany', '', 0, 0),
(33, 'OBX', '', 0, 0),
(35, 'Taylor Swift', '', 0, 1),
(36, 'OBX', '', 0, 0),
(37, 'hey', '', 0, 0),
(38, 'hello', '', 0, 0),
(39, 'Germany', '', 0, 0),
(40, 'Spain', 'cool country', 1000, 0),
(41, 'UK', 'fish and chips', 1000, 1),
(42, 'Italy', 'Rome', 0, 0),
(43, 'Japan', '', 10000, 0),
(44, 'Egypt', '', 0, 0),
(45, 'Brazil', '', 0, 0),
(46, 'Beach', 'Outer Banks', 500, 0),
(48, 'test4', '', 0, 0),
(49, 'Light Show', 'See Christmas light show', 20, 0),
(50, 'item', NULL, NULL, 0),
(51, 'Meet Bucee', 'Buc-ee', 45, 1),
(52, 'Say hi to Bucee', '', 11, 0),
(53, 'Shake Bucee\'s paw', '', 12, 0),
(55, 'buceeeeee', NULL, NULL, 0),
(56, 'One Direction', '', 0, 1),
(57, 'Taylor Swift', '', 500, 0),
(58, 'hi1', '', 0, 0),
(59, 'hi2', '', 0, 0),
(60, 'test1', NULL, NULL, 0),
(61, 'test2', NULL, NULL, 0),
(62, 'Fun1', 'Have fun', 10000, 0),
(63, 'Fun2!', '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_ID` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_ID`, `category_name`) VALUES
(13, 'Food'),
(14, 'Travel'),
(15, 'Sports'),
(16, 'School'),
(17, 'Outdoors'),
(18, 'Random'),
(19, 'Summer');

-- --------------------------------------------------------

--
-- Table structure for table `is_categorized_as`
--

CREATE TABLE `is_categorized_as` (
  `bucket_list_ID` int(11) NOT NULL,
  `category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_categorized_as`
--

INSERT INTO `is_categorized_as` (`bucket_list_ID`, `category_ID`) VALUES
(98, 13),
(2, 14),
(101, 14),
(96, 16),
(104, 18),
(111, 18),
(95, 19),
(97, 19),
(102, 19),
(108, 19);

-- --------------------------------------------------------

--
-- Table structure for table `is_located_in`
--

CREATE TABLE `is_located_in` (
  `bucket_list_item_ID` int(11) NOT NULL,
  `location_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_located_in`
--

INSERT INTO `is_located_in` (`bucket_list_item_ID`, `location_ID`) VALUES
(41, 49),
(42, 51),
(43, 50),
(44, 52),
(51, 42),
(57, 43),
(58, 47),
(59, 48),
(62, 53),
(63, 54);

-- --------------------------------------------------------

--
-- Table structure for table `is_type`
--

CREATE TABLE `is_type` (
  `bucket_list_item_ID` int(11) NOT NULL,
  `category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_type`
--

INSERT INTO `is_type` (`bucket_list_item_ID`, `category_ID`) VALUES
(21, 13),
(36, 13),
(37, 13),
(38, 13),
(39, 13),
(41, 13),
(5, 14),
(40, 14),
(42, 14),
(43, 14),
(30, 15),
(48, 15),
(1, 17),
(2, 17),
(3, 17),
(4, 17),
(33, 17),
(44, 17),
(46, 17),
(49, 17),
(56, 17),
(62, 17),
(9, 18),
(20, 18),
(29, 19),
(31, 19),
(35, 19),
(51, 19),
(57, 19);

-- --------------------------------------------------------

--
-- Table structure for table `item_in`
--

CREATE TABLE `item_in` (
  `bucket_list_ID` int(11) NOT NULL,
  `bucket_list_item_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_in`
--

INSERT INTO `item_in` (`bucket_list_ID`, `bucket_list_item_ID`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 10),
(2, 6),
(2, 7),
(3, 9),
(5, 1),
(95, 36),
(97, 35),
(100, 40),
(101, 41),
(101, 42),
(101, 43),
(101, 44),
(101, 45),
(102, 46),
(104, 49),
(105, 50),
(107, 55),
(108, 56),
(108, 57),
(109, 58),
(109, 59),
(110, 60),
(110, 61),
(111, 62),
(111, 63);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_ID` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_ID`, `location_name`, `street`, `city`, `state`, `zip_code`) VALUES
(42, 'Japan', '123 Tokyo Street', 'Tokyo', '', NULL),
(43, 'Japan', '123 Tokyo Street', 'Tokyo', '', NULL),
(47, 'Japan', '123 Tokyo Street', 'Tokyo', '', NULL),
(48, 'Japan', '123 Tokyo Street', 'Tokyo', '', NULL),
(49, 'UK', '123 Address', 'London', 'UK', NULL),
(50, 'Japan', '123 Tokyo Street', 'Tokyo', '', NULL),
(51, 'Rome', 'Colosseum', '', '', NULL),
(52, 'Pyramids', '', '', '', NULL),
(53, 'Fun St.', '123 Fun', 'FunCity', 'VA', 22903),
(54, 'Fun Rd.', '456 Fun Rd.', 'FunTown', 'VA', 22903);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_ID`, `user_name`, `username`, `password`) VALUES
(1, 'Maggie', 'mkt', 'p@ssword'),
(2, 'William', 'wlh', '1234'),
(3, 'Andrew', 'al', 'abcd'),
(4, 'Max', 'maxster2', 'password'),
(5, 'Bob', 'bobbbb', 'bobr0ck5'),
(6, 'Anne S', 'anne123', 'qwerty'),
(7, 'Luna T', 'luna3', 'apple478'),
(8, 'Chris Miller', 'cpm89', 'blueberry'),
(9, 'Kelly', 'cookiemonster9', 'ilovecookies'),
(10, 'Josh', 'josh_k', 'jan2003'),
(11, 'Katie Rogers', 'bballstar34', 'ballislife'),
(12, 'Amy', 'amybakes', 'macaron836'),
(13, 'James H', 'james23', 'may92004'),
(14, 'Julia', 'julesss', 'passwordabc123'),
(15, 'Ryan P', 'rkp61', 'fido89$'),
(16, 'Mickey Mouse', 'mm3', 'cheese'),
(23, '', 'test', 'pass'),
(27, '', 'andrew', '$5$rounds=5000$2009065563840f83$kqyDyahMSlqYLzETffOYhBBVIpaEQFPpUZaspdLB7Q2'),
(28, '', 'maggie4', '$5$rounds=5000$1333144890638503$MVqrf2Or69xtcQsw1hLX85oQhJwv4HsgmYbh/BDyqy8'),
(30, '', 'maggiet', 'hey'),
(31, '', 'newuser2', '$5$rounds=5000$9015151786385170$ht5yEmcphxGrAjcrhMMvKaMZMyBqSaEAhDVbIstdv1B'),
(32, '', 'newuser3', '$5$rounds=5000$4937058616385172$IwUQrfAozJhbDcm.5z/Xrd8//lZWaLuJSAOoAqME7q5'),
(33, '', 'max', '$5$rounds=5000$48176412163863a6$OsasIROALPrlTyY37m1t9bDgjI0KhZR0vd.Sm2MKDJB'),
(34, '', 'max2', '$5$rounds=5000$125020388463863f$rCkyJVL8fSNbckw0pAC9ZpgsUpTCzgiZA6Lwx/kjK9C'),
(35, '', 'testuser1', '$5$rounds=5000$3083150966386709$EXem3xvGPdT2kG3JYIWXOKvzABeCXSCdOjKIF1GmzfD'),
(36, '', 'maggiee', '$5$rounds=5000$12407172806386ae$3eGrj.QFaChjNKoAH5YoGmJqc2LR3R1.Qg7YnmLKDZ/'),
(37, '', 'testing', '$5$rounds=5000$17659888516386af$ZxWEaS9.TDI5HhAU0zETP6kriEokmcugB.ER6zAyXf4'),
(38, '', 'maggie1', '$5$rounds=5000$8866251976386bba$4pPWvYAYdE2QDyV5QIgeTpX6Yd/X13MfIlZFGVB7J/2');

-- --------------------------------------------------------

--
-- Table structure for table `user_has`
--

CREATE TABLE `user_has` (
  `user_ID` int(11) NOT NULL,
  `bucket_list_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_has`
--

INSERT INTO `user_has` (`user_ID`, `bucket_list_ID`) VALUES
(1, 2),
(1, 95),
(1, 96),
(1, 97),
(1, 98),
(2, 14),
(3, 1),
(4, 6),
(6, 9),
(7, 3),
(10, 5),
(23, 100),
(27, 101),
(28, 102),
(30, 104),
(32, 105),
(33, 107),
(35, 108),
(36, 109),
(37, 110),
(38, 111);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bucket_lists`
--
ALTER TABLE `bucket_lists`
  ADD PRIMARY KEY (`bucket_list_ID`);

--
-- Indexes for table `bucket_list_items`
--
ALTER TABLE `bucket_list_items`
  ADD PRIMARY KEY (`bucket_list_item_ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_ID`);

--
-- Indexes for table `is_categorized_as`
--
ALTER TABLE `is_categorized_as`
  ADD PRIMARY KEY (`bucket_list_ID`),
  ADD KEY `category_ID` (`category_ID`) USING BTREE;

--
-- Indexes for table `is_located_in`
--
ALTER TABLE `is_located_in`
  ADD PRIMARY KEY (`bucket_list_item_ID`,`location_ID`),
  ADD UNIQUE KEY `bucket_list_item_ID` (`bucket_list_item_ID`),
  ADD UNIQUE KEY `location_ID` (`location_ID`);

--
-- Indexes for table `is_type`
--
ALTER TABLE `is_type`
  ADD PRIMARY KEY (`bucket_list_item_ID`),
  ADD KEY `category_ID` (`category_ID`);

--
-- Indexes for table `item_in`
--
ALTER TABLE `item_in`
  ADD PRIMARY KEY (`bucket_list_item_ID`),
  ADD KEY `bucket_list_ID` (`bucket_list_ID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `user_has`
--
ALTER TABLE `user_has`
  ADD PRIMARY KEY (`bucket_list_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bucket_lists`
--
ALTER TABLE `bucket_lists`
  MODIFY `bucket_list_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `bucket_list_items`
--
ALTER TABLE `bucket_list_items`
  MODIFY `bucket_list_item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `is_categorized_as`
--
ALTER TABLE `is_categorized_as`
  ADD CONSTRAINT `is_categorized_as_ibfk_1` FOREIGN KEY (`bucket_list_ID`) REFERENCES `bucket_lists` (`bucket_list_ID`),
  ADD CONSTRAINT `is_categorized_as_ibfk_2` FOREIGN KEY (`category_ID`) REFERENCES `categories` (`category_ID`);

--
-- Constraints for table `is_located_in`
--
ALTER TABLE `is_located_in`
  ADD CONSTRAINT `is_located_in_ibfk_1` FOREIGN KEY (`bucket_list_item_ID`) REFERENCES `bucket_list_items` (`bucket_list_item_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `is_located_in_ibfk_2` FOREIGN KEY (`location_ID`) REFERENCES `locations` (`location_ID`) ON DELETE CASCADE;

--
-- Constraints for table `is_type`
--
ALTER TABLE `is_type`
  ADD CONSTRAINT `is_type_ibfk_1` FOREIGN KEY (`bucket_list_item_ID`) REFERENCES `bucket_list_items` (`bucket_list_item_ID`),
  ADD CONSTRAINT `is_type_ibfk_2` FOREIGN KEY (`category_ID`) REFERENCES `categories` (`category_ID`);

--
-- Constraints for table `item_in`
--
ALTER TABLE `item_in`
  ADD CONSTRAINT `item_in_ibfk_1` FOREIGN KEY (`bucket_list_ID`) REFERENCES `bucket_lists` (`bucket_list_ID`),
  ADD CONSTRAINT `item_in_ibfk_2` FOREIGN KEY (`bucket_list_item_ID`) REFERENCES `bucket_list_items` (`bucket_list_item_ID`);

--
-- Constraints for table `user_has`
--
ALTER TABLE `user_has`
  ADD CONSTRAINT `bucket_lists` FOREIGN KEY (`bucket_list_ID`) REFERENCES `bucket_lists` (`bucket_list_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_has_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`user_ID`),
  ADD CONSTRAINT `user_has_ibfk_2` FOREIGN KEY (`bucket_list_ID`) REFERENCES `bucket_lists` (`bucket_list_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
