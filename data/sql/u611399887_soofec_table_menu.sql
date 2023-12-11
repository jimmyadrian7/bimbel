
-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `parent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `kode`, `nama`, `parent`) VALUES
(1, 'profile', 'Profile', NULL),
(2, 'pengeluaran', 'Pengeluaran', NULL),
(3, 'pembayaran', 'Pembayaran', NULL),
(4, 'laporan', 'Laporan', NULL),
(5, 'siswa', 'Siswa', NULL),
(6, 'guru', 'Guru', NULL),
(7, 'web', 'Web', NULL),
(8, 'konfigurasi', 'Konfigurasi', NULL),
(9, 'aset', 'Aset', 'pengeluaran'),
(10, 'tabungan_aset', 'Tabungan Aset', 'pengeluaran'),
(11, 'pengeluaran', 'Pengeluaran', 'pengeluaran'),
(12, 'pembiayaan', 'Pembiayaan', 'pembayaran'),
(13, 'diskon', 'Diskon', 'pembayaran'),
(14, 'tagihan', 'Tagihan', 'pembayaran'),
(15, 'iuran', 'Iuran', 'pembayaran'),
(16, 'laba_rugi', 'Laba Rugi', 'laporan'),
(17, 'gaji_guru', 'Gaji Guru', 'laporan'),
(18, 'pendapatan', 'Pendapatan', 'laporan'),
(19, 'pengeluaran', 'Pengeluaran', 'laporan'),
(20, 'deposit', 'Deposit', 'laporan'),
(21, 'siswa', 'Siswa', 'siswa'),
(22, 'deposit', 'Deposit', 'siswa'),
(23, 'pengumuman', 'Pengumuman', 'web'),
(24, 'promo', 'Promo', 'web'),
(25, 'testimoni', 'Testimoni', 'web'),
(26, 'konfigurasi', 'Konfigurasi', 'web'),
(27, 'agama', 'Agama', 'konfigurasi'),
(28, 'user', 'User', 'konfigurasi'),
(29, 'referal', 'Referensi', 'konfigurasi'),
(30, 'kursus', 'Tempat Kursus', 'konfigurasi'),
(32, 'pendapatan_guru', 'Pendapatan Guru', 'laporan');
