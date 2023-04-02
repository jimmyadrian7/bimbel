
-- --------------------------------------------------------

--
-- Table structure for table `tunjangan_guru`
--

CREATE TABLE `tunjangan_guru` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
