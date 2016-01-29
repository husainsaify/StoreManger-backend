-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2016 at 09:46 AM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `storemanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `time` text NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `image` text NOT NULL,
  `image_thumb` text NOT NULL,
  `code` text NOT NULL,
  `CP` int(11) NOT NULL,
  `SP` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `time` text NOT NULL,
  `keywords` text NOT NULL,
  `size_keywords` text NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y',
  `last_edited` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` text NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `salesman_name` text NOT NULL,
  `date` text NOT NULL,
  `date_id` text NOT NULL,
  `time` text NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `customer_name`, `salesman_id`, `salesman_name`, `date`, `date_id`, `time`, `active`) VALUES
(1, 1, 'ali husain', 1, 'Owner', '29:01:2016', '29012016', '1454056971', 'y'),
(2, 1, 'ali husain', 1, 'Owner', '29:01:2016', '29012016', '1454057067', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE `salesman` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` text NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`id`, `name`, `user_id`, `time`, `active`) VALUES
(1, 'Owner', 1, '1454053970', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `sales_product_info`
--

CREATE TABLE `sales_product_info` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `costprice` int(11) NOT NULL,
  `sellingprice` int(11) NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_product_info`
--

INSERT INTO `sales_product_info` (`id`, `sales_id`, `user_id`, `name`, `size`, `quantity`, `costprice`, `sellingprice`, `active`) VALUES
(1, 1, 1, 'a', 1, 0, 1, 1, 'y'),
(2, 2, 1, 'a', 1, 1, 1, 1, 'y'),
(3, 2, 1, 'b', 2, 2, 2, 2, 'y'),
(4, 2, 1, 'c', 3, 3, 3, 3, 'y');

-- --------------------------------------------------------

--
-- Table structure for table `sq`
--

CREATE TABLE `sq` (
  `id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `storename` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `password` text NOT NULL,
  `register_at` text NOT NULL,
  `active` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `storename`, `email`, `phone`, `password`, `register_at`, `active`) VALUES
(1, 'husain', 'saify kids shoes', 'hsnsaify22@gmail.com', '8962239913', '$2y$10$ZVZRnoCOoOBDlh0SP0aVTe8YCg6ZChRLgBJJFYflPNWnAIu3bdLvW', '1454053970', 'y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_product_info`
--
ALTER TABLE `sales_product_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sq`
--
ALTER TABLE `sq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sales_product_info`
--
ALTER TABLE `sales_product_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sq`
--
ALTER TABLE `sq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
