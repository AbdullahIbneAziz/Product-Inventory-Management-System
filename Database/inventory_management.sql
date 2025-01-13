-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 01:18 PM
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
-- Database: `inventory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch_stock`
--

CREATE TABLE `branch_stock` (
  `id` int(11) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `request_id` varchar(50) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_stock`
--

INSERT INTO `branch_stock` (`id`, `branch`, `product_id`, `product_name`, `quantity`, `size`, `unit_price`, `request_id`, `date_added`) VALUES
(9, 'Banani', 'P1', 'T-Shirt', 80, 'L', 400.00, 'BREQ1736768923344', '2025-01-13 17:49:07'),
(10, 'Banani', 'P2', 'Lungi', 50, 'M', 700.00, 'BREQ1736768923344', '2025-01-13 17:49:07'),
(11, 'Banani', 'P3', 'Denim Pant', 80, 'L', 1200.00, 'BREQ1736768923344', '2025-01-13 17:49:07'),
(12, 'Shyamoli', 'P1', 'T-Shirt', 200, 'L', 400.00, 'SREQ1736769715111', '2025-01-13 18:02:12'),
(13, 'Shyamoli', 'P2', 'Lungi', 50, 'L', 700.00, 'SREQ1736769715111', '2025-01-13 18:02:12'),
(14, 'Shyamoli', 'P3', 'Denim Pant', 80, 'M', 1200.00, 'SREQ1736769715111', '2025-01-13 18:02:12');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `last_updated` datetime NOT NULL,
  `outlet` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `unit_price`, `supplier`, `last_updated`, `outlet`) VALUES
('P1', 'T-Shirt', 220, 400.00, 'Aarong', '2025-01-13 11:45:59', 'Main'),
('P2', 'Lungi', 250, 700.00, 'Amanat Shah', '2025-01-13 11:46:46', 'Main'),
('P3', 'Denim Pant', 20, 1200.00, 'Swan', '2025-01-13 11:47:35', 'Main');

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

CREATE TABLE `request_items` (
  `id` int(11) NOT NULL,
  `request_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_items`
--

INSERT INTO `request_items` (`id`, `request_id`, `product_name`, `quantity`, `size`) VALUES
(13, 'BREQ1736768923344', 'Denim Pant', 100, 'L'),
(14, 'BREQ1736768923344', 'Lungi', 50, 'M'),
(15, 'BREQ1736768923344', 'T-Shirt', 80, 'L');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `net_price` decimal(10,2) NOT NULL,
  `outlet` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sale_date`, `product_id`, `product_name`, `quantity`, `unit_price`, `net_price`, `outlet`, `created_at`) VALUES
(11, '2025-01-13', 'P3', 'Denim Pant', 20, 1200.00, 24000.00, 'Banani', '2025-01-13 11:49:44'),
(12, '2025-01-13', 'P2', 'Lungi', 50, 700.00, 35000.00, 'Shyamoli', '2025-01-13 12:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `shyamoli_request_items`
--

CREATE TABLE `shyamoli_request_items` (
  `id` int(11) NOT NULL,
  `request_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shyamoli_request_items`
--

INSERT INTO `shyamoli_request_items` (`id`, `request_id`, `product_name`, `quantity`, `size`) VALUES
(6, 'SREQ1736769715111', 'Lungi', 100, 'L'),
(7, 'SREQ1736769715111', 'Denim Pant', 80, 'M'),
(8, 'SREQ1736769715111', 'T-Shirt', 200, 'L');

-- --------------------------------------------------------

--
-- Table structure for table `shyamoli_stock_requests`
--

CREATE TABLE `shyamoli_stock_requests` (
  `id` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `request_date` date NOT NULL,
  `urgency` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `action_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shyamoli_stock_requests`
--

INSERT INTO `shyamoli_stock_requests` (`id`, `branch`, `request_date`, `urgency`, `notes`, `status`, `action_date`) VALUES
('SREQ1736769715111', 'Shyamoli', '2025-01-13', 'High', 'Needed', 'approved', '2025-01-13 18:02:12');

-- --------------------------------------------------------

--
-- Table structure for table `stock_requests`
--

CREATE TABLE `stock_requests` (
  `id` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `request_date` date NOT NULL,
  `urgency` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `action_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_requests`
--

INSERT INTO `stock_requests` (`id`, `branch`, `request_date`, `urgency`, `notes`, `status`, `action_date`) VALUES
('BREQ1736768923344', 'Banani', '2025-01-13', 'High', 'Needed', 'approved', '2025-01-13 17:49:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch_stock`
--
ALTER TABLE `branch_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_product_name` (`name`);

--
-- Indexes for table `request_items`
--
ALTER TABLE `request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shyamoli_request_items`
--
ALTER TABLE `shyamoli_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `shyamoli_stock_requests`
--
ALTER TABLE `shyamoli_stock_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_requests`
--
ALTER TABLE `stock_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch_stock`
--
ALTER TABLE `branch_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `request_items`
--
ALTER TABLE `request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shyamoli_request_items`
--
ALTER TABLE `shyamoli_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch_stock`
--
ALTER TABLE `branch_stock`
  ADD CONSTRAINT `branch_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `request_items`
--
ALTER TABLE `request_items`
  ADD CONSTRAINT `request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `stock_requests` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `shyamoli_request_items`
--
ALTER TABLE `shyamoli_request_items`
  ADD CONSTRAINT `shyamoli_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `shyamoli_stock_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
