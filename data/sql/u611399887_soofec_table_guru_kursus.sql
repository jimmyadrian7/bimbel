
-- --------------------------------------------------------

--
-- Table structure for table `guru_kursus`
--

CREATE TABLE `guru_kursus` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `kursus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guru_kursus`
--

INSERT INTO `guru_kursus` (`id`, `guru_id`, `kursus_id`) VALUES
(1, 2, 1),
(2, 3, 1),
(3, 4, 1),
(4, 4, 2),
(5, 5, 3),
(6, 6, 2),
(7, 7, 3),
(8, 7, 2),
(9, 8, 3),
(10, 9, 2);
