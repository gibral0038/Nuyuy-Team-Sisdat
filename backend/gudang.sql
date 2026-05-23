-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2026 at 05:34 AM
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
-- Memastikan Database `db_gudang` Terbuat dan Terpilih Otomatis
--
CREATE DATABASE IF NOT EXISTS `db_gudang`;
USE `db_gudang`;

-- --------------------------------------------------------

--
-- Table structure for table `best_seller`
--

CREATE TABLE `best_seller` (
  `id_best_seller` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_produk` int(11) DEFAULT NULL,
  `jumlah_terjual` int(11) DEFAULT NULL,
  `periode` enum('bulan','tahun') DEFAULT NULL,
  PRIMARY KEY (`id_best_seller`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `id_gudang` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_produk` int(11) DEFAULT NULL,
  `stok_awal` int(11) DEFAULT NULL,
  `stok_sekarang` int(11) DEFAULT NULL,
  `tanggal_update` date DEFAULT NULL,
  PRIMARY KEY (`id_gudang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_penjualan`
--

CREATE TABLE `laporan_penjualan` (
  `id_laporan` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_produk` int(11) DEFAULT NULL,
  `jumlah_terjual` int(11) DEFAULT NULL,
  `tanggal_laporan` date DEFAULT NULL,
  PRIMARY KEY (`id_laporan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `nama_supplier` varchar(50) DEFAULT NULL,
  `alamat_supplier` varchar(50) DEFAULT NULL,
  `kontak_supplier` varchar(20) DEFAULT NULL, -- Diubah ke VARCHAR agar nomor hp dengan angka '0' depan tidak hilang
  `id_pengguna` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT, -- Ditambahkan Auto Increment
  `id_supplier` int(11) DEFAULT NULL,
  `nama_produk` varchar(50) DEFAULT NULL,
  `deskripsi_produk` varchar(100) DEFAULT NULL,
  `harga_produk` int(11) DEFAULT NULL,
  `stok_produk` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes & Relasi (Constraints) untuk Tabel Gudang
--

ALTER TABLE `best_seller` ADD KEY `fk_best_seller_produk` (`id_produk`);
ALTER TABLE `gudang` ADD KEY `fk_gudang_produk` (`id_produk`);
ALTER TABLE `laporan_penjualan` ADD KEY `fk_laporan_penjualan_produk` (`id_produk`);
ALTER TABLE `produk` ADD KEY `fk_produk_supplier` (`id_supplier`);
ALTER TABLE `supplier` ADD KEY `fk_supplier_pengguna` (`id_pengguna`);

--
-- Aturan Foreign Key Fisik (Internal db_gudang saja)
--
ALTER TABLE `best_seller`
  ADD CONSTRAINT `fk_best_seller_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `gudang`
  ADD CONSTRAINT `fk_gudang_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `laporan_penjualan`
  ADD CONSTRAINT `fk_laporan_penjualan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;