
-- --------------------------------------------------------

--
-- Table structure for table `iuran`
--

CREATE TABLE `iuran` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bulan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iuran`
--

INSERT INTO `iuran` (`id`, `nama`, `bulan`) VALUES
(1, 'Iuran Pertahun', 12),
(2, 'Iuran Perbulan', 1),
(3, 'Iuran Pendaftaran', 1),
(4, 'Iuran Pemeliharaan', 12),
(5, 'Iuran Pendaftaran bayar SPP Perbulan', 1),
(6, 'Iuran Pendaftaran Bayar Per3 bulan', 3),
(7, 'Iuran Pendaftaran bayar SPP per6 bulan', 6),
(8, 'iuran perbulan 450', 1),
(9, 'SPP per 3 bulan', 3),
(10, 'iuran per 6 bulan 450', 6),
(11, 'iuran per 3 bulan 450', 3),
(12, 'iuran per6 bulan 300', 6),
(13, 'Iuran perbulan 300', 1),
(14, 'Iuran per3bulan 300', 3),
(15, 'iuran perbulan 250', 1),
(16, 'iuran per3 bulan 250', 3),
(17, 'iuran per6bulan 250', 6),
(18, 'iuran perbulan 350', 1),
(19, 'iuran per 3 bulan 350', 3),
(20, 'iuran per 6 bulan 350', 6),
(21, 'iuran perbulan 400', 1),
(22, 'iuran per 3 bulan 400', 3),
(23, 'iuran per 6 bulan 400', 6),
(24, 'iuran per 1 tahun 250', 12),
(25, 'iuran per 1 tahun 300', 12),
(26, 'iuran per 1 tahun 350', 12),
(27, 'iuran per 1 tahun 400', 12),
(28, 'iuran per 1 tahun 450', 12),
(29, 'Iuran Pendaftaran bayar SPP pertahun', 12),
(30, 'iuran per 1 bulan 550', 1),
(31, 'iuran per 3 bulan 550', 3),
(32, 'iuran per 6 bulan 550', 6),
(33, 'iuran per 1 tahun 550', 12),
(34, 'iuran per 1 bulan 600', 1),
(35, 'iuran per 3 bulan 600', 3),
(36, 'iuran per 6 bulan 600', 6),
(37, 'iuran per 1 tahun 600', 12),
(38, 'iuran per 1 bulan 800', 1),
(39, 'iuran per 3 bulan 800', 3),
(40, 'iuran per 6 bulan 800', 6),
(41, 'iuran per 1 tahun 800', 12),
(42, 'iuran per 1 bulan 500', 1),
(43, 'iuran per 3 bulan 500', 3),
(44, 'iuran per 6 bulan 500', 6),
(45, 'iuran per 1 tahun 500', 12);
