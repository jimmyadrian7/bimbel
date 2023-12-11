
-- --------------------------------------------------------

--
-- Table structure for table `role_menu`
--

CREATE TABLE `role_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `create` tinyint(1) DEFAULT NULL,
  `update` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_menu`
--

INSERT INTO `role_menu` (`id`, `role_id`, `menu_id`, `create`, `update`, `delete`) VALUES
(1, 2, 1, NULL, 1, NULL),
(2, 2, 3, NULL, NULL, NULL),
(3, 2, 5, NULL, NULL, NULL),
(4, 2, 12, 1, 1, NULL),
(5, 2, 14, 1, 1, NULL),
(6, 2, 21, 1, 1, 1),
(7, 2, 22, NULL, NULL, NULL),
(8, 3, 1, NULL, 1, NULL),
(9, 3, 3, NULL, NULL, NULL),
(10, 3, 5, NULL, NULL, NULL),
(11, 3, 14, NULL, NULL, NULL),
(12, 3, 22, NULL, NULL, NULL),
(13, 4, 2, NULL, NULL, NULL),
(14, 4, 4, NULL, NULL, NULL),
(15, 4, 6, 1, 1, 1),
(16, 4, 8, NULL, NULL, NULL),
(17, 4, 9, 1, 1, 1),
(18, 4, 10, 1, 1, 1),
(19, 4, 11, 1, 1, 1),
(20, 4, 12, 1, 1, 1),
(21, 4, 13, 1, 1, 1),
(22, 4, 15, 1, 1, 1),
(23, 4, 16, NULL, NULL, NULL),
(24, 4, 17, NULL, NULL, NULL),
(25, 4, 18, NULL, NULL, NULL),
(26, 4, 19, NULL, NULL, NULL),
(27, 4, 20, NULL, NULL, NULL),
(28, 4, 21, 1, 1, 1),
(29, 4, 27, 1, 1, 1),
(30, 4, 29, 1, 1, 1);
