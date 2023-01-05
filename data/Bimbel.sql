CREATE TABLE `agama` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kode` varchar(255),
  `nama` varchar(255)
);

CREATE TABLE `menu` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kode` varchar(255),
  `nama` varchar(255)
);

CREATE TABLE `file` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `filename` varchar(255),
  `filetype` varchar(255),
  `base64` longtext
);

CREATE TABLE `sequance` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kode` varchar(255) UNIQUE,
  `nama` varchar(255) UNIQUE,
  `nomor` int DEFAULT 1
);

CREATE TABLE `orang` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `nama_mandarin` varchar(255),
  `jenis_kelamin` ENUM ('l', 'p'),
  `agama_id` int,
  `alamat` varchar(255),
  `email` varchar(255),
  `tempat_lahir` varchar(255),
  `tanggal_lahir` datetime,
  `hobi` varchar(255),
  `no_hp` varchar(255),
  `nama_ayah` varchar(255),
  `nama_ibu` varchar(255),
  `no_hp_ortu` varchar(255),
  `pekerjaan_ayah` varchar(255),
  `pekerjaan_ibu` varchar(255)
);

CREATE TABLE `kursus` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kode` varchar(255),
  `nama` varchar(255)
);

CREATE TABLE `siswa` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `no_formulir` varchar(255),
  `guru_id` int,
  `orang_id` int,
  `status` ENUM ('b', 'a', 'p', 'n') DEFAULT "b",
  `tanggal_pendaftaran` date DEFAULT (now()),
  `komisi` int,
  `pinyin` varchar(255),
  `dengar` varchar(255),
  `bicara` varchar(255),
  `membaca` varchar(255),
  `menulis` varchar(255),
  `kondisi` varchar(255),
  `respon` varchar(255),
  `tanggapan` varchar(255),
  `program` varchar(255),
  `paket_belajar` varchar(255),
  `referal_other` varchar(255),
  `kursus_id` int,
  `sekolah` varchar(255),
  `kelas` varchar(255)
);

CREATE TABLE `deposit` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tanggal` date DEFAULT (now()),
  `nominal` int,
  `siswa_id` int,
  `bukti_pembayaran_id` int,
  `status` ENUM ('a', 't', 'h') DEFAULT "a"
);

CREATE TABLE `siswa_iuran` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `iuran_id` int
);

CREATE TABLE `iuran_terbuat` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `bulan` int,
  `tahun` int,
  `iuran_id` int
);

CREATE TABLE `jadwal` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `hari` ENUM ('1', '2', '3', '4', '5', '6', '7'),
  `waktu` varchar(255)
);

CREATE TABLE `referal` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255)
);

CREATE TABLE `siswa_referal` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `referal_id` int
);

CREATE TABLE `guru` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `orang_id` int,
  `status` ENUM ('a', 'n') DEFAULT "a",
  `berhenti` text,
  `memilih` text,
  `kelebihan` text,
  `kekurangan` text,
  `kesehatan` text,
  `lingkungan` text,
  `aturan` text,
  `pelatihan` text,
  `kapan` text,
  `gaji_sebelumnya` int,
  `gaji_diminta` int,
  `rekaman_id` int,
  `ideal` text
);

CREATE TABLE `tunjangan_guru` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `guru_id` int,
  `nama` varchar(255),
  `nominal` int
);

CREATE TABLE `aset` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `tanggal_beli` date,
  `kondisi` varchar(255),
  `jumlah` varchar(255),
  `harga` int
);

CREATE TABLE `gaji` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `guru_id` int,
  `total_siswa` int,
  `potongan` int,
  `sub_total` int,
  `tunjangan` int,
  `komisi` int,
  `total` int,
  `tanggal` date DEFAULT (now()),
  `pengeluaran_id` int
);

CREATE TABLE `tabungan_aset` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `jumlah` int,
  `harga` int,
  `total` int,
  `sisa` int,
  `keterangan` varchar(255),
  `cicil` int,
  `status` ENUM ('a', 'c', 'l') DEFAULT "a"
);

CREATE TABLE `cicilan_aset` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tabungan_aset_id` int,
  `bukti_pembayaran_id` int,
  `pengeluaran_id` int,
  `nominal` int,
  `tanggal` datetime DEFAULT (now())
);

CREATE TABLE `pengeluaran` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `jumlah` int,
  `harga` int,
  `total` int,
  `gaji` boolean,
  `aset` boolean,
  `tanggal` datetime DEFAULT (now())
);

CREATE TABLE `pembiayaan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kategori_pembiayaan` ENUM ('a', 's', 'p', 'd', 'l'),
  `kode` varchar(255),
  `nama` varchar(255),
  `harga` int,
  `stok` boolean,
  `jumlah_stok` int,
  `komisi` boolean
);

CREATE TABLE `diskon` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `diskon` varchar(255),
  `tipe_diskon` ENUM ('p', 'n')
);

