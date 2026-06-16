-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2026 pada 07.12
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Struktur dari tabel `fund_distributions`
--

CREATE TABLE `fund_distributions` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `beneficiary` varchar(150) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `proof_image` text DEFAULT NULL,
  `distributed_at` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `programs`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `programs`
--

INSERT INTO `programs` (`id`, `title`, `category`, `description`, `target_amount`, `collected_amount`, `image_url`, `status`, `end_date`, `created_at`) VALUES
(1, 'Pembangunan Masjid Baiturrahman', 'jariyah', NULL, '500000000.00', '120500000.00', NULL, 'active', NULL, '2026-04-21 06:13:17'),
(2, 'Bantuan Medis & Kemanusiaan Darurat', 'darurat', NULL, '100000000.00', '54560750.00', NULL, 'active', NULL, '2026-04-21 06:13:17'),
(3, 'Santunan Pendidikan Anak Yatim', 'yatim', NULL, '50000000.00', '15000000.00', NULL, 'active', NULL, '2026-04-21 06:13:17'),
(4, 'Indonesia Darurat Bencana: Longsor & Banjir', 'darurat', 'Bantuan darurat banjir dan longsor di Malalak Timur Agam.', '100000000.00', '35076524.00', 'https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200', 'active', '2026-08-31', '2026-06-01 12:05:08'),
(5, 'Berbagi Paket Sembako Untuk Keluarga Dhuafa', 'pangan', 'Distribusi sembako untuk keluarga dhuafa yang terdampak ekonomi sulit.', '30000000.00', '18500000.00', 'img/program_1780969683_f67d0b08.jpg', 'active', '2026-09-30', '2026-06-01 12:05:08'),
(6, 'Bantuan Medis Darurat & Kemanusiaan', 'darurat', 'Dukungan medis dan obat-obatan untuk wilayah bencana dan kesehatan kritis.', '100000000.00', '54560000.00', 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80', 'active', '2026-09-30', '2026-06-01 12:05:08'),
(7, 'Bantu Pangan dan Air Bersih Untuk Palestina', 'pangan', 'Distribusi pangan dan air bersih ke warga terdampak konflik di Palestina.', '200000000.00', '27066258.00', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80', 'active', '2026-11-30', '2026-06-01 12:05:08'),
(8, 'Santunan Anak Yatim Pelosok Negeri', 'yatim', 'Bantuan pendidikan dan kebutuhan dasar untuk anak yatim di daerah pelosok.', '50000000.00', '12400000.00', 'img/program_1780972874_1f7aef79.webp', 'active', '2026-10-31', '2026-06-01 12:05:08'),
(9, 'Bantuan Modal Usaha Untuk Ibu Tangguh', 'jariyah', 'Modal usaha untuk ibu-ibu pemberdayaan agar bisa bangkit mandiri.', '20000000.00', '8000000.00', 'https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg', 'active', '2026-12-31', '2026-06-01 12:05:08'),
(10, 'Pembangunan Masjid Pelosok Desa', 'jariyah', 'Pembangunan masjid baru untuk mendukung ibadah desa.', '500000000.00', '250000000.00', 'img/program_1780972880_24830858.webp', 'active', '2027-01-31', '2026-06-01 12:05:08'),
(11, 'Beasiswa Pendidikan Santri Penghafal Quran', 'yatim', 'Beasiswa untuk santri yang menghafal Quran.', '150000000.00', '45000000.00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s=10', 'active', '2026-12-31', '2026-06-01 12:05:08'),
(12, 'Sedekah Makanan Hangat Untuk Pekerja Jalanan', 'pangan', 'Penyediaan makanan hangat untuk pekerja jalanan dan lansia.', '10000000.00', '5200000.00', 'https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg', 'active', '2026-09-30', '2026-06-01 12:05:08'),
(13, 'when yah', 'jariyah', 'jembut', '241414214.00', '0.00', 'img/program_1781585329_0aa15933.jpg', 'active', '2026-06-18', '2026-06-01 12:07:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `trx_code` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `trx_code`, `user_id`, `program_id`, `amount`, `payment_method`, `message`, `status`, `verified_by`, `created_at`) VALUES
(1, 'TRX-001', 2, 1, '2500000.00', 'Transfer Bank BCA', 'Bismillah, semoga berkah untuk pembangunan masjid.', 'success', NULL, '2026-04-21 06:13:17'),
(2, 'TRX-002', 3, 2, '135000.00', 'QRIS', 'Semoga saudara kita diberi ketabahan.', 'pending', NULL, '2026-04-21 06:13:17'),
(3, 'TRX-20260601141439-6', 10, 4, '500000.00', 'BCA Virtual Account', 'semoga berkah', 'pending', NULL, '2026-06-01 12:14:39'),
(4, 'TRX-20260601142202-9', 10, 4, '1231313.00', 'BCA Virtual Account', 'awewaea', 'pending', NULL, '2026-06-01 12:22:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `admin_role` enum('super_admin','staff_keuangan','staff_program') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `admin_role`, `phone`, `avatar_url`, `created_at`) VALUES
(1, 'Admin DonasiKu', 'admin@email.com', '12345', 'admin', 'super_admin', NULL, NULL, '2026-04-21 06:13:17'),
(2, 'Kyosei', 'kyosei@gmail.com', '12345', 'admin', 'staff_keuangan', NULL, NULL, '2026-04-21 06:13:17'),
(3, 'miza', 'miza@gmail.com', '12345', 'admin', 'staff_keuangan', NULL, NULL, '2026-04-21 06:13:17'),
(4, 'Kevin', 'Kevin@gmail.com', '12345', 'admin', 'staff_program', NULL, NULL, '2026-04-21 06:13:17'),
(6, 'suud', 'suud@email.com', '12345', 'admin', 'staff_program', NULL, NULL, '2026-04-21 06:13:17'),
(9, 'wengki', 'wengki@gmail.com', '12345', 'user', NULL, NULL, NULL, '2026-04-21 06:13:17'),
(10, 'yoni', 'yoni@gmail.com', '12345', 'user', NULL, NULL, NULL, '2026-04-21 06:13:17'),
(11, 'miza', 'jembut@gmail.com', '$2y$10$Aty83Ht/Q0RJ4LE1Apwc3O7eoEYnK/qHpJFbryFEfjc8wdzgwzwde', 'admin', 'super_admin', '', 'profile-user/jembut-1-09062026.jpg', '2026-06-09 06:37:46'),
(12, 'miza', 'miza1@gmail.com', '$2y$10$LXLRurO/fA4dMVCD6S7UbubLgHG9DMzmEQtedFXgRUzTeWTJ9SDQq', 'user', NULL, '', 'profile-user/miza1-1-16062026.jpg', '2026-06-16 04:52:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trx_code` (`trx_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  ADD CONSTRAINT `fund_distributions_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fund_distributions_program_fk` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
