
-- --------------------------------------------------------

--
-- Table structure for table `user_question`
--

CREATE TABLE `user_question` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_question`
--

INSERT INTO `user_question` (`id`, `name`, `email`, `subject`, `message`) VALUES
(1, 'Cansoni', 'mr.shonz98@gmail.com', 'Daftar', 'Saya Mau Datar');
