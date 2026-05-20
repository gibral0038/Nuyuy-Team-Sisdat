-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2026 at 08:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Memastikan Database `db_penjualan` Terbuat dan Terpilih Otomatis
--
CREATE DATABASE IF NOT EXISTS `db_penjualan`;
USE `db_penjualan`;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `nama_pengguna` varchar(50) DEFAULT NULL,
  `email_pengguna` varchar(50) DEFAULT NULL,
  `password_pengguna` varchar(255) DEFAULT NULL, -- Diubah ke 255 agar muat jika password di-hash (keamanan standar)
  `role_pengguna` enum('admin','customer','supplier') DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_pengguna` int(11) DEFAULT NULL,
  `tanggal_pesanan` date DEFAULT NULL,
  `status_pesanan` enum('pending','diproses','selesai') DEFAULT NULL,
  PRIMARY KEY (`id_pesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_pesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL, -- Tetap ada untuk relasi logis ke db_gudang
  `jumlah` int(11) DEFAULT NULL,
  `harga_total` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detail_pesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_pesanan` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `jumlah_pembayaran` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes untuk Relasi Internal db_penjualan
--
ALTER TABLE `detail_pesanan` ADD KEY `fk_detail_pesanan_pesanan` (`id_pesanan`);
ALTER TABLE `detail_pesanan` ADD KEY `fk_detail_pesanan_produk` (`id_produk`);
ALTER TABLE `pembayaran` ADD KEY `fk_pembayaran_pesanan` (`id_pesanan`);
ALTER TABLE `pesanan` ADD KEY `fk_pesanan_pengguna` (`id_pengguna`);

--
-- Aturan Foreign Key Fisik (Hanya yang lokal di dalam db_penjualan)
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_detail_pesanan_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;