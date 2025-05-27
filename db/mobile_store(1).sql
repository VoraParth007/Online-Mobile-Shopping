-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 09:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobile_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$.2Fi2fPqcqUYhbK2g3aHBuonNFC8AT3h3GslAoWVsM1K3ciHWCiBW', '2025-03-25 17:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `image`, `category`, `author`, `created_at`) VALUES
(7, 'Top 5 Budget Smartphones in 2025', 'Looking for a feature-packed phone without breaking the bank? We’ve rounded up the top 5 budget smartphones of 2025 that offer great performance, battery life, and cameras at an affordable price.', 'smartphone_budget.jpeg', 'Smartphones', 'Mobile Expert', '2025-04-07 17:37:00'),
(8, 'How to Choose the Best Phone for Gaming', 'Choosing a gaming phone isn’t just about RAM and processors. Discover the key features like refresh rate, cooling systems, and touch response that make a mobile perfect for gaming.', 'gaming_phones_guide.jpeg', 'Gaming Phones', 'Tech Guru', '2025-04-07 17:37:35'),
(9, 'Upcoming Mobile Technologies to Watch in 2025', 'From rollable displays to satellite connectivity, mobile technology is evolving fast. Learn about the upcoming innovations that could redefine smartphones in 2025 and beyond.', 'mobile_tech_2025.jpeg', 'Mobile Trends', 'FutureTech', '2025-04-07 17:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `logo_image`, `category_id`) VALUES
(2, 'Apple', 'uploads/brands/applelogo.png', 1),
(3, 'Samsung', 'uploads/brands/samsunglogo.png', 1),
(4, 'Dell', 'uploads/brands/delllogo.png', 3),
(5, 'Hp', 'uploads/brands/hplogo.png', 3),
(7, 'Realme', 'uploads/brands/realme.png', 1),
(8, 'Vivo', 'uploads/brands/vivo-logo.png', 1),
(14, 'Apple', 'uploads/brands/applelogo.png', 3),
(15, 'Samsung', 'uploads/brands/samsunglogo.png', 5),
(16, 'Apple', 'uploads/brands/applelogo.png', 6),
(17, 'Samsung', 'uploads/brands/samsunglogo.png', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(84, 17, 19, 1, '40000.00', '2025-04-04 08:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(3, 'laptops'),
(1, 'Smartphones'),
(6, 'Smartwatch'),
(5, 'Tablet');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `name`, `email`, `phone`, `password`, `status`, `created_at`) VALUES
(3, 'krunal', 'krunal@gmail.com', '1234567890', '$2y$10$ENfCftzDYDL527fmqdfRKO5u0EKeHW69n5YlaUwE8.NOPAXYXCcpO', 'Approved', '2025-04-03 13:24:00'),
(4, 'sanket', 'sanket@gmail.com', '9087654321', '$2y$10$eoETMq4vcKnoM9dp1.eLYOYgaewIKKkoU7Ka5aV3uFEwpZMgIfcBq', 'Rejected', '2025-04-04 07:59:30'),
(5, 'momo', 'momo@gmail.com', '1234567890', '$2y$10$xy4R.kZPrvhytWtrSXGt9eBO1dNw0vSHYMGLEMUEWcG66wfunRU8e', 'Approved', '2025-04-04 08:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `status` enum('Pending','Responded','Closed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `user_id`, `product_id`, `name`, `email`, `phone`, `message`, `response`, `status`, `created_at`, `updated_at`) VALUES
(1, 17, 23, 'jenish', 'jenish@gmail.com', '1234567890', 'DO YOU HAVE STOCK OF THIS LAPTOP AVAILABLE ?', 'YES , STOCK AVAILABLE', 'Responded', '2025-04-04 08:25:26', '2025-04-04 08:26:42'),
(2, 16, 19, 'Parth', 'Parth@gmail.com', '7228941513', 'I WILL GET ANOTHER COLOR IN THIS MOBILE', 'I ONLY HAVE THIS ONE COLOR ', 'Responded', '2025-04-04 08:28:56', '2025-04-04 08:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('Razorpay') NOT NULL,
  `payment_id` varchar(200) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `delivery_boy_id` int(11) DEFAULT NULL,
  `delivery_status` enum('Picked','Out for Delivery','Delivered') DEFAULT NULL,
  `delivery_otp` varchar(6) DEFAULT NULL,
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `payment_method`, `payment_id`, `total_price`, `created_at`, `status`, `delivery_boy_id`, `delivery_status`, `delivery_otp`, `delivery_charge`) VALUES
(48, 16, 'Razorpay', 'pay_QDrlElBV8nfY0P', '2999.00', '2025-04-01 15:06:58', 'Delivered', NULL, 'Out for Delivery', '786339', '0.00'),
(49, 16, 'Razorpay', 'pay_QE6FOMLHfr7095', '399.00', '2025-04-02 07:03:47', 'Delivered', NULL, 'Out for Delivery', '628673', '0.00'),
(50, 17, 'Razorpay', 'pay_QE6OFKeEFVbZTr', '799.00', '2025-04-02 07:12:10', 'Delivered', NULL, 'Out for Delivery', '411102', '0.00'),
(51, 16, 'Razorpay', 'pay_QEDWKML4RZUF7I', '35049.00', '2025-04-02 08:29:16', 'Delivered', NULL, 'Out for Delivery', '419476', '50.00'),
(52, 16, 'Razorpay', 'pay_QEbLow4hF7Bmyr', '15050.00', '2025-04-03 13:29:22', 'Delivered', 3, 'Out for Delivery', '931106', '50.00'),
(53, 16, 'Razorpay', 'pay_QEc5CN10UKpnat', '849.00', '2025-04-03 14:12:19', 'Delivered', 3, 'Out for Delivery', '113911', '50.00'),
(54, 16, 'Razorpay', 'pay_QEeruxPi5RGCT9', '38050.00', '2025-04-03 16:55:57', 'Delivered', 3, NULL, '627384', '50.00'),
(55, 16, 'Razorpay', 'pay_QEewQercieIBZ6', '3049.00', '2025-04-03 17:00:16', 'Delivered', 3, NULL, '373213', '50.00'),
(56, 16, 'Razorpay', 'pay_QEfDHnJHofEMDW', '3049.00', '2025-04-03 17:16:04', 'Delivered', 3, 'Out for Delivery', '867896', '50.00'),
(57, 16, 'Razorpay', 'pay_QEfMZp7xwWXqON', '15050.00', '2025-04-03 17:24:54', 'Delivered', 3, 'Out for Delivery', '371995', '50.00'),
(58, 16, 'Razorpay', 'pay_QEfOvr2pv0QXJR', '19050.00', '2025-04-03 17:27:06', 'Delivered', 3, 'Out for Delivery', '662254', '50.00'),
(59, 17, 'Razorpay', 'pay_QEzXMr20pVlJGY', '38050.00', '2025-04-04 08:16:43', 'Delivered', 5, 'Out for Delivery', '631053', '50.00'),
(60, 16, 'Razorpay', 'pay_QFqTRlkeaV5uw9', '15050.00', '2025-04-06 16:56:02', 'Delivered', 5, 'Out for Delivery', '899885', '50.00'),
(61, 16, 'Razorpay', 'pay_QGFHawWw0r0QiC', '40050.00', '2025-04-07 17:12:24', 'Delivered', 3, 'Out for Delivery', '691051', '50.00');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total_price`) VALUES
(40, 48, 20, 1, '2999.00', '2999.00'),
(41, 49, 26, 1, '399.00', '399.00'),
(42, 50, 25, 1, '799.00', '799.00'),
(43, 51, 22, 1, '34999.00', '34999.00'),
(44, 52, 21, 1, '15000.00', '15000.00'),
(45, 53, 25, 1, '799.00', '799.00'),
(46, 54, 23, 1, '46999.00', '46999.00'),
(47, 55, 20, 1, '2999.00', '2999.00'),
(48, 56, 20, 1, '2999.00', '2999.00'),
(49, 57, 21, 1, '15000.00', '15000.00'),
(50, 58, 24, 1, '19000.00', '19000.00'),
(51, 59, 23, 1, '38000.00', '38000.00'),
(52, 60, 21, 1, '15000.00', '15000.00'),
(53, 61, 19, 1, '40000.00', '40000.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `availability` enum('In Stock','Out of Stock') DEFAULT 'Out of Stock',
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `features` text NOT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `details` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `new_arrival` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `stock`, `availability`, `rating`, `features`, `specifications`, `details`, `image`, `category_id`, `brand_id`, `new_arrival`) VALUES
(19, 'iPhone 15 Pro', '40000.00', 20, 'In Stock', 4, 'A17 Pro chip, Dynamic Island, Titanium frame', '{}', 'Flagship Apple smartphone with high-end performance.', '1741961453_67d438ede954c.jpg', 1, 2, 1),
(20, 'Samsung Galaxy S24 Ultra', '2999.00', 38, 'In Stock', 5, 'S Pen support, 200MP Camera, Snapdragon 8 Gen 3', '{\"screen\":\"ddf\",\"processor\":\"fdff\",\"ram\":\"8GB\",\"storage\":\"128GB\",\"battery\":\"500mh\",\"camera\":\"48MB\"}', 'Samsung’s best flagship with AI-powered photography.', '1741961234_67d4381219f1c.jpeg', 1, 3, 0),
(21, 'realme 9', '15000.00', 5, 'In Stock', 5, 'look is very good', '{\"screen\":\"16.3cm\",\"processor\":\"snapdragon\",\"ram\":\"8GB\",\"storage\":\"256GB\",\"battery\":\"5000 mAh\",\"camera\":\"fron 16mp\\r\\nRear 108MP+*mp\"}', 'RMX345', '1743237331_67e7b0d32860d.png', 1, 7, 0),
(22, 'MacBook Pro M3 Max', '34999.00', 10, 'In Stock', 5, 'Mini-LED display, ProMotion 120Hz, MagSafe', '{\"screen\":\"16.2-inch Liquid Retina XDR\",\"processor\":\"Apple M3 Max\",\"ram\":\"32GB\",\"storage\":\"1TB \",\"battery\":\"22 Hours\",\"camera\":\"1080p FaceTime HD\"}', 'Apple’s most powerful laptop for professionals.', '1743314897_67e8dfd16f1f7.jpeg', 3, 14, 0),
(23, 'XPS 17', '38000.00', 10, 'In Stock', 4, 'InfinityEdge 4K Display, Carbon Fiber Build', '{\"screen\":\"17-inch UHD+ (3840x2400)\",\"processor\":\"Intel Core i9 13th Gen\",\"ram\":\" 32GB\",\"storage\":\"512GB\",\"battery\":\"14 Hours\",\"camera\":\"1080p Webcam\"}', 'Ultra-thin premium Windows laptop for creators.', '1743315173_67e8e0e5856d9.jpeg', 3, 4, 1),
(24, 'Galaxy Tab S9 Ultra', '19000.00', 5, 'In Stock', 4, 'S Pen, AMOLED 120Hz, Dex Mode', '{\"screen\":\" 14.6-inch Super AMOLED\",\"processor\":\"Snapdragon 8 Gen 2\",\"ram\":\"16GB\",\"storage\":\"512GB\",\"battery\":\" 11200mAh\",\"camera\":\"13MP+8MP Ultra-Wide\"}', 'Samsung’s largest and most powerful tablet.', '1743315460_67e8e20429320.webp', 5, 15, 0),
(25, 'Apple Watch Ultra 2', '799.00', 10, 'In Stock', 5, '2000-nit Display, ECG, Dive Computer', '{\"screen\":\"1.92-inch OLED\",\"processor\":\" Apple S9\",\"ram\":\"2GB\",\"storage\":\"64GB\",\"battery\":\"36 Hours\",\"camera\":\"N\\/A\"}', 'Rugged watch for extreme conditions.', '1743316103_67e8e487620b0.jpeg', 6, 16, 1),
(26, 'Samsung Galaxy Watch 6 Classic', '399.00', 10, 'In Stock', 5, 'Rotating Bezel, Sleep Tracking, ECG', '{\"screen\":\"1.5-inch Super AMOLED\",\"processor\":\"Exynos W930\",\"ram\":\"2GB\",\"storage\":\"64GB\",\"battery\":\"40 Hours\",\"camera\":\"N\\/A\"}', 'Elegant smartwatch with advanced health tracking.', '1743317082_67e8e85a1babb.jpeg', 6, 17, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `order_id`, `product_id`, `rating`, `review`, `created_at`) VALUES
(4, 16, 48, 20, 5, 'good service', '2025-04-01 15:28:37'),
(5, 17, 50, 25, 5, 'good', '2025-04-02 07:14:13'),
(6, 16, 53, 25, 3, 'A cool , Royal looking watch that is comfortable to wear ', '2025-04-03 16:32:44'),
(7, 17, 59, 23, 4, 'NICE LAPTOP', '2025-04-04 08:21:22'),
(8, 16, 60, 21, 3, 'good to game', '2025-04-06 17:33:20'),
(9, 16, 61, 19, 5, 'good', '2025-04-07 17:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('Razorpay') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_reports`
--

INSERT INTO `sales_reports` (`id`, `order_id`, `user_id`, `total_price`, `payment_method`, `created_at`) VALUES
(2, 57, 16, '15050.00', 'Razorpay', '2025-04-03 17:24:54'),
(3, 58, 16, '19050.00', 'Razorpay', '2025-04-03 17:27:07'),
(4, 59, 17, '38050.00', 'Razorpay', '2025-04-04 08:16:44'),
(5, 60, 16, '15050.00', 'Razorpay', '2025-04-06 16:56:02'),
(6, 61, 16, '40050.00', 'Razorpay', '2025-04-07 17:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'Parth@gmail.com', '2025-04-04 00:05:58'),
(5, 'jenish@gmail.com', '2025-04-04 00:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `address`, `city`, `pincode`, `state`, `created_at`) VALUES
(16, 'Parth', 'Parth@gmail.com', '$2y$10$y5g/vcJLtzvn4a6fNoj0GOm1/cdmoLSagd3tMRyBlKgcilRujqL0q', '7228941513', 'B-1006,suman-sathi', 'surat', '395004', 'gujarat', '2025-04-01 14:05:27'),
(17, 'jenish', 'jenish@gmail.com', '$2y$10$YgH3yawOX.0OLDT/5pkNzOS3RHCjHd/s/5xmyZAMTrEyn5ySgelx2', '1234567890', 'B-44 raman nagar soc.', 'surat', '395004', 'gujarat', '2025-04-01 16:35:50'),
(18, 'sanket', 'sanket@gmail.com', '$2y$10$cn5swV.25LacWD4rmllhFeE9Q9cSbGDn2xr9hPpWoqhA2A7EBfusW', '9087654323', '90,shiv chhaya soc,', 'surat', '395004', 'gujarat', '2025-04-01 16:38:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_delivery_boy` (`delivery_boy_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inquiries_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_delivery_boy` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD CONSTRAINT `sales_reports_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
