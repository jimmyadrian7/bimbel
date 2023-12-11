
-- --------------------------------------------------------

--
-- Table structure for table `iuran`
--

CREATE TABLE `iuran` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bulan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iuran`
--

INSERT INTO `iuran` (`id`, `nama`, `bulan`) VALUES
(1, 'Biaya Iuran Pendaftaran', 1),
(2, 'Biaya Iuran Pemeliharaan', 12),
(3, 'Biaya Iuran 800 / 1 Tahun', 12),
(4, 'Biaya Iuran 800 / 6 Bulan', 6),
(5, 'Biaya Iuran 800 / 3 Bulan', 3),
(6, 'Biaya Iuran 800 / 1 Bulan', 1),
(7, 'Biaya Iuran 600 / 1 Tahun', 12),
(8, 'Biaya Iuran 600 / 6 Bulan', 6),
(9, 'Biaya Iuran 600 / 3 Bulan', 3),
(10, 'Biaya Iuran 600 / 1 Bulan', 1),
(11, 'Biaya Iuran 550 / 1 Tahun', 12),
(12, 'Biaya Iuran 550 / 6 Bulan', 6),
(13, 'Biaya Iuran 550 / 3 Bulan', 3),
(14, 'Biaya Iuran 550 / 1 Bulan', 1),
(15, 'Biaya Iuran 500 / 1 Tahun', 12),
(16, 'Biaya Iuran 500 / 6 Bulan', 6),
(17, 'Biaya Iuran 500 / 3 Bulan', 3),
(18, 'Biaya Iuran 500 / 1 Bulan', 1),
(19, 'Biaya Iuran 450 / 1 Tahun', 12),
(20, 'Biaya Iuran 450 / 6 Bulan', 6),
(21, 'Biaya Iuran 450 / 3 Bulan', 3),
(22, 'Biaya Iuran 450 / 1 Bulan', 1),
(23, 'Biaya Iuran 400 / 1 Tahun', 12),
(24, 'Biaya Iuran 400 / 6 Bulan', 6),
(25, 'Biaya Iuran 400 / 3 Bulan', 3),
(26, 'Biaya Iuran 400 / 1 Bulan', 1),
(27, 'Biaya Iuran 350 / 1 Tahun', 12),
(28, 'Biaya Iuran 350 / 6 Bulan', 6),
(29, 'Biaya Iuran 350 / 3 Bulan', 3),
(30, 'Biaya Iuran 350 / 1 Bulan', 1),
(31, 'Biaya Iuran 300 / 1 Tahun', 12),
(32, 'Biaya Iuran 300 / 6 Bulan', 6),
(33, 'Biaya Iuran 300 / 3 Bulan', 3),
(34, 'Biaya Iuran 300 / 1 Bulan', 1),
(35, 'Biaya Iuran 250 / 1 Tahun', 12),
(36, 'Biaya Iuran 250 / 6 Bulan', 6),
(37, 'Biaya Iuran 250 / 3 Bulan', 3),
(38, 'Biaya Iuran 250 / 1 Bulan', 1),
(45, 'Biaya Iuran 250 (Cuti) / 1 Bulan', 1),
(46, 'Biaya Iuran 1 Juta / 1 Tahun', 12),
(47, 'Biaya Iuran 1 Juta / 6 Bulan', 6),
(48, 'Biaya Iuran 1 Juta / 3 Bulan', 3),
(49, 'Biaya Iuran 1 Juta / 1 Bulan', 1),
(50, 'Biaya Iuran 1/2 Bulan', 1),
(51, 'Biaya Iuran Pendaftaran Dewasa', 1);
