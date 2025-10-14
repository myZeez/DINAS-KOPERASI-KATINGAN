-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2025 at 02:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinas_koperasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_services`
--

CREATE TABLE `featured_services` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_detail` longtext COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#00ff88',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_services`
--

INSERT INTO `featured_services` (`id`, `title`, `slug`, `description`, `content_detail`, `icon`, `image`, `color`, `link`, `external_link`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pembinaan Koperasi', 'pembinaan-koperasi', 'Pembinaan dan pengembangan koperasi untuk meningkatkan kesejahteraan anggota', NULL, 'fas fa-handshake', NULL, '#00ff88', '#pembinaan', NULL, 1, 1, '2025-10-09 11:52:29', '2025-10-09 11:52:29', NULL),
(2, 'Pengembangan UMK', 'pengembangan-umk', 'Pemberdayaan usaha mikro, kecil, dan menengah untuk pertumbuhan ekonomi', NULL, 'fas fa-chart-line', NULL, '#00ff88', '#umk', NULL, 2, 1, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(3, 'Pelatihan & Edukasi', 'pelatihan-edukasi', 'Program pelatihan untuk meningkatkan kapasitas koperasi dan UKM', NULL, 'fas fa-graduation-cap', NULL, '#00ff88', '#pelatihan', NULL, 3, 1, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_downloads`
--

CREATE TABLE `file_downloads` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL DEFAULT '0',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kegiatan',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_carousels`
--

CREATE TABLE `hero_carousels` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_carousels`
--

INSERT INTO `hero_carousels` (`id`, `title`, `subtitle`, `image`, `button_text`, `button_link`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pemberdayaan Koperasi dan UKM', 'Mendukung pertumbuhan ekonomi masyarakat melalui pemberdayaan koperasi dan usaha mikro kecil menengah', 'carousel/hero-1.jpg', 'Pelajari Lebih Lanjut', '#layanan', 1, 1, '2025-10-09 11:52:29', '2025-10-09 11:52:29', NULL),
(2, 'Pelayanan Terpadu Koperasi', 'Memberikan pelayanan terbaik dalam pengurusan perizinan dan pembinaan koperasi', 'carousel/hero-2.jpg', 'Hubungi Kami', '#kontak', 2, 1, '2025-10-09 11:52:29', '2025-10-09 11:52:29', NULL),
(3, 'Inovasi dan Teknologi UKM', 'Mendorong adopsi teknologi dan inovasi dalam usaha mikro kecil menengah', 'carousel/hero-3.jpg', 'Lihat Program', '#program', 3, 1, '2025-10-09 11:52:29', '2025-10-09 11:52:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_27_082720_create_news_table', 1),
(5, '2025_08_27_082727_create_galleries_table', 1),
(6, '2025_08_27_082734_create_profiles_table', 1),
(7, '2025_08_27_082740_create_public_contents_table', 1),
(8, '2025_08_27_082754_create_structures_table', 1),
(9, '2025_08_27_082834_create_reviews_table', 1),
(10, '2025_08_27_082839_create_complaints_table', 1),
(11, '2025_08_28_021827_add_additional_columns_to_galleries_table', 1),
(12, '2025_08_28_022622_make_image_nullable_in_galleries_table', 1),
(13, '2025_08_28_053502_add_role_to_users_table', 1),
(14, '2025_08_28_120000_create_hero_carousels_table', 1),
(15, '2025_08_28_120001_create_featured_services_table', 1),
(16, '2025_08_28_120002_update_public_contents_table', 1),
(17, '2025_09_01_075949_add_photo_remove_icon_from_structures_table', 1),
(18, '2025_09_12_000001_add_fields_to_reviews_table', 1),
(19, '2025_09_12_000100_drop_complaints_table', 1),
(20, '2025_09_14_035918_add_soft_deletes_to_main_tables', 1),
(21, '2025_09_14_040117_create_activity_logs_table', 1),
(22, '2025_09_16_000000_create_file_downloads_table', 1),
(23, '2025_09_18_000001_add_detail_fields_to_featured_services_table', 1),
(24, '2025_09_22_000001_fix_featured_services_slug_constraint', 1),
(25, '2025_10_07_091222_add_detail_to_profiles_table', 1),
(26, '2025_10_07_100805_add_quotes_to_profiles_table', 1),
(27, '2025_10_07_145854_add_additional_fields_to_profiles_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `status`, `published_at`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pelatihan Kewirausahaan UMKM Sukses Digelar', 'Dinas Koperasi berhasil menggelar pelatihan kewirausahaan untuk pelaku UMKM se-wilayah. Pelatihan ini diikuti oleh 100 peserta dari berbagai sektor usaha. Materi yang disampaikan meliputi strategi pemasaran digital, manajemen keuangan, dan pengembangan produk inovatif.', 'news/sample-training.jpg', 'published', '2025-10-08 18:52:30', 1, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(2, 'Program Bantuan Modal Koperasi Tahap 2 Dimulai', 'Dinas Koperasi meluncurkan program bantuan modal tahap 2 untuk mendukung pengembangan koperasi di daerah. Program ini menyediakan bantuan modal hingga Rp 50 juta per koperasi dengan bunga rendah. Pendaftaran dibuka mulai hari ini hingga akhir bulan.', 'news/sample-funding.jpg', 'published', '2025-10-06 18:52:30', 1, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(3, 'Workshop Digital Marketing untuk UMKM', 'Dalam rangka meningkatkan daya saing UMKM di era digital, Dinas Koperasi mengadakan workshop digital marketing. Workshop ini menghadirkan praktisi ahli di bidang digital marketing dan e-commerce. Peserta akan belajar cara memasarkan produk melalui media sosial dan platform online.', 'news/sample-workshop.jpg', 'published', '2025-10-04 18:52:30', 2, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(4, 'Pameran Produk UMKM Lokal Akan Segera Digelar', 'Dinas Koperasi akan menggelar pameran produk UMKM lokal pada bulan depan. Pameran ini bertujuan untuk memperkenalkan produk-produk unggulan dari UMKM binaan kepada masyarakat luas. Diharapkan kegiatan ini dapat meningkatkan omzet dan membuka peluang kerjasama baru.', NULL, 'draft', NULL, 2, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(5, 'Sosialisasi Regulasi Koperasi Terbaru', 'Dinas Koperasi mengadakan sosialisasi mengenai regulasi terbaru dalam pengelolaan koperasi. Sosialisasi ini penting untuk memberikan pemahaman kepada pengurus koperasi mengenai kewajiban pelaporan, tata kelola yang baik, dan standar operasional yang harus dipenuhi.', 'news/sample-regulation.jpg', 'published', '2025-10-02 18:52:30', 3, '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dinas Koperasi',
  `head_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vision` text COLLATE utf8mb4_unicode_ci,
  `mission` text COLLATE utf8mb4_unicode_ci,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `quotes` text COLLATE utf8mb4_unicode_ci,
  `tujuan` text COLLATE utf8mb4_unicode_ci,
  `tentang` text COLLATE utf8mb4_unicode_ci,
  `tugas_pokok` text COLLATE utf8mb4_unicode_ci,
  `peran` text COLLATE utf8mb4_unicode_ci,
  `fokus_utama` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `operating_hours` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `head_name`, `address`, `phone`, `email`, `vision`, `mission`, `detail`, `quotes`, `tujuan`, `tentang`, `tugas_pokok`, `peran`, `fokus_utama`, `logo`, `latitude`, `longitude`, `operating_hours`, `created_at`, `updated_at`) VALUES
(1, 'Dinas Koperasi dan UKM', NULL, 'Jl. Gatot Subroto Kav. 94, Jakarta Selatan 12870', '(021) 5254578', 'info@dinaskoperasi.go.id', 'Menjadi lembaga terdepan dalam pemberdayaan koperasi dan UMKM yang berdaya saing tinggi dan berkelanjutan.', '1. Meningkatkan kualitas dan kapasitas koperasi serta UMKM\n2. Mengembangkan akses permodalan dan pemasaran\n3. Memperkuat kelembagaan koperasi dan UMKM\n4. Meningkatkan inovasi dan teknologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'logos/cZDmUv5jrkE1CLfraQABplXoEYshRgcDeW3q13C6.png', '-6.22970000', '106.82410000', '{\"sabtu\": \"08:00 - 12:00\", \"minggu\": \"Tutup\", \"senin_jumat\": \"08:00 - 16:00\"}', '2025-10-09 11:52:29', '2025-10-09 11:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `public_contents`
--

CREATE TABLE `public_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `created_at`, `updated_at`, `name`, `email`, `rating`, `comment`, `is_visible`, `is_verified`, `status`, `deleted_at`) VALUES
(1, '2025-10-09 11:52:30', '2025-10-09 11:52:30', 'Sarah Putri', 'sarah@example.com', 5, 'Pelayanan sangat memuaskan!', 1, 1, 'approved', NULL),
(2, '2025-10-08 11:52:30', '2025-10-08 11:52:30', 'Budi Santoso', 'budi@example.com', 4, 'Cukup baik, bisa ditingkatkan.', 1, 0, 'approved', NULL),
(3, '2025-10-07 11:52:30', '2025-10-07 11:52:30', NULL, NULL, 3, 'Biasa saja.', 0, 0, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dBR3oXyhxNdVnWNq8lm5wLCsQQ5NyMW5J8Yz94Sh', 1, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibHJZZ0ZMNURUYU01VVhyTE9ocUlEc3FvQ3BOT3RhZDJrVUM5RU91ViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wdWJsaWMvZ2FsZXJpIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1760011126);

-- --------------------------------------------------------

--
-- Table structure for table `structures`
--

CREATE TABLE `structures` (
  `id` bigint UNSIGNED NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int NOT NULL DEFAULT '1',
  `parent_id` int DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `structures`
--

INSERT INTO `structures` (`id`, `position`, `name`, `nip`, `email`, `phone`, `rank`, `level`, `parent_id`, `sort_order`, `color`, `photo`, `is_active`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kepala Dinas', 'Dr. Ahmad Surya, M.Si', '196512031990031002', 'ahmad.surya@dinaskoperasi.go.id', '+62 812-3456-7890', 'Pembina Utama Muda', 1, NULL, 1, '#ff6b6b', NULL, 1, 'Memimpin dan mengelola seluruh kegiatan Dinas Koperasi', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(2, 'Sekretaris', 'Siti Rahayu, S.Sos', '197803142005012009', 'siti.rahayu@dinaskoperasi.go.id', '+62 813-4567-8901', 'Penata Tingkat I', 2, 1, 1, '#00ff88', NULL, 1, 'Mengelola administrasi dan kesekretariatan dinas', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(3, 'Kabid Koperasi', 'Budi Santoso, S.E', '198205102008011003', 'budi.santoso@dinaskoperasi.go.id', '+62 814-5678-9012', 'Penata Tingkat I', 2, 1, 2, '#ffd93d', NULL, 1, 'Mengelola bidang koperasi dan pembinaan anggota', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(4, 'Kabid UMKM', 'Maya Sari, S.T', '199001152015032001', 'maya.sari@dinaskoperasi.go.id', '+62 815-6789-0123', 'Penata', 2, 1, 3, '#a8e6cf', NULL, 1, 'Mengelola bidang UMKM dan perdagangan', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(5, 'Staff Keuangan', 'Andi Wijaya, S.E', '199506112019031005', 'andi.wijaya@dinaskoperasi.go.id', '+62 816-7890-1234', 'Penata Muda', 3, 2, 1, '#ddd', NULL, 1, 'Mengelola keuangan dan pembukuan dinas', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(6, 'Staff Kepegawaian', 'Dewi Lestari, S.Sos', '199203082017032002', 'dewi.lestari@dinaskoperasi.go.id', '+62 817-8901-2345', 'Penata Muda', 3, 2, 2, '#ddd', NULL, 1, 'Mengelola administrasi kepegawaian', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(7, 'Staff Pembinaan', 'Rudi Hartono, S.P', '199408052018011007', 'rudi.hartono@dinaskoperasi.go.id', '+62 818-9012-3456', 'Penata Muda', 3, 3, 1, '#ddd', NULL, 1, 'Pembinaan dan pelatihan koperasi', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(8, 'Staff Monitoring', 'Lisa Permata, S.E', '199701122020032003', 'lisa.permata@dinaskoperasi.go.id', '+62 819-0123-4567', 'Penata Muda', 3, 3, 2, '#ddd', NULL, 1, 'Monitoring dan evaluasi koperasi', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(9, 'Staff Pengembangan', 'Agus Prasetyo, S.T', '199512152019031008', 'agus.prasetyo@dinaskoperasi.go.id', '+62 820-1234-5678', 'Penata Muda', 3, 4, 1, '#ddd', NULL, 1, 'Pengembangan UMKM dan inovasi', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL),
(10, 'Staff Pemasaran', 'Nina Handayani, S.E', '199809032021032004', 'nina.handayani@dinaskoperasi.go.id', '+62 821-2345-6789', 'Penata Muda', 3, 4, 2, '#ddd', NULL, 1, 'Pemasaran dan promosi UMKM', '2025-10-09 11:52:30', '2025-10-09 11:52:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_active`, `avatar`, `last_login_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Dinas Koperasi', 'admin@dinaskoperasi.go.id', 'super_admin', 1, NULL, '2025-10-09 11:53:48', '2025-10-09 11:52:28', '$2y$12$.YIswrn.Ajo1GEaBjgQFiu8we2PH8ptFw41qrXeQUZ4hTN.z0BUCS', NULL, '2025-10-09 11:52:28', '2025-10-09 11:53:48'),
(2, 'Budi Santoso', 'budi@dinaskoperasi.go.id', 'admin', 1, NULL, NULL, '2025-10-09 11:52:29', '$2y$12$A3UnDek.JLa9c7K1prw2UuKdVEnPTRanWJrrdVlZSS3.uroH8m3ze', NULL, '2025-10-09 11:52:29', '2025-10-09 11:52:29'),
(3, 'Siti Rahayu', 'siti@dinaskoperasi.go.id', 'admin', 1, NULL, NULL, '2025-10-09 11:52:29', '$2y$12$mLm0tZpBAzrhKXaxZJNpAOMd6is4LIfg77wkvvjOyFr67D01scaxK', NULL, '2025-10-09 11:52:29', '2025-10-09 11:52:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `featured_services`
--
ALTER TABLE `featured_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `featured_services_slug_unique` (`slug`);

--
-- Indexes for table `file_downloads`
--
ALTER TABLE `file_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_downloads_deleted_at_created_at_index` (`deleted_at`,`created_at`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_user_id_foreign` (`user_id`);

--
-- Indexes for table `hero_carousels`
--
ALTER TABLE `hero_carousels`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public_contents`
--
ALTER TABLE `public_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `structures`
--
ALTER TABLE `structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_services`
--
ALTER TABLE `featured_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_downloads`
--
ALTER TABLE `file_downloads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_carousels`
--
ALTER TABLE `hero_carousels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `public_contents`
--
ALTER TABLE `public_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `structures`
--
ALTER TABLE `structures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
