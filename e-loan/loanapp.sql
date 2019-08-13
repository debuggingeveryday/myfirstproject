-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 13, 2019 at 10:43 AM
-- Server version: 8.0.16
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loanapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_bracket`
--

CREATE TABLE `class_bracket` (
  `id` int(11) NOT NULL,
  `from_years` int(3) NOT NULL,
  `to_years` int(3) NOT NULL,
  `amount` double(12,2) NOT NULL,
  `creditor_class_id` int(11) NOT NULL,
  `class_desc` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_bracket`
--

INSERT INTO `class_bracket` (`id`, `from_years`, `to_years`, `amount`, `creditor_class_id`, `class_desc`) VALUES
(1, 1, 5, 5000.00, 1, 'D&C'),
(2, 6, 9, 10000.00, 1, 'D&C'),
(3, 10, 100, 15000.00, 1, 'D&C'),
(4, 1, 5, 10000.00, 2, 'REGULAR'),
(5, 6, 9, 15000.00, 2, 'REGULAR'),
(6, 10, 100, 20000.00, 2, 'REGULAR'),
(7, 1, 5, 15000.00, 3, 'SUPERVISOR'),
(8, 6, 9, 20000.00, 3, 'SUPERVISOR'),
(9, 10, 100, 25000.00, 3, 'SUPERVISOR'),
(10, 1, 5, 15000.00, 4, '0'),
(11, 6, 9, 20000.00, 4, '0'),
(12, 10, 100, 30000.00, 4, '0'),
(13, 1, 5, 20000.00, 5, '0'),
(14, 6, 9, 25000.00, 5, '0'),
(15, 10, 100, 30000.00, 5, '0'),
(16, 1, 5, 30000.00, 6, '0'),
(17, 6, 9, 40000.00, 6, '0'),
(18, 10, 100, 50000.00, 6, '0'),
(19, 1, 5, 10000.00, 7, '0'),
(20, 6, 9, 15000.00, 7, '0'),
(21, 10, 100, 20000.00, 7, '0');

-- --------------------------------------------------------

--
-- Table structure for table `creditors`
--

