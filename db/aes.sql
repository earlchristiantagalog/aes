-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 09:17 AM
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
  `is_hot` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `status`, `is_hot`, `created_at`, `updated_at`) VALUES
(14, 'Gadget', '', 'bi-laptop', 'active', 0, '2026-02-14 04:53:48', '2026-02-14 04:53:48'),
(15, 'School Supplies', '', 'bi-book', 'active', 0, '2026-02-14 04:55:59', '2026-02-14 04:55:59'),
(16, 'Office Supplies', '', 'bi-paperclip', 'active', 0, '2026-02-20 11:34:20', '2026-02-20 11:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_id` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'Pending',
  `payment_status` varchar(50) DEFAULT 'Unpaid',
  `receipt_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_number` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address_id`, `payment_method`, `shipping_id`, `subtotal`, `shipping_fee`, `discount`, `total_amount`, `order_status`, `payment_status`, `receipt_image`, `created_at`, `order_number`, `payment_reference`, `confirmed_at`, `shipped_at`, `delivered_at`) VALUES
(1, 9, 3, 'bank-transfer', 2, 27000.00, 50.00, 0.00, 24350.00, 'Pending', 'Unpaid', 'uploads/receipts/1772263003_4e72cb6d-b760-4a54-883f-18f7d29ccad8.jpeg', '2026-02-28 07:16:43', NULL, NULL, NULL, NULL, NULL),
(2, 9, 3, 'bank-transfer', 2, 27000.00, 50.00, 0.00, 27050.00, 'Pending', 'Unpaid', 'uploads/receipts/1772263190_7c361e88-379b-4de0-8e80-b36d92c3de20.jpeg', '2026-02-28 07:19:50', 'AES-20260228-73443', 'PENDING', NULL, NULL, NULL),
(3, 9, 3, 'credit-card', 5, 240.00, 100.00, 0.00, 316.00, 'Pending', 'Unpaid', NULL, '2026-02-28 07:25:55', 'AES-20260228-51932', 'PENDING', NULL, NULL, NULL),
(4, 9, 3, 'credit-card', 5, 100.00, 100.00, 0.00, 190.00, 'Pending', 'Unpaid', NULL, '2026-02-28 07:31:12', 'AES-20260228-32384', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `variant` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `variant`, `price`, `quantity`, `total`) VALUES
(1, 1, 5, 'Book Shelf', 'XL', 0.00, 2, 0.00),
(2, 2, 5, 'Book Shelf', 'XL', 0.00, 2, 0.00),
(3, 3, 6, 'One Bond Paper', '80', 0.00, 2, 0.00),
(4, 4, 6, 'One Bond Paper', '70', 0.00, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `method_key` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `method_key`, `display_name`, `is_active`, `instructions`) VALUES
(1, 'payment_gcash', 'GCash', 1, 'Please have your GCash app ready for verification.'),
(2, 'payment_maya', 'Maya', 1, NULL),
(3, 'payment_cards', 'Credit/Debit Cards', 1, NULL),
(4, 'payment_paypal', 'PayPal', 1, NULL),
(5, 'payment_cod', 'Cash on Delivery', 1, NULL),
(6, 'payment_bank', 'Bank Transfer', 1, 'Bank Name: BDO Unibank\nAccount Name: Aralin Educational Supplies\nAccount Number: 1234-5678-9012');

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
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` int(11) NOT NULL,
  `shipping_name` varchar(100) NOT NULL,
  `shipping_price` decimal(10,2) NOT NULL,
  `shipping_courier` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_zones`
--

INSERT INTO `shipping_zones` (`id`, `shipping_name`, `shipping_price`, `shipping_courier`, `created_at`) VALUES
(2, 'Express', 50.00, 'J&T Express', '2026-02-26 13:53:21'),
(5, 'Standard', 100.00, 'AES Express', '2026-02-28 04:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `staff_id` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `first_name`, `last_name`, `email`, `phone`, `department`, `status`, `staff_id`, `password`, `created_at`) VALUES
(1, 'Test', 'Test', 'earlchristiantagalog10@gmail.com', '09168218393', 'Administration', 'active', 'STF-9535', '$2y$10$FWTMc4mNbjY8B44m3XPQLugvwEFm4I7O5KtXbi7tGVqCFuKwY90gW', '2026-02-17 12:43:54'),
(2, 'Earl', 'Tagalog', 'earlchristiantagalog1@gmail.com', '09168218393', 'Owner', 'active', 'STF-6142', '$2y$10$RcXk0VBbZomv5Hhm60dTEuJEXtrD9iZiFBKr0LIoA8fGWquq8q8bS', '2026-02-17 12:48:20');

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
(9, '462777', 'Earl Christian Tagalog', 'earlchristianespina@gmail.com', '$2y$10$LSFCiOWDCXmkbhPFd.9SKO5Nk3aoVt1S6MUUlZUhlGQi7HDGf11Lm', 0, 'active', '2026-02-24 12:26:34', '2026-02-24 20:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address_line` text DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `first_name`, `last_name`, `address_line`, `province`, `city`, `barangay`, `zip_code`, `phone`, `is_default`, `created_at`) VALUES
(3, 9, 'Home', 'Earl', 'Tagalog', 'Malunhaw St.', 'Cebu', 'Consolacion', 'Pulpogan', '6001', '09168218393', 1, '2026-02-24 14:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_type` enum('fixed','percentage') DEFAULT 'fixed',
  `user_id` int(11) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_amount`, `discount_type`, `user_id`, `is_used`, `expiry_date`, `created_at`) VALUES
(1, 'TEST10', 10.00, 'percentage', NULL, 0, '2026-03-31', '2026-02-28 06:33:16');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method_key` (`method_key`);

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
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_no` (`account_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
