-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 11:04 PM
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
-- Database: `alumni_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `AlumniID` varchar(50) NOT NULL,
  `AlumniName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `ContactNo` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `GraduationYear` int(11) DEFAULT NULL CHECK (`GraduationYear` >= 1900),
  `DateOfBirth` date DEFAULT NULL,
  `Job` varchar(100) DEFAULT NULL,
  `DepartmentID` varchar(50) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`AlumniID`, `AlumniName`, `Email`, `ContactNo`, `Address`, `GraduationYear`, `DateOfBirth`, `Job`, `DepartmentID`, `UserID`, `CreatedAt`) VALUES
('ALM10001', 'Farheen Islam', 'asfiyaarifina@gmail.com', '01752828823', 'Tongi', 2025, '2003-03-16', 'Software Engineer', 'CS', 1, '2025-09-04 05:20:05'),
('ALM10002', 'Nilima Alim', 'nilimaalim@gmail.com', '01722334455', 'Uttara', 2024, '2001-04-04', 'Data Analyst', 'CS', 2, '2025-09-04 05:20:05'),
('ALM10003', 'Maisha Khan', 'maisha@gmail.com', '01234567898', 'Tongi', 2017, '2001-09-17', 'Teacher', 'ME', 3, '2025-09-04 05:20:05'),
('ALM10004', 'Rahim Uddin', 'rahim@gmail.com', '01788889999', 'Banani', 2023, '2000-06-10', 'Web Developer', 'IT', 6, '2025-09-04 05:20:05'),
('ALM10005', 'Karim Ahmed', 'karim@gmail.com', '01777778888', 'Mirpur', 2022, '1999-08-22', 'Civil Engineer', 'CE', 7, '2025-09-04 05:20:05'),
('ALM10006', 'Sadia Akter', 'sadia@gmail.com', '01811112222', 'Dhanmondi', 2021, '1998-02-15', 'HR Manager', 'BBA', 8, '2025-09-04 05:20:05'),
('ALM10007', 'Tania Sultana', 'tania@gmail.com', '01833334444', 'Gulshan', 2020, '1997-12-01', 'Lawyer', 'LAW', 9, '2025-09-04 05:20:05'),
('ALM10008', 'Sohel Rana', 'sohel@gmail.com', '01955556666', 'Motijheel', 2019, '1996-05-05', 'Electrical Engineer', 'EEE', 10, '2025-09-04 05:20:05'),
('ALM10009', 'Anika Rahman', 'anika@gmail.com', '01977778888', 'Farmgate', 2018, '1995-09-09', 'Lecturer', 'ENG', 5, '2025-09-04 05:20:05'),
('ALM10010', 'Rakib Hasan', 'rakib@gmail.com', '01799990000', 'Mohakhali', 2026, '2002-11-11', 'Mechanical Engineer', 'ME', 4, '2025-09-04 05:20:05'),
('ALM10802', 'oishi Islam', 'oishi@gmail.com', '01782345679', 'gazipur', 2025, '2006-03-19', 'student', 'CE', 12, '2025-09-04 08:23:59'),
('ALM86496', 'zara', 'zara@gmail.com', '01722334455', 'ttt', 1951, '5444-04-04', 'student', 'IT', 14, '2025-09-04 12:37:25'),
('ALM87539', 'oishi islam', 'toa@gmail.com', '01782345679', 'Gazipur', 2030, '2006-11-29', 'student', 'ME', 13, '2025-09-04 08:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `DepartmentID` varchar(50) NOT NULL,
  `DepartmentName` varchar(100) NOT NULL,
  `RoomNo` varchar(20) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`DepartmentID`, `DepartmentName`, `RoomNo`, `CreatedAt`) VALUES
