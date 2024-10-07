-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2023 at 10:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agri_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `uname`, `pass`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '2023-11-04 04:49:05', '2023-11-04 04:49:05');

-- --------------------------------------------------------

--
-- Table structure for table `ar_apply_service`
--

CREATE TABLE `ar_apply_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serviceid` varchar(60) NOT NULL,
  `service` varchar(60) NOT NULL,
  `provider` varchar(60) NOT NULL,
  `address` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  `mobile` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `rdate` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_apply_service`
--

INSERT INTO `ar_apply_service` (`id`, `serviceid`, `service`, `provider`, `address`, `city`, `mobile`, `status`, `rdate`) VALUES
(1, '1', 'Advisory Services', 'raja', 'trichy', 'trichy', '7565767896', '1', '05-12-2023'),
(2, '2', 'ewfwe', 'gewgw', 'gewe', 'gg', 'gg', '1', 'gg');

-- --------------------------------------------------------

--
-- Table structure for table `ar_booking`
--

CREATE TABLE `ar_booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(60) NOT NULL,
  `provider` varchar(60) NOT NULL,
  `vid` varchar(60) NOT NULL,
  `duration` varchar(60) NOT NULL,
  `time_type` varchar(60) NOT NULL,
  `req_date` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `amount` varchar(60) NOT NULL,
  `pay_st` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_booking`
--

INSERT INTO `ar_booking` (`id`, `uname`, `provider`, `vid`, `duration`, `time_type`, `req_date`, `status`, `amount`, `pay_st`, `created_at`, `updated_at`) VALUES
(1, 'jebin', 'selva', '1', '1', '1', '2023-11-07', '2', '500', '0', '2023-11-06 11:38:38', '2023-11-23 10:33:43'),
(2, 'jebin', 'selva', '2', '1', '1', '2023-11-09', '1', '500', '0', '2023-11-07 05:23:08', '2023-11-08 12:38:07'),
(3, 'jebin', 'raja', '3', '15', '1', '2023-11-10', '1', '7500', '0', '2023-11-07 05:23:51', '2023-11-07 05:26:09'),
(4, 'jebin', 'raja', '4', '10', '2', '2023-11-11', '1', '20000', '0', '2023-11-07 05:24:52', '2023-11-07 05:26:09'),
(5, 'jebin', 'selva', '5', '2', '2', '2023-11-25', '1', '4000', '0', '2023-11-23 10:50:10', '2023-11-23 10:53:34'),
(6, 'jebin', 'raja', '6', '1', '2', '2023-12-09', '2', '1000', '0', '2023-12-08 08:22:46', '2023-12-08 08:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `ar_customer`
--

CREATE TABLE `ar_customer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `address` varchar(60) NOT NULL,
  `district` varchar(40) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(60) NOT NULL,
  `uname` varchar(15) NOT NULL,
  `pass` varchar(15) NOT NULL,
  `status` varchar(40) NOT NULL,
  `create_date` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_customer`
--

INSERT INTO `ar_customer` (`id`, `name`, `address`, `district`, `mobile`, `email`, `uname`, `pass`, `status`, `create_date`, `created_at`, `updated_at`) VALUES
(1, 'lavanya', 'trichy', 'trichy', '7565767896', 'lavanya@gmail.com', 'godl', '1234', '0', '04-12-2023', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ar_live_stock_booking`
--

CREATE TABLE `ar_live_stock_booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(60) NOT NULL,
  `animal` varchar(60) NOT NULL,
  `aweight` varchar(60) NOT NULL,
  `location` varchar(60) NOT NULL,
  `details` varchar(60) NOT NULL,
  `cost` varchar(60) NOT NULL,
  `file` varchar(60) NOT NULL,
  `latitude` varchar(60) NOT NULL,
  `longitude` varchar(60) NOT NULL,
  `rdate` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_live_stock_booking`
--

