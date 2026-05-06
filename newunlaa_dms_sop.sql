-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 05 Bulan Mei 2026 pada 14.14
-- Versi server: 8.0.45-cll-lve
-- Versi PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newunlaa_dms_sop`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `singkatan` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`, `singkatan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Fakultas Hukum', 'FH', NULL, '2026-03-09 06:36:47', NULL, 1),
(2, 'Fakultas Ekonomi dan Bisnis', 'FEB', NULL, '2026-03-09 03:55:29', NULL, 1),
(3, 'Fakultas Ilmu Sosial dan Ilmu Pemerintahan', 'FISIP', NULL, NULL, NULL, NULL),
(4, 'Fakultas Keguruan dan Ilmu Pendidikan', 'FKIP', NULL, '2026-03-09 04:17:15', NULL, 1),
(5, 'Fakultas Teknik', 'FT', NULL, '2026-01-08 01:12:53', NULL, 1),
(6, 'Pascasarjana', 'PASCA', NULL, '2026-01-08 23:09:51', NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_sop`
--

CREATE TABLE `kategori_sop` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `deskripsi` text,
  `status` tinyint(1) DEFAULT '1',
  `slug` varchar(255) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `landing_pages`
--

CREATE TABLE `landing_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `konten` longtext,
  `tipe` varchar(100) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `aktif` tinyint(1) DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `modul` varchar(100) DEFAULT NULL,
  `aktivitas` text,
  `referensi_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `modul`, `aktivitas`, `referensi_id`, `created_at`) VALUES
