-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 01:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@travel.com', '$2y$10$qQ1PDC0RJMzYNOJUVHSsou1WmOYd7mT8LyVRbh4wihQAlzhDEmJz6', '2026-05-14 13:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `priority` enum('normal','high') DEFAULT 'normal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `priority`, `created_at`) VALUES
(1, 'Summer Peak Season Protocol', 'All staff must be available on weekends June-August. Overtime will be compensated at 1.5x rate.', 'high', '2026-05-18 01:45:04'),
(2, 'Team Outing — Murree Trip', 'Celebrating our 14th anniversary! Join us June 5th for a company-sponsored trip to Murree.', 'normal', '2026-05-18 01:45:04'),
(3, 'New Booking System Training', 'Mandatory training on the updated booking platform on May 28th at 10 AM. All departments required.', 'high', '2026-05-18 01:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `guests` int(11) DEFAULT NULL,
  `arrival` date DEFAULT NULL,
  `leaving` date DEFAULT NULL,
  `trip_type` varchar(50) DEFAULT NULL,
  `accommodation` varchar(50) DEFAULT NULL,
  `meal` varchar(50) DEFAULT NULL,
  `transport` varchar(50) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `package_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `location`, `guests`, `arrival`, `leaving`, `trip_type`, `accommodation`, `meal`, `transport`, `budget`, `special_requests`, `status`, `created_at`, `package_id`) VALUES
(1, 0, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'pak', 'paris', 12, '2026-05-23', '2026-06-06', 'Adventure', 'Resort', 'All Inclusive', 'Flight', 1500.00, '', 'confirmed', '2026-05-15 01:56:10', NULL),
(2, 0, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'pak', 'paris', 11, '2026-05-30', '2026-07-11', 'Adventure', 'Resort', 'All Inclusive', 'Flight', 1500.00, '', 'cancelled', '2026-05-17 04:50:00', NULL),
(3, 1, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'larkhana', 'uk', 11, '2026-05-23', '2026-06-06', 'Family', 'Hotel', 'All Inclusive', 'Flight', 3000.00, '', 'pending', '2026-05-17 05:29:13', NULL),
(4, 1, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'pak', 'UAE', 21, '2026-05-30', '2026-06-06', 'Business', 'Hotel', 'All Inclusive', 'Flight', 3000.00, '', 'confirmed', '2026-05-17 05:31:29', NULL),
(5, 0, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'wer', 'china', 12, '2026-05-21', '2026-05-28', 'Adventure', 'Hotel', 'All Inclusive', 'Flight', 1500.00, '', 'confirmed', '2026-05-17 06:11:26', NULL),
(6, 1, 'Noor Fatima', 'noorfatim611612@gmail.com', '3494323053', 'sargodha', 'India', 12, '2026-06-06', '2026-07-11', 'Adventure', 'Hotel', 'All Inclusive', 'Flight', 3000.00, '', 'confirmed', '2026-05-17 06:19:13', NULL),
(7, 2, 'Noor Fatima', 'noorfatim211212@gmail.com', '3484323053', 'kharachi', 'South Africa', 12, '2026-05-30', '2026-07-04', 'Family', 'Hotel', 'All Inclusive', 'Flight', 3000.00, '', 'pending', '2026-05-17 12:00:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_form`
--

CREATE TABLE `book_form` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `guest` int(11) DEFAULT NULL,
  `arrival` date DEFAULT NULL,
  `leaving` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `dept` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `salary` int(11) DEFAULT 0,
  `status` enum('active','remote','leave','inactive') DEFAULT 'active',
  `join_date` date DEFAULT NULL,
  `leaves` int(11) DEFAULT 0,
  `performance` int(11) DEFAULT 85,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `emp_id`, `name`, `role`, `dept`, `email`, `phone`, `salary`, `status`, `join_date`, `leaves`, `performance`, `created_at`) VALUES
