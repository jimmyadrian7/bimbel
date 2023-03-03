
-- --------------------------------------------------------

--
-- Table structure for table `sequance`
--

CREATE TABLE `sequance` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nomor` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sequance`
--

INSERT INTO `sequance` (`id`, `kode`, `nama`, `nomor`) VALUES
(1, 'PMB-', 'pembiayaan', 221),
(2, 'PNDF-', 'pendaftaran', 1),
(3, 'TA-', 'tabungan_aset', 1);
