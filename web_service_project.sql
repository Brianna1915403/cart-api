-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2021 at 05:33 AM
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
-- Database: `web_service_project`
--
CREATE DATABASE IF NOT EXISTS `web_service_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `web_service_project`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_amount` int(11) NOT NULL,
  `status` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `price` decimal(65,2) DEFAULT NULL,
  `picture` varchar(250) DEFAULT NULL,
  `tag` varchar(250) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `user_id`, `item_name`, `description`, `price`, `picture`, `tag`, `stock`) VALUES
(1, 1, 'Sport Socks - 6 pack', 'Sporty socks are a great gift for any friend or family member wanting to get healthy for the holidays. After all how can one justify wearing such sporty socks, while neglecting their new years resolution!?', '3.99', '8d5a1_sportsocks6pack.png', NULL, 0),
(2, 1, 'Cat Sock - Pink', 'The purrfect set for you feline-inclined people. ', '24.99', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `license_key` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `license_key`) VALUES
(1, 'email@domain.ext', '$2y$10$FmF8eWBhixU.jtJqbyuiHuPYt.WxvtEbMWbqm9B3NCKG.drVVw5kO', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJrZXkiOiJkNjNiMC5OemsyWWpsbFptVXhZbVl5T0RkaU9UZzNPVFprTlRReU56WmhZemMzWldJIn0.Fu7wZjPoIQ-vj6FF1zqYlFSfmuI2pnYyvimAM38qGzA'),
(2, 'email_1@domain.ext', '$2y$10$EjIqiEpfkeUygYyT9g7TMudRjgm4/nDueqj9APIyOtNWirpvCI6Fu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJrZXkiOiJkNjJkNC5ZVE14WTJJNVl6Rm1aR1EyTldWa1pETTJNakJoTWpGaU1XRTNNREV5Wm1FIn0.zOQsOO8S6PJVH2RMUyuKwgcK-wYs98eELNUjylz75JA'),
(3, 'email_2@domain.ext', '$2y$10$VejA.B8MpEKmA/WyTBo7gOyytF5LZ/wJPr67PEXXzfThepnGujpFS', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJrZXkiOiJlZTIxNi5aRFU1TURBeU1EWXlNbVkzTnpjek1tSmpPVFV6WVdOak1XTm1PVFppWm1JIn0.GVFAfiik8vRGf827UvwjYtL2AOhDj5uXtZ5mUEGzWRE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `User_to_cart` (`user_id`),
  ADD KEY `Item_to_cart` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `picture` (`picture`),
  ADD KEY `User_to_Item` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `license_key` (`license_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `Item_to_cart` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User_to_cart` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `User_to_Item` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
