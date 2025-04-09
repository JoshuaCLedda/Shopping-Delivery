-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2025 at 04:13 PM
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
-- Database: `onlinefoodphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `d_id` int(11) NOT NULL,
  `rs_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available_quantity` varchar(100) NOT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `stall` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`d_id`, `rs_id`, `title`, `available_quantity`, `slogan`, `price`, `img`, `status`, `stall`) VALUES
(1, 0, 'kwek kwek', '', '3 pcs', 20.00, '67cf88453eac4.jpg', 'not_available', ''),
(2, 0, 'Spaghetti', '', '1 order', 30.00, '67cf92fe51eb8.jpg', 'not_available', ''),
(3, 0, 'Pansit Palabok', '', '1 order', 30.00, '67cf956ebf27b.jpg', 'not_available', ''),
(4, 12, 'Sopas', '', '1 order', 30.00, '67ecbe6d064a9.jpg', 'not_available', ''),
(5, 12, 'Turonn', '', '3 pcs', 20.00, '67ecaa040fda1.jpg', 'available', ''),
(6, 0, 'Lumpiang Gulay', '', '3 pcs', 20.00, '67ecbfc6bfe29.jpg', 'available', 'khisha Amore Food Corner');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('place_order','order_confirmation','wait_for_delivery','in_process','delivered','rejected','cancelled') NOT NULL DEFAULT 'place_order',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating_rider`
--

CREATE TABLE `rating_rider` (
  `id` int(11) NOT NULL,
  `rider_id` int(11) NOT NULL,
  `rider_name` varchar(255) NOT NULL,
  `rating` tinyint(50) DEFAULT NULL,
  `complaint` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating_rider`
--

INSERT INTO `rating_rider` (`id`, `rider_id`, `rider_name`, `rating`, `complaint`, `created_at`) VALUES
(1, 0, 'Andreigh Lee Dolormente', 5, 'werdtfuyguhi', '2025-03-17 05:51:42'),
(3, 0, 'Maxine Wilkins', 5, '', '2025-03-24 07:33:18'),
(10, 0, 'Marife Dor-as', 2, 'Sunt voluptas quidem', '2025-04-09 14:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `remark`
--

CREATE TABLE `remark` (
  `id` int(11) NOT NULL,
  `frm_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `remark` text DEFAULT NULL,
  `remarkDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `rs_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `o_hr` varchar(50) NOT NULL,
  `c_hr` varchar(50) NOT NULL,
  `o_days` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`rs_id`, `c_id`, `title`, `email`, `phone`, `url`, `o_hr`, `c_hr`, `o_days`, `address`, `image`, `date`) VALUES
