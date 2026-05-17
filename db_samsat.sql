-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Mar 2026 pada 14.08
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
-- Database: `db_samsat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `registrasi_stnk`
--

CREATE TABLE `registrasi_stnk` (
  `id` bigint(20) NOT NULL,
  `tanggal` date NOT NULL,
  `nopol` varchar(20) NOT NULL,
  `pemilik` varchar(100) NOT NULL,
  `jenis_kendaraan` enum('R2','R4') NOT NULL,
  `jenis_plat` enum('Merah','Kuning','Putih') NOT NULL,
  `jenis_layanan` enum('BBN1','Duplikat','Ganti Plat','Ganti Nopol','BBN2','Fiskal','Rubah') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `registrasi_stnk`
--

INSERT INTO `registrasi_stnk` (`id`, `tanggal`, `nopol`, `pemilik`, `jenis_kendaraan`, `jenis_plat`, `jenis_layanan`, `created_at`, `updated_at`) VALUES
(1, '2026-03-05', '12021918', 'Junita', 'R2', 'Kuning', 'Fiskal', '2026-03-05 12:09:25', NULL),
(2, '2026-03-05', '2122112', 'Adi', 'R2', 'Kuning', 'Fiskal', '2026-03-05 12:10:23', NULL),
(3, '2025-01-03', 'DP 1201 AB', 'Andi', 'R2', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(4, '2025-01-05', 'DP 2204 TG', 'Juni', 'R2', 'Kuning', 'Duplikat', '2026-03-05 12:20:31', NULL),
(5, '2025-01-07', 'DP 3456 CD', 'Rina', 'R4', 'Putih', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(6, '2025-01-09', 'DP 7788 EF', 'Budi', 'R2', 'Putih', 'BBN1', '2026-03-05 12:20:31', NULL),
(7, '2025-01-12', 'DP 9012 GH', 'Sari', 'R4', 'Merah', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(8, '2025-01-15', 'DP 1122 IJ', 'Rahmat', 'R2', 'Kuning', 'Fiskal', '2026-03-05 12:20:31', NULL),
(9, '2025-01-18', 'DP 3344 KL', 'Dewi', 'R4', 'Putih', 'BBN2', '2026-03-05 12:20:31', NULL),
(10, '2025-01-22', 'DP 5566 MN', 'Fajar', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(11, '2025-01-25', 'DP 7781 OP', 'Nabila', 'R4', 'Kuning', 'Rubah', '2026-03-05 12:20:31', NULL),
(12, '2025-01-28', 'DP 9901 QR', 'Rizky', 'R2', 'Putih', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(13, '2025-02-02', 'DP 1010 ST', 'Agus', 'R4', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(14, '2025-02-04', 'DP 2020 UV', 'Maya', 'R2', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(15, '2025-02-06', 'DP 3030 WX', 'Ilham', 'R2', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(16, '2025-02-08', 'DP 4040 YZ', 'Lina', 'R4', 'Putih', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(17, '2025-02-10', 'DP 5050 AA', 'Dian', 'R2', 'Merah', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(18, '2025-02-12', 'DP 6060 BB', 'Arman', 'R4', 'Kuning', 'BBN2', '2026-03-05 12:20:31', NULL),
(19, '2025-02-14', 'DP 7070 CC', 'Wulan', 'R2', 'Putih', 'Fiskal', '2026-03-05 12:20:31', NULL),
(20, '2025-02-16', 'DP 8080 DD', 'Hendra', 'R4', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(21, '2025-02-20', 'DP 9090 EE', 'Farhan', 'R2', 'Kuning', 'Rubah', '2026-03-05 12:20:31', NULL),
(22, '2025-02-24', 'DP 1212 FF', 'Yuni', 'R4', 'Putih', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(23, '2025-03-01', 'DP 1313 GG', 'Fikri', 'R2', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(24, '2025-03-03', 'DP 1414 HH', 'Intan', 'R4', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(25, '2025-03-05', 'DP 1515 II', 'Bayu', 'R2', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(26, '2025-03-07', 'DP 1616 JJ', 'Nina', 'R4', 'Merah', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(27, '2025-03-09', 'DP 1717 KK', 'Rudi', 'R2', 'Kuning', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(28, '2025-03-11', 'DP 1818 LL', 'Salsa', 'R4', 'Putih', 'BBN2', '2026-03-05 12:20:31', NULL),
(29, '2025-03-13', 'DP 1919 MM', 'Hani', 'R2', 'Merah', 'Fiskal', '2026-03-05 12:20:31', NULL),
(30, '2025-03-15', 'DP 2021 NN', 'Yusuf', 'R4', 'Kuning', 'Duplikat', '2026-03-05 12:20:31', NULL),
(31, '2025-03-18', 'DP 2122 OO', 'Tika', 'R2', 'Putih', 'Rubah', '2026-03-05 12:20:31', NULL),
(32, '2025-03-22', 'DP 2323 PP', 'Alif', 'R4', 'Merah', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(33, '2025-03-25', 'DP 2424 QQ', 'Putri', 'R2', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(34, '2025-03-28', 'DP 2525 RR', 'Doni', 'R4', 'Putih', 'BBN2', '2026-03-05 12:20:31', NULL),
(35, '2025-04-02', 'DP 2626 SS', 'Rama', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(36, '2025-04-04', 'DP 2727 TT', 'Citra', 'R4', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(37, '2025-04-06', 'DP 2828 UU', 'Dede', 'R2', 'Putih', 'Fiskal', '2026-03-05 12:20:31', NULL),
(38, '2025-04-08', 'DP 2929 VV', 'Mila', 'R4', 'Putih', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(39, '2025-04-10', 'DP 3031 WW', 'Iqbal', 'R2', 'Merah', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(40, '2025-04-12', 'DP 3131 XX', 'Nadya', 'R4', 'Kuning', 'BBN2', '2026-03-05 12:20:31', NULL),
(41, '2025-04-14', 'DP 3232 YY', 'Hafiz', 'R2', 'Putih', 'Rubah', '2026-03-05 12:20:31', NULL),
(42, '2025-04-16', 'DP 3333 ZZ', 'Aulia', 'R4', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(43, '2025-04-20', 'DP 3434 AB', 'Reni', 'R2', 'Kuning', 'Duplikat', '2026-03-05 12:20:31', NULL),
(44, '2025-04-24', 'DP 3535 BC', 'Arif', 'R4', 'Putih', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(45, '2025-05-01', 'DP 3636 CD', 'Nanda', 'R2', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(46, '2025-05-03', 'DP 3737 DE', 'Sinta', 'R4', 'Kuning', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(47, '2025-05-05', 'DP 3838 EF', 'Rio', 'R2', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(48, '2025-05-07', 'DP 3939 FG', 'Eka', 'R4', 'Merah', 'BBN2', '2026-03-05 12:20:31', NULL),
(49, '2025-05-09', 'DP 4041 GH', 'Fauzan', 'R2', 'Kuning', 'Fiskal', '2026-03-05 12:20:31', NULL),
(50, '2025-05-11', 'DP 4141 HI', 'Yolanda', 'R4', 'Putih', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(51, '2025-05-13', 'DP 4242 IJ', 'Rangga', 'R2', 'Merah', 'Rubah', '2026-03-05 12:20:31', NULL),
(52, '2025-05-15', 'DP 4343 JK', 'Meta', 'R4', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(53, '2025-05-18', 'DP 4444 KL', 'Dimas', 'R2', 'Putih', 'BBN2', '2026-03-05 12:20:31', NULL),
(54, '2025-05-22', 'DP 4545 LM', 'Tari', 'R4', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(55, '2025-05-25', 'DP 4646 MN', 'Bagas', 'R2', 'Kuning', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(56, '2025-05-28', 'DP 4747 NO', 'Lukman', 'R4', 'Putih', 'BBN1', '2026-03-05 12:20:31', NULL),
(57, '2025-06-02', 'DP 4848 OP', 'Juni', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(58, '2025-06-04', 'DP 4949 PQ', 'Sari', 'R4', 'Kuning', 'BBN1', '2026-03-05 12:20:31', NULL),
(59, '2025-06-06', 'DP 5051 QR', 'Andi', 'R2', 'Putih', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(60, '2025-06-08', 'DP 5151 RS', 'Dewi', 'R4', 'Merah', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(61, '2025-06-10', 'DP 5252 ST', 'Budi', 'R2', 'Kuning', 'BBN2', '2026-03-05 12:20:31', NULL),
(62, '2025-06-12', 'DP 22044 TG', 'Juni', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(63, '2025-06-12', 'DP 22044 TG', 'Juni', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(64, '2025-06-12', 'DP 22044 TG', 'Juni', 'R2', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(65, '2025-06-12', 'DP 22044 TG', 'Juni', 'R2', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(66, '2025-06-12', 'DP 22044 TG', 'Juni', 'R4', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(67, '2025-06-12', 'DP 22044 TG', 'Juni', 'R4', 'Putih', 'Duplikat', '2026-03-05 12:20:31', NULL),
(68, '2025-06-12', 'DP 22044 TG', 'Juni', 'R4', 'Kuning', 'Duplikat', '2026-03-05 12:20:31', NULL),
(69, '2025-06-14', 'DP 5353 TU', 'Rina', 'R2', 'Putih', 'Fiskal', '2026-03-05 12:20:31', NULL),
(70, '2025-06-16', 'DP 5454 UV', 'Rahmat', 'R4', 'Merah', 'BBN1', '2026-03-05 12:20:31', NULL),
(71, '2025-06-18', 'DP 5555 VW', 'Nabila', 'R2', 'Kuning', 'Rubah', '2026-03-05 12:20:31', NULL),
(72, '2025-06-20', 'DP 5656 WX', 'Fajar', 'R4', 'Putih', 'BBN2', '2026-03-05 12:20:31', NULL),
(73, '2025-06-22', 'DP 5757 XY', 'Maya', 'R2', 'Merah', 'Ganti Plat', '2026-03-05 12:20:31', NULL),
(74, '2025-06-24', 'DP 5858 YZ', 'Agus', 'R4', 'Kuning', 'Ganti Nopol', '2026-03-05 12:20:31', NULL),
(75, '2025-06-26', 'DP 5959 ZA', 'Dian', 'R2', 'Putih', 'BBN1', '2026-03-05 12:20:31', NULL),
(76, '2025-06-28', 'DP 6061 AB', 'Arman', 'R4', 'Merah', 'Duplikat', '2026-03-05 12:20:31', NULL),
(77, '2026-03-20', 'DD 28171 AE', 'Afdal', 'R2', 'Merah', 'Ganti Nopol', '2026-03-05 12:42:09', '2026-03-05 12:42:21'),
(78, '2026-04-05', '19218171', 'Anjas', 'R2', 'Merah', 'BBN1', '2026-03-05 13:03:15', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('pegawai','admin') DEFAULT 'pegawai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `nama`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$islBHmsEEjLHvGAPBs5Wne2NAIl8H1nCikAUcvV2/dwP1UH9r9XUS', 'Admin Samsat', 'admin', '2026-03-05 12:00:29');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `registrasi_stnk`
--
ALTER TABLE `registrasi_stnk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_nopol` (`nopol`),
  ADD KEY `idx_kendaraan` (`jenis_kendaraan`),
  ADD KEY `idx_plat` (`jenis_plat`),
  ADD KEY `idx_layanan` (`jenis_layanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `registrasi_stnk`
--
ALTER TABLE `registrasi_stnk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
