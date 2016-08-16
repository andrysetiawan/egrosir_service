-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2016 at 07:12 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_egrosir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(15) NOT NULL,
  `id_pasar` int(15) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto` text,
  `nik` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id` int(15) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `harga` decimal(20,2) DEFAULT NULL,
  `harga_diskon` decimal(20,0) DEFAULT NULL,
  `stock` int(15) DEFAULT NULL,
  `deskripsi` text,
  `status_nego` enum('N','Y') NOT NULL DEFAULT 'N',
  `status_diskon` enum('N','Y') NOT NULL DEFAULT 'N',
  `status_cuci_gudang` enum('N','Y') NOT NULL DEFAULT 'N',
  `jasa_pengiriman` varchar(100) DEFAULT NULL,
  `berat` float DEFAULT NULL,
  `gambar` text,
  `ukuran_tersedia` varchar(100) DEFAULT NULL,
  `id_kategori` int(15) DEFAULT NULL,
  `id_pasar` int(15) DEFAULT NULL,
  `status_iklan` enum('N','Y') NOT NULL DEFAULT 'Y',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_cart`
--

CREATE TABLE `tb_cart` (
  `id` int(15) NOT NULL,
  `id_barang` int(15) DEFAULT NULL,
  `id_user` int(15) DEFAULT NULL,
  `tgl_pesan` datetime DEFAULT NULL,
  `ukuran` varchar(45) DEFAULT NULL,
  `jumlah_barang` int(15) DEFAULT NULL,
  `alamat_pengiriman` text,
  `status_transaksi` enum('N','Y') NOT NULL DEFAULT 'N',
  `metode_pembayaran` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id` int(15) NOT NULL,
  `nama_kategori` varchar(45) DEFAULT NULL,
  `gambar` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kode_promo`
--

CREATE TABLE `tb_kode_promo` (
  `id` int(15) NOT NULL,
  `kode` varchar(45) DEFAULT NULL,
  `nilai` decimal(20,2) DEFAULT NULL,
  `tgl_kadaluarsa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_komplain`
--

CREATE TABLE `tb_komplain` (
  `id` int(15) NOT NULL,
  `id_transaksi` int(15) DEFAULT NULL,
  `pesan` text,
  `id_user` int(15) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_konf_pembayaran`
--

CREATE TABLE `tb_konf_pembayaran` (
  `id` int(15) NOT NULL,
  `id_transaksi` int(15) DEFAULT NULL,
  `bukti_transaksi` text,
  `no_transfer` varchar(100) DEFAULT NULL,
  `nama_pemilik_rekening` varchar(45) DEFAULT NULL,
  `id_bank_tujuan` varchar(45) DEFAULT NULL,
  `no_rekening_pengirim` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pasar`
--

CREATE TABLE `tb_pasar` (
  `id` int(11) NOT NULL,
  `nama_pasar` varchar(45) DEFAULT NULL,
  `alamat` text,
  `gambar` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_rate`
--

CREATE TABLE `tb_rate` (
  `id` int(15) NOT NULL,
  `id_transaksi` int(15) DEFAULT NULL,
  `komentar` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_transaksi`
--

CREATE TABLE `tb_status_transaksi` (
  `id` int(15) NOT NULL,
  `nama_status` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_token`
--

CREATE TABLE `tb_token` (
  `id` int(15) NOT NULL,
  `id_user` int(15) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(15) NOT NULL,
  `id_cart` int(15) DEFAULT NULL,
  `total_harga` decimal(20,2) DEFAULT NULL,
  `jasa_pengiriman` varchar(45) DEFAULT NULL,
  `tgl_transaksi` datetime DEFAULT NULL,
  `konfirmasi_pembayaran` enum('N','Y') NOT NULL DEFAULT 'N',
  `id_status_transaksi` int(15) DEFAULT NULL,
  `kode_promo` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(15) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `alamat` text,
  `kelurahan` varchar(45) DEFAULT NULL,
  `kecamatan` varchar(45) DEFAULT NULL,
  `kabupaten_kota` varchar(45) DEFAULT NULL,
  `propinsi` varchar(45) DEFAULT NULL,
  `kelamin` char(1) DEFAULT NULL,
  `hp` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `verified` enum('N','Y') NOT NULL DEFAULT 'N',
  `created_at` datetime DEFAULT NULL,
  `is_deleted` enum('N','Y') NOT NULL DEFAULT 'N',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kode_promo`
--
ALTER TABLE `tb_kode_promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_komplain`
--
ALTER TABLE `tb_komplain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_konf_pembayaran`
--
ALTER TABLE `tb_konf_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pasar`
--
ALTER TABLE `tb_pasar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_rate`
--
ALTER TABLE `tb_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_transaksi`
--
ALTER TABLE `tb_status_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_token`
--
ALTER TABLE `tb_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_cart`
--
ALTER TABLE `tb_cart`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_kode_promo`
--
ALTER TABLE `tb_kode_promo`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_komplain`
--
ALTER TABLE `tb_komplain`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_konf_pembayaran`
--
ALTER TABLE `tb_konf_pembayaran`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_pasar`
--
ALTER TABLE `tb_pasar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_rate`
--
ALTER TABLE `tb_rate`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_status_transaksi`
--
ALTER TABLE `tb_status_transaksi`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_token`
--
ALTER TABLE `tb_token`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