('BBA', 'Business Administration', '303', '2025-09-04 05:20:05'),
('CE', 'Civil Engineering', '506', '2025-09-04 05:20:05'),
('CS', 'Computer Science', '301', '2025-09-04 05:20:05'),
('CSE', 'Computer Science & Engineering', '101', '2025-09-04 05:20:05'),
('EC', 'Electronics & Communication', '302', '2025-09-04 05:20:05'),
('EEE', 'Electrical Engineering', '202', '2025-09-04 05:20:05'),
('ENG', 'English Literature', '201', '2025-09-04 05:20:05'),
('IT', 'Information Technology', '203', '2025-09-04 05:20:05'),
('LAW', 'Law Department', '404', '2025-09-04 05:20:05'),
('ME', 'Mechanical Engineering', '102', '2025-09-04 05:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `DonationID` int(11) NOT NULL,
  `AlumniID` varchar(50) DEFAULT NULL,
  `DonationAmount` decimal(10,2) NOT NULL,
  `DonationDate` date NOT NULL,
  `DonationPurpose` varchar(200) DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`DonationID`, `AlumniID`, `DonationAmount`, `DonationDate`, `DonationPurpose`, `PaymentMethod`, `CreatedAt`) VALUES
(1, 'ALM10001', 10000.00, '2025-01-10', 'Library Fund', 'Bank Transfer', '2025-09-04 05:20:05'),
(2, 'ALM10002', 5000.00, '2025-02-15', 'Scholarship', 'Cash', '2025-09-04 05:20:05'),
(3, 'ALM10003', 12000.00, '2025-03-20', 'Sports Event', 'Mobile Banking', '2025-09-04 05:20:05'),
(4, 'ALM10004', 8000.00, '2025-04-25', 'Alumni Meetup', 'Bank Transfer', '2025-09-04 05:20:05'),
(5, 'ALM10005', 15000.00, '2025-05-30', 'Charity Drive', 'Card Payment', '2025-09-04 05:20:05'),
(6, 'ALM10006', 7000.00, '2025-06-05', 'Lab Equipment', 'Bank Transfer', '2025-09-04 05:20:05'),
(7, 'ALM10007', 9000.00, '2025-07-12', 'Cultural Program', 'Mobile Banking', '2025-09-04 05:20:05'),
(8, 'ALM10008', 6000.00, '2025-08-18', 'Research Grant', 'Bank Transfer', '2025-09-04 05:20:05'),
(9, 'ALM10009', 11000.00, '2025-09-01', 'Student Support', 'Credit Card', '2025-09-04 05:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `EventName` varchar(100) NOT NULL,
  `AlumniID` varchar(50) DEFAULT NULL,
  `Date` date NOT NULL,
  `Location` varchar(200) DEFAULT NULL,
  `Organizer` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `EventName`, `AlumniID`, `Date`, `Location`, `Organizer`, `Description`, `CreatedAt`) VALUES
