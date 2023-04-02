
-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `gambar_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tipe` enum('g','l') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `gambar_id`, `link`, `tipe`) VALUES
(4, NULL, 'https://www.youtube.com/embed/xcVZdRSiVhU', 'l'),
(5, 8, NULL, 'g');
