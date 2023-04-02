
--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_configuration`
--
ALTER TABLE `account_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agama`
--
ALTER TABLE `agama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `broadcast`
--
ALTER TABLE `broadcast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `broadcast_siswa`
--
ALTER TABLE `broadcast_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cicilan_aset`
--
ALTER TABLE `cicilan_aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabungan_aset_id` (`tabungan_aset_id`),
  ADD KEY `bukti_pembayaran_id` (`bukti_pembayaran_id`),
  ADD KEY `pengeluaran_id` (`pengeluaran_id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `bukti_pembayaran_id` (`bukti_pembayaran_id`);

--
-- Indexes for table `diskon`
--
ALTER TABLE `diskon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `pengeluaran_id` (`pengeluaran_id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orang_id` (`orang_id`),
  ADD KEY `pp_id` (`pp_id`),
  ADD KEY `rekaman_id` (`rekaman_id`);

--
-- Indexes for table `guru_kursus`
--
ALTER TABLE `guru_kursus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `iuran`
--
ALTER TABLE `iuran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iuran_detail`
--
ALTER TABLE `iuran_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iuran_id` (`iuran_id`),
  ADD KEY `pembiayaan_id` (`pembiayaan_id`);

--
-- Indexes for table `iuran_terbuat`
--
ALTER TABLE `iuran_terbuat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `iuran_id` (`iuran_id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `konfigurasi_web`
--
ALTER TABLE `konfigurasi_web`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kursus`
--
ALTER TABLE `kursus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orang`
--
ALTER TABLE `orang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agama_id` (`agama_id`);

--
-- Indexes for table `pembiayaan`
--
ALTER TABLE `pembiayaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gambar_id` (`gambar_id`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gambar_id` (`gambar_id`);

