
-- --------------------------------------------------------

--
-- Table structure for table `iuran_terbuat`
--

CREATE TABLE `iuran_terbuat` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `bulan` int(11) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `iuran_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iuran_terbuat`
--

INSERT INTO `iuran_terbuat` (`id`, `siswa_id`, `bulan`, `tahun`, `iuran_id`) VALUES
(19, 15, 1, 2024, 1),
(20, 15, 2, 2023, 3),
(21, 15, 1, 2024, 4),
(25, 10, NULL, NULL, 15),
(26, 10, NULL, NULL, 3),
(27, 10, NULL, NULL, 4),
(28, 11, NULL, NULL, 15),
(29, 11, NULL, NULL, 3),
(30, 11, NULL, NULL, 4),
(31, 12, NULL, NULL, 12),
(32, 12, NULL, NULL, 3),
(33, 12, NULL, NULL, 4),
(34, 16, 2, 2023, 3),
(35, 16, 1, 2024, 4),
(36, 16, 1, 2024, 25),
(37, 17, 3, 2023, 18),
(40, 18, 2, 2023, 3),
(41, 18, 1, 2024, 4),
(42, 18, 1, 2024, 25),
(49, 21, 2, 2023, 3),
(50, 21, 1, 2024, 4),
(51, 21, 7, 2023, 12),
(61, 25, 1, 2024, 25),
(62, 25, 2, 2023, 3),
(63, 25, 1, 2024, 4),
(67, 27, 1, 2024, 26),
(68, 27, 2, 2023, 3),
(69, 27, 1, 2024, 4),
(70, 37, 2, 2023, 3),
(71, 37, 1, 2024, 4),
(72, 37, 4, 2023, 19),
(73, 43, 2, 2023, 18),
(74, 43, 2, 2023, 3),
(75, 43, 1, 2024, 4),
(76, 47, 2, 2023, 3),
(77, 47, 1, 2024, 4),
(78, 47, 7, 2023, 20),
(79, 57, 2, 2023, 3),
(80, 57, 1, 2024, 4),
(81, 57, 2, 2023, 18),
(82, 61, 2, 2023, 3),
(83, 61, 1, 2024, 4),
(84, 61, 7, 2023, 20),
(95, 68, 7, 2023, 23),
(96, 68, 2, 2023, 3),
(97, 68, 1, 2024, 4),
(98, 70, 7, 2023, 23),
(101, 71, 4, 2023, 22),
(128, 83, 1, 2024, 27),
(130, 85, 1, 2024, 27),
(131, 86, 2, 2023, 21),
(132, 93, 2, 2023, 21),
(133, 94, 7, 2023, 23),
(134, 95, 4, 2023, 22),
(135, 104, 1, 2024, 27),
(136, 105, 7, 2023, 23),
(137, 107, 1, 2024, 27),
(138, 108, 1, 2024, 27),
(139, 109, 4, 2023, 22),
(140, 110, 4, 2023, 22),
(141, 111, 2, 2023, 3),
(142, 111, 1, 2024, 4),
(143, 111, 4, 2023, 19),
(144, 112, 4, 2023, 22),
(145, 113, 4, 2023, 22),
(148, 117, 2, 2023, 3),
(149, 117, 1, 2024, 4),
(150, 117, 2, 2023, 18),
(151, 119, 4, 2023, 22),
(152, 120, 4, 2023, 22),
(153, 121, 2, 2023, 3),
(154, 121, 1, 2024, 4),
(155, 121, 7, 2023, 23),
(156, 130, 2, 2023, 3),
(157, 130, 1, 2024, 4),
(158, 130, 7, 2023, 23),
(159, 131, 1, 2024, 27),
(160, 135, 2, 2023, 3),
(161, 135, 1, 2024, 4),
(162, 135, 7, 2023, 23),
(164, 137, 1, 2024, 27),
(165, 141, 2, 2023, 3),
(166, 141, 1, 2024, 4),
(167, 141, 1, 2024, 27),
(169, 145, 2, 2023, 3),
(170, 145, 1, 2024, 4),
(171, 145, 7, 2023, 23),
(172, 146, 2, 2023, 3),
(173, 146, 1, 2024, 4),
(174, 146, 7, 2023, 23),
(175, 147, 2, 2023, 3),
(176, 147, 1, 2024, 4),
(177, 147, 1, 2024, 27),
(178, 148, 2, 2023, 3),
(179, 148, 1, 2024, 4),
(180, 148, 1, 2024, 27),
(181, 149, 2, 2023, 3),
(182, 149, 1, 2024, 4),
(183, 149, 7, 2023, 23),
(184, 150, 2, 2023, 3),
(185, 150, 1, 2024, 4),
(186, 150, 4, 2023, 22),
(187, 151, 2, 2023, 3),
(188, 151, 1, 2024, 4),
(189, 151, 7, 2023, 23),
(190, 152, 2, 2023, 3),
(191, 152, 1, 2024, 4),
(192, 152, 7, 2023, 23),
(193, 153, 2, 2023, 3),
(194, 153, 1, 2024, 4),
(195, 153, 7, 2023, 23),
(196, 154, 1, 2024, 27),
(197, 155, 7, 2023, 23),
(198, 156, 7, 2023, 23),
(199, 159, 2, 2023, 3),
(200, 159, 1, 2024, 4),
(201, 159, 4, 2023, 22),
(202, 160, 2, 2023, 3),
(203, 160, 1, 2024, 4),
(204, 160, 4, 2023, 22),
(205, 162, 2, 2023, 21),
(206, 163, 2, 2023, 21),
(207, 164, 7, 2023, 23),
(208, 165, 2, 2023, 3),
(209, 165, 1, 2024, 4),
(210, 165, 2, 2023, 21),
(211, 166, 2, 2023, 21),
(212, 167, 2, 2023, 3),
(213, 167, 1, 2024, 4),
(214, 167, 4, 2023, 22),
(215, 168, 1, 2024, 27),
(216, 169, 2, 2023, 21),
(217, 170, 2, 2023, 3),
(218, 170, 1, 2024, 4),
(219, 170, 2, 2023, 21),
(220, 171, 1, 2024, 28),
(221, 172, 1, 2024, 28),
(222, 173, 4, 2023, 11),
(223, 174, 4, 2023, 11),
(224, 175, 4, 2023, 11),
(225, 176, 2, 2023, 3),
(226, 176, 1, 2024, 4),
(227, 176, 2, 2023, 21),
(228, 177, 1, 2024, 28),
(229, 178, 1, 2024, 28),
(230, 179, 2, 2023, 3),
(231, 179, 1, 2024, 4),
(232, 179, 4, 2023, 22),
(235, 66, 3, 2023, 23),
(236, 181, 2, 2023, 5),
(238, 181, 1, 2024, 4),
(240, 182, 2, 2023, 15),
(241, 188, 2, 2023, 3),
(242, 188, 1, 2024, 4),
(243, 188, 4, 2023, 22),
(244, 93, NULL, NULL, 3),
(245, 201, 2, 2023, 8),
(246, 201, 2, 2023, 3),
(247, 202, 2, 2023, 3),
(248, 202, 2, 2023, 8),
(249, 203, 2, 2023, 3),
(250, 203, 1, 2024, 4),
(251, 203, 4, 2023, 22),
(252, 206, 2, 2023, 3),
(253, 206, 1, 2024, 4),
(254, 206, 4, 2023, 22),
(255, 207, 1, 2024, 28),
(256, 207, 2, 2023, 3),
(257, 208, 2, 2023, 3),
(258, 208, 1, 2024, 4),
(259, 208, 2, 2023, 21),
(260, 209, 2, 2023, 3),
(261, 209, 1, 2024, 4),
(262, 209, 4, 2023, 22),
(265, 211, 2, 2023, 3),
(266, 211, 1, 2024, 4),
(267, 211, 2, 2023, 21),
(270, 213, 2, 2023, 3),
(271, 213, 1, 2024, 4),
(272, 213, 2, 2023, 21),
(273, 214, 2, 2023, 3),
(274, 214, 1, 2024, 4),
(275, 214, 2, 2023, 21),
(278, 216, 2, 2023, 3),
(279, 216, 1, 2024, 4),
(280, 216, 4, 2023, 22),
(281, 217, 2, 2023, 3),
(282, 217, 1, 2024, 4),
(283, 217, 4, 2023, 22),
(287, 219, 2, 2023, 3),
(288, 219, 1, 2024, 4),
(289, 219, 4, 2023, 22),
(290, 221, 2, 2023, 3),
(291, 221, 1, 2024, 4),
(292, 221, 2, 2023, 21),
(293, 222, 2, 2023, 3),
(294, 222, 1, 2024, 4),
(295, 222, 4, 2023, 22),
(296, 223, 2, 2023, 3),
(297, 223, 1, 2024, 4),
(298, 223, 4, 2023, 22),
(299, 224, 2, 2023, 3),
(300, 224, 1, 2024, 4),
(301, 224, 7, 2023, 23),
(302, 225, 2, 2023, 3),
(303, 225, 1, 2024, 4),
(304, 225, 2, 2023, 21),
(305, 226, 2, 2023, 3),
(306, 226, 1, 2024, 4),
(307, 226, 4, 2023, 11),
(308, 227, 2, 2023, 3),
(309, 227, 1, 2024, 4),
(310, 227, 1, 2024, 28),
(311, 228, 2, 2023, 3),
(312, 228, 1, 2024, 4),
(313, 228, 1, 2024, 28),
(314, 229, 2, 2023, 3),
(315, 229, 1, 2024, 4),
(316, 229, 4, 2023, 22),
(317, 230, 2, 2023, 3),
(318, 230, 1, 2024, 4),
(319, 230, 4, 2023, 11),
(320, 231, 2, 2023, 3),
(321, 231, 1, 2024, 4),
(322, 231, 4, 2023, 11),
(324, 232, 2, 2023, 3),
(325, 232, 1, 2024, 4),
(326, 232, NULL, NULL, 8),
(327, 233, 2, 2023, 3),
(328, 233, 1, 2024, 4),
(329, 233, 7, 2023, 10),
(330, 234, 4, 2023, 11),
(331, 234, 2, 2023, 3),
(332, 234, 1, 2024, 4),
(333, 235, 2, 2023, 3),
(334, 235, 1, 2024, 4),
(335, 235, 7, 2023, 12),
(336, 236, 2, 2023, 3),
(337, 236, 1, 2024, 4),
(338, 236, 2, 2023, 8),
(339, 237, 2, 2023, 3),
(340, 237, 1, 2024, 4),
(341, 237, 2, 2023, 13),
(342, 238, 1, 2024, 28),
(343, 239, 1, 2024, 27),
(344, 239, 2, 2023, 3),
(345, 239, 1, 2024, 4),
(346, 240, 1, 2024, 26),
(347, 240, 2, 2023, 3),
(348, 240, 1, 2024, 4),
(349, 241, 1, 2024, 24),
(350, 241, 2, 2023, 3),
(351, 241, 1, 2024, 4),
(352, 242, 7, 2023, 23),
(353, 242, 2, 2023, 3),
(354, 242, 1, 2024, 4),
(355, 243, 2, 2023, 3),
(356, 243, 1, 2024, 4),
(357, 243, 2, 2023, 8),
(358, 244, 2, 2023, 3),
(359, 244, 1, 2024, 4),
(360, 244, 2, 2023, 13),
(361, 180, NULL, NULL, 3),
(362, 180, NULL, NULL, 4),
(363, 180, NULL, NULL, 24),
(364, 182, NULL, NULL, 3),
(365, 182, NULL, NULL, 4),
(366, 245, 2, 2023, 3),
(367, 245, 1, 2024, 4),
(368, 245, 4, 2023, 11),
(369, 246, 2, 2023, 3),
(370, 246, 1, 2024, 4),
(371, 246, 4, 2023, 11),
(372, 247, 2, 2023, 3),
(373, 247, 1, 2024, 4),
(374, 247, 4, 2023, 11),
(390, 255, 2, 2023, 3),
(391, 255, 1, 2024, 4),
(392, 255, 2, 2023, 38),
(393, 256, 2, 2023, 3),
(394, 256, 1, 2024, 4),
(395, 256, 4, 2023, 22),
(408, 261, 2, 2023, 3),
(409, 261, 1, 2024, 4),
(410, 261, 4, 2023, 22),
(411, 262, 2, 2023, 3),
(412, 262, 1, 2024, 4),
(413, 262, 1, 2024, 25),
(414, 263, 2, 2023, 3),
(415, 263, 1, 2024, 4),
(416, 263, 1, 2024, 25),
(417, 264, 2, 2023, 3),
(418, 264, 1, 2024, 4),
(419, 264, 2, 2023, 13),
(420, 265, 2, 2023, 3),
(421, 265, 1, 2024, 4),
(422, 265, 2, 2023, 18),
(423, 266, 1, 2024, 4),
(424, 266, 2, 2023, 5),
(425, 266, 2, 2023, 8),
(426, 181, 3, 2023, 11),
(427, 271, 3, 2023, 18),
(428, 272, 8, 2023, 23),
(429, 273, 2, 2024, 28),
(430, 274, 3, 2023, 8),
(431, 275, 3, 2023, 21),
(432, 276, 3, 2023, 21),
(433, 277, 3, 2023, 42),
(434, 278, 8, 2023, 20),
(435, 279, 3, 2023, 42),
(436, 280, 3, 2023, 42),
(437, 281, 3, 2023, 8),
(438, 282, 3, 2023, 8),
(439, 283, 3, 2023, 21),
(440, 284, 3, 2023, 8),
(441, 285, 8, 2023, 44),
(442, 286, 5, 2023, 11),
(443, 287, 3, 2023, 21),
(444, 288, 3, 2023, 8),
(445, 289, 3, 2023, 8),
(446, 290, 2, 2024, 27),
(447, 291, 3, 2023, 21),
(448, 292, 3, 2023, 21),
(449, 293, 5, 2023, 22),
(450, 294, 3, 2023, 21),
(451, 295, 3, 2023, 21),
(452, 296, 3, 2023, 18),
(453, 297, 5, 2023, 11),
(454, 298, 3, 2023, 21),
(455, 299, 3, 2023, 21),
(456, 300, 5, 2023, 11),
(457, 301, 3, 2023, 21),
(458, 302, 8, 2023, 20),
(459, 303, 3, 2023, 13),
(460, 304, 8, 2023, 23),
(461, 305, 5, 2023, 22),
(463, 306, 2, 2024, 24),
(464, 306, 3, 2023, 3),
(465, 17, NULL, NULL, 3),
(466, 84, NULL, NULL, 3),
(467, 84, 3, 2023, 21),
(468, 23, 3, 2023, 18),
(469, 23, 3, 2023, 4),
(470, 23, 3, 2023, 3),
(471, 164, NULL, NULL, 3),
(473, 142, 3, 2023, 27),
(474, 9, 3, 2023, 3),
(475, 9, 3, 2023, 4),
(476, 9, 3, 2023, 17),
(481, 253, 3, 2023, 3),
(482, 253, 3, 2023, 4),
(483, 253, 3, 2023, 36),
(487, 252, 3, 2023, 3),
(488, 252, 3, 2023, 4),
(489, 252, 3, 2023, 35),
(490, 24, 3, 2023, 3),
(491, 24, 3, 2023, 4),
(492, 24, 3, 2023, 20),
(493, 251, 3, 2023, 3),
(494, 251, 3, 2023, 4),
(495, 251, 3, 2023, 35),
(496, 26, 3, 2023, 18),
(497, 26, 3, 2023, 4),
(498, 26, 3, 2023, 3),
(499, 22, 3, 2023, 4),
(500, 22, 3, 2023, 18),
(501, 22, 3, 2023, 3),
(502, 20, 3, 2023, 3),
(503, 20, 3, 2023, 18),
(504, 20, 3, 2023, 4),
(505, 19, 3, 2023, 18),
(506, 14, 3, 2023, 25),
(507, 249, 3, 2023, 3),
(508, 249, 3, 2023, 4),
(509, 249, 3, 2023, 31),
(511, 248, 3, 2023, 3),
(512, 248, 3, 2023, 4),
(514, 307, 8, 2023, 23),
(515, 307, 3, 2023, 3),
(516, 248, 3, 2023, 11),
(517, 308, 8, 2023, 23),
(518, 308, 3, 2023, 3),
(520, 142, 3, 2023, 4),
(521, 142, NULL, NULL, 5);