-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2022 at 04:16 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gooby_keyboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing_info`
--

CREATE TABLE `billing_info` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `mobile_number` varchar(26) NOT NULL,
  `country` varchar(256) NOT NULL,
  `region` varchar(256) NOT NULL,
  `postcode_zip` varchar(256) NOT NULL,
  `province` varchar(256) NOT NULL,
  `town_city` varchar(256) NOT NULL,
  `brgy` varchar(256) NOT NULL,
  `street_address` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing_info`
--

INSERT INTO `billing_info` (`customer_id`, `first_name`, `last_name`, `mobile_number`, `country`, `region`, `postcode_zip`, `province`, `town_city`, `brgy`, `street_address`) VALUES
(1, 'Eric Simon', 'Larios', '546546', 'Philippines', 'REGION V (BICOL REGION)', '1234', 'CAMARINES SUR', 'BALATAN', 'San Jose', 'Blk 12'),
(2, 'Joshua', 'Austria', '12343214123', 'Philippines', '', '1234', '', '', 'Edi ', 'sa Puso mo'),
(13, 'Eric ', 'Larios', '546546', 'Philippines', 'REGION IV-A (CALABARZON)', '45646', 'RIZAL', 'RODRIGUEZ (MONTALBAN)', 'San Jose', 'Blk 12'),
(14, 'Eric Simon', 'Larios', '09995668770', 'Philippines', 'REGION IV-A (CALABARZON)', '1234', 'RIZAL', 'RODRIGUEZ (MONTALBAN)', 'San Jose', 'Blk 12 Lot 106 PH 1-A'),
(18, 'Eric Simon', 'Larios', '09995668770', 'Philippines', 'REGION IV-A (CALABARZON)', '1234', 'RIZAL', 'RODRIGUEZ (MONTALBAN)', 'San Jose', 'Blk 12 Lot 106');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `username`, `pass`, `email`) VALUES
(1, 'ericsimon1234', '$2y$10$fXX0pm40gwPv7ls8j7iQb.vlxUOQa/X6vo2pIusZdmLmobV7LbYGu', 'asdjkalskdj@gmail.com'),
(2, 'joshua123', '$2y$10$U1r5Qjyn9loTrva5wWRJu.ua.PZC3ZZ8qwPJs0V8SAzvNBIflzEva', 'asdasfa@gmail.com'),
(3, 'asdasjdlk', '$2y$10$RJy1KEW3.NgHyEN3I96xNOdxM6MmzCQhrE7po7t4uIXrx1cRlwJV2', 'asdjlsak@gmail.com'),
(13, 'simon32', '$2y$10$Bv4agGEsO.gKo6.uAIxPZ.6nPxp1VCoU5982MKWGGIxoP1qKKxUOW', 'simon32@gmail.com'),
(14, 'eric123', '$2y$10$Zz0sAiFLOy24ISilZCLljeoFMkfYr01Ka0XEOE6DbqDgX7CpDK.jC', 'eric123@gmail.com'),
(15, 'admin', '$2y$10$bUnRWqtZ/DwJxyak9uc.wOjWtY3v4tX7gTv82hgapAprMovwyDVRq', 'admin@gmail.com'),
(16, 'simon1234', '$2y$10$M4DrXTaD/D2MyLrWWwkXNuEyambeYngaFX9wnjknhlqL8MdufJg5G', 'simon@gmail.com'),
(17, 'ericsimon321', '$2y$10$MFluib0GL92dck.SscC6ju.34nGmG.RFIDr2v5o/YhuXcUh1zLe.K', 'ASKJFLKASFLK@gmail.com'),
(18, 'ericsimon3214', '$2y$10$xAbdl0RLRMaNZN3xHLF5jOrjLc00uDbvjsjq.O4Wa92v4x6y2.M2K', 'asfaslkj@gmail.com'),
(19, 'simon12345', '$2y$10$tXAqoBNeTHLGrzZjH1srl.R0/3WksLOVovJnXSY5QzNPIF/lwPQmK', 'dgjdsgkjhK@gmail.com'),
(20, 'simon13', '$2y$10$JuK/5D51uq3CYobYhpjhg.9.hhw17grKNAnoXEI4ljSTcGOyzbGKK', 'askfjasf@gmail.com'),
(21, 'eric321', '$2y$10$4njSfW/TwhwEaeqZLDb.uOIhfyKIB2kjcSVd2dp1K4yOS4p1VmvKm', 'asdjf@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(256) NOT NULL,
  `product_img` varchar(256) NOT NULL,
  `category` varchar(256) NOT NULL,
  `price` varchar(256) NOT NULL,
  `stock` varchar(256) NOT NULL,
  `product_desc` longtext NOT NULL,
  `date_added` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_img`, `category`, `price`, `stock`, `product_desc`, `date_added`) VALUES
(25, 'Skyloong Mechanical Keyboard', '../PRODUCT-IMG/Skyloong Mechanical Keyboard.png', 'Keyboard', '2500', '2', '<p><strong>Holy Sheet</strong></p>', '2022/03/04'),
(26, 'Switches', '../PRODUCT-IMG/Switches.jpg', 'Switches', '2500', '5', '<p>I will have order</p>', '2022/03/04'),
(27, 'Classic A4Tech', '../PRODUCT-IMG/Classic A4Tech.png', 'Keyboard', '2500', '5', '<p><strong>A4TECH NUMBAWAN</strong></p>', '2022/03/04'),
(29, 'Accessories', '../PRODUCT-IMG/Accessories.jpg', 'Accessory', '2500', '5', '<p>ACcessory</p>', '2022/03/06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing_info`
--
ALTER TABLE `billing_info`
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
