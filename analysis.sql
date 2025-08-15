-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 04:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `analysis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `email` varchar(25) NOT NULL,
  `ID` int(10) NOT NULL,
  `subject` varchar(10) NOT NULL,
  `total_class` int(3) NOT NULL,
  `present` int(3) NOT NULL,
  `absent` int(3) NOT NULL,
  `percent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`email`, `ID`, `subject`, `total_class`, `present`, `absent`, `percent`) VALUES
('mutupsundus3@gmail.com', 220911400, 'Hindi', 20, 16, 4, 80),
('mutupsundus3@gmail.com', 220911400, 'English', 20, 17, 3, 85),
('mutupsundus3@gmail.com', 220911400, 'Science', 20, 15, 5, 75),
('mutupsundus3@gmail.com', 220911400, 'Maths', 20, 14, 6, 70),
('mutupsundus3@gmail.com', 220911400, 'SST', 20, 15, 5, 75);

-- --------------------------------------------------------

--
-- Table structure for table `datesheet`
--

CREATE TABLE `datesheet` (
  `id` int(11) NOT NULL,
  `term` varchar(10) NOT NULL,
  `subject` varchar(10) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datesheet`
--

INSERT INTO `datesheet` (`id`, `term`, `subject`, `date`) VALUES
(4, 'Term 1', 'English', '2025-05-05'),
(5, 'Term 1', 'Hindi', '2025-05-06'),
(6, 'Term 1', 'SST', '2025-05-07'),
(7, 'Term 1', 'Science', '2025-05-08'),
(8, 'Term 1', 'Maths', '2025-05-09'),
(9, 'Term 2', 'English', '2025-10-03'),
(10, 'Term 2', 'Hindi', '2025-10-05'),
(11, 'Term 2', 'SST', '2025-10-07'),
(12, 'Term 2', 'Science', '2025-10-09'),
(13, 'Term 2', 'Maths', '2025-10-11'),
(14, 'Term 3', 'English', '2025-12-13'),
(15, 'Term 3', 'Hindi', '2025-12-14'),
(16, 'Term 3', 'SST', '2025-12-15'),
(17, 'Term 3', 'Science', '2025-12-16'),
(18, 'Term 3', 'Maths', '2025-12-17'),
(19, 'Term 4', 'English', '2026-03-05'),
(20, 'Term 4', 'Hindi', '2026-03-07'),
(21, 'Term 4', 'SST', '2026-03-09'),
(22, 'Term 4', 'Science', '2026-03-11'),
(23, 'Term 4', 'Maths', '2026-03-13');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `email` varchar(25) NOT NULL,
  `ID` int(10) NOT NULL,
  `term` varchar(6) NOT NULL,
  `english` int(3) NOT NULL,
  `hindi` int(3) NOT NULL,
  `sst` int(3) NOT NULL,
  `science` int(3) NOT NULL,
  `maths` int(3) NOT NULL,
  `percent` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`email`, `ID`, `term`, `english`, `hindi`, `sst`, `science`, `maths`, `percent`) VALUES
('mutupsundus3@gmail.com', 220911400, 'Term 1', 95, 95, 95, 99, 100, 96.8),
('mutupsundus3@gmail.com', 220911400, 'Term 2', 45, 56, 76, 45, 78, 60),
('mutupsundus3@gmail.com', 220911400, 'Term 3', 56, 78, 99, 89, 100, 84.4),
('mutupsundus3@gmail.com', 220911400, 'Term 4', 67, 98, 34, 98, 45, 68.4),
('sahilgadhi6762@gmail.com', 220953042, 'Term 1', 67, 89, 56, 33, 78, 64.6),
('sahilgadhi6762@gmail.com', 220953042, 'Term 2', 23, 33, 22, 11, 11, 20),
('sahilgadhi6762@gmail.com', 220953042, 'Term 3', 67, 56, 78, 89, 100, 78),
('sahilgadhi6762@gmail.com', 220953042, 'Term 4', 98, 89, 78, 76, 65, 81.2);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `email` varchar(25) NOT NULL,
  `name` varchar(15) NOT NULL,
  `ID` int(10) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(7) NOT NULL,
  `class` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`email`, `name`, `ID`, `dob`, `gender`, `class`) VALUES
('mutupsundus3@gmail.com', 'Akash Gupta', 220911400, '2004-06-08', 'Male', 7),
('sahilgadhi6762@gmail.com', 'Sahil Gadhi', 220953042, '2003-12-21', 'Male', 6);

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE `study_materials` (
  `id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_materials`
--

INSERT INTO `study_materials` (`id`, `subject`, `class`, `file_name`, `file_path`, `uploaded_at`) VALUES
(7, 'English', '6', 'A Tale of two birds.pdf', 'uploads/A Tale of two birds.pdf', '2024-11-17 10:05:52'),
(8, 'Maths', '6', 'Knowing Our numbers.pdf', 'uploads/Knowing Our numbers.pdf', '2024-11-17 10:06:01'),
(9, 'SST', '6', 'When, Where and Hwo.pdf', 'uploads/When, Where and Hwo.pdf', '2024-11-17 10:06:09'),
(10, 'Science', '6', 'Component of foods.pdf', 'uploads/Component of foods.pdf', '2024-11-17 10:06:19'),
(11, 'English', '7', 'Three Questions.pdf', 'uploads/Three Questions.pdf', '2024-11-17 10:06:28'),
(12, 'Maths', '7', 'Integers.pdf', 'uploads/Integers.pdf', '2024-11-17 10:06:34'),
(13, 'SST', '7', 'Tracing Changes.pdf', 'uploads/Tracing Changes.pdf', '2024-11-17 10:06:48'),
(14, 'Science', '7', 'Nutrition in Plants.pdf', 'uploads/Nutrition in Plants.pdf', '2024-11-17 10:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `subject` varchar(15) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `student_id`, `day`, `subject`, `start_time`, `end_time`) VALUES
(35, 220911400, 'Monday', 'Hindi', '09:00:00', '10:00:00'),
(36, 220911400, 'Monday', 'English', '10:00:00', '11:00:00'),
(37, 220911400, 'Monday', 'SST', '11:00:00', '12:00:00'),
(38, 220911400, 'Monday', 'Science', '13:00:00', '14:00:00'),
(39, 220911400, 'Monday', 'Maths', '14:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(25) NOT NULL,
  `password` varchar(10) NOT NULL,
  `auth` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password`, `auth`) VALUES
('mutupsundus3@gmail.com', 'aa', 1),
('sahilgadhi6762@gmail.com', 'ss', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user`),
  ADD UNIQUE KEY `password` (`password`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD KEY `email` (`email`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `datesheet`
--
ALTER TABLE `datesheet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD KEY `email` (`email`),
  ADD KEY `ID` (`ID`),
  ADD KEY `marks_ibfk_3` (`term`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD UNIQUE KEY `ID` (`ID`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datesheet`
--
ALTER TABLE `datesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `study_materials`
--
ALTER TABLE `study_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`ID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`ID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
