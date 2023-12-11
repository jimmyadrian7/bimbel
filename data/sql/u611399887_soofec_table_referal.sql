
-- --------------------------------------------------------

--
-- Table structure for table `referal`
--

CREATE TABLE `referal` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referal`
--

INSERT INTO `referal` (`id`, `nama`) VALUES
(1, 'Teman'),
(2, 'Saudara'),
(3, 'Brosur'),
(4, 'Spanduk'),
(5, 'Internet'),
(6, 'Koran');
