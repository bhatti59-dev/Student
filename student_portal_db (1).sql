-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2025 at 08:37 AM
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
-- Database: `student_portal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_record`
--

CREATE TABLE `academic_record` (
  `RecordID` int(11) NOT NULL,
  `StudentID` varchar(50) NOT NULL,
  `CourseID` varchar(50) NOT NULL,
  `Grade` varchar(5) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `CompletionDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_record`
--

INSERT INTO `academic_record` (`RecordID`, `StudentID`, `CourseID`, `Grade`, `Status`, `CompletionDate`) VALUES
(10, 'S006', 'CS250', 'B', 'Completed', '2024-10-16'),
(11, 'S008', 'CS250', 'F', 'Completed', '2024-10-29'),
(12, 'S002', 'CS330', 'C', 'Completed', '2025-03-30'),
(13, 'S002', 'CS220', 'F', 'Completed', '2024-12-15'),
(14, 'S003', 'CS220', 'F', 'Completed', '2025-01-09'),
(15, 'S004', 'CS330', 'F', 'In Progress', '2025-04-10'),
(16, 'S010', 'CS220', 'A', 'In Progress', '2025-02-15'),
(17, 'S010', 'CS330', 'A', 'Completed', '2024-12-19');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `CourseID` varchar(50) NOT NULL,
  `CourseName` varchar(100) NOT NULL,
  `CreditHours` int(11) NOT NULL,
  `Semester` varchar(20) NOT NULL,
  `Program` varchar(100) NOT NULL,
  `PrerequisiteCourseID` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`CourseID`, `CourseName`, `CreditHours`, `Semester`, `Program`, `PrerequisiteCourseID`) VALUES
('CS220', 'Database Systems', 3, '3', 'CS110', NULL),
('CS250', 'Data Structures', 3, '3', 'CS110', NULL),
('CS330', 'Operating Systems', 3, '5', 'CS250', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `EnrollmentID` int(11) NOT NULL,
  `StudentID` varchar(50) NOT NULL,
  `CourseID` varchar(50) NOT NULL,
  `EnrollmentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`EnrollmentID`, `StudentID`, `CourseID`, `EnrollmentDate`) VALUES
(1, 'S003', 'CS220', '2025-08-21 07:01:18'),
(2, 'S003', 'CS250', '2025-08-21 07:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Semester` varchar(20) NOT NULL,
  `Program` varchar(100) DEFAULT NULL,
  `ProgramName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `Name`, `Email`, `Password`, `Semester`, `Program`, `ProgramName`) VALUES
('S001', 'Ali Khan', 'ali.khan@example.com', 'pass123', '1', NULL, 'BSCS'),
('S002', 'Sara Ahmed', 'sara.ahmed@example.com', 'pass456', '2', NULL, 'BSCS'),
('S003', 'Hassan Raza', 'hassan.raza@example.com', 'pass789', '3', NULL, 'BSSE'),
('S004', 'Maria Fatima', 'maria.fatima@example.com', 'pass234', '4', NULL, 'BSCS'),
('S005', 'Omar Qureshi', 'omar.qureshi@example.com', 'pass345', '5', NULL, 'BSDS'),
('S006', 'Ayesha Siddiqui', 'ayesha.siddiqui@example.com', 'pass567', '6', NULL, 'BSCS'),
('S007', 'Imran Haider', 'imran.haider@example.com', 'pass678', '7', NULL, 'BSAI'),
('S008', 'Noor Bukhari', 'noor.bukhari@example.com', 'pass890', '8', NULL, 'BSCS'),
('S009', 'Zain Ul Abideen', 'zain.abideen@example.com', 'pass901', '1', NULL, 'BSSE'),
('S010', 'Anum Javed', 'anum.javed@example.com', 'pass012', '2', NULL, 'BSDS');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `TeacherID` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`TeacherID`, `Name`, `Email`) VALUES
('T001', 'Ali Khan', 'ali.khan@university.edu'),
('T002', 'Sara Ahmed', 'sara.ahmed@university.edu'),
('T003', 'Hamza Malik', 'hamza.malik@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_courses`
--

CREATE TABLE `teacher_courses` (
  `ID` int(11) NOT NULL,
  `TeacherID` varchar(50) NOT NULL,
  `CourseID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_courses`
--

INSERT INTO `teacher_courses` (`ID`, `TeacherID`, `CourseID`) VALUES
(10, 'T001', 'CS220'),
(11, 'T002', 'CS250'),
(13, 'T001', 'CS220'),
(14, 'T002', 'CS250');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','student','teacher','coordinator') NOT NULL,
  `RefID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Role`, `RefID`) VALUES
(1, 'admin1', 'admin123', 'admin', '1'),
(4, 'coord1', 'coord123', 'coordinator', 'C001'),
(5, 'S001', 'pass123', 'student', 'S001'),
(6, 'S002', 'pass456', 'student', 'S002'),
(7, 'S003', 'pass789', 'student', 'S003'),
(8, 'S004', 'pass234', 'student', 'S004'),
(9, 'S005', 'pass345', 'student', 'S005'),
(10, 'S006', 'pass567', 'student', 'S006'),
(11, 'S007', 'pass678', 'student', 'S007'),
(12, 'S008', 'pass890', 'student', 'S008'),
(13, 'S009', 'pass901', 'student', 'S009'),
(14, 'S010', 'pass012', 'student', 'S010'),
(25, 'teacher1', 'teach123', 'teacher', 'T001'),
(26, 'teacher2', 'teach123', 'teacher', 'T002'),
(27, 'teacher3', 'teach123', 'teacher', 'T003');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_record`
--
ALTER TABLE `academic_record`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`CourseID`),
  ADD KEY `PrerequisiteCourseID` (`PrerequisiteCourseID`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`EnrollmentID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`TeacherID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `teacher_courses`
--
ALTER TABLE `teacher_courses`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_record`
--
ALTER TABLE `academic_record`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `EnrollmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teacher_courses`
--
ALTER TABLE `teacher_courses`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_record`
--
ALTER TABLE `academic_record`
  ADD CONSTRAINT `academic_record_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `academic_record_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`PrerequisiteCourseID`) REFERENCES `course` (`CourseID`) ON DELETE SET NULL;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_courses`
--
ALTER TABLE `teacher_courses`
  ADD CONSTRAINT `teacher_courses_ibfk_1` FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`TeacherID`),
  ADD CONSTRAINT `teacher_courses_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