CREATE TABLE `creditors` (
  `id` int(11) NOT NULL,
  `employee_code` char(20) NOT NULL,
  `fullname` varchar(60) DEFAULT NULL,
  `emp_company` varchar(20) DEFAULT NULL,
  `emp_position` varchar(50) DEFAULT NULL,
  `emp_classification` varchar(30) NOT NULL,
  `employed_date` date DEFAULT NULL,
  `mobileno` varchar(15) NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `salary` double(12,2) NOT NULL,
  `salary_type` varchar(100) DEFAULT NULL,
  `isnotactive` tinyint(1) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `creditors`
--

INSERT INTO `creditors` (`id`, `employee_code`, `fullname`, `emp_company`, `emp_position`, `emp_classification`, `employed_date`, `mobileno`, `picture`, `salary`, `salary_type`, `isnotactive`, `image`) VALUES
(1, '5107498', 'Agapetos Jack', 'SOUTH   ', 'N/A                                         ', 'RANK & FILE                   ', '2010-01-01', '9338631479', NULL, 0.00, 'Daily', 0, ''),
(2, '5108271', 'Lottie Buffy', 'NORTH', 'DRIVER', 'D&C', '2012-06-30', '09177711207', NULL, 327.50, 'Daily', 0, ''),
(3, '5107985', 'Wanda Kingsley', 'NORTH', 'DRIVER', 'D&C', '2011-11-22', '9776417590', NULL, 331.50, 'Daily', 0, ''),
(4, '5106940', 'Beauregard Bessie', 'NORTH', 'DRIVER', 'D&C', '2008-10-10', '9091693163', NULL, 331.50, 'Daily', 0, ''),
(5, '5107658', 'Keefe Percy', 'NORTH', 'DRIVER', 'D&C', '2011-01-29', '23123', NULL, 331.50, 'Daily', 0, ''),
(6, '5104102', 'Edmund Keeley', 'NORTH', 'DRIVER', 'D&C', '1999-12-24', '', NULL, 331.50, 'Daily', 0, ''),
(7, '5107963', 'Cristen Kiaran', 'NORTH', 'DRIVER', 'D&C', '2011-10-26', '', NULL, 331.50, 'Daily', 0, ''),
(8, '5104346', 'Cyril Chrysanta', 'NORTH', 'DRIVER', 'D&C', '2001-06-27', '', NULL, 331.50, 'Daily', 0, ''),
(9, '5211000', 'Rosalynne Lennie', 'NORTH', 'DRIVER', 'REGULAR', '2016-12-02', '', NULL, 323.50, 'Daily', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `session_code` varchar(100) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `session_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `session_code`, `session_id`, `session_date`) VALUES
(1, 'f2901e337cd716760740b7fcf2b96f07', '5106940', '2019/08/13');

-- --------------------------------------------------------

--
-- Table structure for table `transloan`
--

CREATE TABLE `transloan` (
  `id` int(11) NOT NULL,
  `creditor_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `applied_amount` double(14,4) NOT NULL,
  `terms` int(11) NOT NULL,
  `purpose` varchar(120) DEFAULT NULL,
  `loan_principal` double(12,4) DEFAULT NULL,
  `loan_interest` double(14,4) DEFAULT NULL,
  `loan_total` double(12,4) DEFAULT NULL,
  `loan_ammortization` double(12,4) DEFAULT NULL,
  `suggested_principal` double(14,4) DEFAULT NULL,
  `suggested_interest` double(12,2) DEFAULT NULL,
  `suggested_total` double(12,2) DEFAULT NULL,
  `suggested_ammortization` double(12,2) DEFAULT NULL,
  `approved_amount` double(14,4) NOT NULL,
  `approved_term` int(11) DEFAULT NULL,
  `created_by` varchar(80) DEFAULT NULL,
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `comaker1` char(11) NOT NULL,
  `comaker2` char(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `updated_dt` datetime DEFAULT NULL,
  `updated_by` char(40) NOT NULL,
  `lengthofservice` int(3) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `pin` char(6) NOT NULL,
  `paysched` char(15) NOT NULL,
  `validators_remark` varchar(50) DEFAULT NULL,
  `co1_name` char(40) NOT NULL,
  `co2_name` char(40) NOT NULL,
  `co1_los` char(30) NOT NULL,
  `co2_los` char(30) NOT NULL,
  `los` char(30) NOT NULL,
  `ave_month_pay` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transloan`
--

INSERT INTO `transloan` (`id`, `creditor_id`, `product_id`, `applied_amount`, `terms`, `purpose`, `loan_principal`, `loan_interest`, `loan_total`, `loan_ammortization`, `suggested_principal`, `suggested_interest`, `suggested_total`, `suggested_ammortization`, `approved_amount`, `approved_term`, `created_by`, `comaker1`, `comaker2`, `status`, `updated_dt`, `updated_by`, `lengthofservice`, `remarks`, `pin`, `paysched`, `validators_remark`, `co1_name`, `co2_name`, `co1_los`, `co2_los`, `los`, `ave_month_pay`) VALUES
(11, 5106940, NULL, 1500.0000, 24, 'wala', 1500.0000, 3600.0000, 5100.0000, 212.5000, 15000.0000, 36000.00, 51000.00, 2125.00, 0.0000, NULL, NULL, '5104102', '5107658', 'PENDING', NULL, 'test', 10, 'test', 'test', 'Daily', NULL, 'Edmund Keeley', 'Keefe Percy', '19 Year(s) & 7Month(s)', '8 Year(s) & 6Month(s)', '10 Year(s) & 10Month(s)', 212.50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_bracket`
--
ALTER TABLE `class_bracket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `creditors`
--
ALTER TABLE `creditors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transloan`
--
ALTER TABLE `transloan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `creditors`
--
ALTER TABLE `creditors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transloan`
--
ALTER TABLE `transloan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
