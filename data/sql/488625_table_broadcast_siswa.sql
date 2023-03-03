
-- --------------------------------------------------------

--
-- Table structure for table `broadcast_siswa`
--

CREATE TABLE `broadcast_siswa` (
  `id` int(11) NOT NULL,
  `broadcast_id` int(11) DEFAULT NULL,
  `siswa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
