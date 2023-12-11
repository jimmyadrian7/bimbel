
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset`
--

INSERT INTO `aset` (`id`, `nama`, `tanggal_beli`, `kondisi`, `jumlah`, `harga`, `kursus_id`) VALUES
(1, '手提包', '2023-03-01', '新', '129', 4515000, 1),
(2, '出席卡', '2023-03-01', '新', '16', 80000, 1),
(3, '汉语2', '2023-01-01', '新', '2', 166000, 1),
(4, '汉语4', '2023-02-09', '新', '1', 85000, 1);
