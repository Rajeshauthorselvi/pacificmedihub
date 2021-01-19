-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2020 at 07:54 PM
-- Server version: 5.7.32-0ubuntu0.16.04.1
-- PHP Version: 7.3.24-3+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pacificmedihub`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Test', '1608279276.png', 0, '2020-12-18 02:44:36', '2020-12-27 22:26:18', 1, '2020-12-28 03:56:18'),
(2, 'Medihub', '1608613174.png', 1, '2020-12-27 22:27:59', '2020-12-27 22:27:59', 0, NULL),
(3, 'Surgical EQPO', '1608720327.png', 1, '2020-12-27 22:26:14', '2020-12-27 22:26:14', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `show_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not show, 1 - show',
  `display_order` int(11) DEFAULT NULL,
  `search_engine_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_category_id`, `name`, `image`, `description`, `published`, `show_home`, `display_order`, `search_engine_name`, `meta_title`, `meta_keyword`, `meta_description`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 2, 'Low testosterone', '1608550537.png', 'Low testosterone', 1, 1, 1, 'Low testosterone', 'Low testosterone', 'Low testosterone', 'Low testosterone', '2020-12-21 06:05:37', '2020-12-27 22:27:07', 0, NULL),
(2, NULL, 'Men\'s Health', '1608719428.png', 'Men', 1, 0, 1, 'mens-health-care', 'mens-health-care', 'mens-health-care', 'mens-health-care', '2020-12-23 05:00:28', '2020-12-23 05:00:28', 0, NULL),
(3, 2, 'Hair Loss', '1608719597.png', 'Hair', 1, 0, 1, 'hair-loss', 'hair-loss', 'hair-loss', 'hair-loss', '2020-12-23 05:03:17', '2020-12-27 22:27:19', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `commission_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `commission_name`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Base Commission', 1, '2020-12-29 21:29:22', '2020-12-29 21:29:22', 0, NULL),
(2, 'Product Commission', 1, '2020-12-29 21:29:22', '2020-12-29 21:29:22', 0, NULL),
(3, 'Target Commission', 1, '2020-12-29 21:29:22', '2020-12-29 21:29:22', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commission_values`
--

CREATE TABLE `commission_values` (
  `id` int(10) UNSIGNED NOT NULL,
  `commission_id` int(10) UNSIGNED NOT NULL,
  `commission_type` enum('p','f') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'P->Percentage, F->Fixed',
  `commission_value` double(8,2) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commission_values`
--

INSERT INTO `commission_values` (`id`, `commission_id`, `commission_type`, `commission_value`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'p', 12.00, 1, NULL, '2020-12-30 07:43:41', 0, NULL),
(2, 2, 'f', 12.00, 0, NULL, '2020-12-30 13:13:58', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zone`
--

CREATE TABLE `delivery_zone` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_code` int(10) UNSIGNED NOT NULL,
  `delivery_fee` double(8,2) UNSIGNED NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_zone`
--

INSERT INTO `delivery_zone` (`id`, `post_code`, `delivery_fee`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 2001, 12.00, 1, '2020-12-30 07:24:52', '2020-12-30 07:26:32', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_12_17_044110_create_roles_table', 1),
(2, '2020_12_17_044127_create_users_table', 1),
(3, '2020_12_18_051749_create_brands_table', 2),
(4, '2020_12_18_051825_create_product_units_table', 2),
(5, '2020_12_18_051856_create_categories_table', 2),
(6, '2020_12_22_115718_create_vendors_table', 3),
(7, '2020_12_22_120057_create_vendor_poc_table', 3),
(8, '2020_12_23_102030_create_products_table', 4),
(9, '2020_12_23_142154_create_product_variants_table', 4),
(10, '2020_12_23_142213_create_product_images_table', 4),
(11, '2020_12_28_032601_create_product_variant_vendors_table', 5),
(12, '2020_12_30_060530_create_delivery_zone_table', 6),
(13, '2020_12_30_081312_create_commissions_table', 6),
(14, '2020_12_30_082128_create_commission_values_table', 6),
(15, '2020_12_30_122056_create_product_options_table', 7),
(16, '2020_12_30_122117_create_product_option_values_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `brand_id` bigint(20) DEFAULT NULL,
  `main_image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_description` text COLLATE utf8mb4_unicode_ci,
  `treatment_information` text COLLATE utf8mb4_unicode_ci,
  `dosage_instructions` text COLLATE utf8mb4_unicode_ci,
  `alert_quantity` int(11) DEFAULT NULL,
  `commission_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - percentage(%), 1 - fixed(amount)',
  `commission_value` double(8,2) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `show_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not show, 1 - show',
  `search_engine_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `sku`, `category_id`, `brand_id`, `main_image`, `short_description`, `long_description`, `treatment_information`, `dosage_instructions`, `alert_quantity`, `commission_type`, `commission_value`, `published`, `show_home`, `search_engine_name`, `meta_title`, `meta_keyword`, `meta_description`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Surgical products', 'sp', '324ef', 3, 2, '1609132572.jpeg', 'Surgical products', '<p>Surgical products</p>', '<p>Surgical products</p>', '<p>Surgical products</p>', 10, 0, 10.00, 1, 1, 'surgical-products', 'surgical-products', 'surgical-products', 'surgical-products', '2020-12-27 23:46:12', '2020-12-28 05:43:53', 0, NULL),
(13, 'Surgical products2', 'sdf2', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, '2020-12-28 05:39:22', '2020-12-28 05:39:22', 0, NULL),
(14, 'Test 123', 'sdf', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, '2020-12-30 04:20:14', '2020-12-30 04:20:14', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `name`, `display_order`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'product_img3.jpeg', 1, 0, NULL),
(2, 1, 'product_img2.jpeg', 2, 0, NULL),
(3, 1, 'product_img1.jpeg', 3, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_options`
--

CREATE TABLE `product_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` bigint(20) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_options`
--

INSERT INTO `product_options` (`id`, `option_name`, `display_order`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Size', 1, 1, '2020-12-31 03:24:27', '2020-12-31 08:54:27', 0, NULL),
(2, 'Color', 2, 1, '2020-12-31 03:24:37', '2020-12-31 03:24:50', 0, NULL),
(3, 'Weight', 3, 1, '2020-12-31 04:08:42', '2020-12-31 09:38:42', 0, NULL),
(4, 'Test', 4, 1, '2020-12-31 04:12:10', '2020-12-31 09:42:10', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_option_values`
--

CREATE TABLE `product_option_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_id` bigint(20) NOT NULL,
  `option_value` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_option_values`
--

INSERT INTO `product_option_values` (`id`, `option_id`, `option_value`, `display_order`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'S', 1, '2020-12-31 03:25:28', '2020-12-31 08:55:28', 0, NULL),
(2, 1, 'M', 2, '2020-12-31 03:25:38', '2020-12-31 08:55:38', 0, NULL),
(3, 1, 'L', 3, '2020-12-31 03:26:20', '2020-12-31 08:56:20', 0, NULL),
(4, 1, 'XL', 4, '2020-12-31 03:26:35', '2020-12-31 08:56:35', 0, NULL),
(5, 1, 'XXL', 5, '2020-12-31 03:26:46', '2020-12-31 08:56:46', 0, NULL),
(6, 2, 'Red', 1, '2020-12-31 03:26:57', '2020-12-31 03:27:13', 0, NULL),
(7, 2, 'Blue', 2, '2020-12-31 03:27:23', '2020-12-31 08:57:23', 0, NULL),
(8, 2, 'Green', 3, '2020-12-31 03:27:33', '2020-12-31 08:57:33', 0, NULL),
(9, 2, 'Yellow', 4, '2020-12-31 03:27:44', '2020-12-31 03:27:49', 0, NULL),
(10, 3, '5ml', 1, '2020-12-31 06:21:29', '2020-12-31 11:51:29', 0, NULL),
(11, 3, '10ml', 2, '2020-12-31 06:21:38', '2020-12-31 11:51:38', 0, NULL),
(12, 3, '15ml', 3, '2020-12-31 06:21:51', '2020-12-31 11:51:51', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not published, 1 - published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `unit_name`, `unit_code`, `published`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Test', 'ts', 0, '2020-12-18 04:15:47', '2020-12-23 05:14:15', 1, '2020-12-23 10:44:15'),
(2, 'testing1', 'tg3', 0, '2020-12-21 23:33:48', '2020-12-21 23:41:27', 1, '2020-12-22 05:11:27'),
(3, 'Kilograms', 'Kg', 1, '2020-12-23 05:11:47', '2020-12-23 05:13:02', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `base_price` double(8,2) DEFAULT NULL,
  `retail_price` double(8,2) DEFAULT NULL,
  `minimum_selling_price` double(8,2) DEFAULT NULL,
  `product_option_id1` int(11) DEFAULT NULL,
  `product_option_value_id1` int(11) DEFAULT NULL,
  `product_option_id2` int(11) DEFAULT NULL,
  `product_option_value_id2` int(11) DEFAULT NULL,
  `product_option_id3` int(11) DEFAULT NULL,
  `product_option_value_id3` int(11) DEFAULT NULL,
  `display_variant` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not display, 1 - display',
  `display_order` bigint(20) DEFAULT NULL,
  `stock_quantity` bigint(20) DEFAULT NULL,
  `vendor_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_vendors`
--

CREATE TABLE `product_variant_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `product_variant_id` bigint(20) DEFAULT NULL,
  `variant_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_vendors`
--

INSERT INTO `product_variant_vendors` (`id`, `product_id`, `product_variant_id`, `variant_id`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(15, 13, 16, 1),
(16, 13, 16, 4),
(17, 13, 17, 1),
(18, 14, 18, 1),
(19, 14, 19, 4);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`, `is_delete`, `deleted_at`) VALUES
(1, 'Super Admin', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(2, 'Admin', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(3, 'Logistics', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(4, 'Sales Rep', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(5, 'Employee', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(6, 'Delivery Person', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL),
(7, 'Customer', '2020-12-17 06:34:00', '2020-12-17 06:39:05', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'M- male, F - female',
  `dob` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not visible, 1 - visible',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `gender`, `dob`, `email`, `contact_number`, `profile_image`, `password`, `remember_token`, `last_login`, `created_at`, `updated_at`, `status`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'Super', 'Admin', 'Male', NULL, 'admin@medihub.com', NULL, 'admin.png', '$2y$10$nbVoPSxH83sRYacDLzLT2e7FYZmb0TCSiRs87goSpe633mMkqNs/i', NULL, NULL, '2020-12-17 10:27:23', '2020-12-17 13:40:36', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uen` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paynow_contact_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_place` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `others` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not approved, 1 - approved',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - is deleted',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `code`, `uen`, `name`, `email`, `contact_number`, `logo_image`, `gst_no`, `gst_image`, `pan_no`, `pan_image`, `address_line1`, `address_line2`, `post_code`, `country`, `state`, `city`, `account_name`, `account_number`, `bank_name`, `bank_branch`, `paynow_contact_number`, `bank_place`, `others`, `created_at`, `updated_at`, `status`, `is_deleted`, `deleted_at`) VALUES
(1, NULL, 'Vendor 1', 'Vendor 1', 'Vendor1@gmail.com', '9874657483', '1608654371.jpeg', 'Vendor123456', '1608654371.png', 'Vendor123', '1608654371.png', 'Vendor1 address1', 'Vendor1 address2', '0900', 'India', 'TamilNadu', 'Vellore', 'Vendor1 Acc', '2334343233', 'TestBank1', 'Test1branch', '32432421', 'SVC', 'sewrr3 sdfsd sefsdsd werfwq vwdascsc reyeyr jikuyij i7ihgm', '2020-12-22 10:56:11', '2020-12-28 04:02:36', 0, 0, NULL),
(4, NULL, 'Vendor 2', 'Vendor 2', 'Vendor2@gmailw.com', '9874657483', '1608654371.jpeg', 'Vendor234567', '1608654371.png', 'Vendor234', '1608654371.png', 'Vendor2 address2', 'Vendor2 address2', '0900', 'India', 'TamilNadu', 'Vellore', 'Vendor2 Acc', '2334343233', 'TestBank2', 'Test2branch', '32432421', 'BYG', 'sewrr3 sdfsd sefsdsd werfwq vwdascsc reyeyr jikuyij i7ihgm', '2020-12-22 10:56:11', '2020-12-28 04:08:21', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_poc`
--

CREATE TABLE `vendor_poc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_poc`
--

INSERT INTO `vendor_poc` (`id`, `vendor_id`, `name`, `email`, `contact_no`) VALUES
(1, 1, 'testpoc', 'testpoc@gmail.com', 'rew'),
(2, 1, 'testpoc2', 'testpoc2@gmail.com', 'res2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commission_values`
--
ALTER TABLE `commission_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commission_values_commission_id_foreign` (`commission_id`);

--
-- Indexes for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_option_values`
--
ALTER TABLE `product_option_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variant_vendors`
--
ALTER TABLE `product_variant_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_email_unique` (`email`);

--
-- Indexes for table `vendor_poc`
--
ALTER TABLE `vendor_poc`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `commission_values`
--
ALTER TABLE `commission_values`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_option_values`
--
ALTER TABLE `product_option_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_variant_vendors`
--
ALTER TABLE `product_variant_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `vendor_poc`
--
ALTER TABLE `vendor_poc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `commission_values`
--
ALTER TABLE `commission_values`
  ADD CONSTRAINT `commission_values_commission_id_foreign` FOREIGN KEY (`commission_id`) REFERENCES `commissions` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
