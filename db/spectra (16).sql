-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Sep 2025 pada 12.04
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

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
-- Struktur dari tabel `affiliate_bonus_rules`
--

CREATE TABLE `affiliate_bonus_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `target_omset` decimal(18,2) NOT NULL DEFAULT 10000.00,
  `extra_percent` decimal(8,4) NOT NULL DEFAULT 1.2500,
  `required_min_deposit` decimal(18,2) NOT NULL DEFAULT 1000.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `affiliate_bonus_rules`
--

INSERT INTO `affiliate_bonus_rules` (`id`, `target_omset`, `extra_percent`, `required_min_deposit`, `created_at`, `updated_at`) VALUES
(9, 10000.00, 1.2500, 1000.00, '2025-09-16 07:04:05', '2025-09-16 07:45:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `affiliate_commissions`
--

CREATE TABLE `affiliate_commissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `level` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `affiliate_commissions`
--

INSERT INTO `affiliate_commissions` (`id`, `user_id`, `from_user_id`, `type`, `amount`, `status`, `level`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'bonus', 30.00, 'paid', 1, '2025-09-16 19:36:43', '2025-09-16 19:36:43'),
(4, 2, 3, 'sponsor', 50.00, 'paid', 1, '2025-09-16 22:07:04', '2025-09-16 22:27:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `affiliate_levels`
--

CREATE TABLE `affiliate_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `type` enum('sponsor_fee','profit_sharing') NOT NULL,
  `percent` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `affiliate_levels`
--

INSERT INTO `affiliate_levels` (`id`, `level`, `type`, `percent`, `description`, `created_at`, `updated_at`) VALUES
(3, 1, 'sponsor_fee', 7.5000, 'Direct sponsor fee', '2025-09-16 18:23:30', '2025-09-16 18:24:03'),
(4, 2, 'sponsor_fee', 2.5000, 'Indirect sponsor fee', '2025-09-16 18:23:43', '2025-09-16 18:23:43'),
(6, 1, 'profit_sharing', 1.2500, 'Level 1 profit sharing', '2025-09-16 18:24:40', '2025-09-16 18:24:40'),
(7, 2, 'profit_sharing', 0.7500, 'Level 2 profit sharing', '2025-09-16 18:24:54', '2025-09-16 18:24:54'),
(8, 3, 'profit_sharing', 0.5000, 'Level 3 profit sharing', '2025-09-16 18:25:06', '2025-09-16 18:25:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
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
-- Dumping data untuk tabel `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `image_path`, `active`, `order`, `created_at`, `updated_at`) VALUES
(7, 'TEST1', 'announcements/DjWD5y9wHf_1757775166.jpg', 1, 0, '2025-09-13 14:52:48', '2025-09-13 14:52:48'),
(8, NULL, 'announcements/ElulC8ZaU9_1757775184.jpg', 1, 0, '2025-09-13 14:53:04', '2025-09-13 15:30:09'),
(9, NULL, 'announcements/5zQDn47WHO_1757775195.jpg', 1, 0, '2025-09-13 14:53:15', '2025-09-13 15:19:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `commissions`
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
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
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
-- Dumping data untuk tabel `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `package_id`, `amount`, `start_date`, `end_date`, `status`, `proof_transfer`, `validated_by`, `validated_at`, `created_at`, `updated_at`) VALUES
(16, 2, 1, 300.00, '2025-09-15', '2026-03-15', 'active', 'deposits/43ae737f-6a17-4219-b79b-d64864d96f88.png', 1, '2025-09-15 05:01:50', '2025-09-15 04:55:58', '2025-09-15 05:01:50'),
(17, 8, 1, 300.00, '2025-09-15', '2026-03-15', 'active', 'deposits/2bf6f3ab-7d8c-4f40-8202-a5fbba04b781.png', 1, '2025-09-15 08:29:39', '2025-09-15 08:28:55', '2025-09-15 08:29:39'),
(18, 2, 1, 300.00, NULL, NULL, 'pending', 'deposits/b4128569-fec4-4ab1-b362-5d6cb703064d.png', NULL, NULL, '2025-09-16 02:41:07', '2025-09-16 02:41:07'),
(19, 28, NULL, 10.00, NULL, NULL, 'active', 'deposits/d24e6a7a-3c65-434e-aa4c-422ef60e76b2.jpg', NULL, NULL, '2025-09-18 08:51:17', '2025-09-18 08:51:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `investment_packages`
--

CREATE TABLE `investment_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `duration_months` int(11) NOT NULL DEFAULT 6,
  `profit_percent` decimal(5,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `investment_packages`
--

INSERT INTO `investment_packages` (`id`, `name`, `amount`, `duration_months`, `profit_percent`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'USD 300', 300, 6, 7.50, 'USDT', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(2, 'USD 500', 500, 6, 7.50, 'USDT', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(3, 'USD 1000', 1000, 6, 7.50, 'USDT', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(4, 'USD 5000', 5000, 6, 7.50, 'USDT', '2025-09-11 21:04:40', '2025-09-11 21:04:40'),
(5, 'USD 10000', 10000, 6, 7.50, 'USDT', '2025-09-11 21:04:40', '2025-09-11 21:04:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
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
(14, '2025_09_13_101707_create_user_preferences_table', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
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
-- Struktur dari tabel `profits`
--

CREATE TABLE `profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `profit_amount` bigint(20) NOT NULL DEFAULT 0,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profits`
--

INSERT INTO `profits` (`id`, `investment_id`, `user_id`, `period_start`, `period_end`, `profit_amount`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 16, 2, '2025-09-15', '2026-03-15', 300, 'paid', '2025-09-16 19:36:43', '2025-09-16 19:36:43', '2025-09-16 19:36:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sponsor_id` bigint(20) UNSIGNED NOT NULL,
  `referral_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `referrals`
--

INSERT INTO `referrals` (`id`, `sponsor_id`, `referral_id`, `level`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1, '2025-09-16 18:09:43', '2025-09-16 18:09:43'),
(2, 8, 7, 1, '2025-09-16 18:09:43', '2025-09-16 18:09:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('W33REfbHij5KO8eYID2FYHTxq6ooWSnHF0sfJN58', 28, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiallRRmU2cGxoZHZTbTBiTnNLWEs3R0poMmJwa2RhTzRFZTlRbTlZcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb21wZXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyODt9', 1758189568);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key_name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
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
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','investor') NOT NULL DEFAULT 'investor',
  `wallet_address` varchar(255) DEFAULT NULL,
  `is_consultant` tinyint(4) NOT NULL DEFAULT 0,
  `consultant_status` enum('active','inactive') DEFAULT 'inactive',
  `consultant_activated_at` datetime DEFAULT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `konsultan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `referred_by`, `name`, `username`, `email`, `phone`, `password`, `role`, `wallet_address`, `is_consultant`, `consultant_status`, `consultant_activated_at`, `referral_code`, `konsultan_id`, `is_active`, `remember_token`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, NULL, 'Admin Spectra', NULL, 'admin@spectra.com', '081234567890', '$2y$12$ODdRQgwf/J7LGRcQx/9EeOKmXmkRe3jqokxiJ9vz5qnD7Xkng3fV2', 'admin', 'SPC-1-396279', 0, 'inactive', NULL, NULL, NULL, 1, 'HUvsQ2pnEXg6DDrVfwHqG80eciFcp1FnHIT1K3sKuu5Z2FstvJPySGK0vmuL', '2025-09-11 21:04:38', '2025-09-14 09:33:46', '2025-09-14 09:33:46'),
(2, NULL, 'Investor One', NULL, 'investor1@spectra.com', '081298765432', '$2y$12$JkoM9rAjarsrhK8mr.wVieSe8YqLS4DQxVBCUQ4Cnalu6cCJ72n6q', 'investor', 'SPC-2-538880', 1, 'inactive', NULL, 'ABC123', NULL, 1, NULL, '2025-09-11 21:04:39', '2025-09-14 09:40:43', '2025-09-14 09:40:43'),
(3, NULL, 'Investor Two', NULL, 'investor2@spectra.com', '081223344556', '$2y$12$TrPsgLzIfsrB/QkxgE7AQ.7vpVsLrS2zAVyKwEaFQ3APunXWyKP9W', 'investor', NULL, 0, 'inactive', NULL, NULL, NULL, 0, NULL, '2025-09-11 21:04:39', '2025-09-13 20:59:22', NULL),
(6, 2, 'bambang', 'bambang', 'bambang@gmail.com', '0891234124314', '$2y$12$rbI.YHl4vF0POiSQ/bDuwOuPLkZHFqJ/frqrK7ZjxcYfDWZabP5PO', 'investor', NULL, 0, 'inactive', NULL, 'XYO1BF', 2, 1, NULL, '2025-09-15 07:35:04', '2025-09-15 07:35:04', NULL),
(7, 2, 'ali', 'ali', 'ali@gmail.com', '091024112702139', '$2y$12$pugm.h8IVnwy/7boIzVCr.jgQLjQkoDxPMU//7OliWBQ/NceudoiC', 'investor', NULL, 0, 'inactive', NULL, 'O8ZGO7', 2, 1, NULL, '2025-09-15 07:41:52', '2025-09-15 07:41:52', NULL),
(8, 2, 'elip', 'elip', 'elip@gmail.com', '09876543121', '$2y$12$i5UZvSXjTNm3beGzp6Zc0u7LQir.kHIAnxvZHhQBZWQjzhDPGNry2', 'investor', NULL, 0, 'inactive', NULL, 'OG2J2C', 2, 1, NULL, '2025-09-15 08:26:37', '2025-09-15 08:26:37', NULL),
(9, 6, 'andi', NULL, 'andi@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFANDI', NULL, 1, NULL, NULL, NULL, NULL),
(10, 6, 'budi', NULL, 'budi@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFBUDI', NULL, 1, NULL, NULL, NULL, NULL),
(11, 7, 'cici', NULL, 'cici@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFCICI', NULL, 1, NULL, NULL, NULL, NULL),
(12, 9, 'dodi', NULL, 'dodi@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFDODI', NULL, 1, NULL, NULL, NULL, NULL),
(13, 10, 'erik', NULL, 'erik@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFERIK', NULL, 1, NULL, NULL, NULL, NULL),
(20, 8, 'dodo', NULL, 'dodo@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFDODO4', NULL, 1, NULL, '2025-09-17 06:32:04', '2025-09-17 06:32:04', NULL),
(23, 11, 'fajar', NULL, 'fajar@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFFAJAR7', NULL, 1, NULL, '2025-09-17 06:32:04', '2025-09-17 06:32:04', NULL),
(24, 12, 'gilang', NULL, 'gilang@example.com', NULL, '$2y$12$fakehash', 'investor', NULL, 0, 'inactive', NULL, 'REFGILANG8', NULL, 1, NULL, '2025-09-17 06:32:04', '2025-09-17 06:32:04', NULL),
(25, NULL, 'asdasd', 'asdasd', 'asdasd@gmail.com', '1234512345', '$2y$12$fm6bon78.doBzCqoOcqxnuN.AouA0a6ReozutPqUO6PSkZA87.hxa', 'investor', NULL, 0, 'inactive', NULL, 'FD9WX7', 2, 1, NULL, '2025-09-17 09:54:32', '2025-09-17 09:54:32', NULL),
(26, NULL, 'loki', 'loki', 'loki@gmail.com', '1234098756', '$2y$12$RvB/XiUNul2CVq1/gpebAevqXQx7PKKMEWjBTEjfcAie8dq/d4.Mq', 'investor', NULL, 0, 'inactive', NULL, 'O3YBKM', 2, 1, NULL, '2025-09-18 04:12:33', '2025-09-18 04:12:33', NULL),
(27, NULL, 'login', 'login', 'login@gmail.com', '12345', '$2y$12$/HUDCUSr87m0aOp.JvBylOyjgEtICTV0Dal.i8nIKUB9fZIzEEnKm', 'investor', NULL, 0, 'inactive', NULL, 'BTQRXA', 2, 1, NULL, '2025-09-18 04:25:03', '2025-09-18 04:25:03', NULL),
(28, NULL, 'logout', 'logout', 'logout@gmail.com', '12345555', '$2y$12$weNDglh0qhr96.JOTezwpuIMgLkyi.otwWyuRlgmi6vCLsaZCwsDW', 'investor', NULL, 0, 'inactive', NULL, '2KVDNG', 2, 1, NULL, '2025-09-18 04:50:12', '2025-09-18 04:50:12', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'id',
  `theme` varchar(10) NOT NULL DEFAULT 'light',
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_number` varchar(50) DEFAULT NULL,
  `bank_qr` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `language`, `theme`, `currency`, `bank_name`, `bank_number`, `bank_qr`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'dark', 'USDT', 'BCA', '321123098', 'qrcode/30945478-0825-4650-9d47-b4396ce24340.png', '2025-09-13 03:55:48', '2025-09-16 02:54:22'),
(2, 2, 'id', 'light', 'USDT', 'BCA', '123456789', NULL, '2025-09-14 06:42:47', '2025-09-15 02:32:02'),
(3, 3, 'id', 'light', 'USDT', 'BRI', '987654321', NULL, '2025-09-14 06:42:47', '2025-09-14 18:03:20'),
(4, 25, 'id', 'light', 'USDT', NULL, NULL, NULL, '2025-09-17 10:04:03', '2025-09-17 10:04:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Relasi ke users',
  `type` enum('deposit','profit','affiliate_commission','withdraw') NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID referensi dari tabel terkait (deposits, profits, commissions, dsb)',
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `withdrawals`
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
-- Indeks untuk tabel `affiliate_bonus_rules`
--
ALTER TABLE `affiliate_bonus_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `affiliate_levels`
--
ALTER TABLE `affiliate_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`),
  ADD KEY `commissions_source_investment_foreign` (`source_investment`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_user_id_foreign` (`user_id`),
  ADD KEY `investments_package_id_foreign` (`package_id`),
  ADD KEY `investments_validated_by_foreign` (`validated_by`);

--
-- Indeks untuk tabel `investment_packages`
--
ALTER TABLE `investment_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `profits`
--
ALTER TABLE `profits`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_sponsor_id_foreign` (`sponsor_id`),
  ADD KEY `referrals_referral_id_foreign` (`referral_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_code` (`referral_code`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `idx_konsultan_id` (`konsultan_id`);

--
-- Indeks untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_preferences_user` (`user_id`);

--
-- Indeks untuk tabel `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_index` (`user_id`);

--
-- Indeks untuk tabel `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_user_id_foreign` (`user_id`),
  ADD KEY `withdrawals_processed_by_foreign` (`processed_by`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `affiliate_bonus_rules`
--
ALTER TABLE `affiliate_bonus_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `affiliate_levels`
--
ALTER TABLE `affiliate_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `investment_packages`
--
ALTER TABLE `investment_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `profits`
--
ALTER TABLE `profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_source_investment_foreign` FOREIGN KEY (`source_investment`) REFERENCES `investments` (`id`),
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `investments_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_referral_id_foreign` FOREIGN KEY (`referral_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `referrals_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
