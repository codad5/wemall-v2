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
-- Database: `wemall`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `email` varchar(300) NOT NULL,
  `passwords` varchar(300) NOT NULL,
  `addedby` varchar(300) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `datejoined` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `email`, `passwords`, `addedby`, `verified`, `datejoined`) VALUES
(1, 'Admin', 'aniezeoformic@gmail.com', '$2y$10$52GLgHMpjnADmbVs8imRzOnfDsWX93lJPUyJqHkeG3jg6mV8d8.C6', '', 1, '2022-02-14 16:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `order_id` varchar(300) NOT NULL,
  `payment_id` varchar(300) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL,
  `delivered` tinyint(1) NOT NULL,
  `datejoined` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `email`, `product_name`, `quantity`, `total_price`, `order_id`, `payment_id`, `verified`, `delivered`, `datejoined`) VALUES
(1, 'aniezeoformic@gmail.com', '', 100, 33000, '?R?&?v}??ð%:?cc', 's6w1hjg6d4', 0, 0, '2022-05-09 03:09:29'),
(2, 'aniezeoformic@gmail.com', '', 100, 33000, 'G\n?|6????@?6:?', '38qfe3voql', 0, 0, '2022-05-09 03:27:06'),
(3, 'aniezeoformic@gmail.com', '', 100, 33000, '?䁿P&???u>c??', 'p6bqg183tw', 0, 0, '2022-05-09 03:45:06'),
(4, 'aniezeoformic@gmail.com', '', 100, 33000, '?t?>?+?<??+؉m}', 'y56lngs19h', 0, 0, '2022-05-09 03:46:09'),
(5, 'aniezeoformic@gmail.com', '', 100, 33000, '>??tmc\0u?#B?~?', '0g8r8brdjm', 0, 0, '2022-05-09 03:58:21'),
(6, 'aniezeoformic@gmail.com', '', 100, 33000, '8zr9M???m۷?^??', 'cpgrlkg3av', 0, 0, '2022-05-09 03:58:38'),
(7, 'aniezeoformic@gmail.com', '', 100, 33000, '?-???[?|*|<?Z?_', '9neeokjp17', 0, 0, '2022-05-09 04:00:12'),
(8, 'aniezeoformic@gmail.com', '', 100, 33000, '-?v?_??Rz???J?', 'sx701we92i', 0, 0, '2022-05-09 04:01:52'),
(9, 'aniezeoformic@gmail.com', '', 100, 33000, '?:???????;c??gn?', 'v0ck3bi9ab', 0, 0, '2022-05-09 04:04:18'),
(10, 'aniezeoformic@gmail.com', '', 100, 33000, '??D?{y?п?G?<???', 'odkyl6xjju', 0, 0, '2022-05-09 04:04:52'),
(11, 'aniezeoformic@gmail.com', '', 100, 33000, '???4??n?n?????^s', 'l14avw8z7b', 0, 0, '2022-05-09 04:05:20'),
(12, 'aniezeoformic@gmail.com', '', 100, 33000, 'jI\0?U\\??F\ZZ?', 'jifut2xuvi', 0, 0, '2022-05-09 04:06:16'),
(13, 'aniezeoformic@gmail.com', '', 100, 55000, 'f??G??^??& *o??', 'j2uqvblk3d', 0, 0, '2022-05-09 04:12:52'),
(14, 'aniezeoformic@gmail.com', '', 100, 55000, '??+?b?s?gr??5\Zl', 'iyoiq1v4ca', 0, 0, '2022-05-09 04:14:07'),
(15, 'aniezeoformic@gmail.com', '', 100, 55000, '?U?G??V8?H?;qg?', 'bfuobsq3to', 0, 0, '2022-05-09 04:14:51'),
(16, 'aniezeoformic@gmail.com', '', 100, 55000, 'AM?	~<A?\0?3O????', 'kbueifsrpf', 0, 0, '2022-05-09 04:17:37'),
(17, 'aniezeoformic@gmail.com', '', 100, 55000, '????S?x޲????\Z', 'u5itgnqbtj', 0, 0, '2022-05-09 04:17:54'),
(18, 'aniezeoformic@gmail.com', '', 100, 55000, '??z?;|_?%g?u??=?', 'ywyvl4bxqk', 0, 0, '2022-05-09 04:18:38'),
(19, 'aniezeoformic@gmail.com', '', 100, 55000, '?	?C?!V??h?-', '5ae2u3c2bv', 0, 0, '2022-05-09 04:18:51'),
(20, 'aniezeoformic@gmail.com', '', 100, 55000, 'pU?Ԙ?|?\nQgH???', 'p14yrmn22t', 0, 0, '2022-05-09 04:19:12'),
(21, 'aniezeoformic@gmail.com', '', 100, 55000, ' ?[M׭*ZgA?m?#>', 'o3p48qnnoz', 0, 0, '2022-05-09 04:22:28'),
(22, 'aniezeoformic@gmail.com', '', 100, 44000, '?8?	b$	L???3znH?', 'ff0zxizwoz', 0, 0, '2022-05-09 04:27:13'),
(23, 'aniezeoformic@gmail.com', '', 100, 650, '#P\"???e?04p??R', 'ygar6v390j', 0, 0, '2022-05-09 04:49:24'),
(24, 'aniezeoformic@gmail.com', '', 100, 650, '??????W???X??', 'lpcliuzl0m', 0, 0, '2022-05-09 04:50:04'),
(25, 'aniezeoformic@gmail.com', '', 100, 650, 'w?F4?????	??i	%)', '9e21apy7dr', 0, 0, '2022-05-09 04:50:13'),
(26, 'aniezeoformic@gmail.com', '', 100, 3090, ',-`?f\\?@???G(L', 'fcu5faxirk', 0, 0, '2022-05-18 01:14:18'),
(27, 'aniezeoformic@gmail.com', '', 100, 14000, 'O7??\\?C??Xa???', '3i58m87lfa', 0, 0, '2022-05-18 23:30:38'),
(28, 'aniezeoformic@gmail.com', '', 100, 14000, '\'t????T-??\\Z?E', 'uggisbjf0m', 0, 0, '2022-05-18 23:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `ordersitems`
--

CREATE TABLE `ordersitems` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_id` varchar(300) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sales_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `order_id` varchar(300) NOT NULL,
  `datejoined` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ordersitems`
--

INSERT INTO `ordersitems` (`id`, `email`, `product_name`, `product_id`, `quantity`, `sales_price`, `total_price`, `order_id`, `datejoined`) VALUES
(1, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '?R?&?v}??ð%:?cc', '2022-05-09 03:09:26'),
(2, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, 'G\n?|6????@?6:?', '2022-05-09 03:27:06'),
(3, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '?䁿P&???u>c??', '2022-05-09 03:45:01'),
(4, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '?t?>?+?<??+؉m}', '2022-05-09 03:46:09'),
(5, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '>??tmc\0u?#B?~?', '2022-05-09 03:58:20'),
(6, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '8zr9M???m۷?^??', '2022-05-09 03:58:38'),
(7, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '?-???[?|*|<?Z?_', '2022-05-09 04:00:11'),
(8, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '-?v?_??Rz???J?', '2022-05-09 04:01:52'),
(9, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '?:???????;c??gn?', '2022-05-09 04:04:18'),
(10, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '??D?{y?п?G?<???', '2022-05-09 04:04:52'),
(11, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, '???4??n?n?????^s', '2022-05-09 04:05:20'),
(12, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 3, 11000, 33000, 'jI\0?U\\??F\ZZ?', '2022-05-09 04:06:15'),
(13, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, 'f??G??^??& *o??', '2022-05-09 04:12:52'),
(14, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, '??+?b?s?gr??5\Zl', '2022-05-09 04:14:07'),
(15, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, '?U?G??V8?H?;qg?', '2022-05-09 04:14:51'),
(16, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, 'AM?	~<A?\0?3O????', '2022-05-09 04:17:36'),
(17, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, '????S?x޲????\Z', '2022-05-09 04:17:54'),
(18, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, '??z?;|_?%g?u??=?', '2022-05-09 04:18:35'),
(19, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, '?	?C?!V??h?-', '2022-05-09 04:18:51'),
(20, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, 'pU?Ԙ?|?\nQgH???', '2022-05-09 04:19:12'),
(21, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 5, 11000, 55000, ' ?[M׭*ZgA?m?#>', '2022-05-09 04:22:28'),
(22, 'aniezeoformic@gmail.com', 'Tus', '62743bf145c490.38476519', 4, 11000, 44000, '?8?	b$	L???3znH?', '2022-05-09 04:27:13'),
(23, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 1, 650, 650, '#P\"???e?04p??R', '2022-05-09 04:49:24'),
(24, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 1, 650, 650, '??????W???X??', '2022-05-09 04:50:04'),
(25, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 1, 650, 650, 'w?F4?????	??i	%)', '2022-05-09 04:50:13'),
(26, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 3, 650, 1950, ',-`?f\\?@???G(L', '2022-05-18 01:12:31'),
(27, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 3, 650, 1950, '???#?l׼:*FZ?{?', '2022-05-18 01:10:11'),
(28, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 3, 650, 1950, '-?(???x??,V?ط', '2022-05-18 01:10:20'),
(29, 'aniezeoformic@gmail.com', 'New Vintage up', '624dca46d4cea7.38412932', 1, 1140, 1140, ',-`?f\\?@???G(L', '2022-05-18 01:14:17'),
(30, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 4, 650, 2600, '\'t????T-??\\Z?E', '2022-05-18 23:30:29'),
(31, 'aniezeoformic@gmail.com', 'Sweet Sweater', '624dcab521a429.71215399', 4, 650, 2600, 'O7??\\?C??Xa???', '2022-05-18 23:30:35'),
(32, 'aniezeoformic@gmail.com', 'New Vintage up', '624dca46d4cea7.38412932', 10, 1140, 11400, 'O7??\\?C??Xa???', '2022-05-18 23:30:37'),
(33, 'aniezeoformic@gmail.com', 'New Vintage up', '624dca46d4cea7.38412932', 10, 1140, 11400, '\'t????T-??\\Z?E', '2022-05-18 23:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `payment_id` varchar(300) NOT NULL,
  `payment_method` varchar(300) NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `datejoined` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id_private` int(11) NOT NULL,
  `id` varchar(300) NOT NULL DEFAULT 'product_id',
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_size` varchar(300) NOT NULL,
  `product_gender` varchar(300) NOT NULL,
  `product_category` varchar(300) NOT NULL,
  `product_price` int(11) NOT NULL,
  `discount_method` varchar(300) NOT NULL,
  `product_discount` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `total_delivery` int(11) NOT NULL,
  `product_image1` varchar(300) NOT NULL,
  `product_image2` varchar(300) NOT NULL,
  `product_image3` varchar(300) NOT NULL,
  `product_image4` varchar(300) NOT NULL,
  `product_image5` varchar(300) NOT NULL,
  `product_perm_link` varchar(300) NOT NULL,
  `addedby` varchar(300) NOT NULL,
  `active_status` varchar(300) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id_private`, `id`, `product_id`, `product_name`, `product_size`, `product_gender`, `product_category`, `product_price`, `discount_method`, `product_discount`, `product_quantity`, `total_delivery`, `product_image1`, `product_image2`, `product_image3`, `product_image4`, `product_image5`, `product_perm_link`, `addedby`, `active_status`, `dateadded`) VALUES
(7, 'product_id', '624dca46d4cea7.38412932', 'New Vintage up', '12b', 'male', 'Vintage , expensive, cool, luxury', 1200, 'price_cut', 5, 10, 0, '624dca46d4dee9.85404398.png', '624dca46e042a1.84592306.png', '624dca46e0f0f1.40741671.png', '624dca46e182c1.08748144.png', '624dca46e182c1.08748144.png', '624dca46d4cea7.38412932/New-Vintage-', 'aniezeoformic@gmail.com', '', '2022-04-06 18:13:42'),
(8, 'product_id', '624dcab521a429.71215399', 'Sweet Sweater', '10', 'male', 'cold, cotton, cheap. sweater', 700, 'price_cut', 50, 20, 0, '624dcab521ae05.06678149.png', '624dcab5225dd2.01798704.png', '624dcab5230fe3.47378873.png', '624dcab526cd51.87282516.png', '624dcab526cd51.87282516.png', '624dcab521a429.71215399/Sweet-Sweater-', 'aniezeoformic@gmail.com', '', '2022-04-06 18:15:33'),
(9, 'product_id', '62743bf145c490.38476519', 'Tus', '12', 'female', 'cheap, classic, awesome, night wear', 12000, 'price_cut', 1000, 12, 0, '62743bf145d0a1.90867317.jpg', '62743bf146e858.49072236.png', '62743bf147e8c3.78516228.png', '62743bf148ec12.15553987.png', '62743bf148ec12.15553987.png', '62743bf145c490.38476519/Tus-', 'aniezeoformic@gmail.com', '', '2022-05-05 22:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_changes`
--

CREATE TABLE `product_changes` (
  `product_id_private` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_category` varchar(300) NOT NULL,
  `product_price` int(11) NOT NULL,
  `discount_method` varchar(300) NOT NULL,
  `product_discount` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `total_delivery` int(11) NOT NULL,
  `changedBy` varchar(300) NOT NULL,
  `changed_made` varchar(300) NOT NULL,
  `dateChanged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(300) NOT NULL,
  `passwords` varchar(300) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `datejoined` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `passwords`, `verified`, `datejoined`) VALUES
(2, 'Aniezeofor Chibueze', 'aniezeoformic@gmail.com', '08142572488', '$2y$10$RjOgCNM8w5TsxcGgOexm6u.VNVqQomYWpxcd/6UX3iiWmi2e9Z.SG', 0, '2022-04-17 20:35:16'),
(3, 'Chibueze Aniezeofor', 'anicezeoformic@gmail.com', '081425724889', '$2y$10$XFeCUlVYWkEHnx0vvKs8de6eQt5eoEye.CVubL4lydQz6MH7N83fC', 0, '2022-04-17 21:49:43'),
(4, 'Chibuesze', 'ridoxchannel@gmail.com', '07016132393', '$2y$10$V59lcXf9f6goMubpYf6vf.9DAnyL/OowotR1H/4vzmlNr77JLORyu', 0, '2022-04-17 23:01:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordersitems`
--
ALTER TABLE `ordersitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id_private`);

--
-- Indexes for table `product_changes`
--
ALTER TABLE `product_changes`
  ADD PRIMARY KEY (`product_id_private`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `ordersitems`
--
ALTER TABLE `ordersitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id_private` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_changes`
--
ALTER TABLE `product_changes`
  MODIFY `product_id_private` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
