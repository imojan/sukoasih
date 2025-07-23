-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 11:34 AM
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
-- Database: `klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_resep`
--

CREATE TABLE `tb_detail_resep` (
  `id_detail` int(11) NOT NULL,
  `id_resep` varchar(15) NOT NULL,
  `id_obat` int(3) NOT NULL,
  `cara_minum` text NOT NULL,
  `jumlah` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_detail_resep`
--

INSERT INTO `tb_detail_resep` (`id_detail`, `id_resep`, `id_obat`, `cara_minum`, `jumlah`) VALUES
(12, 'RO20250720-001', 3, 'Apabila ada demam', 6),
(15, 'RO20250719-001', 1, '2 x 1 Setelah Makan', 20),
(16, 'RO20250719-001', 3, '2 x 1 Setelah Makan', 8);

-- --------------------------------------------------------

--
-- Table structure for table `tb_dokter`
--

CREATE TABLE `tb_dokter` (
  `id_dokter` int(3) NOT NULL,
  `nm_dokter` varchar(60) NOT NULL,
  `id_poli` int(3) NOT NULL,
  `telp_dokter` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dokter`
--

INSERT INTO `tb_dokter` (`id_dokter`, `nm_dokter`, `id_poli`, `telp_dokter`) VALUES
(1, 'dr. Andy Hermawan, Sp.OG.', 1, '081234567890'),
(2, 'dr. Hanang Novianto', 2, '081224566890'),
(3, 'dr. Erma Rachmayanti', 2, '081134566890'),
(4, 'drg. Sigit Siswanto ', 3, '081222567890'),
(5, 'H. Suminem, Amd.Keb., S.ST', 4, '0812345678910');

-- --------------------------------------------------------

--
-- Table structure for table `tb_obat`
--

CREATE TABLE `tb_obat` (
  `id_obat` int(3) NOT NULL,
  `nm_obat` varchar(60) NOT NULL,
  `bentuk_obat` enum('Tablet','Kapsul','Sirup','Salep','Injeksi','Strip') NOT NULL,
  `stok_obat` smallint(4) UNSIGNED NOT NULL,
  `exp_obat` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_obat`
--

INSERT INTO `tb_obat` (`id_obat`, `nm_obat`, `bentuk_obat`, `stok_obat`, `exp_obat`) VALUES
(1, 'Amoxcillin', 'Strip', 380, '2027-02-12'),
(2, 'Vit B12', 'Tablet', 164, '2028-02-21'),
(3, 'Paracetamol', 'Kapsul', 4, '2027-03-12'),
(4, 'Calorex', 'Sirup', 20, '2027-08-25'),
(5, 'CTM', 'Tablet', 60, '2028-12-12'),
(6, 'Microgynon', 'Strip', 49, '2026-09-11');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pasien`
--

CREATE TABLE `tb_pasien` (
  `no_rm` int(6) NOT NULL,
  `nik` bigint(16) NOT NULL,
  `nm_pasien` varchar(60) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `telp_psn` varchar(15) NOT NULL,
  `alergi_obat` text NOT NULL,
  `riwayat_penyakit` text NOT NULL,
  `nm_pj` varchar(60) NOT NULL,
  `telp_pj` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pasien`
--

INSERT INTO `tb_pasien` (`no_rm`, `nik`, `nm_pasien`, `jenis_kelamin`, `tgl_lahir`, `alamat`, `telp_psn`, `alergi_obat`, `riwayat_penyakit`, `nm_pj`, `telp_pj`) VALUES
(1, 3171234567889012, 'Intan', 'Perempuan', '2003-10-16', 'Pacitan', '081234567890', 'Tidak ada', 'Ada', 'Arisukma', '0856789012345'),
(2, 3123456789011223, 'Keisyabella ', 'Perempuan', '2004-01-25', 'Pekalongan', '089523132578', 'Tidak Ada', 'Tidak ada', 'Safira', '088900123456'),
(3, 3310187354678990, 'Roewina', 'Perempuan', '2003-06-23', 'Klaten', '087654321246', '-', 'Vertigo', 'Kusuma', '087678954312'),
(4, 1103153108910056, 'Adi Kurniawan', 'Laki-Laki', '1991-08-31', 'Jawa Tengah', '08123456789', 'Vit C', 'Tidak ada', 'Giyem', 'Tidak Punya'),
(5, 1234567891011123, 'Yuli', 'Perempuan', '2004-07-02', 'Jawa Tengah', '081234567890', '-', 'Anemia', 'Gipanda', '0'),
(6, 3312345678901011, 'Hartati', 'Perempuan', '1996-04-08', 'Sukoharjo, Jawa Tengah', '081234567890', '-', '', 'Dani', '087678954312');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan`
--

CREATE TABLE `tb_pemeriksaan` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `id_pendaftaran` varchar(15) NOT NULL,
  `id_user` int(3) DEFAULT NULL,
  `no_rm` int(6) DEFAULT NULL,
  `id_poli` int(3) DEFAULT NULL,
  `id_dokter` int(3) DEFAULT NULL,
  `tgl_pemeriksaan` date NOT NULL,
  `status` enum('0','1','2','3') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan`
--

INSERT INTO `tb_pemeriksaan` (`id_pemeriksaan`, `id_pendaftaran`, `id_user`, `no_rm`, `id_poli`, `id_dokter`, `tgl_pemeriksaan`, `status`) VALUES
('PR20250622-001', 'PD20250622-001', 4, 1, 1, 1, '2025-06-22', '2'),
('PR20250719-001', 'PD20250624-002', 4, 5, 4, 5, '2025-07-19', '1'),
('PR20250720-001', 'PD20250711-001', 4, 4, 2, 2, '2025-07-20', '1'),
('PR20250720-002', 'PD20250705-001', 4, 2, 3, 4, '2025-07-20', '1'),
('PR20250720-003', 'PD20250630-001', 4, 3, 4, 5, '2025-07-20', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan_gigi`
--

CREATE TABLE `tb_pemeriksaan_gigi` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `S` text NOT NULL,
  `O` text NOT NULL,
  `kd_diagnosa` varchar(7) NOT NULL,
  `A` text NOT NULL,
  `P` text NOT NULL,
  `tindak_lanjut` enum('Selesai','Kontrol','Rujuk','Rawat Inap') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan_gigi`
--

INSERT INTO `tb_pemeriksaan_gigi` (`id_pemeriksaan`, `S`, `O`, `kd_diagnosa`, `A`, `P`, `tindak_lanjut`) VALUES
('PR20250720-002', '-', '-', '-', '-', '-', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan_imunisasi`
--

CREATE TABLE `tb_pemeriksaan_imunisasi` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `riwayat_imunisasi` text NOT NULL,
  `jenis_imunisasi` enum('HB-0','BCG','DPT-HB-Hib','OPV','PCV','RV','IPV','MR','JE*','DT','Td','HPV','Lainnya') NOT NULL,
  `tgl_imunisasi` date NOT NULL,
  `diagnosa` text NOT NULL,
  `kd_diagnosa` varchar(7) NOT NULL,
  `terapi` text NOT NULL,
  `tindak_lanjut` enum('Selesai','Kontrol','Rujuk','Rawat Inap') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan_imunisasi`
--

INSERT INTO `tb_pemeriksaan_imunisasi` (`id_pemeriksaan`, `riwayat_imunisasi`, `jenis_imunisasi`, `tgl_imunisasi`, `diagnosa`, `kd_diagnosa`, `terapi`, `tindak_lanjut`) VALUES
('PR20250719-001', 'Campak', 'HPV', '2025-07-19', 'Vaksin rutin', '-', 'Pemberian vaksin ', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan_kb`
--

CREATE TABLE `tb_pemeriksaan_kb` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `jenis_kb` enum('TF','CF','DG','IUD','Pil') NOT NULL,
  `tgl_pasang` date NOT NULL,
  `anamnesa` text NOT NULL,
  `diagnosa` text NOT NULL,
  `kd_diagnosa` varchar(7) NOT NULL,
  `terapi` text NOT NULL,
  `tindak_lanjut` enum('Selesai','Kontrol','Rujuk','Rawat Inap') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan_kb`
--

INSERT INTO `tb_pemeriksaan_kb` (`id_pemeriksaan`, `jenis_kb`, `tgl_pasang`, `anamnesa`, `diagnosa`, `kd_diagnosa`, `terapi`, `tindak_lanjut`) VALUES
('PR20250720-003', 'Pil', '2025-01-12', 'Kontrol Rutin Bulanan, Muncul Jerawat yang Tidak Biasa', '-', '-', '-', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan_obgyn`
--

CREATE TABLE `tb_pemeriksaan_obgyn` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `hpht` date NOT NULL,
  `hpl` date NOT NULL,
  `td` varchar(7) NOT NULL,
  `bb` decimal(5,2) NOT NULL,
  `lila` decimal(4,1) NOT NULL,
  `umur_hamil` int(3) NOT NULL,
  `gpa` varchar(10) NOT NULL,
  `riwayat_persalinan` text NOT NULL,
  `keluhan` text NOT NULL,
  `diagnosa` text NOT NULL,
  `kd_diagnosa` varchar(7) NOT NULL,
  `terapi` text NOT NULL,
  `tindak_lanjut` enum('Selesai','Kontrol','Rujuk','Rawat Inap') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan_obgyn`
--

INSERT INTO `tb_pemeriksaan_obgyn` (`id_pemeriksaan`, `hpht`, `hpl`, `td`, `bb`, `lila`, `umur_hamil`, `gpa`, `riwayat_persalinan`, `keluhan`, `diagnosa`, `kd_diagnosa`, `terapi`, `tindak_lanjut`) VALUES
('PR20250622-001', '2022-12-12', '2022-12-12', '120/80', 65.00, 23.5, 21, 'G1P0A0', 'Belum pernah', 'Mual, Muntah saat Malam Hari', 'Morning Sickness', '-', '-', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemeriksaan_umum`
--

CREATE TABLE `tb_pemeriksaan_umum` (
  `id_pemeriksaan` varchar(15) NOT NULL,
  `bb` decimal(5,2) NOT NULL,
  `tb` decimal(5,2) NOT NULL,
  `suhu` decimal(4,1) NOT NULL,
  `td` varchar(7) NOT NULL,
  `keluhan` text NOT NULL,
  `diagnosa` text NOT NULL,
  `kd_diagnosa` varchar(7) NOT NULL,
  `terapi` text NOT NULL,
  `tindak_lanjut` enum('Selesai','Kontrol','Rujuk','Rawat Inap') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pemeriksaan_umum`
--

INSERT INTO `tb_pemeriksaan_umum` (`id_pemeriksaan`, `bb`, `tb`, `suhu`, `td`, `keluhan`, `diagnosa`, `kd_diagnosa`, `terapi`, `tindak_lanjut`) VALUES
('PR20250720-001', 60.00, 170.00, 39.0, '120/80', 'Panas dingin, Pucat, BAB cair', '-', '-', '-', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pendaftaran`
--

CREATE TABLE `tb_pendaftaran` (
  `id_pendaftaran` varchar(15) NOT NULL,
  `no_rm` int(6) NOT NULL,
  `tgl_pendaftaran` datetime NOT NULL,
  `id_dokter` int(3) NOT NULL,
  `id_poli` int(3) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `jenis_kia` enum('Umum','Obgyn','Imunisasi','KB') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pendaftaran`
--

INSERT INTO `tb_pendaftaran` (`id_pendaftaran`, `no_rm`, `tgl_pendaftaran`, `id_dokter`, `id_poli`, `status`, `jenis_kia`) VALUES
('PD20250622-001', 1, '2025-06-22 10:22:00', 1, 1, '0', ''),
('PD20250624-001', 1, '2025-06-24 13:39:00', 5, 4, '1', 'Umum'),
('PD20250624-002', 5, '2025-06-24 13:45:00', 5, 4, '0', 'Imunisasi'),
('PD20250630-001', 3, '2025-06-30 22:20:00', 5, 4, '0', 'KB'),
('PD20250705-001', 2, '2025-07-05 19:38:00', 4, 3, '0', ''),
('PD20250711-001', 4, '2025-07-11 10:12:00', 2, 2, '0', ''),
('PD20250718-001', 5, '2025-07-18 16:40:00', 5, 4, '1', 'Obgyn'),
('PD20250721-001', 1, '2025-07-21 18:37:00', 5, 4, '1', 'Obgyn');

-- --------------------------------------------------------

--
-- Table structure for table `tb_poli`
--

CREATE TABLE `tb_poli` (
  `id_poli` int(3) NOT NULL,
  `nm_poli` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_poli`
--

INSERT INTO `tb_poli` (`id_poli`, `nm_poli`) VALUES
(1, 'Obgyn (Kandungan)'),
(2, 'Umum'),
(3, 'Gigi'),
(4, 'KIA');

-- --------------------------------------------------------

--
-- Table structure for table `tb_resep`
--

CREATE TABLE `tb_resep` (
  `id_resep` varchar(15) NOT NULL,
  `id_pemeriksaan` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_resep`
--

INSERT INTO `tb_resep` (`id_resep`, `id_pemeriksaan`) VALUES
('RO20250719-001', 'PR20250622-001'),
('RO20250720-001', 'PR20250719-001');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(3) NOT NULL,
  `username` varchar(20) NOT NULL,
  `nm_lengkap` varchar(60) NOT NULL,
  `password` varchar(20) NOT NULL,
  `jabatan` enum('pendaftaran','dokter','pimpinan','apoteker') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `nm_lengkap`, `password`, `jabatan`) VALUES
(1, 'pimpinan', 'H. Suminem, Amd.Keb., S.ST', 'pimpinan', 'pimpinan'),
(2, 'dokter', 'dr. Andy Hermawan, Sp.OG', 'dokter', 'dokter'),
(3, 'apoteker', 'Rina Fika Prastiwi, S.Far.Apt', 'apoteker', 'apoteker'),
(4, 'pendaftaran', 'Tika Wulandari, A.Md.Keb', 'Pendaftaran', 'pendaftaran');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_detail_resep`
--
ALTER TABLE `tb_detail_resep`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_resep` (`id_resep`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `tb_dokter`
--
ALTER TABLE `tb_dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `tb_obat`
--
ALTER TABLE `tb_obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `tb_pasien`
--
ALTER TABLE `tb_pasien`
  ADD PRIMARY KEY (`no_rm`);

--
-- Indexes for table `tb_pemeriksaan`
--
ALTER TABLE `tb_pemeriksaan`
  ADD PRIMARY KEY (`id_pemeriksaan`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`),
  ADD KEY `fk_pasien` (`no_rm`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `fk_poli` (`id_poli`),
  ADD KEY `fk_dokter_pemeriksaan` (`id_dokter`) USING BTREE;

--
-- Indexes for table `tb_pemeriksaan_gigi`
--
ALTER TABLE `tb_pemeriksaan_gigi`
  ADD KEY `fk_pemeriksaan_gigi` (`id_pemeriksaan`) USING BTREE;

--
-- Indexes for table `tb_pemeriksaan_imunisasi`
--
ALTER TABLE `tb_pemeriksaan_imunisasi`
  ADD KEY `fk_pemeriksaan_imunisasi` (`id_pemeriksaan`);

--
-- Indexes for table `tb_pemeriksaan_kb`
--
ALTER TABLE `tb_pemeriksaan_kb`
  ADD KEY `fk_pemeriksaan_kb` (`id_pemeriksaan`) USING BTREE;

--
-- Indexes for table `tb_pemeriksaan_obgyn`
--
ALTER TABLE `tb_pemeriksaan_obgyn`
  ADD KEY `fk_pemeriksaan_obgyn` (`id_pemeriksaan`) USING BTREE;

--
-- Indexes for table `tb_pemeriksaan_umum`
--
ALTER TABLE `tb_pemeriksaan_umum`
  ADD KEY `fk_pemeriksaan_umum` (`id_pemeriksaan`) USING BTREE;

--
-- Indexes for table `tb_pendaftaran`
--
ALTER TABLE `tb_pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `id_pasien` (`no_rm`),
  ADD KEY `id_poli` (`id_poli`),
  ADD KEY `fk_pendaftaran_dokter` (`id_dokter`);

--
-- Indexes for table `tb_poli`
--
ALTER TABLE `tb_poli`
  ADD PRIMARY KEY (`id_poli`);

--
-- Indexes for table `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD PRIMARY KEY (`id_resep`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail_resep`
--
ALTER TABLE `tb_detail_resep`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_dokter`
--
ALTER TABLE `tb_dokter`
  MODIFY `id_dokter` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_obat`
--
ALTER TABLE `tb_obat`
  MODIFY `id_obat` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_pasien`
--
ALTER TABLE `tb_pasien`
  MODIFY `no_rm` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_poli`
--
ALTER TABLE `tb_poli`
  MODIFY `id_poli` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detail_resep`
--
ALTER TABLE `tb_detail_resep`
  ADD CONSTRAINT `tb_detail_resep_ibfk_1` FOREIGN KEY (`id_resep`) REFERENCES `tb_resep` (`id_resep`),
  ADD CONSTRAINT `tb_detail_resep_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `tb_obat` (`id_obat`);

--
-- Constraints for table `tb_dokter`
--
ALTER TABLE `tb_dokter`
  ADD CONSTRAINT `tb_dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `tb_poli` (`id_poli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pemeriksaan`
--
ALTER TABLE `tb_pemeriksaan`
  ADD CONSTRAINT `fk_pasien` FOREIGN KEY (`no_rm`) REFERENCES `tb_pasien` (`no_rm`),
  ADD CONSTRAINT `fk_poli` FOREIGN KEY (`id_poli`) REFERENCES `tb_poli` (`id_poli`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tb_pemeriksaan_gigi`
--
ALTER TABLE `tb_pemeriksaan_gigi`
  ADD CONSTRAINT `fk_pemeriksaan_gigi` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `tb_pemeriksaan` (`id_pemeriksaan`) ON DELETE CASCADE;

--
-- Constraints for table `tb_pemeriksaan_imunisasi`
--
ALTER TABLE `tb_pemeriksaan_imunisasi`
  ADD CONSTRAINT `fk_pemeriksaan_imunisasi` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `tb_pemeriksaan` (`id_pemeriksaan`) ON DELETE CASCADE;

--
-- Constraints for table `tb_pemeriksaan_kb`
--
ALTER TABLE `tb_pemeriksaan_kb`
  ADD CONSTRAINT `fk_pemeriksaan` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `tb_pemeriksaan` (`id_pemeriksaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pemeriksaan_obgyn`
--
ALTER TABLE `tb_pemeriksaan_obgyn`
  ADD CONSTRAINT `fk_obgyn` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `tb_pemeriksaan` (`id_pemeriksaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pemeriksaan_umum`
--
ALTER TABLE `tb_pemeriksaan_umum`
  ADD CONSTRAINT `fk_umum` FOREIGN KEY (`id_pemeriksaan`) REFERENCES `tb_pemeriksaan` (`id_pemeriksaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pendaftaran`
--
ALTER TABLE `tb_pendaftaran`
  ADD CONSTRAINT `fk_id_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pendaftaran_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `tb_dokter` (`id_dokter`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
