
-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `orang_id` int(11) DEFAULT NULL,
  `status` enum('a','n') DEFAULT 'a',
  `pp_id` int(11) DEFAULT NULL,
  `berhenti` text DEFAULT NULL,
  `memilih` text DEFAULT NULL,
  `kelebihan` text DEFAULT NULL,
  `kekurangan` text DEFAULT NULL,
  `kesehatan` text DEFAULT NULL,
  `lingkungan` text DEFAULT NULL,
  `aturan` text DEFAULT NULL,
  `pelatihan` text DEFAULT NULL,
  `kapan` text DEFAULT NULL,
  `gaji_sebelumnya` int(11) DEFAULT NULL,
  `gaji_diminta` int(11) DEFAULT NULL,
  `rekaman_id` int(11) DEFAULT NULL,
  `ideal` text DEFAULT NULL,
  `nama_bank` varchar(255) NOT NULL,
  `no_rek` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `orang_id`, `status`, `pp_id`, `berhenti`, `memilih`, `kelebihan`, `kekurangan`, `kesehatan`, `lingkungan`, `aturan`, `pelatihan`, `kapan`, `gaji_sebelumnya`, `gaji_diminta`, `rekaman_id`, `ideal`, `nama_bank`, `no_rek`) VALUES
(1, 1, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(2, 2, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(3, 3, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(4, 11, 'a', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(5, 160, 'a', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(6, 161, 'a', 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(7, 162, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(8, 163, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(9, 164, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(10, 189, 'a', 14, 'asd', 'asd', 'asd', 'asd', 'asd', 'qaswd', 'asd', 'easdasd', 'asd', 123, 123, NULL, 'asd', '', ''),
(11, 240, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(12, 316, 'a', NULL, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 100, 100, NULL, 'K', 'BCA', '789.123.456');