--
-- Indexes for table `referal`
--
ALTER TABLE `referal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_menu`
--
ALTER TABLE `role_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `sequance`
--
ALTER TABLE `sequance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `orang_id` (`orang_id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `siswa_iuran`
--
ALTER TABLE `siswa_iuran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `iuran_id` (`iuran_id`);

--
-- Indexes for table `siswa_referal`
--
ALTER TABLE `siswa_referal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `referal_id` (`referal_id`);

--
-- Indexes for table `tabungan_aset`
--
ALTER TABLE `tabungan_aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `kursus_id` (`kursus_id`);

--
-- Indexes for table `tagihan_detail`
--
ALTER TABLE `tagihan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_id` (`tagihan_id`),
  ADD KEY `diskon_id` (`diskon_id`),
  ADD KEY `pembiayaan_id` (`pembiayaan_id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gambar_id` (`gambar_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_id` (`tagihan_id`),
  ADD KEY `bukti_pembayaran_id` (`bukti_pembayaran_id`);

--
-- Indexes for table `tunjangan_guru`
--
ALTER TABLE `tunjangan_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `orang_id` (`orang_id`);

--
-- Indexes for table `user_question`
--
ALTER TABLE `user_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_configuration`
--
ALTER TABLE `account_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agama`
--
ALTER TABLE `agama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `broadcast`
--
ALTER TABLE `broadcast`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `broadcast_siswa`
--
ALTER TABLE `broadcast_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cicilan_aset`
--
ALTER TABLE `cicilan_aset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `diskon`
--
ALTER TABLE `diskon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `guru_kursus`
--
ALTER TABLE `guru_kursus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `iuran`
--
ALTER TABLE `iuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `iuran_detail`
--
ALTER TABLE `iuran_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `iuran_terbuat`
--
ALTER TABLE `iuran_terbuat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `konfigurasi_web`
--
ALTER TABLE `konfigurasi_web`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kursus`
--
ALTER TABLE `kursus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1442;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orang`
--
ALTER TABLE `orang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

--
-- AUTO_INCREMENT for table `pembiayaan`
--
ALTER TABLE `pembiayaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referal`
--
ALTER TABLE `referal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_menu`
--
ALTER TABLE `role_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sequance`
--
ALTER TABLE `sequance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT for table `siswa_iuran`
--
ALTER TABLE `siswa_iuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT for table `siswa_referal`
--
ALTER TABLE `siswa_referal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `tabungan_aset`
--
ALTER TABLE `tabungan_aset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tagihan_detail`
--
ALTER TABLE `tagihan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `tunjangan_guru`
--
ALTER TABLE `tunjangan_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `user_question`
--
ALTER TABLE `user_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `cicilan_aset`
--
ALTER TABLE `cicilan_aset`
  ADD CONSTRAINT `cicilan_aset_ibfk_1` FOREIGN KEY (`tabungan_aset_id`) REFERENCES `tabungan_aset` (`id`),
  ADD CONSTRAINT `cicilan_aset_ibfk_2` FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `cicilan_aset_ibfk_3` FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`);

--
-- Constraints for table `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `deposit_ibfk_2` FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `gaji_ibfk_2` FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`);

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`),
  ADD CONSTRAINT `guru_ibfk_2` FOREIGN KEY (`pp_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `guru_ibfk_3` FOREIGN KEY (`rekaman_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `guru_kursus`
--
ALTER TABLE `guru_kursus`
  ADD CONSTRAINT `guru_kursus_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `guru_kursus_ibfk_2` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `iuran_detail`
--
ALTER TABLE `iuran_detail`
  ADD CONSTRAINT `iuran_detail_ibfk_1` FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`),
  ADD CONSTRAINT `iuran_detail_ibfk_2` FOREIGN KEY (`pembiayaan_id`) REFERENCES `pembiayaan` (`id`);

--
-- Constraints for table `iuran_terbuat`
--
ALTER TABLE `iuran_terbuat`
  ADD CONSTRAINT `iuran_terbuat_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `iuran_terbuat_ibfk_2` FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `orang`
--
ALTER TABLE `orang`
  ADD CONSTRAINT `orang_ibfk_1` FOREIGN KEY (`agama_id`) REFERENCES `agama` (`id`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `promo`
--
ALTER TABLE `promo`
  ADD CONSTRAINT `promo_ibfk_1` FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `role_menu`
--
ALTER TABLE `role_menu`
  ADD CONSTRAINT `role_menu_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `role_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`),
  ADD CONSTRAINT `siswa_ibfk_3` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `siswa_iuran`
--
ALTER TABLE `siswa_iuran`
  ADD CONSTRAINT `siswa_iuran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `siswa_iuran_ibfk_2` FOREIGN KEY (`iuran_id`) REFERENCES `iuran` (`id`);

--
-- Constraints for table `siswa_referal`
--
ALTER TABLE `siswa_referal`
  ADD CONSTRAINT `siswa_referal_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `siswa_referal_ibfk_2` FOREIGN KEY (`referal_id`) REFERENCES `referal` (`id`);

--
-- Constraints for table `tabungan_aset`
--
ALTER TABLE `tabungan_aset`
  ADD CONSTRAINT `tabungan_aset_ibfk_1` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `tagihan_ibfk_2` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`);

--
-- Constraints for table `tagihan_detail`
--
ALTER TABLE `tagihan_detail`
  ADD CONSTRAINT `tagihan_detail_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`),
  ADD CONSTRAINT `tagihan_detail_ibfk_2` FOREIGN KEY (`diskon_id`) REFERENCES `diskon` (`id`),
  ADD CONSTRAINT `tagihan_detail_ibfk_3` FOREIGN KEY (`pembiayaan_id`) REFERENCES `pembiayaan` (`id`);

--
-- Constraints for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD CONSTRAINT `testimoni_ibfk_1` FOREIGN KEY (`gambar_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`bukti_pembayaran_id`) REFERENCES `file` (`id`);

--
-- Constraints for table `tunjangan_guru`
--
ALTER TABLE `tunjangan_guru`
  ADD CONSTRAINT `tunjangan_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
