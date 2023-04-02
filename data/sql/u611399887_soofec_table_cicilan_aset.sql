
-- --------------------------------------------------------

--
-- Table structure for table `cicilan_aset`
--

CREATE TABLE `cicilan_aset` (
  `id` int(11) NOT NULL,
  `tabungan_aset_id` int(11) DEFAULT NULL,
  `bukti_pembayaran_id` int(11) DEFAULT NULL,
  `pengeluaran_id` int(11) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
