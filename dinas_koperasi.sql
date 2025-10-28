-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2025 at 03:35 AM
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

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `description`, `ip_address`, `user_agent`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'create', 'App\\Models\\Structure', 11, NULL, '{\"id\": 11, \"nip\": \"23151233125123123124\", \"name\": \"Budi Aulyansyah Ahmad Trisna\", \"rank\": \"Pembina Utama - IV/e\", \"level\": 1, \"photo\": \"structure_photos/1761618725_FINAL.jpg\", \"position\": \"Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan\", \"is_active\": true, \"parent_id\": null, \"created_at\": \"2025-10-28T02:32:06.000000Z\", \"sort_order\": 1, \"updated_at\": \"2025-10-28T02:32:06.000000Z\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '2025-10-28 02:32:07', '2025-10-28 02:32:07'),
(2, 'create', 'App\\Models\\Structure', 12, NULL, '{\"id\": 12, \"nip\": \"12371982739187298723\", \"name\": \"Ahmad Sahroni\", \"rank\": \"Pembina Utama Madya - IV/d\", \"level\": 2, \"photo\": \"structure_photos/1761621071_day.png\", \"is_plt\": false, \"plt_nip\": null, \"plt_name\": null, \"plt_rank\": null, \"position\": \"Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten\", \"is_active\": true, \"parent_id\": null, \"plt_notes\": null, \"created_at\": \"2025-10-28T03:11:12.000000Z\", \"sort_order\": 1, \"updated_at\": \"2025-10-28T03:11:12.000000Z\", \"plt_end_date\": null, \"plt_start_date\": null, \"plt_from_structure_id\": null}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '2025-10-28 03:11:12', '2025-10-28 03:11:12'),
(3, 'update', 'App\\Models\\Structure', 11, '{\"id\": 11, \"nip\": \"23151233125123123124\", \"name\": \"Budi Aulyansyah Ahmad Trisna\", \"rank\": \"Pembina Utama - IV/e\", \"color\": null, \"email\": null, \"level\": 1, \"phone\": null, \"photo\": \"structure_photos/1761618725_FINAL.jpg\", \"is_plt\": false, \"plt_nip\": null, \"plt_name\": null, \"plt_rank\": null, \"position\": \"Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan\", \"is_active\": true, \"parent_id\": null, \"plt_notes\": null, \"created_at\": \"2025-10-28T02:32:06.000000Z\", \"deleted_at\": null, \"sort_order\": 1, \"updated_at\": \"2025-10-28T02:32:06.000000Z\", \"description\": null, \"plt_end_date\": null, \"plt_start_date\": null, \"plt_from_structure_id\": null}', '{\"is_plt\": true, \"updated_at\": \"2025-10-28 10:11:34\", \"plt_end_date\": \"2025-10-29 00:00:00\", \"plt_start_date\": \"2025-10-28 00:00:00\", \"plt_from_structure_id\": \"12\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '2025-10-28 03:11:34', '2025-10-28 03:11:34');

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
  `service_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_detail` longtext COLLATE utf8mb4_unicode_ci,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `required_documents` text COLLATE utf8mb4_unicode_ci,
  `important_notes` text COLLATE utf8mb4_unicode_ci,
  `procedure_steps` text COLLATE utf8mb4_unicode_ci,
  `service_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `processing_time` int DEFAULT NULL,
  `processing_time_unit` enum('hari','minggu','bulan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hari',
  `service_hours` text COLLATE utf8mb4_unicode_ci,
  `service_location` text COLLATE utf8mb4_unicode_ci,
  `responsible_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#00ff88',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_download_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tutorial_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `service_status` enum('active','inactive','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_services`
--

INSERT INTO `featured_services` (`id`, `title`, `service_category`, `slug`, `description`, `content_detail`, `requirements`, `required_documents`, `important_notes`, `procedure_steps`, `service_fee`, `processing_time`, `processing_time_unit`, `service_hours`, `service_location`, `responsible_person`, `phone_number`, `contact_email`, `icon`, `image`, `color`, `link`, `external_link`, `form_download_link`, `tutorial_link`, `sort_order`, `is_active`, `service_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pembinaan Koperasi', NULL, 'pembinaan-koperasi', 'Pembinaan dan pengembangan koperasi untuk meningkatkan kesejahteraan anggota', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 'hari', NULL, NULL, NULL, NULL, NULL, 'fas fa-handshake', NULL, '#00ff88', '#pembinaan', NULL, NULL, NULL, 1, 1, 'active', '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(2, 'Pengembangan UMK', NULL, 'pengembangan-umk', 'Pemberdayaan usaha mikro, kecil, dan menengah untuk pertumbuhan ekonomi', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 'hari', NULL, NULL, NULL, NULL, NULL, 'fas fa-chart-line', NULL, '#00ff88', '#umk', NULL, NULL, NULL, 2, 1, 'active', '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(3, 'Pelatihan & Edukasi', NULL, 'pelatihan-edukasi', 'Program pelatihan untuk meningkatkan kapasitas koperasi dan UKM', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 'hari', NULL, NULL, NULL, NULL, NULL, 'fas fa-graduation-cap', NULL, '#00ff88', '#pelatihan', NULL, NULL, NULL, 3, 1, 'active', '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL);

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
(1, 'Pemberdayaan Koperasi dan UKM', 'Mendukung pertumbuhan ekonomi masyarakat melalui pemberdayaan koperasi dan usaha mikro kecil menengah', 'carousel/hero-1.jpg', 'Pelajari Lebih Lanjut', '#layanan', 1, 1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(2, 'Pelayanan Terpadu Koperasi', 'Memberikan pelayanan terbaik dalam pengurusan perizinan dan pembinaan koperasi', 'carousel/hero-2.jpg', 'Hubungi Kami', '#kontak', 2, 1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(3, 'Inovasi dan Teknologi UKM', 'Mendorong adopsi teknologi dan inovasi dalam usaha mikro kecil menengah', 'carousel/hero-3.jpg', 'Lihat Program', '#program', 3, 1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL);

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
-- Table structure for table `mail_settings`
--

CREATE TABLE `mail_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `mail_mailer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` int NOT NULL DEFAULT '587',
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(27, '2025_10_07_145854_add_additional_fields_to_profiles_table', 1),
(28, '2025_10_23_144906_add_professional_fields_to_featured_services_table', 1),
(30, '2025_10_23_152417_create_mail_settings_table', 2),
(31, '2025_10_28_093517_add_plt_fields_to_structures_table', 3);

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
(1, 'Pelatihan Kewirausahaan UMKM Sukses Digelar', 'Dinas Koperasi berhasil menggelar pelatihan kewirausahaan untuk pelaku UMKM se-wilayah. Pelatihan ini diikuti oleh 100 peserta dari berbagai sektor usaha. Materi yang disampaikan meliputi strategi pemasaran digital, manajemen keuangan, dan pengembangan produk inovatif.', 'news/sample-training.jpg', 'published', '2025-10-26 14:45:07', 1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(2, 'Program Bantuan Modal Koperasi Tahap 2 Dimulai', 'Dinas Koperasi meluncurkan program bantuan modal tahap 2 untuk mendukung pengembangan koperasi di daerah. Program ini menyediakan bantuan modal hingga Rp 50 juta per koperasi dengan bunga rendah. Pendaftaran dibuka mulai hari ini hingga akhir bulan.', 'news/sample-funding.jpg', 'published', '2025-10-24 14:45:07', 1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL),
(5, 'Sosialisasi Regulasi Koperasi Terbaru', 'Dinas Koperasi mengadakan sosialisasi mengenai regulasi terbaru dalam pengelolaan koperasi. Sosialisasi ini penting untuk memberikan pemahaman kepada pengurus koperasi mengenai kewajiban pelaporan, tata kelola yang baik, dan standar operasional yang harus dipenuhi.', 'news/sample-regulation.jpg', 'published', '2025-10-20 14:45:07', 3, '2025-10-27 07:45:07', '2025-10-27 07:45:07', NULL);

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
(1, 'Dinas Koperasi dan UKM', NULL, 'Jl. Gatot Subroto Kav. 94, Jakarta Selatan 12870', '(021) 5254578', 'info@dinaskoperasi.go.id', 'Menjadi lembaga terdepan dalam pemberdayaan koperasi dan UMKM yang berdaya saing tinggi dan berkelanjutan.', '1. Meningkatkan kualitas dan kapasitas koperasi serta UMKM\n2. Mengembangkan akses permodalan dan pemasaran\n3. Memperkuat kelembagaan koperasi dan UMKM\n4. Meningkatkan inovasi dan teknologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-6.22970000', '106.82410000', '{\"sabtu\": \"08:00 - 12:00\", \"minggu\": \"Tutup\", \"senin_jumat\": \"08:00 - 16:00\"}', '2025-10-27 07:45:07', '2025-10-27 07:45:07');

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
(1, '2025-10-27 07:45:07', '2025-10-27 07:45:07', 'Sarah Putri', 'sarah@example.com', 5, 'Pelayanan sangat memuaskan!', 1, 1, 'approved', NULL),
(2, '2025-10-26 07:45:07', '2025-10-26 07:45:07', 'Budi Santoso', 'budi@example.com', 4, 'Cukup baik, bisa ditingkatkan.', 1, 0, 'approved', NULL),
(3, '2025-10-25 07:45:07', '2025-10-25 07:45:07', NULL, NULL, 3, 'Biasa saja.', 0, 0, 'pending', NULL);

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
('dlI5ykfqvez1n871lUlk0u8jKTnQuBR3hhgfMsTH', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU0VsMjNZeG1WYkQ0SEpPS0R5TmFaeHVVRVlXWUlqS0oyMGx2S2JlciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1761552494),
('I8yFhCLP7zi586mbFchCKwztTgu8aQk6HLyOtAnh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMENEY0FKTzRCcHU3cnFvOFE4a1ZZV0JDOWxqbWpCYzNQRXA2T2c2UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wdWJsaWMvc3RydWt0dXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1761622113),
('J2YPy8zBb030St2LLDYvgWNsdgkE0id9vS8JJrTV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.105.1 Chrome/138.0.7204.251 Electron/37.6.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibEZ4NGtTZER5YjdTbTRSWDZ1YUM1OXprQmJYSUpsNUp1T3UxTDd5RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9NzZiNTQwZGItNjBlNS00MmRmLTk0YzItYjY4OTliYzRlNTY4JnZzY29kZUJyb3dzZXJSZXFJZD0xNzYxNTUyMDI2MjE0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761552027),
('Sbb4k23fiGHUIJGPWUCXb50FN7wAiCxDPmuatxpz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.105.1 Chrome/138.0.7204.251 Electron/37.6.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXA0cm1BM0xHTWpNV05BMkN2aVhuQ1E5UmYxRkRVcFJ4bjg0enJKUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9YWY1YzAyMzEtOGU5Ni00NzMxLWFmOTMtMDRhMzU0NGMxZDAwJnZzY29kZUJyb3dzZXJSZXFJZD0xNzYxNTUxMzE0OTAzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761551316);

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
  `is_plt` tinyint(1) NOT NULL DEFAULT '0',
  `plt_from_structure_id` bigint UNSIGNED DEFAULT NULL,
  `plt_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plt_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plt_rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plt_start_date` date DEFAULT NULL,
  `plt_end_date` date DEFAULT NULL,
  `plt_notes` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `structures`
--

INSERT INTO `structures` (`id`, `position`, `name`, `nip`, `email`, `phone`, `rank`, `level`, `parent_id`, `sort_order`, `color`, `photo`, `is_active`, `is_plt`, `plt_from_structure_id`, `plt_name`, `plt_nip`, `plt_rank`, `plt_start_date`, `plt_end_date`, `plt_notes`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan', 'YODIHEL, S.E., M.Si', NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(2, 'Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten', 'Drs. SETIADY YUYU', NULL, NULL, NULL, NULL, 2, 1, 1, NULL, NULL, 1, 1, NULL, 'Drs. SETIADY YUYU', NULL, NULL, NULL, NULL, 'PLT dari Kepala Bidang Pengembangan Perdagangan', NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(3, 'Kepala Sub Bagian Umum dan Kepegawaian', 'RITHA IRIANI, S.H', NULL, NULL, NULL, NULL, 3, 2, 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(4, 'Kepala Sub Bagian Keuangan dan Pelaporan', 'ESRA, S.Sos', NULL, NULL, NULL, NULL, 3, 2, 2, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(5, 'Kepala Bidang Koperasi dan UMKM', 'BENYAMIN FRANKLIN JAKOB, S.E', NULL, NULL, NULL, NULL, 2, 1, 2, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(6, 'JFT Pengawas Koperasi Ahli Muda', 'AGUS MAULUDIN, S.P., M.P', NULL, NULL, NULL, NULL, 3, 5, 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(7, 'JFT Pengawas Koperasi Ahli Muda', 'EKO ADHI NUGROHO, S.E', NULL, NULL, NULL, NULL, 3, 5, 2, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(8, 'JFT Pengawas Koperasi Ahli Muda', 'RAHMAD J. RAHMALI, S.E', NULL, NULL, NULL, NULL, 3, 5, 3, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(9, 'Kepala Bidang Pengembangan Perdagangan', 'Drs. SETIADY YUYU', NULL, NULL, NULL, NULL, 2, 1, 3, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(10, 'JFT Pengawas Perdagangan Ahli Muda', 'KOSONG', NULL, NULL, NULL, NULL, 3, 9, 1, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(11, 'JFT Pengawas Perdagangan Ahli Muda', 'YONI MARIANAE, S.Pi', NULL, NULL, NULL, NULL, 3, 9, 2, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(12, 'JFT Pengawas Perdagangan Ahli Muda', 'SODERIYANTO, S.H Adv', NULL, NULL, NULL, NULL, 3, 9, 3, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(13, 'Kepala Bidang Kemetrologian', 'ALEXANDRA SUHARYONO MIKA LAMBANG, S.Si.Pi', NULL, NULL, NULL, NULL, 2, 1, 4, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(14, 'JFT Penera Ahli Muda', 'MANOGAR SIMANULLANG, S.T', NULL, NULL, NULL, NULL, 3, 13, 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(15, 'JFT Penera Ahli Muda', 'YUBILIANTO, S.T', NULL, NULL, NULL, NULL, 3, 13, 2, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(16, 'JFT Pengawas Kemetrologian Ahli Muda', 'SUPRIATNA, SKM', NULL, NULL, NULL, NULL, 3, 13, 3, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL),
(17, 'Fungsional Perencana Ahli Muda (JPT Perencana)', 'SIRMANIK STEPHAN, A.Md', NULL, NULL, NULL, NULL, 2, 1, 5, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:24:21', '2025-10-28 03:24:21', NULL);

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
(1, 'Admin Dinas Koperasi', 'admin@gmail.com', 'super_admin', 1, 'avatars/fJwTdGVFbDuuLevQU4Yl2j0D1PMbiOUTd9e7xyns.jpg', '2025-10-28 02:29:05', '2025-10-27 07:45:06', '$2y$12$CIkHpQG6eORxvrKwZIkgRuNomi/OZ.1g4yMa7tSXWMUgTDbLpWoTS', NULL, '2025-10-27 07:43:25', '2025-10-28 02:29:05'),
(3, 'Siti Rahayu', 'adminbiasa@gmail.com', 'admin', 1, 'avatars/8vVPZMwiXktn3EZuRbQgGRIdG6DCg4KcyTi5oyus.png', '2025-10-27 07:52:19', '2025-10-27 07:45:06', '$2y$12$1kyAJPP./XgCo3w3ajLPEubz76hZB4XWfijCt57ZahWyNP679yPX6', NULL, '2025-10-27 07:45:07', '2025-10-27 07:52:19');

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
-- Indexes for table `mail_settings`
--
ALTER TABLE `mail_settings`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `structures_plt_from_structure_id_foreign` (`plt_from_structure_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `mail_settings`
--
ALTER TABLE `mail_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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

--
-- Constraints for table `structures`
--
ALTER TABLE `structures`
  ADD CONSTRAINT `structures_plt_from_structure_id_foreign` FOREIGN KEY (`plt_from_structure_id`) REFERENCES `structures` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