INSERT INTO `ar_live_stock_booking` (`id`, `provider`, `animal`, `aweight`, `location`, `details`, `cost`, `file`, `latitude`, `longitude`, `rdate`, `created_at`, `updated_at`) VALUES
(1, 'raja', 'goat', '50', 'chatram', 'high weight in goat', '15000', '1701961207_images.jpg', '13.031506', '80.204002', '07-12-2023', NULL, NULL),
(2, 'selva', 'cow', '50', 'chatram', 'high weight in cow', '15000', '1702008502_download.jpg', '12.916022', '80.147026', '08-12-2023', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ar_product`
--

CREATE TABLE `ar_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product` varchar(40) NOT NULL,
  `farmer` varchar(40) NOT NULL,
  `qty` varchar(40) NOT NULL,
  `price` varchar(40) NOT NULL,
  `pfile` varchar(40) NOT NULL,
  `rdate` varchar(40) NOT NULL,
  `status` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_product`
--

INSERT INTO `ar_product` (`id`, `product`, `farmer`, `qty`, `price`, `pfile`, `rdate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tomato', 'jebin', '1000', '100', '1702011252_tomato.jpg', '04-12-2023', '0', NULL, NULL),
(2, 'Rice', 'selva', '1000', '100', '1701673829_rice.jpg', '04-12-2023', '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ar_product_booking`
--

CREATE TABLE `ar_product_booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(60) NOT NULL,
  `farmer` varchar(60) NOT NULL,
  `fid` varchar(60) NOT NULL,
  `qty` varchar(60) NOT NULL,
  `req_date` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `amount` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_product_booking`
--

INSERT INTO `ar_product_booking` (`id`, `uname`, `farmer`, `fid`, `qty`, `req_date`, `status`, `amount`, `created_at`, `updated_at`) VALUES
(1, 'godl', 'jebin', '1', '2', '2023-12-07', '2', '100', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ar_provider`
--

CREATE TABLE `ar_provider` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `address` varchar(100) NOT NULL,
  `district` varchar(40) NOT NULL,
  `mobile` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `uname` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `create_date` varchar(40) NOT NULL,
  `status` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_provider`
--

INSERT INTO `ar_provider` (`id`, `name`, `address`, `district`, `mobile`, `email`, `uname`, `pass`, `create_date`, `status`) VALUES
(1, 'raja', 'Uraiyour', 'trichy', '06578945678', 'jebinp08@gmail.com', 'raja', '1234', '04-11-2023', '1'),
(2, 'selva f', 'ddd', 'trichy', '06381082863', 'jebinp08@gmail.com', 'selva', '1234', '04-11-2023', '1'),
(3, 'Mohanraj.D', 'ddd', 'trichy', '07339591524', 'mohandeva428@gmail.com', 'mohan', '1234', '04-11-2023', '1'),
(4, 'abi', 'trichy', 'trichy', '08764567876', 'jebinp08@gmail.com', 'abi', '1234', '04-11-2023', '1'),
(5, 'lavanya', 'trichy', 'trichy', '07565767896', 'lavanya@gmail.com', 'lavanya', '1234', '04-12-2023', '0');

-- --------------------------------------------------------

--
-- Table structure for table `ar_service`
--

CREATE TABLE `ar_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(60) NOT NULL,
  `rdate` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_service`
--

INSERT INTO `ar_service` (`id`, `service_name`, `rdate`) VALUES
(1, 'Advisory Services', '05-12-2023'),
(2, 'Digital Services', '05-12-2023'),
(3, 'Precision Farming', '05-12-2023'),
(4, 'Supply Quality Seedlings', '05-12-2023'),
(5, 'Supply Daily Workers', '05-12-2023'),
(6, 'Monthly Field visit and Consulting', '05-12-2023'),
(7, 'Farm Development Consulting', '05-12-2023');

-- --------------------------------------------------------

--
-- Table structure for table `ar_service_booking`
--

CREATE TABLE `ar_service_booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(60) NOT NULL,
  `provider` varchar(60) NOT NULL,
  `sid` varchar(60) NOT NULL,
  `duration` varchar(60) NOT NULL,
  `time_type` varchar(60) NOT NULL,
  `req_date` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `amount` varchar(60) NOT NULL,
  `rdate` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_service_booking`
--

INSERT INTO `ar_service_booking` (`id`, `uname`, `provider`, `sid`, `duration`, `time_type`, `req_date`, `status`, `amount`, `rdate`) VALUES
(1, 'godl', 'raja', '1', '1', '2', '2023-12-10', '2', '1000', '06-12-2023');

-- --------------------------------------------------------

--
-- Table structure for table `ar_user`
--

CREATE TABLE `ar_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `address` varchar(60) NOT NULL,
  `district` varchar(40) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(60) NOT NULL,
  `uname` varchar(15) NOT NULL,
  `pass` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_user`
--

INSERT INTO `ar_user` (`id`, `name`, `address`, `district`, `mobile`, `email`, `uname`, `pass`, `created_at`, `updated_at`) VALUES
(1, 'jebin', 'trichy', 'trichy', '8764567876', 'jebinp08@gmail.com', 'jebin', '1234', '2023-11-03 10:56:01', '2023-11-03 10:56:01'),
(2, 'selva f', 'ddd', 'trichy', '6381082863', 'jebinp08@gmail.com', 'selva', '1234', '2023-11-04 04:03:39', '2023-11-04 04:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `ar_vehicle`
--

CREATE TABLE `ar_vehicle` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(100) NOT NULL,
  `vehicle` varchar(100) NOT NULL,
  `vno` varchar(100) NOT NULL,
  `details` varchar(100) NOT NULL,
  `cost1` varchar(100) NOT NULL,
  `cost2` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `create_date` varchar(100) NOT NULL,
  `status` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ar_vehicle`
--

INSERT INTO `ar_vehicle` (`id`, `uname`, `vehicle`, `vno`, `details`, `cost1`, `cost2`, `photo`, `create_date`, `status`) VALUES
(1, 'selva', 'ferari', 'TN45 3321', 'good to use agri land', '500', '2000', '1699096704_ag1.jpg', '04-11-2023', '0'),
(2, 'selva', 'ferari', 'TN45 3321', 'good to use agri land', '500', '2000', '1699096824_ag2.jpg', '04-11-2023', '0'),
(3, 'raja', 'tata', 'TN45 3321', 'good to use agri land', '500', '2000', '1699097036_ag3.jpg', '04-11-2023', '0'),
(4, 'raja', 'mahendra', 'TN45 3321', 'good to use agri land', '500', '2000', '1699097116_ag5.jpg', '04-11-2023', '0'),
(5, 'selva', 'mahendra', 'TN45 3333', 'good to use agri land', '500', '2000', '1700634315_222.png', '22-11-2023', '1'),
(6, 'raja', 'ferari', 'TN 09 0987', 'electric vechicle', '300', '1000', '1702023411_download (1).jpg', '08-12-2023', '0');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_service_booking`
--

CREATE TABLE `farmer_service_booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uname` varchar(60) NOT NULL,
  `provider` varchar(60) NOT NULL,
  `sid` varchar(60) NOT NULL,
  `duration` varchar(60) NOT NULL,
  `time_type` varchar(60) NOT NULL,
  `req_date` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `amount` varchar(60) NOT NULL,
  `rdate` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_service_booking`
--

INSERT INTO `farmer_service_booking` (`id`, `uname`, `provider`, `sid`, `duration`, `time_type`, `req_date`, `status`, `amount`, `rdate`) VALUES
(1, 'jebin', 'raja', '1', '2', '2', '2023-12-24', '2', '2200', '07-12-2023');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2023_11_03_095123_create_ar_user_table', 1),
(5, '2023_11_04_044602_create_admins_table', 2),
(6, '2023_11_04_045701_create_ar_provider_table', 3),
(7, '2023_11_04_111025_create_ar_vehicle_table', 4),
(8, '2023_11_06_094611_create_ar_booking_table', 5),
(9, '2023_12_04_041807_create_ar_customer_table', 6),
(11, '2023_12_04_062530_create_ar_product_table', 7),
(12, '2023_12_04_095731_create_ar_product_booking_table', 8),
(13, '2023_12_05_120951_create_ar_service_table', 9),
(14, '2023_12_05_135631_create_ar_apply_service_table', 10),
(15, '2023_12_06_055926_create_ar_service_booking_table', 11),
(16, '2023_12_07_072615_create_farmer_service_booking_table', 12),
(17, '2023_12_07_132646_create_ar_live_stock_booking_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_apply_service`
--
ALTER TABLE `ar_apply_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_booking`
--
ALTER TABLE `ar_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_customer`
--
ALTER TABLE `ar_customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ar_customer_uname_unique` (`uname`);

--
-- Indexes for table `ar_live_stock_booking`
--
ALTER TABLE `ar_live_stock_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_product`
--
ALTER TABLE `ar_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_product_booking`
--
ALTER TABLE `ar_product_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_provider`
--
ALTER TABLE `ar_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_service`
--
ALTER TABLE `ar_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_service_booking`
--
ALTER TABLE `ar_service_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ar_user`
--
ALTER TABLE `ar_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ar_user_uname_unique` (`uname`);

--
-- Indexes for table `ar_vehicle`
--
ALTER TABLE `ar_vehicle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmer_service_booking`
--
ALTER TABLE `farmer_service_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ar_apply_service`
--
ALTER TABLE `ar_apply_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ar_booking`
--
ALTER TABLE `ar_booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ar_customer`
--
ALTER TABLE `ar_customer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ar_live_stock_booking`
--
ALTER TABLE `ar_live_stock_booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ar_product`
--
ALTER TABLE `ar_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ar_product_booking`
--
ALTER TABLE `ar_product_booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ar_provider`
--
ALTER TABLE `ar_provider`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ar_service`
--
ALTER TABLE `ar_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ar_service_booking`
--
ALTER TABLE `ar_service_booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ar_user`
--
ALTER TABLE `ar_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ar_vehicle`
--
ALTER TABLE `ar_vehicle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `farmer_service_booking`
--
ALTER TABLE `farmer_service_booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
