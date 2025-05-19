-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 10:47 AM
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
-- Database: `furnicraft`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `quantity`, `price`, `sale_price`, `created_at`, `updated_at`) VALUES
(1, NULL, '01cr01cmvrlvp0t9ufr5bskis4', 31, 5, 20000.00, 18000.00, '2025-05-05 08:27:06', '2025-05-05 08:28:28'),
(3, NULL, 'guest_20250505_45325', 28, 7, 15999.99, NULL, '2025-05-05 08:33:42', '2025-05-05 08:34:16'),
(7, NULL, 'guest_20250505_72454', 30, 4, 24999.99, NULL, '2025-05-05 08:39:05', '2025-05-05 08:46:24'),
(9, NULL, 'guest_20250505_17214', 30, 1, 24999.99, NULL, '2025-05-05 08:49:49', '2025-05-05 08:49:49'),
(18, NULL, 'jl40u3g0vt9s9r543gsjl3tfbk', 30, 1, 0.00, NULL, '2025-05-05 12:06:22', '2025-05-05 12:06:22'),
(19, NULL, 'jl40u3g0vt9s9r543gsjl3tfbk', 26, 1, 0.00, NULL, '2025-05-05 12:10:34', '2025-05-05 12:10:34'),
(20, NULL, 'jl40u3g0vt9s9r543gsjl3tfbk', 29, 1, 0.00, NULL, '2025-05-05 12:27:22', '2025-05-05 12:27:22'),
(25, NULL, 'guest_20250506_49698', 29, 4, 22299.99, NULL, '2025-05-06 10:31:28', '2025-05-06 11:28:22'),
(26, NULL, 'guest_20250506_49698', 30, 1, 24999.99, 24000.00, '2025-05-06 11:28:26', '2025-05-06 11:28:26'),
(43, NULL, 'guest_20250515_72532', 29, 1, 22299.99, 22000.00, '2025-05-15 05:44:57', '2025-05-15 05:44:57');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `status`, `created_at`, `updated_at`) VALUES
(36, 'Living Room', 'cat_681356fec1e876.79800305.jfif', 'active', '2025-04-29 06:32:37', '2025-05-13 08:35:08'),
(37, 'Bedroom', 'cat_682491b4426546.01033575.jfif', 'active', '2025-04-29 06:32:37', '2025-05-14 12:51:00'),
(38, 'Dining Room', 'cat_6813571a1b75e5.70666756.jfif', 'active', '2025-04-29 06:32:37', '2025-05-13 08:35:24'),
(39, 'Office', 'cat_6813570cd43536.06156288.jfif', 'active', '2025-04-29 06:32:37', '2025-05-13 08:34:58'),
(40, 'Outdoor', 'cat_6824912868c901.07740814.jfif', 'active', '2025-04-29 06:32:37', '2025-05-14 12:48:40'),
(44, 'Storage', 'category_1746681211.jfif', 'active', '2025-05-08 05:13:31', '2025-05-13 08:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Devaxi', 'techturtle2024@gamil.com', 'hello', '15', '', '2025-05-07 06:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `billing_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `payment_status`, `shipping_address`, `billing_address`, `notes`, `created_at`, `updated_at`) VALUES
(100001, 6, 'ORD-68148d272952d', 751999.82, 'processing', 'pending', 'Nikol', 'Nikol', '', '2025-05-02 05:45:19', '2025-05-02 09:47:32'),
(100002, 9, 'ORD-6814977218dfe', 24999.99, 'shipped', 'pending', 'Nikol', 'Nikol', '', '2025-05-02 06:29:14', '2025-05-03 04:37:05'),
(100003, 8, 'ORD-6815d24d3af9f', 255999.96, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-03 04:52:37', '2025-05-03 04:52:37'),
(100004, 8, 'ORD-6815d2963cf6b', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-03 04:53:50', '2025-05-03 04:53:50'),
(100005, 6, 'ORD-6815d33843b46', 54000.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-03 04:56:32', '2025-05-03 04:56:32'),
(100006, 8, 'ORD-681871ac5303f', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:37:08', '2025-05-05 04:37:08'),
(100007, 8, 'ORD-681871ca6a95d', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:37:38', '2025-05-05 04:37:38'),
(100008, 6, 'ORD-681871db3437b', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:37:55', '2025-05-05 04:37:55'),
(100009, 6, 'ORD-68187202b6d2a', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:38:34', '2025-05-05 04:38:34'),
(100010, 6, 'ORD-6818734e0c923', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:44:06', '2025-05-05 04:44:06'),
(100011, 6, 'ORD-681874514c7a8', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:48:25', '2025-05-05 04:48:25'),
(100012, 6, 'ORD-6818751393c8f', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:51:39', '2025-05-05 04:51:39'),
(100013, 6, 'ORD-681875a1a41dc', 0.00, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:54:01', '2025-05-13 12:10:56'),
(100014, 6, 'ORD-6818761e6f95a', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:56:06', '2025-05-05 04:56:06'),
(100015, 6, 'ORD-68187638c03de', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:56:32', '2025-05-05 04:56:32'),
(100016, 8, 'ORD-68187673b6348', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:57:31', '2025-05-05 04:57:31'),
(100017, 8, 'ORD-681876ec2468e', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 04:59:32', '2025-05-05 04:59:32'),
(100018, 6, 'ORD-6818773903753', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:00:49', '2025-05-05 05:00:49'),
(100019, 8, 'ORD-681877b3c4cf2', 24999.99, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:02:51', '2025-05-05 05:02:51'),
(100020, 6, 'ORD-681877f57d4c3', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:03:57', '2025-05-05 05:03:57'),
(100021, 8, 'ORD-6818780ebf148', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:04:22', '2025-05-05 05:04:22'),
(100022, 6, 'ORD-68187863b7d82', 80599.98, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:05:47', '2025-05-05 05:05:47'),
(100023, 6, 'ORD-681878cd72027', 22000.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:07:33', '2025-05-05 05:07:33'),
(100024, 6, 'ORD-6818793cdaad4', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:09:24', '2025-05-05 05:09:24'),
(100025, 8, 'ORD-6818797127f00', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:10:17', '2025-05-05 05:10:17'),
(100026, 10, 'ORD-68187accaa49c', 0.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:16:04', '2025-05-05 05:16:04'),
(100027, 6, 'ORD-68187aeb18b73', 0.00, 'processing', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:16:35', '2025-05-13 12:21:55'),
(100028, 8, 'ORD-68187b1d37b84', 47999.97, 'processing', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:17:25', '2025-05-13 12:20:54'),
(100043, 8, 'ORD-6818822a34c25', 0.00, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 05:47:30', '2025-05-13 12:17:53'),
(100044, 8, 'ORD-68188915f343b', 74999.97, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 06:17:01', '2025-05-13 12:17:42'),
(100045, 6, 'ORD-6818899ddcf95', 75000.00, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 06:19:17', '2025-05-13 12:17:32'),
(100046, 8, 'ORD-6818a1913b301', 120000.00, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-05 08:01:29', '2025-05-07 11:13:49'),
(100047, 8, 'ORD-6819e4f773d04', 40000.00, 'delivered', 'pending', 'Nikol', 'Nikol', '', '2025-05-06 07:01:19', '2025-05-07 11:09:45'),
(100048, 6, 'ORD-682590138f3f5', 75000.00, 'pending', 'pending', 'New naroda', 'New naroda', '', '2025-05-15 03:26:19', '2025-05-15 03:26:19'),
(100049, 10, 'ORD-6825b45fb8550', 54000.00, 'pending', 'pending', 'Nikol', 'Nikol', '', '2025-05-15 06:01:11', '2025-05-15 06:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 100001, 28, 15, 20000.00, '2025-05-02 09:40:01'),
(16, 100043, 29, 1, 22299.99, '2025-05-05 09:17:30'),
(17, 100044, 30, 3, 24999.99, '2025-05-05 09:47:02'),
(18, 100045, 26, 3, 25000.00, '2025-05-05 09:49:17'),
(19, 100046, 31, 6, 20000.00, '2025-05-05 11:31:29'),
(20, 100047, 31, 2, 20000.00, '2025-05-06 10:31:19'),
(21, 100048, 36, 3, 25000.00, '2025-05-15 06:56:19'),
(22, 100049, 35, 2, 27000.00, '2025-05-15 09:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `sale_price`, `stock`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(26, 'Modern Fabric Sofa Set', 'Chic and comfortable, this modern fabric sofa set brings contemporary style and cozy relaxation to any living space', 'product_1746530743.jfif', 25000.00, 22000.00, 10, 36, 'active', '2025-04-29 06:32:37', '2025-05-07 05:47:53'),
(28, '6-Seater Dining Table Set', 'Spacious and stylish, this 6-seater dining table set offers the perfect centerpiece for family meals, casual gatherings, and elegant entertaining.', 't.jfif', 15999.99, 15999.99, 8, 38, 'active', '2025-04-29 06:32:37', '2025-05-07 05:48:57'),
(29, 'Modern Office Work Desk', 'A perfect blend of style and efficiency, this modern office work desk features a streamlined design, durable materials, and smart organization for today’s professional spaces.', '1746095541_Home Office Furniture Sets.jfif', 22299.99, 22000.00, 12, 40, 'active', '2025-04-29 06:32:38', '2025-05-07 05:46:03'),
(30, 'Weatherproof Outdoor Lounge', 'Durable and stylish, this weatherproof outdoor lounge is designed to withstand the elements while offering ultimate comfort and modern elegance for any patio, garden, or poolside space.', 'product_1746530826.jfif', 24999.99, 24000.00, 6, 40, 'active', '2025-04-29 06:32:38', '2025-05-07 05:43:22'),
(31, 'Modern Office Work Desk', 'Sleek and functional, this modern office work desk offers a clean design and smart features to enhance productivity and complement any contemporary workspace.', 'product_1746530786.jfif', 20000.00, 18000.00, 10, 39, 'active', '2025-04-29 07:07:17', '2025-05-07 05:44:52'),
(35, 'Bedroom Furniture', 'Create a relaxing retreat with bedroom furniture that blends comfort, style, and functionality to suit any personal space and design', 'product_1746520876.jfif', 27000.00, 25000.00, 15, 37, 'active', '2025-05-06 08:41:16', '2025-05-07 05:44:10'),
(36, 'TV Stand/Media Console', 'A low cabinet or table designed to hold a television, with shelves or drawers for media devices, DVDs, or gaming consoles. Often features cable management holes and can be made of wood, metal, or glass.', 'product_1747140865.jfif', 25000.00, 23000.00, 15, 44, 'active', '2025-05-13 12:54:25', '2025-05-13 12:54:25');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `city`, `state`, `zip_code`, `image`, `created_at`, `updated_at`, `role`, `status`) VALUES
(3, 'Admin', 'admin@furnicraft.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, '', '2025-04-29 06:32:38', '2025-04-29 06:32:38', 'admin', 'active'),
(5, 'Ajay', 'user@furnicraft.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9562418596', NULL, NULL, NULL, NULL, '', '2025-04-29 06:32:38', '2025-05-01 10:05:32', 'user', 'inactive'),
(6, 'Ajay', 'ajay@gmail.com', '$2y$10$LA.1Hf40i2amkUy10HJxwemSP8yYQMc7fMaB3Cs9//KY0EV7pIphG', '7895625412', 'Bapunagar', NULL, NULL, NULL, 'assets/images/avatar/user_6.jpg', '2025-04-29 09:38:34', '2025-05-15 09:42:38', 'user', 'active'),
(7, 'Devaxi', 'devaxi@furnicraft.com', '$2y$10$nK982FUbJargdLh5UKpEZ.A75PUsZWgH83syzXkD9IT6zbvlet0Vu', '1234560789', 'Nikol', 'Ahm', 'Guj', '789654', '', '2025-05-01 11:20:18', '2025-05-01 11:20:18', 'user', 'active'),
(8, 'Devaxi', 'techturtle2024@gamil.com', '$2y$10$MwBBbO5hKu4PSV2RObjCkOMEWlbccrEbc0k36T1L.VKeScPBWKYV2', '1234560789', 'Nikol', 'Ahm', 'Guj', '789654', '', '2025-05-01 11:21:22', '2025-05-01 11:21:22', 'user', 'active'),
(9, 'Ajay', 'ajay@gamil.com', '', '1234560789', 'Nikol', 'Ahm', 'Guj', '789654', '', '2025-05-02 09:59:14', '2025-05-02 09:59:14', '', 'active'),
(10, 'devaxi', 'ajay3@gmail.com', '', '1024785963', 'Nikol', 'Ahmedabad', 'Gujarat', '789654', '', '2025-05-05 08:46:04', '2025-05-05 08:46:04', '', 'active'),
(11, 'Sujal Gohel', 'sujal@gmail.com', '$2y$10$qBXA3rzqLtI6R4BXgXYRFe0K8RYajSeVFWqPfjB/0UxRYIQ30G9He', '1024785963', 'Isanpur', 'Ahmedabad', 'Gujarat', '382345', '', '2025-05-15 05:59:50', '2025-05-15 05:59:50', 'user', 'active'),
(12, 'MAYUR J PANCHAL', 'mayur@gmail.com', '$2y$10$eFypDhsVx6hFvt29mPRP0ujMq3wgbkG77UwpDtzWikvAvahG7y6qm', '1234560789', 'Shop 18, Mahesh Tenament, Near Holi Child School Opposite Hirawadi BRTS Bus Stop, Thakkarbapa Nagar, Bapunagar, Ahmedabad-382350, Gujarat, India', 'AHMEDABAD', 'GUJARAT', '382345', 'user_1747303133.jpg', '2025-05-15 09:58:54', '2025-05-15 09:58:54', 'user', 'active'),
(13, 'ajay', 'raju@gmail.com', '$2y$10$RYdqacfzu8IunkgwEXFnW.bx93UtlvSJI5Yb22uaZFkCr5Odtftga', '1234560789', 'Nikol', 'Ahmedabad', 'Gujarat', '789654', 'user_1747303218.jpg', '2025-05-15 10:00:18', '2025-05-15 10:00:18', 'user', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_ibfk_1` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100050;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
