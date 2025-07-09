-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 12:33 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `servicehub`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `scheduled_date` datetime DEFAULT NULL,
  `status` enum('pending','accepted','in_progress','completed','cancelled') DEFAULT 'pending',
  `confirmed` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `additional_notes` text DEFAULT NULL,
  `payment_method` enum('wallet','card','cash') DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `client_id`, `provider_id`, `service_id`, `scheduled_date`, `status`, `confirmed`, `created_at`, `updated_at`, `additional_notes`, `payment_method`, `amount`) VALUES
(2, 1, NULL, 6, 1, '2025-06-12 11:24:00', 'pending', 0, '2025-06-30 08:20:41', '2025-06-30 08:20:41', 'To be submitted today.', 'wallet', '5000.00');

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `user_id`, `service_id`, `title`, `description`, `location`, `price`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 'John Plumbing Services', 'Expert plumbing services for residential and commercial spaces.', 'Lagos', '5000.00', 4.5, 'active', '2025-06-30 01:54:01', '2025-06-30 01:54:01'),
(7, 2, 2, 'Bright Electricals', 'Reliable electrical installations and repairs.', 'Abuja', '7000.00', 4.8, 'active', '2025-06-30 01:54:01', '2025-06-30 01:54:01'),
(8, 3, 3, 'CleanPro Services', 'Professional cleaning services for homes and offices.', 'Port Harcourt', '3000.00', 4.2, 'active', '2025-06-30 01:54:01', '2025-06-30 01:54:01'),
(9, 4, 4, 'Paint Masters', 'High-quality painting services for interior and exterior walls.', 'Ibadan', '8000.00', 4.7, 'active', '2025-06-30 01:54:01', '2025-06-30 01:54:01'),
(10, 5, 5, 'WoodWorks Carpentry', 'Skilled carpentry services for furniture and woodwork.', 'Enugu', '6000.00', 4.6, 'active', '2025-06-30 01:54:01', '2025-06-30 01:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL CHECK (`stars` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `category`, `title`, `description`, `price`, `status`, `created_at`, `updated_at`, `icon`) VALUES
(1, 1, 'Home Services', 'Plumbing', 'Professional plumbing services for your home or office.', '5000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-wrench'),
(2, 2, 'Home Services', 'Electrical', 'Expert electrical services for installations and repairs.', '7000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-bolt'),
(3, 3, 'Cleaning Services', 'Cleaning', 'Reliable cleaning services for residential and commercial spaces.', '3000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-broom'),
(4, 4, 'Painting Services', 'Painting', 'High-quality painting services for interior and exterior walls.', '8000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-paint-roller'),
(5, 5, 'Woodwork', 'Carpentry', 'Skilled carpentry services for furniture and woodwork.', '6000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-hammer'),
(6, 6, 'Outdoor Services', 'Gardening', 'Professional gardening and landscaping services.', '4000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-seedling'),
(7, 7, 'IT Services', 'IT Support', 'Technical IT support for hardware and software issues.', '10000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-laptop-code'),
(8, 8, 'Relocation', 'Moving', 'Efficient moving and relocation services.', '12000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:39', 'fa-solid fa-truck'),
(9, 9, 'Childcare', 'Babysitting', 'Trusted babysitting services for your children.', '5000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:40', 'fa-solid fa-baby'),
(10, 10, 'Education', 'Tutoring', 'Expert tutoring services for academic and skill development.', '4000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:40', 'fa-solid fa-book-open'),
(11, 11, 'Photography', 'Photography', 'Professional photography services for events and portraits.', '15000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:40', 'fa-solid fa-camera'),
(12, 12, 'Event Management', 'Event Planning', 'Creative event planning and management services.', '20000.00', 'active', '2025-06-30 01:48:13', '2025-06-30 01:48:40', 'fa-solid fa-calendar-check');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `type` enum('deposit','hold','release','withdrawal') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','provider') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `location`, `verified`, `created_at`, `updated_at`) VALUES
(1, 'Emerald Tate', 'kabriacid01@gmail.com', '$2y$10$K1n62Njaml1hVTOibO/UTeXeRw7XbFJZ0abSzQ1pAM1jZhtdZfKvS', 'provider', '0907897653', 'Est quibusdam vel ut aut culpa', 0, '2025-06-20 18:12:32', '2025-06-30 01:57:14'),
(2, 'Ebony Harrison', 'mudafybyr@mailinator.com', '$2y$10$CTuelpdmhOBsHMewM5/tjOYwij4pAKZdQh.xnVXtmbsoLMydo74oe', 'provider', '+1 (607) 327-3633', 'Proident beatae sint voluptas autem aut aute numquam cupidatat adipisci numquam adipisci rerum mole', 0, '2025-06-20 18:31:09', '2025-06-20 18:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `balance` decimal(12,2) DEFAULT 0.00,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `last_updated`) VALUES
(1, 1, '3560.00', '2025-06-30 08:20:41'),
(2, 2, '1120.00', '2025-06-30 01:27:17');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` datetime DEFAULT current_timestamp(),
  `processed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
