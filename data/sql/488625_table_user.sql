
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `unenpass` varchar(255) DEFAULT NULL,
  `jenis_user` enum('s','c','u') DEFAULT 'u',
  `orang_id` int(11) DEFAULT NULL,
  `status` enum('a','n') DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `unenpass`, `jenis_user`, `orang_id`, `status`) VALUES
(1, 'Admin', '$2y$10$CC2Mjkn19Fv2TTdgta5kmeZ0PuuCeAs6WGQFKlS6GDZMRiusDhbZ.', 'Admin', 's', 1, 'a'),
(2, 'Admin Cabang Permata Baloi', '$2y$10$J1pnzvhJl2j5aA2jlkudTeZB3.ZkPxi2z6i.VY6taCl312kA3soKO', '12345', 'c', 2, 'a'),
(3, 'Teddy', '$2y$10$m8UoDYSkuLYBobcf2HOtZOWzqaOrqYdR2op3G.ajmrsJHBKBIz8Ga', 'Teddy', 'u', 3, 'a'),
(4, 'Cansoni', '$2y$10$c.m4ze/EYpSO1DpGgcITxOFHtKBiv91pjM/Js9HvqH8JG3NmGl1wC', 'Cansoni', 'u', 11, 'a'),
(5, 'Joni', '$2y$10$h1rXAxaB7m71modHm33Cd.d2c.5.7xrW.0sn7NKzfQ.0g/XtQFXvm', 'Joni', 'u', 160, 'a'),
(6, 'Jeen Scyca', '$2y$10$w3pJdU6GpHi1pbUkA6oYre31JGNof/ZRb2BFTajbD/umvjvywdm4e', '211088', 'u', 161, 'a'),
(7, 'Jeffri', '$2y$10$xEFkrBB/hFKuQ3Hxs4V/nOHeTLvgFuqGUUITEyG3srG3Bge1K5OxW', 'Jeffrixu123', 'u', 162, 'a'),
(8, 'Admin Cabang Cahaya Garden', '$2y$10$O0d0hcPbL.i5B8vjtT6Sje/L8qXu6c2QPyAOFPz0sEbHxW3iHfKEe', '12345', 'c', 163, 'a'),
(9, 'Admin Cabang Griya Mas', '$2y$10$svBlzkJIzW6rPXGzmC3oju5vlVxuYEWS1zi3MBvNR2B.lWgco5vhK', '12345', 'c', 164, 'a'),
(10, '8c1b2e', '$2y$10$QdmwlNX8axDOHOGtMgTXDe9sDlOCBMwSKhzcoWFzSyRCFQ196SoFy', 'b1eb2bcc', 'u', 21, 'a'),
(11, '4a7811', '$2y$10$fF0D/vkj8NdnaRpJEKsmDujm3V7cj5cdVpLsOv.9u060g/IU659Rq', '1dfeeadd', 'u', 114, 'a'),
(12, 'a68a1d', '$2y$10$mNa4wHhW0wvB6UF05qBsre3bDVbKkWX7eEpjYn3pCY14qsVxXUcWa', '07e7b41d', 'u', 16, 'a'),
(13, '07bcd8', '$2y$10$4SM7//Femi/4fIWKBdxuE.KeWiuQ4iT68KP06MXGuGVrqQ8foHVhG', '8a13b1e9', 'u', 19, 'a'),
(14, '56240d', '$2y$10$VOkDHj2ZHGVnPSQflD6sUuHpggZzsRVVtpcayGtriw03Guk27X2.6', '3066d5da', 'u', 20, 'a'),
(15, '5df1a0', '$2y$10$qkGhYXmEUNZLGA4edy.l8OdeW.6veL4VHEp3E/aJPlJstxSn60Jiu', '75b30c48', 'u', 22, 'a'),
(16, '4da0e7', '$2y$10$h/CFrO611W8rPz291AQ1WuAj7tOoR/GiTTe5G81wwni2Vxm1K6DRK', '178b1a92', 'u', 25, 'a'),
(17, '4e5115', '$2y$10$pGdywgto5tcje70DrvsZF.bI8zHJy1m.s2AnC2GiAyoGCIRRXsDFi', 'f1debba8', 'u', 29, 'a'),
(18, '358d48', '$2y$10$CL8dJoDYPywTooCSnhfAUOA2a1J9nn2BJKKupqa8gOhq/sAmP/2Fq', '76bccecc', 'u', 31, 'a'),
(19, '1e3512', '$2y$10$JV9q5Av2ANZTLracJOTL6euglaMlsILG./I8N5o7rONGbrA8hqXyG', '69345b23', 'u', 40, 'a'),
(20, '02b889', '$2y$10$35cLa1XHNqag6GH0DpJ8wuDVgVqfsG89FAn0Ft/4ZLr4RI2Wuc4I2', '9747b04d', 'u', 189, 'a'),
(21, 'dbe9a3', '$2y$10$4J7QJnLGfFBi8q.R9mWJOeVcrt7vbBWiU6iuyWSm7s1/cOKtPRE0W', '3470d73c', 'u', 190, 'a'),
(22, 'f4f3a1', '$2y$10$KeqW8PTqUI.aalaFChVbyuZGuP8iFrcJyMctkx8JcsmxSESgZlkjC', 'ba439f54', 'u', 69, 'a'),
(23, 'ffbb32', '$2y$10$3sKN9Nx.dgDC4kR4taeddOljGRPAqjDaAqRBFq9Gq21iGPJNHdI6m', '01616982', 'u', 87, 'a'),
(24, 'GuruA', '$2y$10$Nobw7titbjdeGQ3hLYhx/O8fsNKRGOnIlBSlThdsdMTIqNrva5RIq', '123456', 'u', 240, 'a'),
(25, '460670', '$2y$10$gXkOnBGKpkuiQY7VrAIUA.ZMZtb5us6ybSt6Nm/iOcBCVzQYDGjDC', '74ce6aa3', 'u', 242, 'a'),
(26, 'fc3d25', '$2y$10$UuHQQSrQ.tnerP5PMmPrw.XQOdqWb7hF2lrvtVCY4stSzP5QHJgVK', 'b34b6cab', 'u', 243, 'a'),
(27, 'b98b94', '$2y$10$fQ1PRHXQXxBA3lOSsSZDxukIwpTg14Fdudcu/VQlfPDnLwc5f5dAa', '4e55a988', 'u', 244, 'a'),
(28, '732b6b', '$2y$10$C7XBZPDTKj3tqNkPQBJYm.JwTivkl/FeJOcanS8YoPJYVnA7UO9QS', '37b45812', 'u', 245, 'a'),
(29, '5d246e', '$2y$10$Kxg.dM0FPjaraWEdxVIdYOuZVbx51cv./qHavtfPSR0/ddSebI/Nm', '4d0559c4', 'u', 247, 'a'),
(30, '3c44b7', '$2y$10$iVpfztfViPEjZEI18MJdCepLGGnNOy5ZhJeTwGu9Pj7XLuUTOWeEO', '48cfef41', 'u', 248, 'a'),
(31, 'a19719', '$2y$10$AAOP07433PRIazFyySkFaO8RsHvPucbBscznK5k5Kwy5LavaBntLu', 'e3ce69da', 'u', 249, 'a'),
(32, '37476d', '$2y$10$22tItYQXPG8s6pcv0s/zJu97qaOim7c716NQEP3ex74XMgs0t1wK6', 'ec21cf62', 'u', 250, 'a'),
(33, '261f73', '$2y$10$H7eHggCCYdx.egHRT.IJmu/hn73UV0csoDa8q1oVIEt4j.yWcKCCW', '1bcc347c', 'u', 251, 'a'),
(34, 'dca94a', '$2y$10$kCo3FUMGz.QTFsXOefqDneedsXCJ3vGaSnU5JRWT3gAcHG5MuH5NS', '69c4a3e8', 'u', 252, 'a'),
(35, 'Herdi', '$2y$10$bOkYYFaBysHM0BvdW7DLu.K2dxgw1N99OigW.SK.XRjCtZvKUjRLm', '12345', 'u', 275, 'a'),
(36, 'c80034', '$2y$10$z1Dd60sJiSnQ9CERzBxQeOeX/FBTHACDwKLfDZVO5gieQrIWTk60m', 'fbda89a6', 'u', 27, 'a'),
(37, '7d8d33', '$2y$10$M6By45x4fJ.JGPbXiDvjyeyFlOxeCrCq4fqfGnWU4PneLPIEBdKsy', 'feb5c409', 'u', 315, 'a'),
(38, '20b3ab', '$2y$10$W7OaSxOtixU6dwGR8dk4AOmjJzcZIm5Wkx312YQEaxM6cP9/1aS3q', 'ea69dd98', 'u', 185, 'a'),
(39, '1a9de6', '$2y$10$d1UZ8S7y0e4hegagOOaz/eA.o5dUXPxA1frkK8D0.GFFpMuGXq1Gm', 'dc65eefa', 'u', 183, 'a'),
(40, '738615', '$2y$10$ZdfJ9eq3w0uh7VLHzln26u5hSdiT24PCmgIayg.g/AG.MlNcqkkqy', '241ac057', 'u', 182, 'a'),
(41, 'd0bc65', '$2y$10$XVGLIZtb6JfnmA132DDiw.e5WD7DlNVP3Fj/caLKChcu0H8RuXwdi', 'a241b66d', 'u', 181, 'a'),
(42, 'd8b6da', '$2y$10$x/BEQVfJCQvR0wu86KJB6uUiaKAf2xYjBJnQKsOPszdqpRNVYxZaW', '38ec7635', 'u', 180, 'a'),
(43, 'fb8f7c', '$2y$10$/ptSIPSvQ01uht3AD6kOKe4vWrCxwiO6lJr8g5RT/Ug9IyCbjBGNG', '6a856ce0', 'u', 179, 'a'),
(44, '361413', '$2y$10$.g4WRFoRvMAj3vbsSexP4uw7E3fN0kqA4SM04G1RxXUZjMen99mGq', 'a5c9ad91', 'u', 177, 'a'),
(45, '574a55', '$2y$10$TTo.Y08eWlmcECp4iOaQ2e8OAuXa5hTzcmkzK4PveTXhMo./GX2eW', 'fdf8eb13', 'u', 176, 'a'),
(46, '51ba78', '$2y$10$CEJPVXNdEZxYgoK2XhcJ1.R0Bxxd02Akusv2isNSixd4uTbbD1rGu', '941431b5', 'u', 174, 'a'),
(47, 'd330df', '$2y$10$eSZAOmPiyympaWOFYv09IeJTmimhHNs5wnUofbdoc43J5osGxRxFC', '5651e1a3', 'u', 172, 'a'),
(48, 'bfccdb', '$2y$10$ENcoyqWV1VhBbsySQOB9AepA18luHx1u2Lomnhxi67a8pYrx3Spri', 'c74c9c9d', 'u', 159, 'a'),
(49, 'd11c00', '$2y$10$Krqs0MjnP9mm/Nr5ZNPSyutOK3Ah49ubUoIE.v/2DQVOi37mdWJAy', '343b852a', 'u', 158, 'a'),
(50, '7cc286', '$2y$10$HmZ2mdg.7Kr7xsLxN.co0eFjZjimZyNvsowWzolV9p9Jln5/YUpva', 'f2ef7697', 'u', 157, 'a'),
(51, '2a7ab3', '$2y$10$LwZDQ6HW2Sp72Vzf39B7heychXgePhsd3UTv3obaPtoi04ob7PsOm', '4b2ab97b', 'u', 145, 'a'),
(52, '9c661c', '$2y$10$kVsA64OC1EKeRfZd4D/gL.16KKohtKXH1NThi3yxWa5Q2py2EErnG', 'a80774ac', 'u', 140, 'a'),
(53, '3c0c7f', '$2y$10$dImInSCobZTihKc1GTi2MOyiDBQzRtf0sPaRsIIm4sXlicugCF3Re', '1f2bb4a9', 'u', 134, 'a'),
(54, 'c43903', '$2y$10$lia2jKsyaDfgn6fe4hHWB.56fboz4oVM/LFM9L/2Yvl.Xb35FAyuy', '6b948236', 'u', 123, 'a'),
(55, '8f21ce', '$2y$10$GW.tNlwxHtgMTK80f/KABO.dEZnEi1w2QD1hRLHPSlz/9JjVE3ZXi', '64980549', 'u', 122, 'a'),
(56, '1d5905', '$2y$10$aydb0xpG7/Op1zrMmUrrQOvJOkRs0LO.GAMW4.V8VsQyce3hxrpcu', '57cecd55', 'u', 316, 'a'),
(57, 'ec670c', '$2y$10$6UA9Cml/tz7X1lXLj4YPpel3KnqWZs9Jl7HcINExRT3ZIBue.qCmi', '847b6106', 'u', 116, 'a'),
(58, 'cfdf7b', '$2y$10$Eta9ioayr4FwP3Gawl/IauUBiGOYIudbtbh/4nRLpEZUQkeKYAG5K', 'd85a0aa5', 'u', 115, 'a'),
(59, '76e0da', '$2y$10$xNI57Y6HlWFxU9P5.ysEUOJ5DYjEWZM.VS1ULYRxku6ZdwCd5aL2e', '0de5a98b', 'u', 113, 'a'),
(60, '49ee28', '$2y$10$QgCVrjNXS8bx8n1qQYr0zu1NXIT04iodKTu9YtTfaPE/XIxjjdn2y', '07c8e96e', 'u', 112, 'a'),
(61, 'a7e7e2', '$2y$10$2tEriyNYZLRygeC.k.WaBezhGupHFkl79nR.o6oaCGajtvz0GGwXm', '68d51cb0', 'u', 111, 'a'),
(62, 'da3c5f', '$2y$10$p9V9Mf7oad2qaWgg.ZS3cOmMdsjKklXhISunKV3QjMBCmhdT5TtVm', '24ecfe87', 'u', 170, 'a'),
(63, 'ba4e43', '$2y$10$OkVZBd1VY4S4Dd/cQ25TB.AMyyWDaFU8e.TLHC1Lx6UZUBpZPPAGC', 'ee153d10', 'u', 13, 'a'),
(64, 'fc1013', '$2y$10$nxQbWDY/UgFE/2V84gLhmeFyOKeT23X1rng1p08FAWf8UO6oESEhW', '86fe9fe4', 'u', 110, 'a'),
(65, '3b7244', '$2y$10$mRw7Wq2ZEEdJtoOswiHoMOGj6uQqlIMyzW8jPYCeRQ68U7DryJrja', '153db61e', 'u', 107, 'a'),
(66, 'bfc645', '$2y$10$RXJAn8vmGwCiVq56w2T0N.d177J1gYqHA3g1QoY4bE.2rkXDRVyXm', 'b3a5428c', 'u', 98, 'a'),
(67, '1bf5a9', '$2y$10$exNYRzI9Z3kDkPd/jGbLs.D/QzZicQXcXH2uJPLvA0ollkK5yOC9i', '29d3d14e', 'u', 97, 'a'),
(68, 'de19b8', '$2y$10$/NvJrBAuwRxwtEajL6BeMeS0f7jJFaW5E4P.Q4nMIWPyINAUD9uBO', '504696a6', 'u', 96, 'a'),
(69, '30affc', '$2y$10$D70Lb2cwsUelt4b2aJw6o.vHnX7LJpbiWKbmysSDawmwIxXMNJiMm', '43eabf13', 'u', 89, 'a'),
(70, '0323d8', '$2y$10$QHnay/zxfZ9bboImXj3sxui5OHylnhLAsqCVOBNZM95JumVYdORxS', 'd59f4125', 'u', 86, 'a'),
(71, '881b82', '$2y$10$FPaBWsKuGZvtmEX0/8cMg.GjtLgdf0ysWAg605yikoaCSBagBqrqC', '2f33fa6f', 'u', 74, 'a'),
(72, 'ffdd46', '$2y$10$SVs9Y/OcHM8dOe3M8wR5/uVWqMsNqZwckii54fEmMnjJIUYuADmu2', '5d430ea8', 'u', 73, 'a'),
(73, 'f5cf63', '$2y$10$qtxsGjLFi8x4krow9fc9i.d8D5i.4y4U6kWhx4VPcMg48Vnf1oqu2', '443e4092', 'u', 71, 'a'),
(74, 'cee4e2', '$2y$10$g9blQKzL6IYDmrvrs76xL.SzDtx0NumuhK27zC6GbvL.SDFSSHwxG', 'de42103d', 'u', 263, 'a'),
(75, '6fb14a', '$2y$10$ANVV2Y4ST8iwXtnycmp//O3r6PjxswzYrcF8d.C4/1PdPwPjeQRc6', '0576c4fe', 'u', 262, 'a'),
(76, 'f2da65', '$2y$10$wYr2rVDgvroMUAb1EioQkuNi88/.Yq7MPXDX9nQZfuI2wn8dg0Ek2', '0a699c7f', 'u', 28, 'a'),
(77, 'f14a8b', '$2y$10$opHLj7gpvxwF13f2LvCPiur3sySkCjuLSw1.xfwM6Hx/shRCprw0e', 'a5f2428d', 'u', 261, 'a'),
(78, 'a793dd', '$2y$10$aPfOCNMSs9gqgjFmVkI5k.oHLzMsD/8Vg9JS2c325yaxuQDQpNxeC', '436b72af', 'u', 30, 'a'),
(79, '4ff660', '$2y$10$15WqgTe0owh1t0whF9sx3eEYo/ChGM9oAl9dyKmOYb.MbR4ue0Cn.', 'eb35e136', 'u', 26, 'a'),
(80, '253aac', '$2y$10$Z2EOrE7q7d4ALh8OeVPqU.TKJiUqQrAyEhiiS5wlnYrJ9wjv3s85u', '408cf2ba', 'u', 23, 'a'),
(81, '205c08', '$2y$10$.O0tq4jrYNKJV6k3qZmLG.RGSi368wj2HRHbxASqI1I74.8/mXf9u', 'a33756f1', 'u', 259, 'a'),
(82, '6ffad7', '$2y$10$LYtAP1wE2tqdv9rqZDbEueAxJTYn27wL.PE076pByxWWBs6AQbUdq', 'd31b55c4', 'u', 258, 'a'),
(83, 'e2993f', '$2y$10$OZloQCiMLDvjAJU1zZKDvuJ5NSSF3tqBinzlSIiORufKwYQzFPuUe', 'c1ebc734', 'u', 257, 'a'),
(84, '3fafa9', '$2y$10$yvDB22yXhid1hapqdTD8a.RHHimlSQ7ftPuYPx1/knV9hy8ThP.Fm', 'b8f83a95', 'u', 256, 'a'),
(85, 'a817ea', '$2y$10$iksmLl3GVc6XN1LRspFo8uOR9favuzfGniKo6/rCQgC/v2BzPcZKK', 'cea949c3', 'u', 318, 'a'),
(86, 'b52bb7', '$2y$10$DV82RvljHIc1.xlkhf7FEe.mm.EbFpMY8xcCj9MqwgfIz.FRuoOl6', 'd83d9379', 'u', 255, 'a'),
(87, '7178cb', '$2y$10$t.6WxBNmQPWF0HRin5KHrunZLkQ0P7bqR06NfuPV7qoTFtLvDyc3u', '2658912d', 'u', 239, 'a'),
(88, '5a72bf', '$2y$10$XQw.vakEKViMlFtBUPf79OcuTrU.Bjqwkr02A71ZibpO0nSD9nE5K', '97cda2a3', 'u', 237, 'a'),
(89, 'b21124', '$2y$10$uzmgR6KELWFo6IMmbMfPd.7SMiHsia5DCg8.RZYs1Lznam91GDery', '58780872', 'u', 218, 'a'),
(90, '8ab665', '$2y$10$H.xsIJS2GKkp2u.5z6a3OeW69ZhaE.r7g7CzObFxZFy6XJDoS8mWm', 'd89809b6', 'u', 236, 'a'),
(91, '128b01', '$2y$10$807Ht1e/yEB4RfQcPBfv8eGuOC7vG9MHO4tWjlnIUwgqmeA6Y/SNC', '4bdae1fd', 'u', 317, 'a'),
(92, '54981e', '$2y$10$rWDmmLHzHkY.4L3eJLMLwu9IBIbdntBija1gsOyW7h6e0vOdzLN4q', '498eba3d', 'u', 235, 'a'),
(93, '0187b1', '$2y$10$LE2GS5ETuAOisW95R1P5aeLUij3yghVVY09rVGdP4sKB6W8cMHj42', '127baad1', 'u', 18, 'a'),
(94, 'f011d2', '$2y$10$e1tMUWuwcO3dKyRNy4wvCupJHftXA9148GZ1odm7THTVH50K0K9nG', '77b5e133', 'u', 233, 'a'),
(95, '9ca316', '$2y$10$keEarwQZOHxaifZDuhToF.Cga3kpse4LZp.DdhBEm4PJRVDCiHXEq', '3e680eb2', 'u', 24, 'a'),
(96, '0cdf3d', '$2y$10$Ngy2s05Y3u/NhyluENUrrerSUG4mce0o1ZiLTK0bR/PPSpRaoRrMq', 'd7d8542b', 'u', 232, 'a'),
(97, '0c67b0', '$2y$10$fih/f.9f2FnvssPJRSKc0ubCzz5iz.tGPHwZeFu1QqfmXXKw35EqO', '7e03e588', 'u', 151, 'a'),
(98, '237da8', '$2y$10$XX5Sz5Vis/kzbTBj4X4X2eIRXwc09ib2PTqft4.cy2oHzTbZxidXi', '7b654e34', 'u', 231, 'a');