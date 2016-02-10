-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2016 at 01:20 PM
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

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `user_id`, `name`, `time`, `active`) VALUES
(1, 1, 'footwear', '1454176424', 'y'),
(2, 2, 'hello', '1455129572', 'y');

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

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `image_thumb`, `code`, `CP`, `SP`, `user_id`, `category_id`, `time`, `keywords`, `size_keywords`, `active`, `last_edited`) VALUES
(1, 'test', '', '', 'test', 100, 200, 1, 1, '1454176504', 'test test', '1 3', 'y', ''),
(2, 'test2', '', '', 'test2', 11, 11, 1, 1, '1454177882', 'test2 test2', '2', 'y', '1454697497'),
(3, 'hello', '', '', 'helloworld', 200, 500, 1, 1, '1454490335', 'hello helloworld', '1 3', 'y', '1454490364');

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
  `sales_type` text NOT NULL,
  `time` text NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `customer_name`, `salesman_id`, `salesman_name`, `date`, `date_id`, `sales_type`, `time`, `active`) VALUES
(1, 1, 'tasneem', 1, 'Owner', '06/02/2016', '06022016', '', '1454754098', 'y'),
(2, 1, 'huzefa', 1, 'Owner', '08/02/2016', '08022016', '', '1454918881', 'y'),
(3, 1, 'test2', 1, 'Owner', '08/02/2016', '08022016', '', '1454918913', 'y'),
(4, 1, '', 1, 'Owner', '10/02/2016', '10022016', 'listed', '1455187116', 'y'),
(5, 1, 'Husain', 1, 'Owner', '10/02/2016', '10022016', '', '1455104731', 'y'),
(6, 1, 'husain sali', 1, 'Owner', '10/02/2016', '10022016', '', '1455104761', 'y');

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
(1, 'Owner', 1, '1454053970', 'y'),
(2, 'Owner', 2, '1455129554', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `sales_product_info`
--

CREATE TABLE `sales_product_info` (
  `id` int(11) NOT NULL,
  `product_id` text NOT NULL,
  `product_code` text NOT NULL,
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

INSERT INTO `sales_product_info` (`id`, `product_id`, `product_code`, `sales_id`, `user_id`, `name`, `size`, `quantity`, `costprice`, `sellingprice`, `active`) VALUES
(1, '', '', 1, 1, 'bitch', 1, 2, 100, 200, 'y'),
(2, '', '', 1, 1, 'dogs', 3, 5, 70, 222, 'y'),
(3, '', '', 2, 1, 'was he', 1, 1, 200, 300, 'y'),
(4, '', '', 3, 1, 'ddddd', 2, 2, 10, 50, 'y'),
(5, '2', 'test2', 4, 1, 'test2', 2, 1, 11, 20, 'y'),
(6, '', '', 5, 1, 'test', 1, 1, 20, 40, 'y'),
(7, '', '', 6, 1, 'huuuu', 2, 2, 1, 2, 'y'),
(8, '', '', 6, 1, '2hhh', 1, 2, 111, 222, 'y');

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

--
-- Dumping data for table `sq`
--

INSERT INTO `sq` (`id`, `size`, `quantity`, `user_id`, `product_id`, `active`) VALUES
(1, 1, 5, 1, 1, 'y'),
(3, 2, 49, 1, 2, 'y'),
(4, 3, 4, 1, 1, 'y'),
(6, 1, 9, 1, 3, 'y'),
(7, 2, 0, 1, 3, 'y'),
(8, 3, 4, 1, 3, 'y');

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
(1, 'husain', 'saify kids shoes', 'hsnsaify22@gmail.com', '8962239913', '$2y$10$ZVZRnoCOoOBDlh0SP0aVTe8YCg6ZChRLgBJJFYflPNWnAIu3bdLvW', '1454053970', 'y'),
(2, 'huzefa', 'ladies footweat', 'huzefa@gmail.com', '8962239913', '$2y$10$NYIxfzBCsE6mApou1JIkfuDzg3kli397upU5uZWfcZlxctyFkPiXW', '1455129554', 'y');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sales_product_info`
--
ALTER TABLE `sales_product_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sq`
--
ALTER TABLE `sq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