CREATE TABLE `tagihan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `sub_total` int,
  `potongan` int,
  `total` int,
  `hutang` int,
  `status` ENUM ('p', 'c', 'l') DEFAULT "p",
  `tanggal` datetime DEFAULT (now())
);

CREATE TABLE `tagihan_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tagihan_id` int,
  `kategori_pembiayaan` ENUM ('a', 's', 'p', 'd', 'l'),
  `diskon_id` int,
  `kode` varchar(255),
  `nama` varchar(255),
  `komisi_guru` int,
  `nominal` varchar(255),
  `qty` int,
  `potongan` int,
  `sub_total` int,
  `total` int,
  `komisi` int,
  `pembiayaan_id` int
);

CREATE TABLE `transaksi` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tagihan_id` int,
  `nominal` int,
  `bukti_pembayaran_id` int,
  `tanggal` datetime DEFAULT (now()),
  `status` ENUM ('p', 'v') DEFAULT "p"
);

CREATE TABLE `iuran` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `bulan` int
);

CREATE TABLE `iuran_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `iuran_id` int,
  `pembiayaan_id` int,
  `skip` boolean,
  `qty` int,
  `total` int
);

CREATE TABLE `role` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `kode` varchar(255),
  `nama` varchar(255)
);

CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255),
  `password` varchar(255),
  `unenpass` varchar(255),
  `super_user` boolean DEFAULT 0,
  `is_public` boolean DEFAULT 0,
  `orang_id` int
);

CREATE TABLE `user_role` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `role_id` int
);

CREATE TABLE `role_menu` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `role_id` int,
  `menu_id` int
);

CREATE TABLE `pengumuman` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `judul` varchar(255),
  `isi` varchar(255),
  `gambar_id` int,
  `tanggal` datetime DEFAULT (now())
);

CREATE TABLE `promo` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tanggal` datetime DEFAULT (now()),
  `judul` varchar(255),
  `gambar_id` int
);

CREATE TABLE `testimoni` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `gambar_id` int,
  `link` varchar(255),
  `tipe` ENUM ('g', 'l')
);

CREATE TABLE `kontak` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `email` varchar(255),
  `subject` varchar(255),
  `message` text
);

CREATE TABLE `konfigurasi_web` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `lokasi` varchar(255),
  `email` varchar(255),
  `gmap` text,
  `no_hp` varchar(255),
  `facebook` text,
  `whatsapp` text,
  `instagram` text
);

CREATE TABLE `message` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `siswa_id` int,
  `message` varchar(255)
);

ALTER TABLE `orang` ADD FOREIGN KEY (`agama_id`) REFERENCES `agama` (`id`);

ALTER TABLE `siswa` ADD FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`);

ALTER TABLE `siswa` ADD FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`);

ALTER TABLE `siswa` ADD FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

ALTER TABLE `deposit` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `deposit` ADD FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`);

ALTER TABLE `siswa_iuran` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `siswa_iuran` ADD FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`);

ALTER TABLE `iuran_terbuat` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `iuran_terbuat` ADD FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`);

ALTER TABLE `jadwal` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `siswa_referal` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `siswa_referal` ADD FOREIGN KEY (`referal_id`) REFERENCES `referal` (`id`);

ALTER TABLE `guru` ADD FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`);

ALTER TABLE `guru` ADD FOREIGN KEY (`rekaman_id`) REFERENCES `file` (`id`);

ALTER TABLE `tunjangan_guru` ADD FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`);

ALTER TABLE `gaji` ADD FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`);

ALTER TABLE `gaji` ADD FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`);

ALTER TABLE `cicilan_aset` ADD FOREIGN KEY (`tabungan_aset_id`) REFERENCES `tabungan_aset` (`id`);

ALTER TABLE `cicilan_aset` ADD FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`);

ALTER TABLE `cicilan_aset` ADD FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`);

ALTER TABLE `tagihan` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

ALTER TABLE `tagihan_detail` ADD FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`);

ALTER TABLE `tagihan_detail` ADD FOREIGN KEY (`diskon_id`) REFERENCES `diskon` (`id`);

ALTER TABLE `tagihan_detail` ADD FOREIGN KEY (`pembiayaan_id`) REFERENCES `pembiayaan` (`id`);

ALTER TABLE `transaksi` ADD FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`);

ALTER TABLE `transaksi` ADD FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`);

ALTER TABLE `iuran_detail` ADD FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`);

ALTER TABLE `iuran_detail` ADD FOREIGN KEY (`pembiayaan_id`) REFERENCES `pembiayaan` (`id`);

ALTER TABLE `user` ADD FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `role_menu` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `role_menu` ADD FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

ALTER TABLE `pengumuman` ADD FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

ALTER TABLE `promo` ADD FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

ALTER TABLE `testimoni` ADD FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

ALTER TABLE `message` ADD FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);