(2, 'Tech Summit', 'ALM10002', '2025-02-12', 'Banani', 'CS Dept', 'Technology showcase', '2025-09-04 05:20:05'),
(3, 'Career Fair', 'ALM10003', '2025-03-14', 'Mirpur', 'BBA Dept', 'Job fair for graduates', '2025-09-04 05:20:05'),
(4, 'Cultural Fest', 'ALM10004', '2025-04-20', 'Gulshan', 'Alumni Assoc.', 'Music and arts festival', '2025-09-04 05:20:05'),
(5, 'Sports Meet', 'ALM10005', '2025-05-10', 'Mohakhali', 'Sports Club', 'Cricket and football matches', '2025-09-04 05:20:05'),
(6, 'Research Expo', 'ALM10006', '2025-06-18', 'Dhanmondi', 'IT Dept', 'Research presentations', '2025-09-04 05:20:05'),
(7, 'Law Seminar', 'ALM10007', '2025-07-22', 'Farmgate', 'Law Dept', 'Legal awareness program', '2025-09-04 05:20:05'),
(8, 'Book Fair', 'ALM10008', '2025-08-15', 'Motijheel', 'ENG Dept', 'Book exhibition', '2025-09-04 05:20:05'),
(9, 'Startup Pitch', 'ALM10009', '2025-09-05', 'Mohakhali', 'EEE Dept', 'Startup funding program', '2025-09-04 05:20:05'),
(10, 'Robotics Competition', 'ALM10010', '2025-09-20', 'Uttara', 'ME Dept', 'Robotics challenge', '2025-09-04 05:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `EventID` int(11) NOT NULL,
  `AlumniID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `UserRole` enum('admin','alumni') DEFAULT 'alumni',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `UserRole`, `CreatedAt`) VALUES
(1, 'asfiya', '$2y$10$53ICP.Q74Ho.Q0oicRpSe.5NLjPZpl6yQegcm30pmGAxVmTR7liFm', 'asfiyaarifina@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(2, 'nilima', '$2y$10$yyLxsqiWI3eWBAMzzfQHYelkQIIqR3OCQC7h67aw52SpYwd0GJCOq', 'nilimaalim@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(3, 'maisha', '$2y$10$dson/ly0z9wS.gH6P249MuMgCwWE8Osk9LqRun30wUXsfoAjIYahS', 'maisha@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(4, 'ds', '$2y$10$Z00EE0bs3P0Ryw3qgEpRt.jFnjIdLMHa3r.LYrCYhqvsF28bzqB/G', 'asd@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(5, 'admin1', '$2y$10$uXl5pDkVdP.TdY5Ko8gP3OaKrbqMEmhZoUuY9zQkqQJ7qQ5o7Fb5y', 'admin@example.com', 'admin', '2025-09-04 05:20:05'),
(6, 'rahim', '$2y$10$5s3djnsE9MxLqUp9XvZTHe8beXx9wqE0KfoYdhtI4l3v/.EQp3I4y', 'rahim@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(7, 'karim', '$2y$10$8as0nJz12SKvSgMok8IWeOL.1Yb1F/61rK2Px7FfVvZr7qLrHq7kG', 'karim@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(8, 'sadia', '$2y$10$9dUsg4uE0BdOEpqPpqlh/8L3Lmqj8G0ZV9sZ3pD4gP2Kfl2eTe2z6', 'sadia@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(9, 'tania', '$2y$10$0hfR6XzO3mLvJtWqP7mC6u5lHv9jQwZtM8Ox1QGz4N6bJkYfRt5Mi', 'tania@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(10, 'sohel', '$2y$10$5LsL1x1fPZbYdV9O1s6k0uRj1nU9j0vE9bS4p0Qy9a5dJ7qOq9o2b', 'sohel@gmail.com', 'alumni', '2025-09-04 05:20:05'),
(11, 'newuser', '$2y$10$abc123hashedpasswordexample', 'newuser@example.com', 'alumni', '2025-09-04 06:49:02'),
(12, 'oishi', '$2y$10$8FD5/TIoR6ptaWMBDFa3A.UIX5BMg1I09rMkYfVmq1NAB/TIgM1t.', 'oishi@gmail.com', 'alumni', '2025-09-04 08:23:59'),
(13, 'toa', '$2y$10$Cg5gFV8arY.KhtvtzrMLIe1sPKooa4R5obZT3hyeMsn43z8ztY8w2', 'toa@gmail.com', 'alumni', '2025-09-04 08:35:00'),
(14, 'zara', '$2y$10$AoGWOMbmj3qF/IfbAIM15OVliSvzdf3v0z6pH9vaU4pvPWgsItyEG', 'zara@gmail.com', 'alumni', '2025-09-04 12:37:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`AlumniID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `idx_alumni_email` (`Email`),
  ADD KEY `idx_alumni_gradyear` (`GraduationYear`),
  ADD KEY `idx_alumni_department` (`DepartmentID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`DonationID`),
  ADD KEY `AlumniID` (`AlumniID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `AlumniID` (`AlumniID`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`EventID`,`AlumniID`),
  ADD KEY `AlumniID` (`AlumniID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `DonationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni`
--
ALTER TABLE `alumni`
  ADD CONSTRAINT `alumni_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `departments` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alumni_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`AlumniID`) REFERENCES `alumni` (`AlumniID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`AlumniID`) REFERENCES `alumni` (`AlumniID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `events` (`EventID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`AlumniID`) REFERENCES `alumni` (`AlumniID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
