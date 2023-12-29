
-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_web`
--

CREATE TABLE `konfigurasi_web` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gmap` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `facebook` text DEFAULT NULL,
  `whatsapp` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `tiktok` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konfigurasi_web`
--

INSERT INTO `konfigurasi_web` (`id`, `lokasi`, `email`, `gmap`, `no_hp`, `facebook`, `whatsapp`, `instagram`, `tiktok`) VALUES
(1, 'Batam', 'soofmandarincentre@gmail.com', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1994.5105541173161!2d104.0376553!3d1.1453949!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98bdd8e08f7a7%3A0x1e26da9f253eaa8e!2zU3R1ZGVudHMgb2YgT25lIEZhbWlseSBFZHVjYXRpb24gQ2VudHJlIC8g5a2m55Sf5LiA5a62!5e0!3m2!1sen!2sid!4v1703165602845!5m2!1sen!2sid', '+628126179612', 'https://www.facebook.com/xuesheng.yijia', 'https://wa.me/628126179612', 'https://www.instagram.com/learnchinesebtm/', NULL);