(15, 5, 'Quon Massey', 'fumebufoho@mailinator.com', '+1 (874) 774-6806', 'Laboris qui illo nat', '7:00 am', '6:00 pm', 'mon-tue', 'Id quisquam quos cul', '67f630f2f132d.png', '2025-04-09 16:28:35'),
(16, 1, 'Roth Burch', 'caxupi@mailinator.com', '+1 (665) 837-9013', 'Tempora autem cum et', '11am', '4pm', 'Mon-Tue', NULL, NULL, '2025-04-09 16:31:09'),
(17, 1, 'Illiana Greene', 'gydo@mailinator.com', '+1 (552) 355-2384', 'Et eos et recusandae', '10am', '--Select your Hours--', 'Mon-Sat', NULL, NULL, '2025-04-09 16:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `res_category`
--

CREATE TABLE `res_category` (
  `c_id` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `res_category`
--

INSERT INTO `res_category` (`c_id`, `c_name`, `date`) VALUES
(1, 'Snack', '2025-02-23 14:58:46'),
(2, 'Lunch', '2025-02-23 14:58:53'),
(3, 'Dessert', '2025-02-23 14:59:04'),
(4, 'Foodcourt', '2025-03-14 12:45:47'),
(5, 'Driscoll Burke', '2025-04-09 16:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_question` text NOT NULL,
  `answer` text NOT NULL,
  `address` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`id`, `username`, `f_name`, `l_name`, `email`, `phone`, `password`, `security_question`, `answer`, `address`, `status`) VALUES
(1, 'zexenuty', 'Malachi', 'Lloyd', 'tazecyq@gmail.com', '+1 (562) 735-25', 'e10adc3949ba59abbe56e057f20f883e', 'Your mother\'s maiden name?', 'Ut molestiae provide', NULL, ''),
(2, 'lewuny', 'Rosalyn', 'Bates', 'tirij@gmail.com', '+1 (862) 943-12', 'e10adc3949ba59abbe56e057f20f883e', 'Your first pet\'s name?', 'Sunt id rem aut par', NULL, 'inactive'),
(3, 'lian', 'Leyanne', 'Benton', 'gopynihab@gmail.com', '+1 (418) 143-46', 'e10adc3949ba59abbe56e057f20f883e', 'Your first pet\'s name?', 'Quos ipsum aut quae ', NULL, ''),
(4, 'ximoxyzef', 'Perry', 'Mcleod', 'dusisi@gmail.com', '+1 (295) 648-48', 'e10adc3949ba59abbe56e057f20f883e', 'Your first pet\'s name?', 'Illum earum dolores', NULL, ''),
(5, 'hixoqe', 'Danielle', 'Gilliam', 'qiwof@gmail.com', '+1 (117) 666-16', 'e10adc3949ba59abbe56e057f20f883e', 'Your first pet\'s name?', 'Deserunt accusantium', NULL, ''),
(6, 'bejytyhe', 'Chase', 'Preston', 'qafixes@gmail.com', '+1 (743) 635-79', 'e10adc3949ba59abbe56e057f20f883e', 'Your mother\'s maiden name?', 'Qui totam assumenda ', NULL, 'inactive'),
(7, 'genasupu', 'Destiny', 'Kennedy', 'gimeqe@gmail.com', '+1 (195) 491-23', 'e10adc3949ba59abbe56e057f20f883e', 'City you were born in?', 'Officia iusto quis p', NULL, ''),
(8, 'kosijep', 'C', 'Gibbs', 'qimodinoze@gmail.com', '+1 (104) 429-41', 'a152e841783914146e4bcd4f39100686', 'Your mother\'s maiden name?', 'Do dolore similique ', NULL, 'inactive'),
(9, 'gujekicyf', 'Rigel', 'Coleman', 'kupijutoxa@gmail.com', '+1 (784) 566-78', 'e10adc3949ba59abbe56e057f20f883e', 'Your first pet\'s name?', 'Rerum nesciunt tota', NULL, 'inactive'),
(10, 'zixotef', 'Aristotle', 'Joseph', 'megynom@gmail.com', '+1 (483) 509-84', 'e10adc3949ba59abbe56e057f20f883e', 'Your mother\'s maiden name?', 'Sit provident quas', NULL, 'active'),
(11, 'xaruvybo', 'Eliana Oneil', 'Macon Hancock', 'vygegyk', '09514424444', '$2y$10$.B/TOE0JOeBNZkPop.dQVOAq8dai0daeTgLan3hvIRTFqFvwBR23q', 'City you were born in?', 'Et dolores dicta non', NULL, NULL),
(12, 'Reiss', 'Amos Munoz', 'Plato Foster', 'sosas', '+1 (676) 818-1947', '$2y$10$Rg/cweSQ3qV.cgrZBl0REegRNye9n2FpAL66Ec6WBaJhNC3fGJNI6', 'Your first pet\'s name?', 'Tenetur repellendus', NULL, NULL),
(13, 'bufeg', 'Cairo Mcclain', 'Christian Vinson', 'wobizazop', '24', '$2y$10$Qf2p.TsLTUYMjlF5aFZe0eBGt11cu8VQLYzm1MPoFZBG/VyO6JIlS', 'City you were born in?', 'Nisi quo mollitia re', NULL, NULL),
(14, 'neduq', 'Travis Cooke', 'Lionel Moon', 'qikukyno', '10', '$2y$10$uZ6WJgtRgEa5CeMjloWIOOkWU6KhtbEoB/Xov29oQR9ypZhd8pU9.', 'City you were born in?', 'Omnis laboriosam no', NULL, NULL),
(15, 'cutuhoji', 'Luke Whitney', 'Raja Osborne', 'pigomaj', '44', '$2y$10$9YYuZTfG3sdg6XJvAZh/g.1qPywdHzq7ZUid2q1wlzkrP14ditCAa', 'Your mother\'s maiden name?', 'Exercitation culpa ', NULL, NULL),
(16, 'fobixofuno', 'Carter Odonnell', 'Mufutau Kline', 'bulagaxe', '65', '$2y$10$z.QiTxEn4sk2xVoxXLxdoe7DJnAoqShZoeJusb/UpVZejP/Putx52', 'City you were born in?', 'Et aliqua Sint aut', NULL, NULL),
(17, 'lahamumyf', 'Quintessa Rodriquez', 'Basil Byrd', 'kosuqoby', '7', '$2y$10$lfOPQpjxjAFeZPulLfptSOI8Qw3IZczCA8uTSdxg.ltHbY9MWHVuK', 'Your first pet\'s name?', 'Ad error libero cons', NULL, 'inactive'),
(18, 'qadovy', 'Isadora Francis', 'Mariko Gilmore', 'rivyzemixe', '40', '$2y$10$ngC9zX2XVhmiWaN59j6hsum0TkNtRCLLkkr9jOcxTK0aiTDND7DeW', 'Your first pet\'s name?', 'Minus nisi duis qui ', 'Id maiores molestia', NULL),
(19, 'javokyxi', 'Nasim Norton', 'Karyn Burch', 'cuzaruzu', '92', '$2y$10$/8j.qgCqFlwlwH05JYfhYOyBZj0wJzXNKHEgS/UAGnSfe7qdIEyGi', 'Your first pet\'s name?', 'Necessitatibus dolor', 'Voluptas et quisquam', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE `security_questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `rider_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `titles` text NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `rs_id` int(11) NOT NULL,
  `rider_rating` tinyint(4) DEFAULT NULL CHECK (`rider_rating` between 1 and 5),
  `complaint` text DEFAULT NULL,
  `payment_method` varchar(100) NOT NULL,
  `gcash_proof` varchar(100) NOT NULL,
  `stall` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `u_id`, `rider_id`, `address`, `total_price`, `stall_id`, `order_date`, `status`, `payment_status`, `titles`, `total_quantity`, `rs_id`, `rider_rating`, `complaint`, `payment_method`, `gcash_proof`, `stall`) VALUES
(5, 1, 0, 'Some Address', 100.00, 1, '2025-03-11 13:37:49', 'order_confirmation', '', 'Pizza', 2, 10, NULL, NULL, '', '', ''),
(8, 1, 0, '', 50.00, 0, '2025-03-11 13:40:08', 'Order_Canceled', NULL, 'kwek kwek', 1, 10, NULL, NULL, '', '', ''),
(9, 1, 0, '', 50.00, 0, '2025-03-11 13:40:59', 'Order_Received', NULL, 'kwek kwek', 1, 10, NULL, NULL, '', '', ''),
(10, 1, 0, '', 50.00, 0, '2025-03-11 13:41:01', 'in_process', NULL, 'kwek kwek', 1, 10, NULL, NULL, '', '', ''),
(11, 1, 0, '', 50.00, 0, '2025-03-11 13:41:14', 'order_confirmation', NULL, 'kwek kwek', 1, 10, NULL, NULL, '', '', ''),
(12, 1, 0, '', 50.00, 0, '2025-03-11 13:41:38', 'order_confirmation', NULL, 'kwek kwek', 1, 10, NULL, NULL, '', '', ''),
(13, 1, 0, '', 110.00, 0, '2025-03-14 13:04:32', 'order_confirmation', NULL, 'kwek kwek, Spaghetti, Pansit Palabok', 3, 12, NULL, NULL, '', '', ''),
(14, 1, 0, '', 110.00, 0, '2025-03-14 13:04:47', 'order_confirmation', NULL, 'kwek kwek, Spaghetti, Pansit Palabok', 3, 12, NULL, NULL, '', '', ''),
(15, 1, 0, '', 90.00, 0, '2025-03-14 13:14:14', 'order_confirmation', NULL, 'Spaghetti, Pansit Palabok', 2, 12, NULL, NULL, '', '', ''),
(16, 1, 0, '', 90.00, 0, '2025-03-14 13:15:01', 'order_confirmation', NULL, 'Spaghetti, Pansit Palabok', 2, 12, NULL, NULL, '', '', ''),
(17, 0, 0, '', 50.00, 0, '2025-03-14 13:43:40', 'order_confirmation', NULL, 'kwek kwek', 1, 12, NULL, NULL, '', '', ''),
(18, 2, 0, '', 190.00, 0, '2025-03-17 13:36:29', 'Order_Canceled', NULL, 'kwek kwek, Spaghetti, Sopas, Turon', 7, 14, NULL, NULL, '', '', ''),
(19, 2, 0, '', 260.00, 0, '2025-03-17 13:44:05', 'Order_Received', NULL, 'kwek kwek, Spaghetti, Sopas, Turon', 9, 14, NULL, NULL, '', '', ''),
(20, 2, 0, '', 320.00, 0, '2025-03-17 13:50:17', 'Order_Received', NULL, 'kwek kwek, Spaghetti, Pansit Palabok, Turon, Lumpiang Gulay', 10, 12, NULL, NULL, '', '', ''),
(21, 2, 7, '', 280.00, 0, '2025-03-17 13:52:21', 'order_confirmation', NULL, 'kwek kwek, Spaghetti, Pansit Palabok, Turon', 9, 12, NULL, NULL, '', '', ''),
(22, 2, 0, '', 300.00, 0, '2025-03-17 14:06:07', 'order_confirmation', NULL, 'kwek kwek, Spaghetti, Pansit Palabok, Turon', 10, 12, NULL, NULL, '', '', ''),
(23, 2, 0, '', 300.00, 0, '2025-03-17 14:09:46', 'place_order', NULL, 'kwek kwek, Spaghetti, Pansit Palabok, Turon', 10, 12, NULL, NULL, '', '', ''),
(24, 2, 0, '', 300.00, 0, '2025-03-17 14:17:28', 'place_order', NULL, 'kwek kwek, Spaghetti, Pansit Palabok, Turon', 10, 12, NULL, NULL, '', '', ''),
(25, 2, 0, '', 50.00, 0, '2025-03-17 14:53:03', 'cancelled', NULL, 'kwek kwek', 1, 14, NULL, NULL, '', '', ''),
(26, 2, 0, '', 50.00, 0, '2025-03-17 14:53:25', 'place_order', NULL, 'kwek kwek', 1, 14, NULL, NULL, '', '', ''),
(27, 2, 0, '', 50.00, 0, '2025-03-17 14:55:17', 'place_order', NULL, 'kwek kwek', 1, 14, NULL, NULL, '', '', ''),
(28, 2, 0, '', 50.00, 0, '2025-03-17 14:58:45', 'place_order', NULL, 'kwek kwek', 1, 14, NULL, NULL, '', '', ''),
(29, 2, 0, '', 50.00, 0, '2025-03-18 09:47:58', 'place_order', NULL, 'kwek kwek', 1, 14, NULL, NULL, '', '', ''),
(30, 2, 0, '', 50.00, 0, '2025-03-20 14:20:29', 'place_order', NULL, 'kwek kwek', 1, 12, NULL, NULL, '', '', ''),
(31, 2, 0, 'sapilang', 50.00, 0, '2025-03-20 14:22:02', 'place_order', NULL, 'kwek kwek', 1, 12, NULL, NULL, '', '', ''),
(32, 2, 0, '', 110.00, 0, '2025-03-20 15:04:50', 'place_order', NULL, 'kwek kwek, Spaghetti, Pansit Palabok', 3, 12, NULL, NULL, '', '', ''),
(33, 2, 0, '', 110.00, 0, '2025-03-20 15:08:08', 'place_order', NULL, 'kwek kwek, Spaghetti, Pansit Palabok', 3, 12, NULL, NULL, '', '', ''),
(34, 2, 0, '', 170.00, 0, '2025-03-20 15:17:09', 'place_order', NULL, 'Lumpiang Gulay, kwek kwek, Spaghetti, Sopas', 6, 14, NULL, NULL, '', '', ''),
(35, 2, 0, '', 120.00, 0, '2025-03-20 15:20:17', 'place_order', NULL, 'kwek kwek, Spaghetti', 4, 12, NULL, NULL, '', '', ''),
(36, 2, 0, '', 80.00, 0, '2025-03-20 15:24:09', 'Order_Received', NULL, 'kwek kwek, Spaghetti', 2, 14, NULL, NULL, '', '', ''),
(37, 8, 0, '', 50.00, 0, '2025-03-24 07:32:11', 'place_order', NULL, 'kwek kwek', 1, 12, NULL, NULL, '', '', ''),
(38, 0, 0, '', 80.00, 0, '2025-03-24 08:29:24', 'order_confirmation', NULL, 'kwek kwek, Spaghetti', 2, 14, NULL, NULL, '', '', ''),
(39, 7, 0, '', 50.00, 0, '2025-03-24 15:03:28', 'Order_Received', NULL, 'Turon', 1, 12, NULL, NULL, '', '', ''),
(40, 1, 0, '', 70.00, 0, '2025-03-24 15:50:06', 'place_order', NULL, 'kwek kwek', 2, 14, NULL, NULL, '', '', ''),
(41, 7, 2, '', 60.00, 0, '2025-03-26 14:46:59', 'Order_Received', NULL, 'Pansit Palabok', 1, 12, NULL, NULL, '', '', ''),
(42, 7, 0, '', 100.00, 0, '2025-03-26 15:03:45', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 14, NULL, NULL, 'COD', '', ''),
(43, 7, 0, '', 100.00, 0, '2025-03-26 15:03:52', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 12, NULL, NULL, 'COD', '', ''),
(44, 7, 0, '', 100.00, 0, '2025-03-26 15:03:53', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 12, NULL, NULL, 'COD', '', ''),
(45, 7, 0, '', 100.00, 0, '2025-03-26 15:03:54', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 12, NULL, NULL, 'COD', '', ''),
(46, 7, 0, '', 100.00, 0, '2025-03-26 15:04:09', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 12, NULL, NULL, 'COD', '', ''),
(47, 7, 0, '', 100.00, 0, '2025-03-26 15:04:30', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 12, NULL, NULL, 'COD', '', ''),
(48, 7, 0, '', 100.00, 0, '2025-03-26 15:04:51', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 14, NULL, NULL, 'COD', '', ''),
(49, 7, 0, '', 100.00, 0, '2025-03-26 15:06:42', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 14, NULL, NULL, 'COD', '', ''),
(50, 7, 0, '', 100.00, 0, '2025-03-26 15:06:43', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 14, NULL, NULL, 'COD', '', ''),
(51, 7, 0, '', 100.00, 0, '2025-03-26 15:06:43', 'place_order', NULL, 'Pansit Palabok, kwek kwek', 3, 14, NULL, NULL, 'COD', '', ''),
(52, 7, 0, '', 60.00, 0, '2025-03-26 15:25:40', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(53, 7, 0, '', 60.00, 0, '2025-03-26 15:25:54', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(54, 7, 0, '', 60.00, 0, '2025-03-26 15:25:54', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(55, 7, 0, '', 60.00, 0, '2025-03-26 15:27:24', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(56, 7, 0, '', 60.00, 0, '2025-03-26 15:27:24', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(57, 7, 0, '', 60.00, 0, '2025-03-26 15:27:25', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(58, 7, 0, '', 60.00, 0, '2025-03-26 15:27:38', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(59, 7, 0, '', 60.00, 0, '2025-03-26 15:27:40', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(60, 7, 0, '', 60.00, 0, '2025-03-26 15:28:24', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, '', '', ''),
(61, 7, 0, '', 60.00, 0, '2025-03-26 15:28:35', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, '', '', ''),
(62, 7, 0, '', 60.00, 0, '2025-03-26 15:28:36', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, '', '', ''),
(63, 7, 0, '', 60.00, 0, '2025-03-26 15:28:49', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, '', '', ''),
(64, 7, 0, '', 60.00, 0, '2025-03-26 15:30:30', 'place_order', NULL, 'Pansit Palabok', 1, 14, NULL, NULL, 'COD', '', ''),
(65, 17, 0, '', 80.00, 0, '2025-03-28 08:07:27', 'Order_Received', NULL, 'kwek kwek, Pansit Palabok', 2, 12, NULL, NULL, 'COD', '', ''),
(66, 7, 0, '', 70.00, 0, '2025-04-02 12:41:36', 'place_order', NULL, 'Lumpiang Gulay', 2, 12, NULL, NULL, 'COD', '', ''),
(67, 7, 0, '', 70.00, 0, '2025-04-02 12:43:23', 'place_order', NULL, 'Lumpiang Gulay', 2, 12, NULL, NULL, 'COD', '', ''),
(68, 7, 0, '', 50.00, 0, '2025-04-02 12:48:58', 'place_order', NULL, 'Lumpiang Gulay', 1, 12, NULL, NULL, 'COD', '', ''),
(69, 7, 0, '', 50.00, 0, '2025-04-02 13:20:47', 'place_order', NULL, 'Lumpiang Gulay', 1, 12, NULL, NULL, 'COD', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `titles` text NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `rs_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'place_order',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `u_id`, `titles`, `total_quantity`, `total_price`, `address`, `rs_id`, `status`, `created_at`, `payment_method`) VALUES
(1, 2, 'Spaghetti, Sopas, kwek kwek, Lumpiang Gulay', 6, 170.00, '', 12, 'place_order', '2025-03-17 03:36:13', ''),
(2, 2, 'Spaghetti, Sopas, kwek kwek, Lumpiang Gulay', 6, 170.00, '', 12, 'place_order', '2025-03-17 03:36:23', ''),
(3, 2, 'Spaghetti, Sopas, kwek kwek, Lumpiang Gulay', 6, 170.00, '', 12, 'place_order', '2025-03-17 03:36:27', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `profile_image` varchar(200) NOT NULL,
  `u_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `date` datetime DEFAULT current_timestamp(),
  `role` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `security_questions` text DEFAULT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`profile_image`, `u_id`, `username`, `f_name`, `l_name`, `email`, `phone`, `password`, `address`, `status`, `date`, `role`, `security_questions`, `answer`) VALUES
('', 1, 'Andrea', 'Andreigh Lee', 'Dolormente', 'andreighlee.dolormente@student.dmmmsu.edu.ph', '09105672539', '$2y$10$4L1WARxm5UN0WPwEYkqE0.I6yD4OyPwzM3l6kZKqHDIoYX1jiRkQS', 'Salincob Bacnotan, La Union', 'active', '2025-02-23 14:57:04', '1', 'birth_city', 'Las Pinas City Metro Manila'),
('', 2, 'Marife', 'Marife', 'Dor-as', 'andreighleed@gmail.com', '09102089228', '$2y$10$Q4p4RXjUN0h996ZdqOu5OeEjgcixxk4EQzJEfd/Rg4j0Z0Pbe21lK', 'sdfiyiui8hk', 'active', '2025-03-17 10:46:54', '2', 'pet_name', 'weweee'),
('', 6, 'werwer', 'tfygiuhoi', 'tfuygij', 'rogendheqe21321t@gmail.com', '093123232313', '$2y$10$IJCFMBNR91DV2LPxxYFvcuuOZnGM2EHvIfcZZm6Xt5LjHa1AeJnKa', 'aestrdghvhh', 'active', '2025-03-18 14:01:03', '2', 'pet_name', 'w'),
('', 7, 'qyrykobys', 'Nasim', 'Walton', 'burytuwu@gmail.com', '+1 (433) 819-7666', '$2y$10$VoOmJv7ZQWMowoKXJxTS0O7A1oyiyAV4Bx1YAjrZ9WEbqNdCWiRCC', 'Reprehenderit cupid', 'active', '2025-03-24 07:31:17', '0', 'birth_city', 'Exercitation numquam'),
('', 8, 'meow', 'Maxine', 'Wilkins', 'dezity@gmail.com', '+1 (617) 859-5683', '$2y$10$0r4mzN8bFSIQhFTqWtC8zuWSRdx2k2UcLB.o.XMr4kLpXXAaOimPm', 'Exercitation sint no', 'active', '2025-03-24 07:31:42', '1', 'birth_city', 'Dolor quis possimus'),
('', 10, 'harley', 'Beck', 'Anderson', 'horobe@gmail.com', '+1 (237) 506-9804', '$2y$10$GolD7RESXIDoeQsgiWWseuU0pxEWeGqXLVn5Iw4xT7isydrjils5G', 'Quia voluptate repre', 'active', '2025-03-27 08:47:40', '0', 'favorite_food', 'foodtrip'),
('', 11, 'mezyfubih', 'Madonna', 'Chan', 'mowebuju@gmail.com', '+1 (406) 822-7834', '$2y$10$gwkOfd6q799rGvqnszLvD.UH.wy7eQHCFHGQiz7m3TOMsu7Bb/9xO', 'Obcaecati ut qui nul', 'active', '2025-03-27 17:58:01', '0', 'pet_name', 'Aut repudiandae qui '),
('', 13, 'jobysun', 'Ashton', 'Austin', 'guqymip@gmail.com', '+1 (434) 995-9846', '$2y$10$KKLkYua7itKS6sNL7gg0We27ZVbSCwaV/mbDAy4TYnXeRmP8twqWu', 'Molestiae cupiditate', 'active', '2025-03-27 18:07:41', '0', 'pet_name', 'Minim dolore ipsa p'),
('', 14, 'tumudym', 'Daryl', 'Lynch', 'deqivaw@gmail.com', '+1 (241) 339-6703', '$2y$10$SI8hzgm5JWg3iiLfDwslouoO9faqGhkcukdXC93PLUYMuO7OME0Ni', 'Eos ad odio magna ei', 'active', '2025-03-27 18:08:52', '0', 'birth_city', 'Provident iure repr'),
('', 17, 'maeee', 'Hillary', 'Miles', 'mopoli@gmail.com', '+1 (916) 192-9939', '$2y$10$svRfRM0UuIuJGiAFnw4uM.vTD5eAfB8nYhae6EH2yQOhnpHmj9icm', 'Et consequatur dese', 'active', '2025-03-28 08:05:22', '0', 'birth_city', 'Amet voluptas debit');

-- --------------------------------------------------------

--
-- Table structure for table `users_orders`
--

CREATE TABLE `users_orders` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `address` text NOT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `rs_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `gcash_proof` varchar(100) NOT NULL,
  `stall` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_orders`
--

INSERT INTO `users_orders` (`id`, `u_id`, `title`, `quantity`, `price`, `address`, `status`, `rs_id`, `transaction_id`, `payment_method`, `gcash_proof`, `stall`) VALUES
(1, 2, 'kwek kwek', 1, 20.00, '', '', 12, 33, '', '', ''),
(2, 2, 'Spaghetti', 1, 30.00, '', '', 12, 33, '', '', ''),
(3, 2, 'Pansit Palabok', 1, 30.00, '', '', 12, 33, '', '', ''),
(4, 2, 'Lumpiang Gulay', 1, 20.00, '', '', 14, 34, '', '', ''),
(5, 2, 'kwek kwek', 3, 20.00, '', '', 14, 34, '', '', ''),
(6, 2, 'Spaghetti', 1, 30.00, '', '', 14, 34, '', '', ''),
(7, 2, 'Sopas', 1, 30.00, '', '', 14, 34, '', '', ''),
(8, 2, 'kwek kwek', 3, 20.00, '', '', 12, 35, '', '', ''),
(9, 2, 'Spaghetti', 1, 30.00, '', '', 12, 35, '', '', ''),
(10, 2, 'kwek kwek', 1, 20.00, '', '', 14, 36, '', '', ''),
(11, 2, 'Spaghetti', 1, 30.00, '', '', 14, 36, '', '', ''),
(12, 8, 'kwek kwek', 1, 20.00, '', '', 12, 37, '', '', ''),
(13, 0, 'kwek kwek', 1, 20.00, '', '', 14, 38, '', '', ''),
(14, 0, 'Spaghetti', 1, 30.00, '', '', 14, 38, '', '', ''),
(15, 7, 'Turon', 1, 20.00, '', '', 12, 39, '', '', ''),
(16, 1, 'kwek kwek', 2, 20.00, '', '', 14, 40, '', '', ''),
(17, 7, 'Pansit Palabok', 1, 30.00, '', '', 12, 41, '', '', ''),
(18, 7, 'Pansit Palabok', 1, 30.00, '', '', 14, 64, 'COD', '', ''),
(19, 17, 'kwek kwek', 1, 20.00, '', '', 12, 65, 'COD', '', ''),
(20, 17, 'Pansit Palabok', 1, 30.00, '', '', 12, 65, 'COD', '', ''),
(21, 7, 'Lumpiang Gulay', 2, 20.00, '', '', 12, 67, 'COD', '', ''),
(22, 7, 'Lumpiang Gulay', 1, 20.00, '', '', 12, 68, 'COD', '', ''),
(23, 7, 'Lumpiang Gulay', 1, 20.00, '', '', 12, 69, 'COD', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`d_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `rating_rider`
--
ALTER TABLE `rating_rider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remark`
--
ALTER TABLE `remark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `frm_id` (`frm_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`rs_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `res_category`
--
ALTER TABLE `res_category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `rs_id` (`rs_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `users_orders`
--
ALTER TABLE `users_orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating_rider`
--
ALTER TABLE `rating_rider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `remark`
--
ALTER TABLE `remark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `rs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `res_category`
--
ALTER TABLE `res_category`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users_orders`
--
ALTER TABLE `users_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD CONSTRAINT `security_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
