-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2025 at 03:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `handmade`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_email`, `admin_password`) VALUES
(1, 'admin@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `item_id`, `buyer_id`) VALUES
(44, 513, 41);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_description` text DEFAULT NULL,
  `item_quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_image_path` varchar(255) DEFAULT NULL,
  `final_rating` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_price`, `item_description`, `item_quantity`, `user_id`, `item_image_path`, `final_rating`) VALUES
(140, 'ستارة', 750.00, 'ستارة من خيوط المكرمية', 12, 5, 'uploads/5/675de0ccbe9c2.jpg', 1),
(513, 'galal', 123.00, '123', 122, 5, 'uploads/5/6779452a01704.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `buyer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `order_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `order_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`buyer_id`, `order_id`, `item_id`, `order_name`, `quantity`, `price`, `order_date`) VALUES
(1006, 25, 140, 'ستارة', 2, 1500, '22 Dec 2024'),
(41, 29, 140, 'ستارة', 1, 750, '24 Dec 2024'),
(41, 31, 513, 'galal', 1, 123, '04 Jan 2025');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rate_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `buyer_rate` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `rate_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rate_id`, `buyer_id`, `item_id`, `buyer_rate`, `comment`, `rate_date`) VALUES
(12, 41, 140, 1, '1', 'Tue Dec 2024');

--
-- Triggers `ratings`
--
DELIMITER $$
CREATE TRIGGER `update_final_rating` AFTER INSERT ON `ratings` FOR EACH ROW BEGIN
    DECLARE avg_rating FLOAT;

    -- حساب متوسط التقييمات للمنتج بناءً على item_id
    SELECT AVG(buyer_rate) INTO avg_rating
    FROM ratings
    WHERE item_id = NEW.item_id;

    -- تحديث التقييم النهائي في جدول items
    UPDATE items
    SET final_rating = avg_rating
    WHERE item_id = NEW.item_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_final_rating_on_update` AFTER UPDATE ON `ratings` FOR EACH ROW BEGIN
    DECLARE avg_rating FLOAT;

    -- حساب متوسط التقييمات للمنتج بناءً على item_id
    SELECT AVG(buyer_rate) INTO avg_rating
    FROM ratings
    WHERE item_id = NEW.item_id;

    -- تحديث التقييم النهائي في جدول items
    UPDATE items
    SET final_rating = avg_rating
    WHERE item_id = NEW.item_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stas`
--

CREATE TABLE `stas` (
  `stas_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_sold` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stas`
--

INSERT INTO `stas` (`stas_id`, `user_id`, `total_sold`, `item_id`) VALUES
(15, 5, 3, 140),
(17, 5, 1, 513);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(32) NOT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `user_role` varchar(6) DEFAULT NULL,
  `user_wallet` int(11) NOT NULL,
  `user_ItemsNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_mobile`, `user_password`, `user_role`, `user_wallet`, `user_ItemsNumber`) VALUES
(1, 'tester', 'tester@gmail', '123', '123', 'buyer', 17700, 0),
(5, 'galal ahmed sayed', 'galalahmednasser0@gmail.com', '210390317928', '123', 'seller', 15423, 0),
(41, 'malek', 'malek@gmail.com', '123456789', '123', 'buyer', 24477, 0),
(42, 'mohamed', 'mohamed2@yahoo.com', '201090317928', '123', 'buyer', 2321, 0),
(1005, 'testseller', 'testseller@gmail.com', '123', '123', 'seller', 0, 0),
(1006, 'test buyer', 'buyerr@gmail.com', '10287305700', '123', 'buyer', 400, 0),
(1007, 'kk', 'k@gmail.com', '1028730570', '123', 'buyer', 0, 0),
(1021, 'test', 'sherif22@gmail.com', '0000000', '0000', 'seller', 0, 0),
(1022, 'Mobile', 'mobile@gmail.com', '01028730570', '123', 'seller', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cart_ibfk_1` (`item_id`),
  ADD KEY `cart_ibfk_2` (`buyer_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `items_ibfk_1` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`item_id`),
  ADD KEY `orders_ibfk_2` (`buyer_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `ratings_ibfk_1` (`buyer_id`),
  ADD KEY `ratings_ibfk_2` (`item_id`);

--
-- Indexes for table `stas`
--
ALTER TABLE `stas`
  ADD PRIMARY KEY (`stas_id`),
  ADD KEY `stas_ibfk_3` (`item_id`),
  ADD KEY `stas_ibfk_2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stas`
--
ALTER TABLE `stas`
  MODIFY `stas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1710;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stas`
--
ALTER TABLE `stas`
  ADD CONSTRAINT `stas_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stas_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
