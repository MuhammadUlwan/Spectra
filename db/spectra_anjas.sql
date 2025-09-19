-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 05:47 AM
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
-- Database: `spectra`
--

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_commissions`
--

CREATE TABLE `affiliate_commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `referred_user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('deposit','profit','investment') NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `percentage` decimal(8,4) NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `meta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `affiliate_commissions`
--

INSERT INTO `affiliate_commissions` (`id`, `user_id`, `referred_user_id`, `type`, `amount`, `percentage`, `level`, `meta`, `created_at`, `updated_at`) VALUES
(1, 10, 11, 'investment', '50.00', '5.0000', 1, '{\"meta\":\"investment_id:25\"}', '2025-09-15 18:50:31', '2025-09-15 18:50:31'),
(2, 11, 12, 'investment', '15.00', '5.0000', 1, '{\"meta\":\"investment_id:26\"}', '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(3, 10, 12, 'investment', '9.00', '3.0000', 2, '{\"meta\":\"investment_id:26\"}', '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(4, 12, 13, 'investment', '250.00', '5.0000', 1, '{\"meta\":\"investment_id:27\"}', '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(5, 11, 13, 'investment', '150.00', '3.0000', 2, '{\"meta\":\"investment_id:27\"}', '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(6, 10, 13, 'investment', '50.00', '1.0000', 3, '{\"meta\":\"investment_id:27\"}', '2025-09-16 02:18:32', '2025-09-16 02:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `image_path`, `active`, `order`, `created_at`, `updated_at`) VALUES
(7, 'TEST1', 'announcements/DjWD5y9wHf_1757775166.jpg', 1, 0, '2025-09-13 14:52:48', '2025-09-13 14:52:48'),
(8, NULL, 'announcements/ElulC8ZaU9_1757775184.jpg', 1, 0, '2025-09-13 14:53:04', '2025-09-13 15:30:09'),
(9, NULL, 'announcements/5zQDn47WHO_1757775195.jpg', 1, 0, '2025-09-13 14:53:15', '2025-09-13 15:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('sponsor','profit_sharing','bonus') NOT NULL,
  `source_investment` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','active','completed','cancelled') NOT NULL DEFAULT 'pending',
  `proof_transfer` varchar(255) DEFAULT NULL,
  `validated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `package_id`, `amount`, `start_date`, `end_date`, `status`, `proof_transfer`, `validated_by`, `validated_at`, `created_at`, `updated_at`) VALUES
(24, 9, 2, '500.00', '2025-09-15', '2026-03-14', 'pending', 'deposits/b0bd0414-3922-409e-8417-dbe434ca90a3.png', 1, '2025-09-15 15:37:13', '2025-09-15 15:36:49', '2025-09-15 15:37:13'),
(25, 11, 3, '1000.00', '2025-09-16', '2025-10-16', 'active', 'deposits/353014da-aee6-442b-b3b2-98a3abe9bfb9.jpg', 1, '2025-09-15 18:50:31', '2025-09-15 18:31:35', '2025-09-15 18:50:31'),
(26, 12, 1, '300.00', '2025-09-16', '2026-03-15', 'active', 'deposits/86eb8733-ee92-4e98-a8ff-04331ff0679a.png', 1, '2025-09-15 18:52:42', '2025-09-15 18:52:33', '2025-09-15 18:52:42'),
(27, 13, 4, '5000.00', '2025-09-16', '2026-03-15', 'active', 'deposits/a1492e41-286a-450d-bcd1-d8341813612e.jpg', 1, '2025-09-16 02:18:32', '2025-09-16 02:17:50', '2025-09-16 02:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `investment_packages`
--

CREATE TABLE `investment_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `duration_months` int(11) NOT NULL DEFAULT 6,
  `profit_percent` int(11) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_packages`
--

INSERT INTO `investment_packages` (`id`, `name`, `amount`, `duration_months`, `profit_percent`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'USD 300', 300, 6, 8, 'USD', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(2, 'USD 500', 500, 6, 8, 'USD', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(3, 'USD 1000', 1000, 1, 8, 'USD', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(4, 'USD 5000', 5000, 6, 8, 'USD', '2025-09-11 21:04:40', '2025-09-11 21:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `investment_profits`
--

CREATE TABLE `investment_profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `profit_amount` decimal(15,2) NOT NULL,
  `profit_percent` decimal(5,2) NOT NULL,
  `scheduled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','success') NOT NULL DEFAULT 'pending',
  `proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_profits`
--

INSERT INTO `investment_profits` (`id`, `investment_id`, `package_name`, `profit_amount`, `profit_percent`, `scheduled_at`, `status`, `proof`, `created_at`, `updated_at`) VALUES
(31, 25, 'USD 1000', '40.00', '4.00', '2025-09-30 18:46:02', 'pending', NULL, '2025-09-15 18:46:02', '2025-09-15 18:46:02'),
(32, 25, 'USD 1000', '40.00', '4.00', '2025-10-15 18:46:02', 'pending', NULL, '2025-09-15 18:46:02', '2025-09-15 18:46:02'),
(33, 25, 'USD 1000', '40.00', '4.00', '2025-09-30 18:50:31', 'pending', NULL, '2025-09-15 18:50:31', '2025-09-15 18:50:31'),
(34, 25, 'USD 1000', '40.00', '4.00', '2025-10-15 18:50:31', 'pending', NULL, '2025-09-15 18:50:31', '2025-09-15 18:50:31'),
(35, 26, 'USD 300', '12.00', '4.00', '2025-09-30 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(36, 26, 'USD 300', '12.00', '4.00', '2025-10-15 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(37, 26, 'USD 300', '12.00', '4.00', '2025-10-30 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(38, 26, 'USD 300', '12.00', '4.00', '2025-11-14 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(39, 26, 'USD 300', '12.00', '4.00', '2025-11-29 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(40, 26, 'USD 300', '12.00', '4.00', '2025-12-14 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(41, 26, 'USD 300', '12.00', '4.00', '2025-12-29 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(42, 26, 'USD 300', '12.00', '4.00', '2026-01-13 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(43, 26, 'USD 300', '12.00', '4.00', '2026-01-28 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(44, 26, 'USD 300', '12.00', '4.00', '2026-02-12 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(45, 26, 'USD 300', '12.00', '4.00', '2026-02-27 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(46, 26, 'USD 300', '12.00', '4.00', '2026-03-14 18:52:42', 'pending', NULL, '2025-09-15 18:52:42', '2025-09-15 18:52:42'),
(47, 27, 'USD 5000', '200.00', '4.00', '2025-10-01 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(48, 27, 'USD 5000', '200.00', '4.00', '2025-10-16 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(49, 27, 'USD 5000', '200.00', '4.00', '2025-10-31 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(50, 27, 'USD 5000', '200.00', '4.00', '2025-11-15 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(51, 27, 'USD 5000', '200.00', '4.00', '2025-11-30 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(52, 27, 'USD 5000', '200.00', '4.00', '2025-12-15 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(53, 27, 'USD 5000', '200.00', '4.00', '2025-12-30 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(54, 27, 'USD 5000', '200.00', '4.00', '2026-01-14 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(55, 27, 'USD 5000', '200.00', '4.00', '2026-01-29 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(56, 27, 'USD 5000', '200.00', '4.00', '2026-02-13 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(57, 27, 'USD 5000', '200.00', '4.00', '2026-02-28 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32'),
(58, 27, 'USD 5000', '200.00', '4.00', '2026-03-15 02:18:32', 'pending', NULL, '2025-09-16 02:18:32', '2025-09-16 02:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_09_12_034909_create_users_table', 1),
(4, '2025_09_12_034931_create_investment_packages_table', 1),
(5, '2025_09_12_034942_create_investments_table', 1),
(6, '2025_09_12_034957_create_commissions_table', 1),
(7, '2025_09_12_035016_create_settings_table', 1),
(8, '2025_09_12_035027_create_referrals_table', 1),
(9, '2025_09_12_035039_create_withdrawals_table', 1),
(10, '2025_09_12_035049_create_notifications_table', 1),
(11, '2025_09_12_035058_create_sessions_table', 1),
(12, '2025_09_12_072538_create_announcements_table', 2),
(13, '2025_09_12_074239_create_profits_table', 3),
(14, '2025_09_13_101707_create_user_preferences_table', 4),
(15, '2025_09_15_214455_create_investment_profits_table', 5),
(16, '2025_09_16_003349_add_referral_to_users', 6),
(17, '2025_09_16_003413_create_affiliate_commissions', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('deposit','profit','withdraw','reminder') NOT NULL,
  `message` text NOT NULL,
  `sent_via` enum('telegram','email') NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profits`
--

CREATE TABLE `profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `profit_amount` bigint(20) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sponsor_id` bigint(20) UNSIGNED NOT NULL,
  `referral_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ICJUu5V772mOXCVyzHx7M1qIkkxHKASLZ8S6h7ay', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRTBMQ0dhZUJObTFDa2hnSXUxYnByTGE1R3NhRnFWVU9IM0tXZTRGZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kZXBvc2l0LXBhY2thZ2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1757994417),
('x2rR6AZl3WWZAe7f40h2qYbO8bQIQ4D9FT2Mh29c', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY1ZMb25XWXViQWdJcVIyTGNhd2dzNk15N0h2bk5ySzVzMEgzdGsxNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaW52ZXN0b3IvcHJvZml0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO30=', 1757994357);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key_name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'profit_percent', '7.5', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(2, 'sponsor_fee_direct', '7.5', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(3, 'sponsor_fee_indirect', '2.5', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(4, 'profit_sharing_level1', '1.25', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(5, 'profit_sharing_level2', '0.75', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(6, 'profit_sharing_level3', '0.5', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(7, 'bonus_target_omset', '10000', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(8, 'bonus_profit_extra', '1.25', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(10, 'url_academy', '', '2025-09-13 04:19:07', '2025-09-15 02:36:00'),
(11, 'url_tutorial', '', '2025-09-13 04:19:07', '2025-09-15 02:36:00'),
(12, 'url_chatbot', '', '2025-09-15 02:52:18', '2025-09-15 02:52:18'),
(14, 'profit_percent_15days', '3.25', '2025-09-14 18:51:37', '2025-09-14 18:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','investor') NOT NULL DEFAULT 'investor',
  `wallet_address` varchar(255) DEFAULT NULL,
  `is_consultant` tinyint(4) NOT NULL DEFAULT 0,
  `referral_code` varchar(50) DEFAULT NULL,
  `referrer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `konsultan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password`, `role`, `wallet_address`, `is_consultant`, `referral_code`, `referrer_id`, `konsultan_id`, `is_active`, `remember_token`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, 'Admin Spectra', NULL, 'admin@spectra.com', '081234567890', '$2y$12$ODdRQgwf/J7LGRcQx/9EeOKmXmkRe3jqokxiJ9vz5qnD7Xkng3fV2', 'admin', 'SPC-1-396279', 0, NULL, NULL, NULL, 1, 'Ubyo8x2rTViNlZcOWs8cwShYT4kPQ83JFHpTv9Mm8Afgl0C30T8aZ5kbFA51', '2025-09-11 21:04:38', '2025-09-14 09:33:46', '2025-09-14 09:33:46'),
(2, 'Investor One', NULL, 'investor1@spectra.com', '081298765432', '$2y$12$JkoM9rAjarsrhK8mr.wVieSe8YqLS4DQxVBCUQ4Cnalu6cCJ72n6q', 'investor', 'SPC-2-538880', 1, NULL, NULL, NULL, 1, NULL, '2025-09-11 21:04:39', '2025-09-14 09:40:43', '2025-09-14 09:40:43'),
(3, 'Investor Two', NULL, 'investor2@spectra.com', '081223344556', '$2y$12$TrPsgLzIfsrB/QkxgE7AQ.7vpVsLrS2zAVyKwEaFQ3APunXWyKP9W', 'investor', NULL, 0, NULL, NULL, NULL, 0, NULL, '2025-09-11 21:04:39', '2025-09-13 20:59:22', NULL),
(6, 'bambang', 'bambang', 'bambang@gmail.com', '0891234124314', '$2y$12$rbI.YHl4vF0POiSQ/bDuwOuPLkZHFqJ/frqrK7ZjxcYfDWZabP5PO', 'investor', NULL, 0, 'XYO1BF', NULL, NULL, 1, NULL, '2025-09-15 07:35:04', '2025-09-15 07:35:04', NULL),
(7, 'ali', 'ali', 'ali@gmail.com', '091024112702139', '$2y$12$pugm.h8IVnwy/7boIzVCr.jgQLjQkoDxPMU//7OliWBQ/NceudoiC', 'investor', NULL, 0, 'O8ZGO7', NULL, 6, 1, NULL, '2025-09-15 07:41:52', '2025-09-15 07:41:52', NULL),
(8, 'elip', 'elip', 'elip@gmail.com', '09876543121', '$2y$12$i5UZvSXjTNm3beGzp6Zc0u7LQir.kHIAnxvZHhQBZWQjzhDPGNry2', 'investor', NULL, 0, 'OG2J2C', NULL, NULL, 1, NULL, '2025-09-15 08:26:37', '2025-09-15 08:26:37', NULL),
(9, 'anjas', NULL, 'anjas@gmail.com', '09844567890', '$2y$12$e.Q4qWJp4yLBsEUDS3WNkOjjfw8LHvAeOCL82aV0vTmrMD6YXrmwW', 'investor', NULL, 0, NULL, NULL, NULL, 1, NULL, '2025-09-15 13:20:33', '2025-09-15 13:20:33', NULL),
(10, 'test', 'test', 'test@gmail.com', '0893482893789', '$2y$12$3fHobcVY2RI4eZLRJXgQju5mcKbShVvIBPqV36tJifIBNqJPkryLq', 'investor', NULL, 0, 'G0GLTE', NULL, NULL, 1, NULL, '2025-09-15 18:29:52', '2025-09-15 18:29:52', NULL),
(11, 'test1', 'TEST1', 'test1@gmail.com', '098767890', '$2y$12$f3qQK9Raq0oGoM3ooBbocONe2e4BlmZ7ZTP/egt9Lix2.Miwj7brO', 'investor', NULL, 0, 'SRL3T0', 10, NULL, 1, NULL, '2025-09-15 18:31:18', '2025-09-15 18:31:18', NULL),
(12, 'test2', 'test2', 'test2@gmail.com', '09876556789', '$2y$12$A5EGOwIrpUcmBNuuZ1tRc.EIkThKego3djJsH.Fu0xlBxv1E30CUi', 'investor', NULL, 0, '1VVO0M', 11, NULL, 1, NULL, '2025-09-15 18:52:18', '2025-09-15 18:52:18', NULL),
(13, 'test3', 'test3', 'test3@gmail.com', '08367290763', '$2y$12$bSS1kmWCdn0UzFlAAn95NezaxotmQUCxq9FlqjIbir2fbfPKKJLiG', 'investor', NULL, 0, 'VMVYQR', 12, NULL, 1, NULL, '2025-09-16 02:17:36', '2025-09-16 02:17:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'id',
  `theme` varchar(10) NOT NULL DEFAULT 'light',
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `language`, `theme`, `currency`, `bank_name`, `bank_number`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'dark', 'USDT', NULL, NULL, '2025-09-13 03:55:48', '2025-09-13 16:08:43'),
(2, 2, 'id', 'light', 'USDT', 'BCA', '123456789', '2025-09-14 06:42:47', '2025-09-15 02:32:02'),
(3, 3, 'id', 'light', 'USDT', 'BRI', '987654321', '2025-09-14 06:42:47', '2025-09-14 18:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) NOT NULL,
  `type` enum('profit','commission','capital') NOT NULL,
  `status` enum('pending','processing','completed','rejected') NOT NULL DEFAULT 'pending',
  `withdraw_method` enum('wallet','bank') NOT NULL DEFAULT 'wallet',
  `proof_transfer` varchar(255) DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `affiliate_commissions_user_id_foreign` (`user_id`),
  ADD KEY `affiliate_commissions_referred_user_id_foreign` (`referred_user_id`),
  ADD KEY `affiliate_commissions_type_index` (`type`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`),
  ADD KEY `commissions_source_investment_foreign` (`source_investment`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_user_id_foreign` (`user_id`),
  ADD KEY `investments_package_id_foreign` (`package_id`),
  ADD KEY `investments_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `investment_packages`
--
ALTER TABLE `investment_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investment_profits`
--
ALTER TABLE `investment_profits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investment_profits_investment_id_foreign` (`investment_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `profits`
--
ALTER TABLE `profits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profits_investment_id_foreign` (`investment_id`),
  ADD KEY `profits_user_id_foreign` (`user_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_sponsor_id_foreign` (`sponsor_id`),
  ADD KEY `referrals_referral_id_foreign` (`referral_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_code` (`referral_code`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_konsultan_id` (`konsultan_id`),
  ADD KEY `users_referrer_id_foreign` (`referrer_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_preferences_user` (`user_id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_user_id_foreign` (`user_id`),
  ADD KEY `withdrawals_processed_by_foreign` (`processed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `investment_packages`
--
ALTER TABLE `investment_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `investment_profits`
--
ALTER TABLE `investment_profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profits`
--
ALTER TABLE `profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  ADD CONSTRAINT `affiliate_commissions_referred_user_id_foreign` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affiliate_commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_source_investment_foreign` FOREIGN KEY (`source_investment`) REFERENCES `investments` (`id`),
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `investment_packages` (`id`),
  ADD CONSTRAINT `investments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `investments_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `investment_profits`
--
ALTER TABLE `investment_profits`
  ADD CONSTRAINT `investment_profits_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `profits`
--
ALTER TABLE `profits`
  ADD CONSTRAINT `profits_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `profits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_referral_id_foreign` FOREIGN KEY (`referral_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `referrals_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
