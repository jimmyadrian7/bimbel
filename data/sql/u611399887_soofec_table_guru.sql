
-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `orang_id` int(11) DEFAULT NULL,
  `status` enum('a','n') DEFAULT 'a',
  `pp_id` int(11) DEFAULT NULL,
  `berhenti` text DEFAULT NULL,
  `memilih` text DEFAULT NULL,
  `kelebihan` text DEFAULT NULL,
  `kekurangan` text DEFAULT NULL,
  `kesehatan` text DEFAULT NULL,
  `lingkungan` text DEFAULT NULL,
  `aturan` text DEFAULT NULL,
  `pelatihan` text DEFAULT NULL,
  `kapan` text DEFAULT NULL,
  `gaji_sebelumnya` int(11) DEFAULT NULL,
  `gaji_diminta` int(11) DEFAULT NULL,
  `rekaman_id` int(11) DEFAULT NULL,
  `ideal` text DEFAULT NULL,
  `nama_bank` varchar(255) NOT NULL,
  `no_rek` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `orang_id`, `status`, `pp_id`, `berhenti`, `memilih`, `kelebihan`, `kekurangan`, `kesehatan`, `lingkungan`, `aturan`, `pelatihan`, `kapan`, `gaji_sebelumnya`, `gaji_diminta`, `rekaman_id`, `ideal`, `nama_bank`, `no_rek`) VALUES
(1, 1, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(2, 2, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(3, 3, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(4, 11, 'a', 9, '-', '-', '-', '-', '-', '-', '-', '-', '-', 500000, 5000000, 24, '-', 'BCA', '0292231952'),
(5, 160, 'a', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(6, 161, 'n', 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(7, 162, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(8, 163, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(9, 164, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', ''),
(13, 461, 'a', NULL, 'karena kebutuhan kuliah yang diminta untuk bimbingan skripsi bentrok dengan pekerjaan, jadi setelah berdiskusi dengan boss perusahaan sebelumnya saya memilih untuk mengundurkan diri', 'untuk mendalami bahasa mandarin dan belajar dalam dunia pendidikan yang akan membantu saya untuk kedepannya', 'tidak gampang emosi, menjaga baik keadaan sekarang dan memajukan dunia spiritual saya untuk lebih baik lagi', 'respon terkadang agak lambat, tidak berpengalaman dalam dunia pendidikan. berusaha untuk mempercepat respons akan hal-hal yang ada, belajar dengan baik untuk dapat mengajar anak anak dengan lebih baik', 'baik, jarang sakit.', 'lingkungan kerja yang tenang, dapat saling membantu. karena dalam lingkungan kerja yang seperti ini saya dapat melakukan kerjaan dengan lebih tenang', 'saya dapat mengikuti aturan yang telah ditentukan, karena saya mengerti bahwa peraturan yangat dibutuhkan untuk sebuah lingkungan kerja yang lebih baik', 'saya bersedia, karena saya masih kurang dalam dunia pendidikan jadi saya bersedia menerima sistem pelatihan yang telah ditentukan', 'secepatnya', 4200000, 4000000, 23, 'guru yang dapat mengajari anak anak dengan baik dalam segi pendidikan dan moral juga.', 'BCA', '8520200950'),
(14, 500, 'a', NULL, '-', '-', '-', '-', '-', '-', '-', '-', '-', 1, 1, 26, '.', 'BCA', '8210447491'),
(15, 501, 'n', NULL, '-', '-', '-', '-', '-', '-', '-', '-', '-', 1000, 2000, 28, '-', '-', '-');
