
-- --------------------------------------------------------

--
-- Table structure for table `diskon`
--

CREATE TABLE `diskon` (
  `id` int(11) NOT NULL,
  `diskon` varchar(255) DEFAULT NULL,
  `tipe_diskon` enum('p','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diskon`
--

INSERT INTO `diskon` (`id`, `diskon`, `tipe_diskon`) VALUES
(1, '10', 'p'),
(2, '50', 'p'),
(3, '200000', 'n'),
(5, '20', 'p'),
(6, '100', 'p');
