
-- --------------------------------------------------------

--
-- Table structure for table `tabungan_aset`
--

CREATE TABLE `tabungan_aset` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `sisa` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `cicil` int(11) DEFAULT NULL,
  `kursus_id` int(11) DEFAULT NULL,
  `status` enum('a','c','l') DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
