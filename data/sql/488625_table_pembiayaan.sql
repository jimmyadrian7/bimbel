
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembiayaan`
--

INSERT INTO `pembiayaan` (`id`, `kategori_pembiayaan`, `kode`, `nama`, `harga`, `stok`, `jumlah_stok`, `jenis_komisi`, `nominal`) VALUES
(1, 'p', 'BP', 'Biaya Pendaftaran', 200000, NULL, NULL, NULL, NULL),
(2, 's', 'SP1', 'SPP Perbulan', 400000, NULL, NULL, 's', NULL),
(3, 'd', 'DP', 'Deposit', 600000, NULL, NULL, NULL, NULL),
(4, 'l', 'OP', 'Operasional', 300000, NULL, NULL, NULL, NULL),
(5, 'l', 'PL', 'Pemeliharaan', 70000, NULL, NULL, NULL, NULL),
(6, 'a', 'JT', 'Jam Tangan', 100000, 1, 10, NULL, NULL),
(7, 's', 'SPP', 'Biaya iuran', 450000, NULL, NULL, 's', NULL),
(8, 'p', 'pendaftaran', 'Biaya Pendaftaran Real', 250000, NULL, NULL, NULL, NULL),
(9, 'l', 'absensi', 'Kartu Absensi', 20000, NULL, NULL, NULL, NULL),
(10, 'l', 'operasional pendidikan', 'Biaya Operasional Pendidikan Tahunan', 300000, NULL, NULL, NULL, NULL),
(11, 'l', 'pemeliharaan tahunan', 'Biaya Pemeliharaan Tahunan', 70000, NULL, NULL, NULL, NULL),
(12, 's', 'CT', 'Cuti Perbulan', 250000, NULL, NULL, 'n', 50),
(13, 's', 'Iuran 300', 'Biaya iuran 300', 300000, NULL, NULL, 's', NULL),
(14, 's', 'Iuran 350', 'Biaya iuran 350', 350000, NULL, NULL, 's', NULL),
(15, 's', 'Iuran 400', 'Biaya iuran 400', 400000, NULL, NULL, 's', NULL),
(16, 's', 'iuran 250', 'Biaya Iuran 250', 250000, NULL, NULL, 's', NULL),
(17, 's', 'iuran 550', 'biaya iuran 550', 550000, NULL, NULL, 's', NULL),
(18, 's', 'iuran 600', 'biaya iuran 600', 600000, NULL, NULL, 's', NULL),
(19, 's', 'iuran 800', 'biaya iuran 800', 800000, NULL, NULL, 's', NULL),
(20, 's', 'iuran 500', 'biaya iuran 500', 500000, NULL, NULL, 's', NULL);
