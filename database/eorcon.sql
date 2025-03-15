-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 02:31 AM
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
-- Database: `eorcon`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `address`) VALUES
(11, '13', 'JP Rizal San Luis'),
(12, '24', 'Bulacan'),
(13, '26', 'kangkungan ni tan'),
(14, '30', 'Bulacan'),
(15, '33', 'Bulacan'),
(16, '33', '2703 admin site Tala Caloocan city'),
(17, '36', 'North Caloocan, Talipapa - test'),
(18, '36', 'North Caloocan, Talipapa - test'),
(19, '37', 'North Caloocan, Bagumbong - 123, Postal Code: 12333'),
(20, '37', 'North Caloocan, Bagumbong - 123 - 12333'),
(21, '37', 'South Caloocan, Balingasa - 321321 - 321321'),
(22, '37', 'South Caloocan, Balingasa - 321321 ZIP 321321'),
(23, '37', 'South Caloocan, Balingasa - 321321 ZIP 321321'),
(24, '37', 'South Caloocan, Bagong Silang - 321111 ZIP 1111'),
(25, '37', 'South Caloocan, Bagong Silang - 321111 ZIP 1111'),
(26, '37', 'North Caloocan, Bagumbong - 33 ZIP 3'),
(27, '37', 'North Caloocan, Bagumbong - 33 ZIP 3'),
(28, '37', 'South Caloocan, Balingasa - 32 ZIP 32'),
(29, '37', 'South Caloocan, Balingasa - 32 ZIP 32'),
(30, '37', 'North Caloocan, Camarin - 3213 ZIP 321'),
(31, '37', 'North Caloocan, Camarin - oo ZIP 00');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `gcash_qr` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `gcash_number` varchar(255) NOT NULL,
  `messages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `gcash_qr`, `account_name`, `gcash_number`, `messages`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'store_account1.jpeg', 'DRM Online Ordering System', '09854273697', ''),
(4, 'staff002', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', ''),
(5, 'staff003', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', ''),
(6, 'Delivery', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `announcements_tb`
--

CREATE TABLE `announcements_tb` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements_tb`
--