(1, 1, 'Seeder', 'Membuat / memastikan Super Admin', 1, '2026-04-07 04:19:06'),
(2, 1, 'AUTH', 'Login ke sistem', NULL, '2026-04-07 04:20:34'),
(3, 1, 'AUTH', 'Login ke sistem', NULL, '2026-04-08 05:34:44'),
(4, 1, 'AUTH', 'Login ke sistem', NULL, '2026-04-13 01:09:10'),
(5, 1, 'AUTH', 'Login ke sistem', NULL, '2026-04-17 02:22:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_permission` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `nama_permission`, `deskripsi`, `created_at`, `updated_at`) VALUES
(20, 'dashboard.view', 'dashboard.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(21, 'sop.view', 'sop.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(22, 'sop.create', 'sop.create', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(23, 'sop.update', 'sop.update', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(24, 'sop.delete', 'sop.delete', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(25, 'kategori.view', 'kategori.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(26, 'unit_kerja.view', 'unit_kerja.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(27, 'tag.view', 'tag.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(28, 'users.view', 'users.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(29, 'users.create', 'users.create', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(30, 'users.update', 'users.update', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(31, 'users.delete', 'users.delete', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(32, 'role.view', 'role.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(33, 'permission.view', 'permission.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(34, 'log.view', 'log.view', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(35, 'sop.download', 'sop.download', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(36, 'sop.view_log', 'sop.view_log', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(37, 'sop.approval', 'sop.approval', '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(38, 'setting.landing', 'setting.landing', '2026-04-07 04:19:06', '2026-04-07 04:19:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_pengguna`
--

CREATE TABLE `profil_pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nim_nip` varchar(100) DEFAULT NULL,
  `nidn` varchar(100) DEFAULT NULL,
  `status_pengguna` varchar(50) DEFAULT NULL,
  `jenis_pengguna` varchar(50) DEFAULT NULL,
  `is_aktif` tinyint(1) DEFAULT '1',
  `fk_prodi_id` bigint UNSIGNED DEFAULT NULL,
  `unit_kerja_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `profil_pengguna`
--

INSERT INTO `profil_pengguna` (`id`, `user_id`, `nama`, `nim_nip`, `nidn`, `status_pengguna`, `jenis_pengguna`, `is_aktif`, `fk_prodi_id`, `unit_kerja_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', '00000001', NULL, 'aktif', 'admin', 1, NULL, NULL, NULL, NULL, '2026-04-07 04:19:06', '2026-04-07 04:19:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
--

CREATE TABLE `program_studi` (
  `id` bigint UNSIGNED NOT NULL,
  `fakultas_id` bigint UNSIGNED DEFAULT NULL,
  `kode_prodi` varchar(5) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `singkatan` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id`, `fakultas_id`, `kode_prodi`, `nama`, `singkatan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 1, '101', 'HUKUM', 'HK', NULL, '2026-03-15 16:33:41', NULL, 1),
(2, 2, '201', 'MANAJEMEN', 'MAN', NULL, '2026-03-15 16:33:49', NULL, 1),
(3, 2, '202', 'AKUNTANSI', 'AK', NULL, '2026-03-15 16:34:07', NULL, 1),
(4, 2, '203', 'BISNIS DIGITAL', 'BD', NULL, '2026-03-15 16:34:16', NULL, 1),
(5, 3, '301', 'ILMU PEMERINTAHAN', 'IP', '2026-01-14 07:30:32', '2026-03-15 16:34:26', 1, 1),
(6, 3, '302', 'ILMU KESEJAHTERAAN SOSIAL', 'IKS', '2026-01-14 07:30:32', '2026-03-15 16:35:10', 1, 1),
(7, 3, '303', 'ILMU KOMUNIKASI', 'ILKOM', '2026-01-14 07:30:32', '2026-02-03 11:03:30', 1, 1),
(8, 3, '305', 'MANAJEMEN KEAMANAN DAN KESELAMATAN PUBLIK', 'MKKP', NULL, NULL, NULL, NULL),
(9, 4, '401', 'PENDIDIKAN EKONOMI', 'PEKO', NULL, NULL, NULL, NULL),
(10, 4, '402', 'PENDIDIKAN MATEMATIKA', 'PMAT', NULL, NULL, NULL, NULL),
(11, 4, '403', 'PENDIDIKAN GURU SEKOLAH DASAR', 'PGSD', NULL, NULL, NULL, NULL),
(12, 5, '501', 'TEKNIK INDUSTRI', 'TIN', NULL, '2026-02-04 11:27:08', NULL, 1),
(13, 5, '502', 'TEKNIK SIPIL', 'TS', NULL, NULL, NULL, NULL),
(14, 5, '503', 'ARSITEKTUR', 'ARS', NULL, NULL, NULL, NULL),
(15, 5, '504', 'TEKNIK ELEKTRO', 'TE', NULL, NULL, NULL, NULL),
(16, 5, '505', 'TEKNIK INFORMATIKA', 'TIF', NULL, NULL, NULL, NULL),
(17, 6, '21', 'MAGISTER ILMU HUKUM', 'MIH', NULL, NULL, NULL, NULL),
(18, 6, '22', 'MAGISTER MANAJEMEN', 'MMAN', NULL, NULL, NULL, NULL),
(19, 6, '23', 'MAGISTER ILMU PEMERINTAHAN', 'MIP', NULL, NULL, NULL, NULL),
(20, 6, '25', 'MAGISTER TEKNIK INFORMATIKA', 'MTI', NULL, NULL, NULL, NULL),
(21, 6, '26', 'MAGISTER ADMINISTRASI PENDIDIKAN', 'MAP', NULL, NULL, NULL, NULL),
(22, 6, '31', 'DOKTOR HUKUM', 'DH', NULL, NULL, NULL, NULL),
(23, 6, '33', 'DOKTOR ILMU PEMERINTAHAN', 'DIP', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_role` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `nama_role`, `deskripsi`, `created_at`, `updated_at`) VALUES
(2, 'Super Admin', 'Akses penuh ke seluruh sistem', '2026-04-07 04:19:06', '2026-04-07 04:19:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop`
--

CREATE TABLE `sop` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_sop` varchar(100) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `kategori_id` bigint UNSIGNED DEFAULT NULL,
  `unit_kerja_id` bigint UNSIGNED DEFAULT NULL,
  `deskripsi` text,
  `status` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_akses`
--

CREATE TABLE `sop_akses` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `unit_kerja_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_approvals`
--

CREATE TABLE `sop_approvals` (
  `id` bigint UNSIGNED NOT NULL,
  `version_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `level_approval` int DEFAULT NULL,
  `approval_order` int DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `catatan` text,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_comments`
--

CREATE TABLE `sop_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `komentar` text,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_download_logs`
--

CREATE TABLE `sop_download_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `version_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  `downloaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_status_history`
--

CREATE TABLE `sop_status_history` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `status_lama` varchar(50) DEFAULT NULL,
  `status_baru` varchar(50) DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `catatan` text,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_tags`
--

CREATE TABLE `sop_tags` (
  `sop_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_versions`
--

CREATE TABLE `sop_versions` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `versi` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `catatan_revisi` text,
  `tanggal_berlaku` date DEFAULT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sop_views`
--

CREATE TABLE `sop_views` (
  `id` bigint UNSIGNED NOT NULL,
  `sop_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `temp_users`
--

CREATE TABLE `temp_users` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `password_lama` varchar(255) DEFAULT NULL,
  `password_baru` varchar(255) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `temp_users`
--

INSERT INTO `temp_users` (`id`, `user_id`, `password_lama`, `password_baru`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'admin123', NULL, NULL, '2026-04-07 04:19:06', '2026-04-07 04:19:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit_kerja`
--

CREATE TABLE `unit_kerja` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_unit` varchar(255) DEFAULT NULL,
  `unit_singkatan` varchar(50) DEFAULT NULL,
  `tipe_unit` varchar(100) DEFAULT NULL,
  `status_unit` tinyint(1) DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `unit_kerja_id` bigint UNSIGNED DEFAULT NULL,
  `fakultas_id` bigint UNSIGNED DEFAULT NULL,
  `prodi_id` bigint UNSIGNED DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `unit_kerja_id`, `fakultas_id`, `prodi_id`, `username`, `email`, `password`, `remember_token`, `no_hp`, `status`, `last_login`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, 'superadmin', 'admin@unla.ac.id', '$2y$12$u8y/78iX5VbZGnIezCFi6umxgDi8GAE12YI3a7k1dam.p6achm3Ay', NULL, NULL, 1, NULL, 1, 1, '2026-04-07 04:19:06', '2026-04-07 04:19:06'),
(2, 2, NULL, NULL, NULL, 'soni', 'soni@unla.ac.id', '$2y$10$wH0tZyK7z0F4pK3YVnWkUe4cY0yq1c9FZ8lP0pX2q1Z9bH8kRrYyG', NULL, NULL, 1, NULL, 1, 1, '2026-04-17 09:19:16', '2026-04-17 09:19:16');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_sop`
--
ALTER TABLE `kategori_sop`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil_pengguna`
--
ALTER TABLE `profil_pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prodi_fakultas` (`fakultas_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`);

--
-- Indeks untuk tabel `sop`
--
ALTER TABLE `sop`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_akses`
--
ALTER TABLE `sop_akses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_approvals`
--
ALTER TABLE `sop_approvals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_comments`
--
ALTER TABLE `sop_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_download_logs`
--
ALTER TABLE `sop_download_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_status_history`
--
ALTER TABLE `sop_status_history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_tags`
--
ALTER TABLE `sop_tags`
  ADD PRIMARY KEY (`sop_id`,`tag_id`);

--
-- Indeks untuk tabel `sop_versions`
--
ALTER TABLE `sop_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sop_views`
--
ALTER TABLE `sop_views`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `temp_users`
--
ALTER TABLE `temp_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori_sop`
--
ALTER TABLE `kategori_sop`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `landing_pages`
--
ALTER TABLE `landing_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `profil_pengguna`
--
ALTER TABLE `profil_pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sop`
--
ALTER TABLE `sop`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_akses`
--
ALTER TABLE `sop_akses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_approvals`
--
ALTER TABLE `sop_approvals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_comments`
--
ALTER TABLE `sop_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_download_logs`
--
ALTER TABLE `sop_download_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_status_history`
--
ALTER TABLE `sop_status_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_versions`
--
ALTER TABLE `sop_versions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sop_views`
--
ALTER TABLE `sop_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `temp_users`
--
ALTER TABLE `temp_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD CONSTRAINT `fk_prodi_fakultas` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
