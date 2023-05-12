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
-- Database: `wemall3`
--


CREATE TABLE `users` (
 `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
 `user_id` varchar(11) UNIQUE NOT NULL,
 `name` varchar(255) NOT NULL,
 `username` varchar(50) UNIQUE NOT NULL,
 `email` varchar(300) UNIQUE NOT NULL,
 `password` varchar(300) NOT NULL,
 `api_key` varchar(300) UNIQUE NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE `shops` (
 `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
 `shop_id` varchar(11) UNIQUE NOT NULL ,
 `name` varchar(50) UNIQUE NOT NULL,
 `email` varchar(200) UNIQUE NOT NULL,
 `description` TEXT ,
 `type` ENUM('clothing')  NOT NULL,
 `creator_id` varchar(11) NOT NULL ,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 FOREIGN KEY (creator_id) REFERENCES users(user_id)
);

CREATE TABLE `products` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `product_id` varchar(11) UNIQUE NOT NULL ,
    `shop_id` varchar(11)  NOT NULL ,
    `name` varchar(100) UNIQUE NOT NULL,
    `price` varchar(200)  NOT NULL,
    `description` TEXT ,
    `type` ENUM('clothing')  NOT NULL,
    `discount` INT(10) NOT NULL ,
    `discount_type` ENUM('percentage', 'cut') NOT NULL ,
    `quantity` INT DEFAULT 0,
    `sold` INT DEFAULT 0,
    `status` ENUM('deleted', 'sold_out', 'active') NOT NULL DEFAULT 'active',
    `creator_id` varchar(11) NOT NULL ,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    FOREIGN KEY (creator_id) REFERENCES users(user_id),
    FOREIGN KEY  (shop_id) REFERENCES  shops(shop_id)
);

CREATE TABLE `product_images` (
  `id` int(55) PRIMARY KEY AUTO_INCREMENT NOT NULL ,
  `product_id` varchar(11) NOT NULL ,
  `shop_id` varchar(11) NOT NULL ,
  `image_path`varchar(265) UNIQUE NOT NULL ,
  FOREIGN KEY (product_id) REFERENCES products(product_id),
  FOREIGN KEY  (shop_id) REFERENCES  shops(shop_id)
);

CREATE TABLE `clothing_products` (
     `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL ,
     `product_id` varchar(11) NOT NULL ,
     'size' varchar(11) NOT NULL ,
     `color` varchar(8) NOT NULL ,
     `gender` ENUM('male', 'female', 'unisex') NOT NULL,
     FOREIGN KEY (product_id) REFERENCES products(product_id)
);


CREATE TABLE `shop_admin` (
    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `user_id` varchar(11) NOT NULL,
    `shop_id` varchar(11) NOT NULL ,
    `level` ENUM('1', '2') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id)
);
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
