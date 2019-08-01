-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2019 at 01:05 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s261412`
--

-- --------------------------------------------------------

--
-- Table structure for table `operationalData`
--

CREATE TABLE `operationalData` (
  `operationID` int(11) NOT NULL,
  `seatID` varchar(3) NOT NULL,
  `executedBy` varchar(45) NOT NULL,
  `operationType` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `operationalData`
--

INSERT INTO `operationalData` (`operationID`, `seatID`, `executedBy`, `operationType`) VALUES
(1, 'B2', 'u2@p.it', 'purchase'),
(2, 'B3', 'u2@p.it', 'purchase'),
(3, 'B4', 'u2@p.it', 'purchase'),
(4, 'A4', 'u1@p.it', 'reservation'),
(5, 'D4', 'u1@p.it', 'reservation'),
(6, 'F4', 'u2@p.it', 'reservation');

-- --------------------------------------------------------

--
-- Table structure for table `userData`
--

CREATE TABLE `userData` (
  `userID` int(11) NOT NULL,
  `userEmail` varchar(45) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `userStatus` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userData`
--

INSERT INTO `userData` (`userID`, `userEmail`, `userPass`, `userStatus`) VALUES
(1, 'u1@p.it', '$2y$10$8GEVoL6IfRKU/9MM51/mk.1MTKc5nru10LRx3o.0fmreR1vkYAz4K', 'passive'),
(2, 'u2@p.it', '$2y$10$rUapN1j8OCOTni8wAjGMVuLheRCrURNlvCp25dYCmTkPigS5HTwC.', 'passive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `operationalData`
--
ALTER TABLE `operationalData`
  ADD PRIMARY KEY (`operationID`),
  ADD UNIQUE KEY `seatID_UNIQUE` (`seatID`);

--
-- Indexes for table `userData`
--
ALTER TABLE `userData`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userEmail_UNIQUE` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `operationalData`
--
ALTER TABLE `operationalData`
  MODIFY `operationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `userData`
--
ALTER TABLE `userData`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
