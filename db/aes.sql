-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2026 at 06:49 AM
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
-- Database: `aes`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(14, 'Gadget', '', 'bi-laptop', 'active', '2026-02-14 04:53:48', '2026-02-14 04:53:48'),
(15, 'School Supplies', '', 'bi-book', 'active', '2026-02-14 04:55:59', '2026-02-14 04:55:59');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(10) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_number` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `small_description` varchar(255) DEFAULT NULL,
  `full_description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `category_id`, `product_number`, `name`, `small_description`, `full_description`, `category`, `base_price`, `status`, `created_at`, `updated_at`) VALUES
(5, 'PX0XKS75RF', NULL, 2147483647, 'Book Shelf', 'sadwadasdsdasdas', 'dasdascscxzcasdasszxzsczxcz', 'Gadget, School Supplies', 0.00, 'active', '2026-02-14 04:57:55', '2026-02-14 04:57:55'),
(6, 'L2AOG6KHK5', NULL, 2147483647, 'One Bond Paper', 'High Quality and Long Lasting', '<p><span class=\"T286Pc\" data-sfc-cp=\"\" jscontroller=\"fly6D\" jsuid=\"rI4kg_d\" style=\"overflow-wrap: break-word;\"><span class=\"T286Pc\" data-sfc-cp=\"\" jscontroller=\"fly6D\" jsuid=\"rI4kg_e\" style=\"overflow-wrap: break-word;\">Bond paper</span>&nbsp;is&nbsp;</span><mark class=\"HxTRcb\" jscontroller=\"DfH0l\" jsuid=\"rI4kg_h\" style=\"border-radius: 4px; background-image: none; background-position: 0% 0%; background-size: auto; background-repeat: repeat; background-attachment: scroll; background-origin: padding-box; background-clip: border-box; padding: 0px 2px;\"><u><span class=\"T286Pc\" data-sfc-cp=\"\" jscontroller=\"fly6D\" jsuid=\"rI4kg_i\" style=\"overflow-wrap: break-word;\">a durable, high-quality writing and printing paper known for its&nbsp;</span><span class=\"Yjhzub\" jscontroller=\"zYmgkd\" jsuid=\"rI4kg_j\" style=\"\">stiffness, strength, and smooth finish</span></u></mark>, originally used for important documents like government bonds. It is commonly used today for professional documents, letterheads, and general office printing.</p>', 'Gadget, School Supplies', 0.00, 'active', '2026-02-17 04:07:29', '2026-02-17 04:07:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_cover` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_cover`) VALUES
(21, 5, 'prod_5_699000d37e277.png', 1),
(22, 5, 'prod_5_699000d37efe8.png', 0),
(23, 5, 'prod_5_699000d37f3d6.png', 0),
(24, 5, 'prod_5_699000d38001e.png', 0),
(25, 5, 'prod_5_699000d383801.png', 0),
(26, 6, 'prod_6_6993e9812c194.png', 1),
(27, 6, 'prod_6_6993e981320fc.png', 0),
(28, 6, 'prod_6_6993e9813a752.png', 0),
(29, 6, 'prod_6_6993e9813f66f.png', 0),
(30, 6, 'prod_6_6993e9814ae58.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_type` varchar(50) DEFAULT NULL,
  `variant_value` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_type`, `variant_value`, `price`, `stock`) VALUES
(4, 5, 'A', 'XL', 13500.00, 10),
(5, 6, 'GSM', '70', 100.00, 11),
(6, 6, 'GSM', '80', 120.00, 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` int(6) DEFAULT 0,
  `status` enum('pending','active','blocked') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `account_no`, `full_name`, `email`, `password`, `otp`, `status`, `created_at`, `otp_expiry`) VALUES
(4, '976880', 'Earl Christian Tagalog', 'earlchristiantagalog10@gmail.com', '$2y$10$RobEg0jdRvaTJZpbMv02f.nBmmkaUjxBVLzflBXFrOHHYFn6cPjW.', 0, 'active', '2026-02-17 05:37:33', '2026-02-17 13:42:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_no` (`account_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
