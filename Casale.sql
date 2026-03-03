
-- Creates
CREATE TABLE USERS (
    id int not null AUTO_INCREMENT,
    username varchar(20) not null,
    password varchar(20) not null, 
    firstname varchar(20) not null,
    lastname varchar(20) not null,
    email varchar(20) not null,
    phone varchar(20) not null,
    address varchar(20) null,
    CONSTRAINT User_PK PRIMARY KEY (id),
    CONSTRAINT Unique_User UNIQUE (username),
    CONSTRAINT Unique_Email UNIQUE (email),
    CONSTRAINT Unique_Phone UNIQUE (phone)
);

CREATE TABLE CASES (
    id int not null AUTO_INCREMENT,
    title varchar(50) not null,
    court varchar(50) not null,
    type varchar(20) not null,
    filing_date DATE not null,
    status varchar(20) not null,
    CONSTRAINT Case_PK PRIMARY KEY (id),
    CONSTRAINT Case_Status_Check CHECK 
        (status IN ("Open", "Closed", "Pending", "Appeal"))
);
-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2026 at 03:31 PM
-- Server version: 8.0.45
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lawfirm`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int UNSIGNED NOT NULL,
  `client_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `client_email` varchar(190) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attorney_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','scheduled','confirmed','completed','cancelled','no_show') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `client_name`, `client_email`, `attorney_name`, `title`, `description`, `location`, `status`, `created_at`, `updated_at`) VALUES
(10, 'chiemela francis', 'chiemela039@gmail.com', 'Attorney', 'loitering', '', '', 'pending', '2026-02-26 08:24:24', '2026-02-26 08:24:24'),
(11, 'chiemela francis', 'chiemela039@gmail.com', 'Attorney', 'bb', '', '', 'pending', '2026-02-26 08:25:01', '2026-02-26 08:25:01'),
(12, 'chiemela francis', 'chiemela039@gmail.com', 'Attorney', 'ljl', '', '', 'confirmed', '2026-02-26 08:35:12', '2026-02-26 08:53:12'),
(13, 'chiemela francis', 'chiemela039@gmail.com', 'Attorney', 'aa', '', '', 'confirmed', '2026-02-26 08:52:07', '2026-02-26 15:46:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2026 at 03:36 PM
-- Server version: 8.0.45
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lawfirm`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_times`
--

CREATE TABLE `appointment_times` (
  `appt_time_id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `timezone` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'America/New_York',
  `type` enum('meeting','call','court','deadline','other') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'meeting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_times`
--

INSERT INTO `appointment_times` (`appt_time_id`, `appointment_id`, `start_time`, `end_time`, `timezone`, `type`) VALUES
(10, 10, '2026-02-26 06:22:00', '2026-02-26 08:22:00', 'America/New_York', 'meeting'),
(11, 11, '2026-02-26 07:24:00', '2026-02-26 10:27:00', 'America/New_York', 'meeting'),
(12, 12, '2026-02-26 03:35:00', '2026-02-26 03:36:00', 'America/New_York', 'meeting'),
(13, 13, '2026-02-26 04:51:00', '2026-02-26 06:53:00', 'America/New_York', 'meeting');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_times`
--
ALTER TABLE `appointment_times`
  ADD PRIMARY KEY (`appt_time_id`),
  ADD KEY `fk_time_appointment` (`appointment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_times`
--
ALTER TABLE `appointment_times`
  MODIFY `appt_time_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment_times`
--
ALTER TABLE `appointment_times`
  ADD CONSTRAINT `fk_time_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;





-- Sample data
INSERT INTO USERS 
    VALUES ("AdminCasale", "1", "Charles", "Casale", "casale@gmail.com", "1112223456");

INSERT INTO USERS 
    VALUES ("jason12", "2", "Jason", "Chandler", "jason@gmail.com", "1234567890");

INSERT INTO USERS 
    VALUES ("Chiemela", "3", "Chiemela", "Francis", "chiemela@gmail.com", "0987654321");

INSERT INTO USERS 
    VALUES ("Stephen", "4", "Stephen", "Escalante", "stephen@gmail.com", "4561237890");

INSERT INTO USERS 
    VALUES ("William", "5", "William", "Mazal", "william@gmail.com", "789012345");


INSERT INTO CASES
    VALUES ("Chandler v. State", "New Jersey Superior Court", 
            "criminal", "2026-02-05", "open");

INSERT INTO CASES
    VALUES ("Francis v. Smith", "Philadelphia Municiple Court", 
            "negligence", "2026-01-28", "open");    

INSERT INTO CASES
    VALUES ("Mazal v. Mayo", "Central Municiple Court", 
            "matrimonial", "2026-02-09", "open");    



