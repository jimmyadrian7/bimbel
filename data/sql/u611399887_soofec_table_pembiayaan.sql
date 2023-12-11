
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
(24, 'd', 'Deposit Dewasa', 'Deposit Dewasa', 1000000, 1, -1, 's', NULL);
