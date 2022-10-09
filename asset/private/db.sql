-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2022 at 08:29 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wemall2`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `unique_id` varchar(300) NOT NULL, 
  `api_key` varchar(300) NOT NULL, 
  `main_role` varchar(300) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` TEXT NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `email` varchar(300) NOT NULL,
  `unique_id` varchar(300) NOT NULL, 
  `api_key` varchar(300) NOT NULL,
  `shop_type` varchar(300) NOT NULL, 
  `admins` TEXT NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `products` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `product_id` varchar(300) NOT NULL, 
  `product_type` varchar(300) NOT NULL,
  `shop_id` varchar(300) NOT NULL, 
  `active_status` tinyInt NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- table for clothing products to inner join with products table
CREATE TABLE `clothing_products` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- table for food products to inner join with products table
CREATE TABLE `food_products` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

-- CREATE TABLE `orders` (
--   `id` int(11) NOT NULL,
--   `email` varchar(300) NOT NULL,
--   `product_name` varchar(255) NOT NULL,
--   `quantity` int(11) NOT NULL,
--   `total_price` int(11) NOT NULL,
--   `order_id` varchar(300) NOT NULL,
--   `payment_id` varchar(300) DEFAULT NULL,
--   `verified` tinyint(1) NOT NULL,
--   `delivered` tinyint(1) NOT NULL,
--   `datejoined` datetime DEFAULT current_timestamp()
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Indexes for dumped tables
--



--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--


--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
