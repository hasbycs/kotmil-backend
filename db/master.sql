-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Apr 2021 pada 13.56
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `master`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT current_timestamp(),
  `create_by` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `banner`
--

INSERT INTO `banner` (`id`, `name`, `url`, `description`, `create_at`, `update_at`, `create_by`) VALUES
(1, 'agam tabik', 'https://lh3.googleusercontent.com/pw/ACtC-3dmjfy9Vw-qlBoBfmD-kUzb_NY5amcmq2K4cMtQ15-ypLaiz0KhTcKLuBcJ9BhUgRVoB4Hwfm4jwT1Hoel-oAruIvcnpkoKw3YxxD6Zhe6Wl41pHDzBW5nugXYnUjkOzd97xwm_4aTuXey5OFxo78SO=w1454-h969-no', 'ini adalah kita', '2021-03-10 13:48:16', '2021-03-10 13:48:16', 'hasbycs'),
(2, 'Ngalau', 'https://lh3.googleusercontent.com/pw/ACtC-3eV-_onf8HrpOP2BtNLOBvR6PuWA75pUfwCSxRMqopMBMir70OaMtQJ1xgYNU_xZ8vi62EGdahLH0ZW1kmIG43n--740_vuLLQmSvQcFzkAHWu5NDpZM0WdrfKh5eGWAcRhvRTRIDLwJrjOvIP2tmjU=w1454-h969-no', 'ini di dalam Ngalau', '2021-03-10 13:48:55', '2021-03-10 13:48:55', 'hasbycs'),
(3, 'Ketua mah', 'https://lh3.googleusercontent.com/pw/ACtC-3dtf8d7aWzKCPH-rMSHIR7zTqkavJl6mWh2LewHC7RyKZEGQgr1FClhAKiuFvcjDn6QK3IW7vb_-Ny6VNwTHffGm_EABlLXOYU4LCjhq7ZxtWQguY3hYehtzGBBHsYvjanblTu6PysVhKaYi0H-WzWb=w1454-h969-no', 'ini ketua dan anggota', '2021-03-10 13:49:32', '2021-03-10 13:49:32', 'hasbycs');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
