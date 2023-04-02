
-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `kode`, `nama`) VALUES
(1, 'A', 'Admin'),
(2, 'G', 'Guru'),
(3, 'S', 'Siswa'),
(4, 'C', 'Admin Cabang');
