-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2022 at 12:53 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `be_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `sysid` int(10) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `staff_id` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`sysid`, `fullname`, `staff_id`, `password`) VALUES
(1, 'hashim akmal Kamarul', '123acva', '12389kaa'),
(2, 'Megat', 'ahs1234', '321aca'),
(3, 'tuti', '1234abn', 'adjn21'),
(5, 'usop', 'abcjk12', '131ls'),
(6, 'cong', 'bhkjd12', 'abhsk14'),
(7, 'widad rusli', 'abcj1243', 'fdsf342');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `sysid` int(10) NOT NULL,
  `registered_by` varchar(100) NOT NULL,
  `workstream` varchar(100) NOT NULL,
  `ws_code` varchar(100) NOT NULL,
  `developer` varchar(100) NOT NULL,
  `svp_others` varchar(100) NOT NULL,
  `iris` varchar(100) NOT NULL,
  `project_system` varchar(100) NOT NULL,
  `brief_description` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `be_submission` varchar(100) NOT NULL,
  `assessment_completed` varchar(100) NOT NULL,
  `target_systemrfs` varchar(100) NOT NULL,
  `revise_target_systemrfs` varchar(100) NOT NULL,
  `actual_systemrfs` varchar(100) NOT NULL,
  `rollout` varchar(100) NOT NULL,
  `mot` varchar(100) NOT NULL,
  `git_internalmd` varchar(100) NOT NULL,
  `git_externalmd` varchar(100) NOT NULL,
  `rm_git_internal` varchar(100) NOT NULL,
  `rm_git_external` varchar(100) NOT NULL,
  `gnt_internalmd` varchar(100) NOT NULL,
  `gnt_externalmd` varchar(100) NOT NULL,
  `rm_gnt_internal` varchar(100) NOT NULL,
  `rm_gnt_external` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `last_update_timestamp` timestamp(5) NOT NULL DEFAULT current_timestamp(5),
  `last_update_by` timestamp(4) NULL DEFAULT NULL ON UPDATE current_timestamp(4),
  `last_update_by_user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`sysid`, `registered_by`, `workstream`, `ws_code`, `developer`, `svp_others`, `iris`, `project_system`, `brief_description`, `status`, `be_submission`, `assessment_completed`, `target_systemrfs`, `revise_target_systemrfs`, `actual_systemrfs`, `rollout`, `mot`, `git_internalmd`, `git_externalmd`, `rm_git_internal`, `rm_git_external`, `gnt_internalmd`, `gnt_externalmd`, `rm_gnt_internal`, `rm_gnt_external`, `remark`, `last_update_timestamp`, `last_update_by`, `last_update_by_user`) VALUES
(2, 'hashim akmal Kamarul', 'gfhfghfgh', 'adsasd', 'GNT,NROAin,', 'SVP2Waiters Clearence', 'adssad', 'rewrwe', 'dwqwq', 'Assessment', '2022-03-04', '2022-03-17', '2022-03-24', '2022-04-07', '2022-03-25', '2022-04-07', 'dwqqd', '2', '2', '', '1', '2', '1', '', '1', 'Testsdd', '2022-03-01 07:54:53.33938', '2022-03-05 14:36:30.3063', 'Megat'),
(3, 'hashim akmal Kamarul', 'adassdasadads', 'adsasd', 'NRO-Megat,NRO-Ain,', 'Service Installation SVP10-ND Digital NMO', '65332', 'ewqqweqe', 'fesw', 'Completed', '2022-03-16', '2022-03-09', '2022-03-09', '2022-03-17', '2022-03-30', '2022-04-06', 'rwewr', '1', '2', '', '232', '3', '1', '', '424', 'Last Stand Standing', '2022-03-01 07:57:45.62013', '2022-03-06 12:15:30.1350', 'Megat'),
(4, 'hashim akmal Kamarul', 'ewqe', '432432', 'GNT,NRO-Ain,', 'SVP2-Waiters Clearence', 'adssad', 'rewrwe', 'dwqwq', 'Assessment', '2022-04-02', '2022-03-25', '2022-03-17', '2022-03-24', '2022-03-23', '2022-03-31', 'dwqqd', '1', '3', '', '44', '5', '1', '', '6', 'Helooo', '2022-03-01 08:17:01.47082', '2022-03-06 13:58:49.4885', 'hashim akmal Kamarul'),
(5, 'tuti', 'qweqwe', '432432', 'GITD,GNT,NRO-Megat,NRO-Ain,', 'SVP3-Accelerate', 'eqweqwe', 'sadasd', 'qweqe', 'Completed', '2022-03-30', '2022-03-19', '2022-03-24', '2022-03-07', '2022-04-09', '2022-04-06', 'qewqweqwe', '1', '2', '800.00', '0', '3', '1', '2400.00', '0', '', '2022-03-02 08:54:47.46106', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`sysid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `sysid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `sysid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
