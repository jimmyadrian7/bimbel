
-- --------------------------------------------------------

--
-- Table structure for table `pembiayaan`
--

CREATE TABLE `pembiayaan` (
  `id` int(11) NOT NULL,
  `kategori_pembiayaan` enum('a','s','p','d','l') DEFAULT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` tinyint(1) DEFAULT NULL,
  `jumlah_stok` int(11) DEFAULT NULL,
  `jenis_komisi` enum('s','p','n') DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembiayaan`
--

INSERT INTO `pembiayaan` (`id`, `kategori_pembiayaan`, `kode`, `nama`, `harga`, `stok`, `jumlah_stok`, `jenis_komisi`, `nominal`) VALUES
(1, 'p', 'Biaya Pendaftaran', 'Biaya Pendaftaran', 250000, NULL, NULL, NULL, NULL),
(2, 'l', 'Kartu Absensi', 'Kartu Absensi', 20000, NULL, NULL, NULL, NULL),
(3, 'l', 'Biaya Pemeliharaan Tahunan', 'Biaya Pemeliharaan Tahunan', 70000, NULL, NULL, NULL, NULL),
(4, 'l', 'Biaya Operasional Pendidikan Tahunan', 'Biaya Operasional Pendidikan Tahunan', 300000, NULL, NULL, NULL, NULL),
(5, 'd', 'Deposit', 'Deposit', 600000, NULL, NULL, NULL, NULL),
(7, 's', 'Biaya Iuran 800', 'Biaya Iuran 800', 800000, NULL, NULL, 's', NULL),
(8, 's', 'Biaya Iuran 600', 'Biaya Iuran 600', 600000, NULL, NULL, 's', NULL),
(11, 's', 'Biaya Iuran 550', 'Biaya Iuran 550', 550000, NULL, NULL, 's', NULL),
(13, 's', 'Biaya Iuran 500', 'Biaya Iuran 500', 500000, NULL, NULL, 's', NULL),
(14, 's', 'Biaya Iuran 450', 'Biaya Iuran 450', 450000, NULL, NULL, 's', NULL),
(15, 's', 'Biaya Iuran 400', 'Biaya Iuran 400', 400000, NULL, NULL, 's', NULL),
(16, 's', 'Biaya Iuran 350', 'Biaya Iuran 350', 350000, NULL, NULL, 's', NULL),
(17, 's', 'Biaya Iuran 300', 'Biaya Iuran 300', 300000, NULL, NULL, 's', NULL),
(18, 's', 'Biaya Iuran 250', 'Biaya Iuran 250', 250000, NULL, NULL, 's', NULL),
(19, 's', 'Biaya Iuran 250 / Cuti', 'Biaya Iuran 250 / Cuti', 250000, NULL, NULL, 's', NULL),
(20, 'd', 'deposit250', 'deposit', 250000, 1, -1, 'n', NULL),
(21, 'd', 'deposit300', 'deposit', 300000, 0, NULL, 's', NULL),
(22, 's', 'Biaya Iuran 1 Juta', 'Biaya Iuran 1 Juta', 1000000, NULL, NULL, 's', NULL),
(23, 's', 'Biaya Iuran 225', 'Biaya Iuran 1/2 Bulan', 225000, NULL, NULL, 's', NULL),
(24, 'd', 'Deposit Dewasa', 'Deposit Dewasa', 1000000, 1, -1, 's', NULL),
(26, 'd', 'Deposit 500', 'Deposit 500', 500000, 1, -2, 's', NULL),
(27, 'd', 'Deposit 350', 'Deposit 350', 350000, 1, -4, 's', NULL),
(28, 'd', 'Deposit 400', 'Deposit 400', 400000, 1, -1, 's', NULL),
(29, 'd', 'Deposit 500', 'Deposit 500', 500000, 1, -1, 's', NULL),
(31, 'a', 'Buku', 'HANYU 1', 76000, 1, -4, 'n', NULL),
(32, 'a', 'Buku', 'HANYU 2', 76000, 1, -5, 'n', NULL),
(33, 'a', 'Buku', 'HANYU 3', 76000, 1, -4, 'n', NULL),
(34, 'a', 'Buku', 'HANYU 4', 76000, 1, -5, 'n', NULL),
(35, 'a', 'Buku', 'HANYU 5', 76000, 1, -1, 'n', NULL),
(36, 'a', 'Buku', 'HANYU 6', 76000, 1, NULL, 'n', NULL),
(37, 'a', 'Buku', 'HANYU 7', 90000, 1, NULL, 'n', NULL),
(38, 'a', 'Buku', 'HANYU 8', 90000, 1, -1, 'n', NULL),
(39, 'a', 'Buku', 'HANYU 9', 90000, 1, NULL, 'n', NULL),
(40, 'a', 'Buku', 'HANYU 10', 90000, 1, -1, 'n', NULL),
(41, 'a', 'Buku', 'HANYU 11', 90000, 1, NULL, 'n', NULL),
(42, 'a', 'Buku', 'HANYU 12', 90000, 1, NULL, 'n', NULL),
(43, 'a', 'Buku', 'HSK1', 142000, 1, NULL, 'n', NULL),
(44, 'a', 'Buku', 'HSK2', 142000, 1, NULL, 'n', NULL),
(45, 'a', 'Buku', 'HSK3', 142000, 1, -2, 'n', NULL),
(46, 'a', 'Buku', 'HSK4', 142000, 1, NULL, 'n', NULL),
(47, 'a', 'Buku', 'HSK5', 190000, 1, NULL, 'n', NULL),
(48, 'a', 'Buku', 'HSK6', 190000, 1, NULL, 'n', NULL),
(49, 'a', 'Buku', 'HANYU JIAOCHENG 1', 75000, 1, NULL, 'n', NULL),
(50, 'a', 'Buku', 'HANYU JIAOCHENG 2', 75000, 1, -2, 'n', NULL);
