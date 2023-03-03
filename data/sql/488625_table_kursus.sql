
-- --------------------------------------------------------

--
-- Table structure for table `kursus`
--

CREATE TABLE `kursus` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kursus`
--

INSERT INTO `kursus` (`id`, `kode`, `nama`) VALUES
(1, 'PB', 'Permata Baloi'),
(2, 'GM', 'Griya Mas'),
(3, 'CG', 'Cahaya Garden');
