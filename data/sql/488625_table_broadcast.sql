
-- --------------------------------------------------------

--
-- Table structure for table `broadcast`
--

CREATE TABLE `broadcast` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `status` enum('n','s') DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
