
-- --------------------------------------------------------

--
-- Table structure for table `agama`
--

CREATE TABLE `agama` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agama`
--

INSERT INTO `agama` (`id`, `kode`, `nama`) VALUES
(1, 'protestan', 'Protestan'),
(2, 'katolik', 'Katolik'),
(3, 'hindu', 'Hindu'),
(4, 'buddha', 'Buddha'),
(5, 'khonghucu', 'Khonghucu'),
(6, 'islam', 'Islam'),
(7, 'BM', 'Buddha Maitreya');
