
-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `judul` varchar(255) DEFAULT NULL,
  `gambar_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `tanggal`, `judul`, `gambar_id`) VALUES
(4, '2024-01-02 00:00:00', 'Promosi Siswa Baru 2024', 168);