INSERT INTO `announcements_tb` (`id`, `title`, `content`, `created_at`) VALUES
(3, 'Announcement 1: 📢 We are now open!', 'Upgrade your projects with DRM Hardware Ordering System! Explore our extensive range of durable, reliable, and efficient hardware solutions, tailored for all your construction and DIY needs. Visit us today and experience exceptional service with quality products in every order.\r\n\r\n⏰ Store Hours:\r\nMonday to Saturday: 8 AM - 6 PM\r\nSunday: 9 AM - 3 PM\r\n\r\n📞 Need assistance? Contact us at (123) 456-7890, or drop by to find the perfect tools and materials for your project!', '2024-10-22 06:50:28'),
(4, 'Announcement 2: 🌟 PROMO ALERT! 🌟', 'Get a Free Tool Kit! Purchase any 5 hardware items from our store and receive a free, high-quality tool kit as a bonus! Don’t miss this limited-time offer to upgrade your project essentials without breaking the bank. Equip yourself with durable, reliable, and efficient tools today!\r\n\r\n📍 Location: [Your Address]\r\n📞 Contact Us: (123) 456-7890', '2024-10-22 06:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`, `status`, `note`) VALUES
(134, 19, 13, 'Light Bulb (Small)', 80, 1, 'l1.png', '0', ''),
(136, 19, 15, 'Gloves', 25, 1, 'g1.jpg', '0', ''),
(142, 25, 18, 'Socket Outlets', 50, 1, '462557250_1015171337046519_7536259874046953178_n (1).jpg', '0', ''),
(143, 25, 15, 'Gloves', 25, 1, 'g1.jpg', '0', ''),
(144, 25, 17, 'Multipurpose Silicone Sealant ', 200, 2, '462550458_1083331472996632_8844245743170148273_n.jpg', '0', ''),
(149, 27, 58, 'Pvc Blue Pipe ', 50, 1, '461598067_888653386055124_6622642738843823467_n.jpg', '0', ''),
(172, 30, 15, 'Gloves', 25, 1, 'g1.jpg', '1', ''),
(185, 33, 13, 'Light Bulb (Small)', 80, 1, 'l1.png', '1', ''),
(186, 35, 13, 'Light Bulb (Small)', 80, 5, 'l1.png', '1', ''),
(220, 36, 17, 'Multipurpose Silicone Sea', 200, 3, '462550458_1083331472996632_8844245743170148273_n.jpg', '1', ''),
(221, 36, 26, 'Cutting Disk ', 10, 9, '462570168_971312861505196_4626271953690477872_n.jpg', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `cms_tb`
--

CREATE TABLE `cms_tb` (
  `id` int(11) NOT NULL,
  `home_details` text NOT NULL,
  `about_details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cms_tb`
--

INSERT INTO `cms_tb` (`id`, `home_details`, `about_details`) VALUES
(1, 'Durability, Reliability & Efficiency ', 'Welcome to DRM Hardware Ordering System – your trusted partner for all your hardware needs. At DRM, we are committed to providing a seamless and efficient experience for businesses and individuals seeking quality hardware products. Our mission is to deliver top-grade materials that support your projects with reliability and precision. From sourcing premium components to ensuring timely delivery, every order is handled with care and professionalism. Whether you need bulk supplies for construction or specialized tools for unique projects, DRM offers a wide selection designed to meet the highest industry standards. Experience quality and dependability with every order at DRM.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `name`, `email`, `rating`, `message`, `created_at`) VALUES
(1, NULL, 'John Doe', 'john@example.com', 5, 'Excellent service!', '2024-12-11 00:25:37'),
(2, NULL, 'John Doe', 'ubayburgers@gmail.com', 4, 'dwadnwadnuadaw\r\n', '2024-12-11 05:43:05'),
(3, NULL, 'John Doe', 'ubayburgers@gmail.com', 4, 'dwadnwadnuadaw\r\n', '2024-12-11 05:44:04'),
(4, 36, 'John Doe', 'ubayburgers@gmail.com', 4, 'dwadnwadnuadaw\r\n', '2024-12-11 05:47:05'),
(5, 36, 'John Doe', 'ubayburgers@gmail.com', 4, 'dwadnwadnuadaw\r\n', '2024-12-11 05:47:47'),
(6, 36, 'John Doe', 'ubayburgers@gmail.com', 3, 'nice', '2024-12-11 05:49:26'),
(7, 36, 'John Doe', 'ubayburgers@gmail.com', 3, 'nice', '2024-12-11 05:49:43'),
(8, 36, 'John Doe', 'ubayburgers@gmail.com', 3, 'nice', '2024-12-11 05:49:45'),
(9, 36, 'John Doe', 'ubayburgers@gmail.com', 3, 'nice', '2024-12-11 05:50:11'),
(10, 36, 'John Doe', 'ubayburgers@gmail.com', 3, 'nice', '2024-12-11 05:50:18'),
(11, 36, 'John Doe', 'ubayburgers@gmail.com', 5, '321', '2024-12-11 05:50:25'),
(12, 36, 'John Doe', 'ubayburgers@gmail.com', 5, '321', '2024-12-11 05:51:29'),
(13, 36, 'John Doe', 'ubayburgers@gmail.com', 5, 'wow\r\n', '2024-12-11 15:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `file_tb`
--

CREATE TABLE `file_tb` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `date_upload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_tb`
--

INSERT INTO `file_tb` (`id`, `filename`, `description`, `file`, `date_upload`) VALUES
(5, 'Confidential File', 'Manangement', 'Chapter-1-4-complete.docx', '2024-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`, `date_sent`) VALUES
(1, 36, 'John Doe', 'ubayburgers@gmail.com', '09948037963', 't3', '2024-12-11 07:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(11) NOT NULL,
  `placed_on` varchar(255) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `order_id` varchar(255) NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `order_id`, `reference_no`, `notes`) VALUES
(92, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Multipurpose Silicone Sea (200 x 1   ) - ', 270, '2024-12-11', 'completed', '1', '', ''),
(93, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'gcash', 'dwadwadwad (Main Address)', 'Socket Outlets (50 x 1   ) - ', 120, '2024-12-11', 'pending', '93', '', ''),
(94, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Multipurpose Silicone Sea (200 x 2   ) - ', 470, '2024-12-11', 'pending', '94', '', 'asd sda'),
(95, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Socket Outlets (50 x 3   ) - ', 220, '2024-12-11', 'pending', '95', '', 'sample'),
(96, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Elbow Blue Hose (20 x 1   ) - ', 90, '2024-12-11', 'pending', '96', '', 'sample 3'),
(97, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Paint Rollers  (130 x 3   ) - ', 460, '2024-12-11', 'pending', '97', '', 'asdasdasdasdas'),
(98, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'L.P.G Regulator  (250 x 2   ) - ', 570, '2024-12-11', 'pending', '98', '', ''),
(99, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'All Purpose Epoxy  (100 x 2   ) - ', 270, '2024-12-11', 'pending', '99', '', ''),
(100, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Elbow Blue Hose (20 x 2   ) - ', 110, '2024-12-11', 'pending', '100', '', ''),
(101, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'PVC BLUE  PIPE  (100 x 2   ) - ', 270, '2024-12-11', 'pending', '101', '', ''),
(102, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Elbow Blue Hose (20 x 2   ) - ', 110, '2024-12-11', 'pending', '102', '', ''),
(103, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Extension wire with outle (300 x 3   ) - ', 970, '2024-12-11', 'pending', '103', '', ''),
(104, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Paint Brush (20 x 1   ) - ', 90, '2024-12-11', 'pending', '104', 'sadasd12312', ''),
(105, 36, 'John Doe', '0994803796', 'ubayburgers@gmail.com', 'cash on delivery', 'dwadwadwad (Main Address)', 'Mighty Bond  (50 x 2   ) - ', 170, '2024-12-11', 'pending', '105', 'asdasdas', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `user_id`, `pid`, `name`, `price`, `quantity`, `note`) VALUES
(74, '1', 14, 12, 'Small Keychain', 75, 1, ''),
(75, '1', 14, 11, 'Basketball Shorts #Green', 250, 1, ''),
(76, '1', 15, 13, 'Light Bulb (Small)', 80, 1, ''),
(77, '1', 15, 14, 'Cutting Disk', 60, 1, ''),
(78, '1', 16, 13, 'Light Bulb (Small)', 80, 1, ''),
(79, '1', 16, 14, 'Cutting Disk', 60, 1, ''),
(80, '1', 16, 15, 'Gloves', 25, 1, ''),
(81, '53', 16, 13, 'Light Bulb (Small)', 80, 1, ''),
(82, '54', 16, 13, 'Light Bulb (Small)', 80, 1, ''),
(83, '54', 16, 14, 'Cutting Disk', 60, 1, ''),
(84, '54', 16, 15, 'Gloves', 25, 1, ''),
(85, '1', 16, 13, 'Light Bulb (Small)', 80, 1, ''),
(86, '1', 16, 14, 'Cutting Disk', 60, 1, ''),
(87, '1', 16, 15, 'Gloves', 25, 1, ''),
(88, '1', 24, 13, 'Light Bulb (Small)', 80, 6, ''),
(89, '1', 24, 14, 'Cutting Disk', 60, 99, ''),
(90, '1', 24, 15, 'Gloves', 25, 99, ''),
(91, '57', 24, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(92, '58', 24, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(93, '1', 26, 13, 'Light Bulb (Small)', 80, 1, ''),
(94, '1', 26, 15, 'Gloves', 25, 1, ''),
(95, '1', 26, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(96, '1', 26, 18, 'Socket Outlets', 50, 1, ''),
(97, '60', 26, 13, 'Light Bulb (Small)', 80, 15, ''),
(98, '60', 26, 15, 'Gloves', 25, 1, ''),
(99, '60', 26, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(100, '1', 29, 13, 'Light Bulb (Small)', 80, 1, ''),
(101, '62', 29, 15, 'Gloves', 25, 1, ''),
(102, '1', 30, 13, 'Light Bulb (Small)', 80, 12, ''),
(103, '1', 30, 15, 'Gloves', 25, 11, ''),
(104, '1', 30, 18, 'Socket Outlets', 50, 10, ''),
(105, '1', 30, 23, 'Measuring Tape ', 150, 1, ''),
(106, '1', 30, 28, 'Rubber Mullet ', 100, 1, ''),
(107, '64', 30, 25, 'L.P.G Regulator ', 250, 1, ''),
(108, '1', 30, 13, 'Light Bulb (Small)', 80, 1, ''),
(109, '1', 30, 15, 'Gloves', 25, 1, ''),
(110, '1', 30, 19, 'Bostik Super Vulca seal ', 120, 1, ''),
(111, '66', 30, 15, 'Gloves', 25, 1, ''),
(112, '66', 30, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(113, '67', 30, 15, 'Gloves', 25, 1, ''),
(114, '67', 30, 18, 'Socket Outlets', 50, 1, ''),
(115, '69', 30, 13, 'Light Bulb (Small)', 80, 1, ''),
(116, '70', 30, 13, 'Light Bulb (Small)', 80, 10, ''),
(117, '71', 30, 15, 'Gloves', 25, 1, ''),
(118, '72', 30, 15, 'Gloves', 25, 1, ''),
(119, '1', 30, 15, 'Gloves', 25, 1, ''),
(120, '1', 33, 15, 'Gloves', 25, 1, ''),
(121, '1', 33, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(122, '74', 30, 15, 'Gloves', 25, 1, ''),
(123, '74', 33, 15, 'Gloves', 25, 50, ''),
(124, '74', 33, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(125, '75', 30, 15, 'Gloves', 25, 1, ''),
(126, '75', 33, 15, 'Gloves', 25, 1, ''),
(127, '75', 33, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(128, '76', 30, 15, 'Gloves', 25, 1, ''),
(129, '76', 33, 15, 'Gloves', 25, 3, ''),
(130, '76', 33, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(131, '77', 30, 15, 'Gloves', 25, 1, ''),
(132, '77', 33, 15, 'Gloves', 25, 1, ''),
(133, '78', 30, 15, 'Gloves', 25, 1, ''),
(134, '78', 33, 17, 'Multipurpose Silicone Sealant ', 200, 1, ''),
(135, '78', 33, 15, 'Gloves', 25, 1, ''),
(136, '1', 30, 15, 'Gloves', 25, 1, ''),
(137, '1', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(138, '1', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(139, '1', 37, 15, 'Gloves', 25, 1, ''),
(140, '80', 30, 15, 'Gloves', 25, 1, ''),
(141, '80', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(142, '80', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(143, '80', 37, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(144, '80', 37, 18, 'Socket Outlets', 50, 1, ''),
(145, '81', 30, 15, 'Gloves', 25, 1, ''),
(146, '81', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(147, '81', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(148, '81', 37, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(149, '82', 30, 15, 'Gloves', 25, 1, ''),
(150, '82', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(151, '82', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(152, '82', 37, 17, 'Multipurpose Silicone Sea', 200, 2, ''),
(153, '82', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(154, '83', 30, 15, 'Gloves', 25, 1, ''),
(155, '83', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(156, '83', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(157, '83', 37, 17, 'Multipurpose Silicone Sea', 200, 2, ''),
(158, '83', 36, 18, 'Socket Outlets', 50, 1, ''),
(159, '84', 30, 15, 'Gloves', 25, 1, ''),
(160, '84', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(161, '84', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(162, '84', 37, 17, 'Multipurpose Silicone Sea', 200, 2, ''),
(163, '84', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(164, '85', 30, 15, 'Gloves', 25, 1, ''),
(165, '85', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(166, '85', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(167, '85', 37, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(168, '1733912206', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(169, '1733912221', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(170, '1733912412', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(171, '1733912607', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(172, '1733912678', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(173, '91', 30, 15, 'Gloves', 25, 1, ''),
(174, '91', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(175, '91', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(176, '91', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(177, '1', 30, 15, 'Gloves', 25, 1, ''),
(178, '1', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(179, '1', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(180, '1', 36, 17, 'Multipurpose Silicone Sea', 200, 1, ''),
(181, '93', 30, 15, 'Gloves', 25, 1, ''),
(182, '93', 33, 13, 'Light Bulb (Small)', 80, 1, ''),
(183, '93', 35, 13, 'Light Bulb (Small)', 80, 5, ''),
(184, '93', 36, 18, 'Socket Outlets', 50, 1, ''),
(185, '94', 36, 17, 'Multipurpose Silicone Sea', 200, 2, ''),
(186, '95', 36, 18, 'Socket Outlets', 50, 3, ''),
(187, '96', 36, 21, 'Elbow Blue Hose', 20, 1, ''),
(188, '97', 36, 29, 'Paint Rollers ', 130, 3, ''),
(189, '98', 36, 25, 'L.P.G Regulator ', 250, 2, ''),
(190, '99', 36, 36, 'All Purpose Epoxy ', 100, 2, ''),
(191, '100', 36, 21, 'Elbow Blue Hose', 20, 2, ''),
(192, '101', 36, 30, 'PVC BLUE  PIPE ', 100, 2, ''),
(193, '102', 36, 21, 'Elbow Blue Hose', 20, 2, ''),
(194, '103', 36, 32, 'Extension wire with outle', 300, 3, ''),
(195, '104', 36, 33, 'Paint Brush', 20, 1, ''),
(196, '105', 36, 37, 'Mighty Bond ', 50, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `stock_out` varchar(255) NOT NULL DEFAULT '0',
  `discount_qnty` varchar(255) NOT NULL DEFAULT '0',
  `discount_price` varchar(255) NOT NULL DEFAULT '0',
  `category` varchar(255) NOT NULL,
  `added_date` date NOT NULL DEFAULT current_timestamp(),
  `sales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `stock`, `image_01`, `image_02`, `image_03`, `stock_out`, `discount_qnty`, `discount_price`, `category`, `added_date`, `sales`) VALUES
(13, 'Light Bulb (Small)', 'Illuminate your space with our energy-efficient Light Bulbs, designed to provide bright, consistent lighting while reducing power consumption.', 80, -42, 'l1.png', 'l1.png', 'l1.png', '92', '0', '0', 'Hardware', '2023-01-01', 0),
(15, 'Gloves', 'Protect your hands with our premium work Gloves, crafted from durable, flexible materials for maximum comfort and safety.', 25, -120, 'g1.jpg', 'g1.jpg', 'g1.jpg', '340', '0', '0', 'Wires', '0000-00-00', 0),
(17, 'Multipurpose Silicone Sealant ', 'Available color : Clear Brown White', 200, 70, '462550458_1083331472996632_8844245743170148273_n.jpg', '462550458_1083331472996632_8844245743170148273_n.jpg', '462550458_1083331472996632_8844245743170148273_n.jpg', '8', '0', '0', 'Hardware', '0000-00-00', 0),
(18, 'Socket Outlets', 'Available color :  white ', 50, 67, '462557250_1015171337046519_7536259874046953178_n (1).jpg', '462557250_1015171337046519_7536259874046953178_n (1).jpg', '462557250_1015171337046519_7536259874046953178_n (1).jpg', '23', '0', '0', 'Hardware', '0000-00-00', 0),
(19, 'Bostik Super Vulca seal ', 'Availabe size : medium ', 120, 78, '462553547_555081363783448_4367916639644067113_n.jpg', '462553547_555081363783448_4367916639644067113_n.jpg', '462553547_555081363783448_4367916639644067113_n.jpg', '2', '0', '0', 'Hardware', '0000-00-00', 0),
(20, 'Welding Electrodes', '45pcs per 1 Bundle', 100, 90, '462551477_2033055173796980_8089840813917067681_n.jpg', '462551477_2033055173796980_8089840813917067681_n.jpg', '462551477_2033055173796980_8089840813917067681_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(21, 'Elbow Blue Hose', 'Available size : small medium large ', 20, 100, '462564819_941281207533392_980933235760742506_n.jpg', '462564819_941281207533392_980933235760742506_n.jpg', '462564819_941281207533392_980933235760742506_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(22, 'Orange Fexible Hose PVC ', '50 per 1meeter ', 50, 80, '462574964_530427193211008_287138339911217868_n.jpg', '462574964_530427193211008_287138339911217868_n.jpg', '462574964_530427193211008_287138339911217868_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(23, 'Measuring Tape ', 'Avalable size : 100 small 130 medium 150 large ', 150, 88, '462567224_1915272232311137_4094783391490215386_n.jpg', '462567224_1915272232311137_4094783391490215386_n.jpg', '462567224_1915272232311137_4094783391490215386_n.jpg', '2', '0', '0', 'Hardware', '0000-00-00', 0),
(24, 'Sucket adopter', 'Available size : 20 small 30 medium 40 large ', 20, 70, '462581345_1266426401452074_7415327887378954891_n.jpg', '462581345_1266426401452074_7415327887378954891_n.jpg', '462581345_1266426401452074_7415327887378954891_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(25, 'L.P.G Regulator ', 'Ensure that the preassure maintained inside the tank ', 250, 76, '462553607_2539029739623337_2722686518144882067_n.jpg', '462553607_2539029739623337_2722686518144882067_n.jpg', '462553607_2539029739623337_2722686518144882067_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(26, 'Cutting Disk ', '10 per pieces 225 per 1 box', 10, 87, '462570168_971312861505196_4626271953690477872_n.jpg', '462570168_971312861505196_4626271953690477872_n.jpg', '462570168_971312861505196_4626271953690477872_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(27, 'Universal outlet', 'Availabe size : 30 pesos per outlet', 170, 56, '462553894_1085243023312644_1151882977958550457_n.jpg', '462553894_1085243023312644_1151882977958550457_n.jpg', '462553894_1085243023312644_1151882977958550457_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(28, 'Rubber Mullet ', 'Available size: 100 small 200 medium 300 large ', 100, 46, '462544108_1245570240043450_257653536250244003_n.jpg', '462544108_1245570240043450_257653536250244003_n.jpg', '462545678_1049898146871180_2303254893236853142_n.jpg', '2', '0', '0', 'Hardware', '0000-00-00', 0),
(29, 'Paint Rollers ', 'Available size : 100 small 120 medium 130 large ', 130, 93, '462558598_592035713493633_8855693196904907864_n.jpg', '462558598_592035713493633_8855693196904907864_n.jpg', '462558598_592035713493633_8855693196904907864_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(30, 'PVC BLUE  PIPE ', 'Available size : 75 per 1m ', 100, 48, '462547562_617038627428021_7596318487429725936_n.jpg', '462562580_1150508146488676_3850068892452997907_n.jpg', '462562580_1150508146488676_3850068892452997907_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(31, 'Plastering Cement Trowel Stainless Steel Blade Wooden Handle Spatula Putty Knife Wall ', 'Availabe brand : 120 steel 150 stainless 100 wood ', 120, 93, '462551333_3932592210307880_4173632692166775769_n.jpg', '462551333_3932592210307880_4173632692166775769_n.jpg', '462551333_3932592210307880_4173632692166775769_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(32, 'Extension wire with outlet', 'Available size : 50 per meeter ', 300, 81, '462553467_540580925269231_2679249782561352384_n.jpg', '462553467_540580925269231_2679249782561352384_n.jpg', '462553467_540580925269231_2679249782561352384_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(33, 'Paint Brush', 'Availabe size : 20 small 30 medium 40 large', 20, 75, '462578213_467587889144782_6347399513597883290_n.jpg', '462578213_467587889144782_6347399513597883290_n.jpg', '462578213_467587889144782_6347399513597883290_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(34, 'Paint tinner ', 'Paint thinner is a solvent or blend of solvents used to dilute oil-based paints and reduce their viscosity', 200, 51, '462549020_27479848171630136_2237489188930155287_n.jpg', '462549020_27479848171630136_2237489188930155287_n.jpg', '462549020_27479848171630136_2237489188930155287_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(35, 'Hand Riviter ', 'Lightweight manual pop rivet tools are ideal for simple tasks such as joining metal sheets or thin materials.', 200, 82, '462540783_2376543742699493_83036114519328809_n.jpg', '462540783_2376543742699493_83036114519328809_n.jpg', '462540783_2376543742699493_83036114519328809_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(36, 'All Purpose Epoxy ', 'Availabe size : 100 20ml 200 50ml 300 90ml', 100, 49, '462540825_936089105246211_4924215422048269943_n.jpg', '462540825_936089105246211_4924215422048269943_n.jpg', '462540825_936089105246211_4924215422048269943_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(37, 'Mighty Bond ', 'Availabe size : 50 pesos 1grams 70 pesos 5 grams 90 pesos 7 grams', 50, 39, '462547708_575842224935425_9091621738718098246_n.jpg', '462547708_575842224935425_9091621738718098246_n.jpg', '462547708_575842224935425_9091621738718098246_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(39, 'Rib type roof', 'Availabe color : white blue brown red green // 85 pesos per feet ', 15, 90, '466044127_1073987383907900_8803225229156984283_n.jpg', '462856612_935519508403869_3665275285358901226_n.jpg', '438077528_826661085529739_1327286636769267713_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(40, 'C Purlins ', 'Availabe Thickness : 0.1 200 pesos 0.2 300 pesos 0.5 400 pesos', 335, 99, '448520940_491087706782365_682472954712519409_n.jpg', '461945515_737616551855606_6557357960801240033_n.jpg', '441874749_7912237452173988_2117263178878126577_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(41, 'GI Tubular ', 'Availabe price starts 200 pesos ', 200, 99, '461417462_535233522432020_5376211491197907488_n.jpg', '460682608_1176241723666641_2992238381754844454_n.jpg', '461860946_906739678037875_4100279513768047136_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(42, 'Tempered Glass ', 'Availabe price : Price start 40 square foot ', 40, 71, '462701529_2021054078324729_30138893273990275_n.jpg', '462372216_1244430873639214_5776853526173960129_n.jpg', '466568878_1084502620043036_2699613756949999333_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(43, 'UPVC. C panel WPC wall cladding panels', 'UPVC CEILING PANEL\r\nLength x Width x Thickness \r\n3m x 20cm x 8mm (10ft) \r\n4m x 20cm x 8mm (13ft)\r\n- Can customize up to 8 meters length\r\n', 100, 180, '462661060_1191788062109448_4055871031065416852_n.jpg', '461414579_1112810856945315_2148328913968867803_n.jpg', '465567934_847000697603318_6516535698808810096_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(44, 'Stainless Gutter/Metal Sheet Colored', '80 PER FEET stainless gutter/metal sheet colored\r\n\r\nspanish gutter\r\nbanawe gutter\r\nbox gutter\r\nflashing\r\nwall capping', 80, 230, '464762948_1075451720686582_3017056921428375376_n.jpg', '464834450_8654780957916082_7456852235074583286_n.jpg', '457497230_8324536440955992_7474378706149032900_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(45, 'Plain Sheet ', 'Long span/color roof- 65per feet\r\nCorrugated color -12ft,10ft & 8ft\r\nPlainsheet- \r\nPalupo- 240\r\nFlashing -240\r\nGutter-240\r\nSteel matting', 85, 370, '458489717_1040069227558721_510542647799015955_n.jpg', '450824025_869812608497920_7300288048439538869_n.jpg', '465953144_893512689591383_6249419428447984987_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(47, 'Bronze Reflective Glass ', 'Tempered glass 80 per Square foot \r\n✔️Reflective glass\r\n✔️Colored glass\r\n✔️Laminated glass\r\n✔️Frosted glass\r\n✔️Double glaze glass\r\n✔️Low E\r\n✔️Mirror\r\n✔️6MM to 19MM (Thickness Available)\r\n✔️Jumbo sizes are also available', 80, 328, '456938447_520657867108794_5176555664768151748_n.jpg', '465821396_883669813894471_3084526420407945052_n.jpg', '452719767_1552270848687317_2157831694969500103_n (1).jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(48, 'Screen Door', 'Price Start 2000 per  Screen Door \r\nHeavy duty\r\nHalf amplimesh \r\nWith lock and key \r\nDoor Closer\r\nCustomize measurement ', 2000, 130, '438134474_1137127017373141_4184322376490787270_n.jpg', '445347852_1094352424961106_2478294446611572692_n.jpg', '438134593_7878328638911406_7854393225206419869_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(49, 'PE Foam Insuation ', 'THICKNESS : 2MM, 3MM , 5MM, 10MM, 12MM, 15MM, 20MM, 25MM (1&#34;), 50MM (2&#34;) \r\nSIZE: 1 Meter x 50 Meters\r\nCOVERAGE: 50sqm/roll ', 850, 280, '458343659_1196515538309936_269496956560933608_n.jpg', '452406381_1181832529701596_2964077876885048719_n.jpg', '463802983_1592192228041296_1479705938253024658_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(50, 'Marine Plywood', '140 per inch Condition\r\nUsed - Good\r\nCUT SIZE MARINE LOCAL PLYWOOD\r\n\r\nSize:\r\n47 inches  by 43 inches\r\nOr\r\n110 cm by 115 cm         \r\nPLYWOODS :\r\n1/4\r\n1/2\r\n3/4\r\nPHENOLIC :\r\n1/2\r\n3/4', 140, 385, '465933213_8034563506645596_1914804509973668934_n.jpg', '439987111_311024185372209_3985276764041988977_n.jpg', '464884673_1585406295396004_2059766042933189420_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(51, 'Heavy Duty Electric Wire ', ' Heavy Duty Electric Wire 100 per Feet \r\nNew\r\nBrand\r\nPhelps Dodge\r\n‼️PHELPS DODGE THHN WIRES‼️\r\nColors 🔴⚪️⚫️🔵🟡🟢\r\n2.0mm #14\r\n3.5mm #12\r\n5.5mm #10\r\n8.0mm #8 \r\n14.0mm #6\r\n', 100, 800, '459103622_814755774072787_9072497349471136177_n.jpg', '466320219_861633809366087_2010120448658770050_n.jpg', '441371272_743935931248090_8999062757481990408_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(52, 'Aluminum Sliding Window', '✔️Sliding door\r\n✔️Sliding window\r\n✔️Mirror\r\n✔️Frameless door\r\n✔️Seamless Window\r\n✔️Awning type window\r\n✔️Casement type window\r\n✔️French Fix Type window\r\n✔️French Casement \r\n✔️Tempered Top Glass \r\n✔️Jealousies type window\r\n✔️Screendoor\r\n✔️Tinted window \r\n✔️Glass wall pannel\r\n✔️Patch Fitting Door\r\n✔️Shower Enclosure', 1500, 380, '432102843_1510554256193514_4332911922694429643_n.jpg', '419830344_7280717331984393_7803918365679521419_n.jpg', '425544510_436197259280764_5763139342343874549_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(53, 'Aquarium tank', 'start in 250 per gallon of aquarium tank ', 250, 324, '450871090_366056953174818_3287844213722179852_n.jpg', '461696884_1214002269801113_8209036168429430224_n.jpg', '438329364_472095535377642_3666124664916776366_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(54, 'Steel Bar ', 'AVAILABLE SIZE : \r\nGRADE 33 - 40-60 \r\n10MM \r\n12MM \r\n16MM \r\n20MM\r\n25MM ', 120, 230, '434278769_800635938683003_5660530075644242989_n.jpg', '449190823_2331544730517532_4729857403416821702_n.jpg', '438267930_762302762766595_530104810105642639_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(55, 'Double Deck Steel Bar ', 'Condition\r\nNew\r\nBed Size\r\nQueen\r\nBed Type\r\nBunk Bed\r\nSale‼️Sale‼️Sale‼️\r\nDouble deck and Bed frame heavy duty\r\nsize: single, double, famly and Queen\r\n30x30 , 36x36 , 36x48, 36x54 , 36x60\r\n30x75, 36x75, 42x75, 48x75, 54x75, 60x75\r\n30x72 , 36x72 ', 3000, 200, '465778230_466307126478839_2400742519517424220_n.jpg', '457575165_390520487152071_860021418879969851_n.jpg', '457450233_1033115677951833_2473368424346735673_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(56, 'Angle Bar ', 'Condition\r\nNew\r\nAvailable sizes:\r\n▪️1x1\r\n▪️1 1/2 x 1 1/2\r\n▪️2x2\r\n▪️2 1/2 x 2 1/2\r\n▪️3x3\r\n▪️4x4', 300, 680, '455041941_440511472319971_6925521324500465274_n.jpg', '461391478_913843294015297_7313214597238687793_n.jpg', '466570500_1228750385022545_1232949702834827062_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(58, 'Pvc Blue Pipe ', 'PVC Blue Pipe - Price start 50 pesos per 20 feet ', 50, 100, '461598067_888653386055124_6622642738843823467_n.jpg', '461942772_1178854589866266_1970292249045385347_n.jpg', '455747028_464883003118169_8866011100894418121_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(59, 'Text Screw / Wood and Iron', 'Condition // 200 pesos per 1 box - 2 pesos per pieces\r\nNew\r\nTek Screw for Wood\r\n1&#34; 25mm (650 pcs. per box)\r\n1 1/2&#34; 45mm (400 pcs. per box)\r\n2&#34; 55mm (300 pcs. per box)\r\n2 1/2&#34; 65mm (250 pcs. pee box)\r\n3&#34; 75mm (25 pcs. per box)        Avail Na Size \r\n25mm\r\n35mm\r\n45mm\r\n50mm\r\n55mm\r\n65mm\r\n75mm              ', 200, 80, '392771589_6984977501545140_2345424731274622007_n.jpg', '395457050_24098219973156047_2098511448710995555_n.jpg', '441987732_443676551858456_3422255683431191446_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(60, 'Black Screw', 'Condition\r\nNew\r\nBLACK SCREWS FOR WOOD AND METAL\r\nSIZES & PRICES:\r\n\r\nIN PACK (100PCS)\r\nWOOD SCREW\r\n25MM - P30/ pack\r\n32MM- P45/ pack\r\n38MM- P55/ pack\r\n50MM- P65/ pack\r\n\r\nMETAL SCREW\r\n25MM - P35/ pack\r\n32MM- P50/ pack\r\n38MM- P60/ pack\r\n50MM- P70/ pack\r\n\r\nalso available in boxes:\r\nWood screw - P200 /BOX\r\nMetal screw - P220 /BOX', 220, 867, '441522022_1147764689830928_8343527254112133884_n.jpg', '463488656_466561246409128_8406597820823020179_n.jpg', '461401025_1041700560834324_7932593223783228553_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(61, 'Colored corrugated Roof', 'Colored roof - 85 pesos per feet                           available color : red blue white green brown          ', 380, 200, '463274437_1100416964987304_1505312032375138575_n.jpg', '454839839_1033947942072121_5986735538179547516_n.jpg', '448617908_2072485776480526_7140394207879430667_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(62, 'BISAGRA FOR SALE HEAVY DUTY BALL BEARING', 'Condition\r\nNew\r\nColor\r\n3.0*3.0（silver） 3.5*3.5（silver） 4.0*4.0（silver） 3.0*3.0（Black） 3.5*3.5（Black） 4.0*3.0（Black） 4.0*4.0（Black）\r\nProduct name: 304 solid stainless steel door hinge\r\n \r\n\r\n \r\nProduct color: silver, black\r\n \r\n\r\n \r\nProduct material: 304 solid stainless steel\r\n ', 200, 499, '464005165_878005091135713_1238674038498783035_n.jpg', '464152568_2851221231714448_5902136132363142599_n.jpg', '464756959_908409854095151_2660109823613239274_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(63, 'Concealed Hinges Soft-close', 'Amerilock Regular Concealed Hinges (Bisagra) Inset , Half Lap & Full Overlap (Sold Per Pair) 50php per Pair Only 100 pairs available. \r\n60php per pair 3D Adjustable', 80, 431, '407763705_6725673420877070_704656100847942544_n.jpg', '438080006_1852452248587295_4857342256253801452_n.jpg', '407769239_6825029087532955_9026617566756635823_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(64, 'Plier Tools ', 'Condition\r\nNew\r\nBrand\r\nStanley\r\nStanley Pliers (Diagonal Cutting / Long Nose / Combination) available for sale~!\r\n\r\n• Price Negotiable /  Willing to price match (if kaya)\r\n\r\n🛠️Size and Price:\r\n•  Diagonal Cutting Pliers 6&#34; = ₱260\r\n•  Diagonal Cutting Pliers 7&#34; = ₱290\r\n\r\n•  Long Nose Pliers 6&#34; = ₱280\r\n•  Long Nose Pliers 8&#34;  = ₱300\r\n\r\n•  Combination Pliers 7&#34; = ₱300\r\n•  Combination Pliers 8&#34; = ₱360', 300, 200, '435669805_379450695076836_6733888151456101501_n.jpg', '435767984_1583963802448808_1288845298702816128_n.jpg', '434586044_963450065452098_2488329478484771499_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(65, 'ScewDriver Set', 'Stanley  Cushion Grip  ScrewDriver Set', 200, 38, '453380535_1033111145139681_7135326875921226084_n.jpg', '460323007_880546870844491_5428357908372078248_n.jpg', '464154766_1066902464837193_8178051232991338749_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(66, 'Neltex - PVC Pipe Cement ', 'Condition\r\nNew\r\nBrand\r\nNeltex\r\nFor Sale!!!!\r\n\r\nNeltex PVC Pipe Cement 100 c.c\r\n\r\nPrice: ₱50/pc', 50, 45, '449193001_971478061126133_373481745841925276_n.jpg', '462202934_1276394300392654_5849779926447714210_n.jpg', '', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(67, 'Aluminum Modular Cabinet', 'Condition\r\nNew\r\n*SERVICES OFFERED*\r\n�MODULAR ALUMINUM CABINET\r\n�KITCHEN CABINET(HANG TYPE AND UNDERSINK)\r\n�PANTRY\r\n�WARDROBE\r\nAnd all kinds of glass and aluminun products                                KITCHEN HANGING WALL CABINET \r\n\r\nWhite, Brown\r\n\r\n2500  60*30*80cm White\r\n2900  90*30*80cm white\r\n3500  120*30*80cm white\r\n2800   90*30*60cm white\r\n3400  120*30*60cm white\r\n3200  90*30*80cm brown\r\n3300  120*30*80cm brown\r\n3000  90*30*60cm brown\r\n3100  120*30*60cm brown ', 3500, 100, '461487376_799272975514299_3161353873086367786_n.jpg', '461774863_1559527288780216_174009226851583047_n.jpg', '380686443_6677525712332879_4819679914027890866_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(68, ' Armak - Electrical Tape', 'LARGE available All Colors \r\nMedium & Small Black Colors Only', 50, 189, '461942959_1058746982556275_2659521644457738626_n.jpg', '461869062_1514765929398924_2106013684335811310_n.jpg', '461837106_918871626962390_754896831903079333_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(69, 'PVC BATHROOM DOOR', 'inclusive of \r\nDoor, Jamb, Brackets & Hinges, Door Pad ', 1000, 153, '454037153_463480929920126_9041928861386601559_n.jpg', '464105792_8396842007091399_1788322264720875041_n.jpg', '464130931_8133742043394285_6578917737292538596_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(70, 'Window Grills and any Metal Works', 'Made to order pm.pm lng po mura na matibay pa for as low as 150 per square ft. \r\n📍 Made to order \r\n💯 Quality Materials Used\r\n💯 Customized size and design', 1000, 231, '457102490_436132055459764_5844744041418280129_n.jpg', '372944396_7175441229167558_5076319354206523780_n.jpg', '457452950_500295416072238_647237655647701398_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(72, 'Iron and Stainless fabricator Grills for balcony and window ', '𝐓𝐑𝐈𝐏𝐋𝐄 𝐉 𝐒𝐓𝐄𝐄𝐋 𝐀𝐍𝐃 𝐀𝐋𝐔𝐌𝐈𝐍𝐔𝐌 𝐖𝐎𝐑𝐊𝐒 𝐖𝐞 𝐟𝐚𝐛𝐫𝐢𝐜𝐚𝐭𝐞 𝐚𝐧𝐝 𝐂𝐮𝐬𝐭𝐨𝐦𝐢𝐳𝐞 𝐚𝐥𝐥 𝐭𝐲𝐩𝐞𝐬 𝐨𝐟 𝐬𝐭𝐞𝐞𝐥 𝐰𝐨𝐫𝐤𝐬 𝐖𝐞 𝐨𝐟𝐟𝐞𝐫𝐞𝐝 -𝐒𝐭𝐞𝐞𝐥 𝐆𝐫𝐢𝐥𝐥𝐬-𝐋𝐨𝐟𝐭 𝐁𝐞𝐝/ 𝐛𝐞𝐝 𝐟𝐫𝐚𝐦𝐞/ 𝐝𝐨𝐮𝐛𝐥𝐞 𝐝𝐞𝐜𝐤-𝐒𝐭𝐞𝐞𝐥 𝐆𝐚𝐭𝐞𝐬-𝐅𝐨𝐥𝐝𝐢𝐧𝐠 𝐆𝐚𝐭𝐞/ 𝐃𝐨𝐮𝐛𝐥𝐞 𝐠𝐚𝐭𝐞-𝐃𝐨𝐠 𝐂𝐚𝐠𝐞𝐬/ 𝐩𝐢𝐠𝐞𝐨𝐧 𝐥𝐨𝐟𝐭 𝐜𝐚𝐠𝐞-𝐒𝐩𝐢𝐫𝐚𝐥 𝐒𝐭𝐚𝐢𝐫𝐬 -𝐒𝐭𝐞𝐞𝐥 𝐬𝐭𝐚𝐢𝐫𝐬, 𝐑𝐚𝐢𝐥𝐢𝐧𝐠𝐬 𝐚𝐧𝐝 𝐅𝐞𝐧𝐜𝐞-𝐓𝐫𝐮𝐬𝐬𝐞𝐬 , 𝐑𝐨𝐨𝐟𝐢𝐧𝐠-𝐅', 123, 123, '454505610_2780232288821719_2233179483849346126_n (1).jpg', '454968663_2864865537014757_1907584924625879716_n.jpg', '455092558_1041688307556663_9062888642488812592_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(73, 'Roll-up door installation and repair ', ' Price itdepends on size and quality of material and Roll up door supply and installation Manual and motorized operation', 1300, 100, '451436847_3666215980295315_6773457428862818856_n.jpg', '449991636_464662119653045_5560200639098682437_n.jpg', '452285907_823313043110232_1513115797467086378_n.jpg', '0', '0', '0', 'Hardware', '0000-00-00', 0),
(75, 'John Doe', 'sample\r\n', 20, 10, 'user.png', 'three-dots.png', 'photo_6062399686549027689_y.jpg', '0', '0', '0', 'Cabinet hardware', '2024-12-11', 0),
(76, 'test', 'dwadawdaw', 32, 32, 'photo_6066785861770525618_x.jpg', 'photo_6062399686549027691_y.jpg', 'photo_6062399686549027690_y.jpg', '0', '0', '0', 'Kitchen hardware', '2024-12-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `Status` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Contact_Number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `code`, `Status`, `Address`, `Contact_Number`) VALUES
(36, 'John Doe', 'ubayburgers@gmail.com', '202cb962ac59075b964b07152d234b70', '67173', 1, 'dwadwadwad', '09948037963'),
(37, 'John Doe', 'razztaman07@gmail.com', '202cb962ac59075b964b07152d234b70', '77622', 1, 'dwadwadwad', '09948037963'),
(41, 'John Doe', 'ubaybers@gmail.com', '76d80224611fc919a5d54f0ff9fba446', '27720', 0, 'dwadwadwad', '994803796');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements_tb`
--
ALTER TABLE `announcements_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_tb`
--
ALTER TABLE `cms_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `file_tb`
--
ALTER TABLE `file_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcements_tb`
--
ALTER TABLE `announcements_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `cms_tb`
--
ALTER TABLE `cms_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `file_tb`
--
ALTER TABLE `file_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
