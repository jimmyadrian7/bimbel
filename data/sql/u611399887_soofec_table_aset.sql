
-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tanggal_beli` date DEFAULT NULL,
  `kondisi` varchar(255) DEFAULT NULL,
  `jumlah` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `kursus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
