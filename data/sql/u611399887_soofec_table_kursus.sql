
-- --------------------------------------------------------

--
-- Table structure for table `kursus`
--

CREATE TABLE `kursus` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `sequance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kursus`
--

INSERT INTO `kursus` (`id`, `kode`, `nama`, `sequance`) VALUES
(1, 'PB', 'Permata Baloi', 418),
(2, 'GM', 'Griya Mas', 167),
(3, 'CG', 'Cahaya Garden', 44),
(5, 'OC', 'Online Class', 9);
