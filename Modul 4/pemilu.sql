-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Apr 2024 pada 11.55
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
-- Database: `pemilu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pemilihan`
--

CREATE TABLE `hasil_pemilihan` (
  `id_pemilih` varchar(6) DEFAULT NULL,
  `id_kandidat` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `hasil_pemilihan`
--
DELIMITER $$
CREATE TRIGGER `update_status_pemilih` AFTER INSERT ON `hasil_pemilihan` FOR EACH ROW BEGIN
    UPDATE Pemilih
    SET status_pemilih = 'Sudah Memilih'
    WHERE id_pemilih = NEW.id_pemilih;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_suara` AFTER INSERT ON `hasil_pemilihan` FOR EACH ROW BEGIN
    UPDATE Jumlah_Suara
    SET jumlah_suara = jumlah_suara + 1
    WHERE id_kandidat = NEW.id_kandidat;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jumlah_suara`
--

CREATE TABLE `jumlah_suara` (
  `id_kandidat` varchar(6) DEFAULT NULL,
  `jumlah_suara` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jumlah_suara`
--

INSERT INTO `jumlah_suara` (`id_kandidat`, `jumlah_suara`) VALUES
('K001', 0),
('K002', 0),
('K003', 0),
('K004', 0),
('K005', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kandidat`
--

CREATE TABLE `kandidat` (
  `id_kandidat` varchar(6) NOT NULL,
  `nama_kandidat` varchar(100) DEFAULT NULL,
  `motto_kandidat` varchar(100) DEFAULT NULL,
  `partai_kandidat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kandidat`
--

INSERT INTO `kandidat` (`id_kandidat`, `nama_kandidat`, `motto_kandidat`, `partai_kandidat`) VALUES
('K001', 'Joko Widodo', 'Kerja Keras', 'PDIP'),
('K002', 'Prabowo Subianto', 'Kerja Cerdas', 'Gerindra'),
('K003', 'Anies Baswedan', 'Kerja Tuntas', 'PKS'),
('K004', 'Ridwan Kamil', 'Kerja Nyata', 'PKB'),
('K005', 'Ganjar Pranowo', 'Kerja Ikhlas', 'Golkar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemilih`
--

CREATE TABLE `pemilih` (
  `id_pemilih` varchar(6) NOT NULL,
  `nama_pemilih` varchar(100) DEFAULT NULL,
  `email_pemilih` varchar(100) DEFAULT NULL,
  `password_pemilih` varchar(100) DEFAULT NULL,
  `alamat_pemilih` varchar(100) DEFAULT NULL,
  `no_telp` varchar(12) DEFAULT NULL,
  `status_pemilih` enum('Belum Memilih','Sudah Memilih') DEFAULT 'Belum Memilih'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemilih`
--

INSERT INTO `pemilih` (`id_pemilih`, `nama_pemilih`, `email_pemilih`, `password_pemilih`, `alamat_pemilih`, `no_telp`, `status_pemilih`) VALUES
('II001', 'Irwin', 'aippirwin@gmail.com', 'aipp123', 'jl.iskandar', '082254892043', 'Belum Memilih'),
('PP002', 'Putra Pangesti Yeah', 'pangesti@gmail.com', 'pangesti', 'jl.mana aja', '082254892043', 'Belum Memilih');

--
-- Trigger `pemilih`
--
DELIMITER $$
CREATE TRIGGER `input_id_pemilih` BEFORE INSERT ON `pemilih` FOR EACH ROW BEGIN
    SET NEW.id_pemilih = CONCAT(UPPER(LEFT(NEW.nama_pemilih, 1)), UPPER(SUBSTRING(NEW.nama_pemilih, LOCATE(' ', NEW.nama_pemilih)+1, 1)), LPAD((SELECT COUNT(*)+1 FROM Pemilih), 3, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hasil_pemilihan`
--
ALTER TABLE `hasil_pemilihan`
  ADD KEY `id_pemilih` (`id_pemilih`),
  ADD KEY `id_kandidat` (`id_kandidat`);

--
-- Indeks untuk tabel `jumlah_suara`
--
ALTER TABLE `jumlah_suara`
  ADD KEY `id_kandidat` (`id_kandidat`);

--
-- Indeks untuk tabel `kandidat`
--
ALTER TABLE `kandidat`
  ADD PRIMARY KEY (`id_kandidat`);

--
-- Indeks untuk tabel `pemilih`
--
ALTER TABLE `pemilih`
  ADD PRIMARY KEY (`id_pemilih`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_pemilihan`
--
ALTER TABLE `hasil_pemilihan`
  ADD CONSTRAINT `hasil_pemilihan_ibfk_1` FOREIGN KEY (`id_pemilih`) REFERENCES `pemilih` (`id_pemilih`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_pemilihan_ibfk_2` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jumlah_suara`
--
ALTER TABLE `jumlah_suara`
  ADD CONSTRAINT `jumlah_suara_ibfk_1` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