(1, 'EMP001', 'ABC Company', 'CEO & Founder', 'Tour Operations', 'aryan@travel.com', '+92 300 1234567', 280000, 'active', '2010-03-15', 5, 98, '2026-05-18 01:45:04'),
(2, 'EMP002', 'Sara Ahmed', 'Head of Destinations', 'Tour Operations', 'sara@travel.com', '+92 321 2345678', 185000, 'active', '2012-07-01', 3, 95, '2026-05-18 01:45:04'),
(3, 'EMP003', 'Omar Khalid', 'Customer Experience Manager', 'Customer Service', 'omar@travel.com', '+92 333 3456789', 145000, 'remote', '2015-01-10', 8, 90, '2026-05-18 01:45:04'),
(4, 'EMP004', 'Nadia Rauf', 'Luxury Travel Specialist', 'Tour Operations', 'nadia@travel.com', '+92 345 4567890', 160000, 'active', '2016-06-20', 2, 92, '2026-05-18 01:45:04'),
(5, 'EMP005', 'Hassan Malik', 'Finance Director', 'Finance', 'hassan@travel.com', '+92 311 5678901', 195000, 'active', '2013-09-05', 4, 88, '2026-05-18 01:45:04'),
(6, 'EMP006', 'Zara Khan', 'Digital Marketing Manager', 'Marketing', 'zara@travel.com', '+92 322 6789012', 130000, 'leave', '2018-03-22', 12, 85, '2026-05-18 01:45:04'),
(7, 'EMP007', 'Bilal Tariq', 'IT Systems Lead', 'IT & Systems', 'bilal@travel.com', '+92 335 7890123', 155000, 'active', '2017-11-14', 1, 94, '2026-05-18 01:45:04'),
(8, 'EMP008', 'Ayesha Siddiqui', 'Senior Tour Guide', 'Guides', 'ayesha@travel.com', '+92 312 8901234', 95000, 'active', '2019-05-30', 6, 89, '2026-05-18 01:45:04'),
(9, 'EMP009', 'Kamran Ali', 'Logistics Coordinator', 'Logistics', 'kamran@travel.com', '+92 344 9012345', 88000, 'inactive', '2020-08-01', 0, 72, '2026-05-18 01:45:04'),
(10, 'EMP010', 'Fatima Zahra', 'HR Specialist', 'HR', 'fatima@travel.com', '+92 301 0123456', 112000, 'active', '2021-02-15', 3, 87, '2026-05-18 01:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `emp_name` varchar(100) DEFAULT NULL,
  `leave_type` varchar(50) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `days` int(11) DEFAULT 1,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `emp_name`, `leave_type`, `from_date`, `to_date`, `days`, `reason`, `status`, `created_at`) VALUES
