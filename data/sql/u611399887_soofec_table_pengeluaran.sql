
-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `kursus_id` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `gaji` tinyint(1) DEFAULT NULL,
  `aset` tinyint(1) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `kursus_id`, `nama`, `jumlah`, `harga`, `total`, `gaji`, `aset`, `tanggal`) VALUES
(1, 3, 'Pengeluaran Juli 2023', 1, 723400, 723400, NULL, NULL, '2023-07-31 00:00:00'),
(2, 2, 'Pengeluaran Juli 2023', 1, 295000, 295000, NULL, NULL, '2023-07-31 00:00:00'),
(3, 3, 'Pengeluaran Agustus 2023', 1, 200000, 200000, NULL, NULL, '2023-08-31 00:00:00'),
(4, 2, 'Pengeluaran Agustus 2023', 1, 522000, 522000, NULL, NULL, '2023-08-31 00:00:00'),
(5, 1, 'Pengeluaran Juli 2023', 1, 943000, 943000, NULL, NULL, '2023-07-31 00:00:00'),
(6, 1, 'Pengeluaran Augustus 2023', 1, 1774500, 1774500, NULL, NULL, '2023-08-31 00:00:00'),
(7, 1, 'Pengeluaran September 2023', 1, 1866000, 1866000, NULL, NULL, '2023-09-30 00:00:00'),
(8, 1, 'Pengeluaran Oktober 2023', 1, 963950, 963950, NULL, NULL, '2023-10-31 00:00:00'),
(9, 1, 'Pengeluaran November 2023', 1, 969090, 969090, NULL, NULL, '2023-11-30 00:00:00');
