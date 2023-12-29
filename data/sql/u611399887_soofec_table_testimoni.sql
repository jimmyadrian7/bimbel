
-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `gambar_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tipe` enum('g','l') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `gambar_id`, `link`, `tipe`) VALUES
(5, 8, NULL, 'g'),
(6, 169, 'https://www.youtube.com/embed/gOY5Uomgn_Y?si=Yv0Gdt_oDwIg3_I0', 'l');
