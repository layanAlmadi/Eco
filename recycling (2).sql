-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2025 at 06:24 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recycling`
--

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `level_name` varchar(30) NOT NULL,
  `points_required` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_name`, `points_required`) VALUES
('Advanced', 100),
('Beginner', 0),
('Intermediate', 30);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `material_id` int(11) NOT NULL,
  `material_name` varchar(30) NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `status` enum('PENDING','COMPLETED','CANCELED') NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`material_id`, `material_name`, `description`, `image_url`, `category`, `phone`, `username`, `status`) VALUES
(31, 'fabrec', 'gold-colored fabric', 'uploads/1743790840_c1.jpg', 'Clothes', '0556666666', 'lara', 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `username_donor` varchar(30) NOT NULL,
  `username_recipient` varchar(30) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) NOT NULL,
  `points` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `level_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `points`, `password`, `email`, `level_name`) VALUES
('Amani', 0, '$2y$10$ymscem2PPvzkTUTXa5bRiOapPURxX3WE9tIjIOt92Q53EdTSY1yhm', 'amani@gmail.com', 'Beginner'),
('lara', 60, '$2y$10$FCftUh3Jx7vLDeNmEulj1u2us5ZBV/5WbgO7hhd.1GQTrAl0qHLJW', 'lara@gmail.com', 'Intermediate'),
('Layan', 0, '$2y$10$eJGAPSBe58b3uNCFEr8zpe4vw7M3e1Ay09a/IBEqrxXTLaKI/xMuC', 'qwery@gmail.com', 'Beginner'),
('lulu', 0, '$2y$10$0lEpdfrQCVuLdPGfnKJDJ.lDUnmsEugbbSRfwAWHfprxR0wl5g2SK', 'lulu@gmail.com', 'Beginner'),
('mnbvc', 0, '$2y$10$Lbzm5QRwAgCc9pzK5MHM/uX7EttFTUKeLxRx7gPJtXCVFbCxnzupC', 'mnbvc@gmail.com', 'Beginner'),
('nbvc', 0, '$2y$10$R4IYj8fa76Tmr86EG/gY8OB9WR4TNLII4QNJ.opuzS9gaTPz.y0Hi', 'nbvc@gmail.com', 'Beginner'),
('tala', 10, '$2y$10$kKwv0UJjLQY8vXw5V/XM9efRH7ipZP.a/hAmmK.wKfZloghji5USS', 'tala@gmail.com', 'Beginner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_name`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `username_donor` (`username_donor`),
  ADD KEY `username_recipient` (`username_recipient`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD KEY `level_name` (`level_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`),
  ADD CONSTRAINT `transaction_ibfk_4` FOREIGN KEY (`username_donor`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `transaction_ibfk_5` FOREIGN KEY (`username_recipient`) REFERENCES `user` (`username`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`level_name`) REFERENCES `level` (`level_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
