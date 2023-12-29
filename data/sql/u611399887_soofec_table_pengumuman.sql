
-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi` varchar(255) DEFAULT NULL,
  `gambar_id` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `gambar_id`, `tanggal`) VALUES
(5, 'Libur NaTaRu', 'Selamat Hari Natal dan Tahun Baru', 167, '2023-12-24 00:00:00');