(1, 'Sara Ahmed', 'Annual Leave', '2025-05-20', '2025-05-25', 5, 'Family vacation', 'approved', '2026-05-18 01:45:04'),
(2, 'Omar Khalid', 'Sick Leave', '2025-05-18', '2025-05-19', 2, 'Medical appointment', 'approved', '2026-05-18 01:45:04'),
(3, 'Zara Khan', 'Annual Leave', '2025-05-15', '2025-05-22', 7, 'Personal travel', 'approved', '2026-05-18 01:45:04'),
(4, 'Ayesha Siddiqui', 'Emergency Leave', '2025-05-21', '2025-05-21', 1, 'Family emergency', 'approved', '2026-05-18 01:45:04'),
(5, 'Kamran Ali', 'Casual Leave', '2025-05-23', '2025-05-23', 1, 'Personal work', 'rejected', '2026-05-18 01:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `description`, `location`, `duration`, `price`, `category`, `image`, `status`, `created_at`) VALUES
(1, 'Himalayan Trek & Adventure', 'Conquer the world\'s most iconic mountain trails with expert guides and premium gear.', 'Nepal', '10 Days', 1299.00, 'adventure', 'images/img-1.jpg', 'active', '2026-05-14 13:17:31'),
(2, 'Bali Honeymoon Escape', 'Romantic villas, private beach dinners, spa rituals and breathtaking Bali sunsets.', 'Bali, Indonesia', '7 Days', 2199.00, 'honeymoon', 'images/img-2.jpg', 'active', '2026-05-14 13:17:31'),
(3, 'Kenya Wildlife Safari', 'Watch the Big Five roam in the Maasai Mara with expert rangers for the whole family.', 'Kenya, Africa', '8 Days', 3499.00, 'family', 'images/img-3.jpg', 'active', '2026-05-14 13:17:31'),
(4, 'Maldives Overwater Retreat', 'Stay in an overwater bungalow above turquoise lagoons. Butler service and infinity pools.', 'Maldives', '6 Days', 5999.00, 'luxury', 'images/img-4.jpg', 'active', '2026-05-14 13:17:31'),
(5, 'Thailand Island Hopping', 'Crystal waters, vibrant nightlife, and ancient temples across stunning islands.', 'Phuket, Thailand', '9 Days', 1799.00, 'beach', 'images/img-5.jpg', 'active', '2026-05-14 13:17:31'),
(6, 'Patagonia End of the World', 'Glaciers, fjords and dramatic peaks at the tip of South America.', 'Patagonia, Chile', '12 Days', 2899.00, 'adventure', 'images/img-6.jpg', 'active', '2026-05-14 13:17:31'),
(7, 'Santorini Sunset Romance', 'White-washed cliffs, volcanic beaches and iconic sunsets in Greece.', 'Santorini, Greece', '7 Days', 2499.00, 'honeymoon', 'images/img-7.jpg', 'active', '2026-05-14 13:17:31'),
(8, 'Japan Cultural Family Tour', 'From Tokyo neon streets to Kyoto ancient temples. Perfect blend of tradition.', 'Japan', '11 Days', 4199.00, 'family', 'images/img-8.jpg', 'active', '2026-05-14 13:17:31'),
(9, 'Dubai City & Desert Luxury', 'Burj Khalifa views, desert dunes, Michelin dining and world-class beaches.', 'Dubai, UAE', '5 Days', 2799.00, 'luxury', 'images/img-9.jpg', 'active', '2026-05-14 13:17:31'),
(10, 'Amazon Rainforest Explorer', 'Explore the world\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'s largest rainforest — jungle treks, Amazon river cruises, exotic wildlife, and eco-lodge stays with expert local guides.', 'Brazil', '8', 1099.00, 'adventure', 'images/img-13.jpg', 'active', '2026-05-15 02:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'card',
  `card_last4` varchar(4) DEFAULT NULL,
  `card_name` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `payment_method`, `card_last4`, `card_name`, `status`, `created_at`) VALUES
(1, 6, 1, 1260000.00, 'card', '1111', 'Test User', 'approved', '2026-05-17 06:24:07'),
(2, 4, 1, 441000.00, 'card', '2345', 'Test User', 'approved', '2026-05-17 10:56:51'),
(3, 3, 1, 462000.00, 'cash', '', '', 'pending', '2026-05-17 11:58:22'),
(4, 7, 2, 1260000.00, 'cash', '', '', 'pending', '2026-05-17 12:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Noor Fatima', 'noorfatim611612@gmail.com', '03494323053', '$2y$10$9Pm4S3RFv.eYrnx0oRALUethXJL6fZ/3Wic385Ejk1lnTmHBR.tAi', '2026-05-15 08:25:18'),
(2, 'Noor Fatima', 'noorfatim211212@gmail.com', '03484323053', '$2y$10$D2cusVXnr2cPSjOgVDWwgeIS7p274ZR026Z9Vp339yLe2vV46C.fK', '2026-05-17 11:30:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_form`
--
ALTER TABLE `book_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `book_form`
--
ALTER TABLE `book_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
