-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 05:15 AM
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
-- Database: `db_donasiku`
--

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` enum('jariyah','yatim','pangan','darurat') NOT NULL,
  `description` text DEFAULT NULL,
  `target_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `collected_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `image_url` text DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `title`, `category`, `description`, `target_amount`, `collected_amount`, `image_url`, `status`, `end_date`, `created_at`) VALUES
(1, 'Pembangunan Masjid Baiturrahman', 'jariyah', NULL, 500000000.00, 120500000.00, NULL, 'active', NULL, '2026-04-21 06:13:17'),
(2, 'Bantuan Medis & Kemanusiaan Darurat', 'darurat', NULL, 100000000.00, 54560750.00, NULL, 'active', NULL, '2026-04-21 06:13:17'),
(3, 'Santunan Pendidikan Anak Yatim', 'yatim', NULL, 50000000.00, 15000000.00, NULL, 'active', NULL, '2026-04-21 06:13:17'),
(4, 'Indonesia Darurat Bencana: Longsor & Banjir', 'darurat', 'Bantuan darurat banjir dan longsor di Malalak Timur Agam.', 100000000.00, 35076524.00, 'https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200', 'active', '2026-08-31', '2026-06-01 12:05:08'),
(5, 'Berbagi Paket Sembako Untuk Keluarga Dhuafa', 'pangan', 'Distribusi sembako untuk keluarga dhuafa yang terdampak pandemi dan ekonomi sulit.', 30000000.00, 18500000.00, 'img/program_1780969683_f67d0b08.jpg', 'active', '2026-09-30', '2026-06-01 12:05:08'),
(6, 'Bantuan Medis Darurat & Kemanusiaan', 'darurat', 'Dukungan medis dan obat-obatan untuk wilayah bencana dan kesehatan kritis.', 100000000.00, 54560000.00, 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80', 'active', '2026-09-30', '2026-06-01 12:05:08'),
(7, 'Bantu Pangan dan Air Bersih Untuk Palestina', 'pangan', 'Distribusi pangan dan air bersih ke warga terdampak konflik di Palestina.', 200000000.00, 27066258.00, 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80', 'active', '2026-11-30', '2026-06-01 12:05:08'),
(8, 'Santunan Anak Yatim Pelosok Negeri', 'yatim', 'Bantuan pendidikan dan kebutuhan dasar untuk anak yatim di daerah pelosok.', 50000000.00, 12400000.00, 'img/program_1780972874_1f7aef79.webp', 'active', '2026-10-31', '2026-06-01 12:05:08'),
(9, 'Bantuan Modal Usaha Untuk Ibu Tangguh', 'jariyah', 'Modal usaha untuk ibu-ibu pemberdayaan agar bisa bangkit mandiri.', 20000000.00, 8000000.00, 'https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg', 'active', '2026-12-31', '2026-06-01 12:05:08'),
(10, 'Pembangunan Masjid Pelosok Desa', 'jariyah', 'Pembangunan masjid baru untuk mendukung ibadah dan kegiatan sosial masyarakat desa.', 500000000.00, 250000000.00, 'img/program_1780972880_24830858.webp', 'active', '2027-01-31', '2026-06-01 12:05:08'),
(11, 'Beasiswa Pendidikan Santri Penghafal Quran', 'yatim', 'Beasiswa untuk santri yang menghafal Quran agar bisa lanjut sekolah dan pesantren.', 150000000.00, 45000000.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s=10', 'active', '2026-12-31', '2026-06-01 12:05:08'),
(12, 'Sedekah Makanan Hangat Untuk Pekerja Jalanan', 'pangan', 'Penyediaan makanan hangat untuk pekerja jalanan dan lansia yang membutuhkan.', 10000000.00, 5200000.00, 'https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg', 'active', '2026-09-30', '2026-06-01 12:05:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
