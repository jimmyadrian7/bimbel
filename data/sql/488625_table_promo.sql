
-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `judul` varchar(255) DEFAULT NULL,
  `gambar_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `tanggal`, `judul`, `gambar_id`) VALUES
(1, '2021-04-17 00:00:00', 'Come On In. The Water’s Fine (Mostly).', 5),
(2, '2021-04-17 00:00:00', 'Trump Lays Plans to Reverse Obama’s Climate Change', 6),
(3, '2021-04-17 00:00:00', 'How a Little Bit of Hydra Regrows a Whole Animal', 7);
