-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bimbel_server
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tagihan_detail`
--

DROP TABLE IF EXISTS `tagihan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tagihan_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tagihan_id` int DEFAULT NULL,
  `kategori_pembiayaan` enum('a','s','p','d','l') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diskon_id` int DEFAULT NULL,
  `kode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `potongan` int DEFAULT NULL,
  `sub_total` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `komisi` int DEFAULT NULL,
  `pembiayaan_id` int DEFAULT NULL,
  `system` tinyint(1) DEFAULT NULL,
  `tanggal_iuran_mulai` date DEFAULT NULL,
  `tanggal_iuran_berakhir` date DEFAULT NULL,
  `bulan` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tagihan_id` (`tagihan_id`),
  KEY `diskon_id` (`diskon_id`),
  KEY `pembiayaan_id` (`pembiayaan_id`),
  CONSTRAINT `tagihan_detail_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`),
  CONSTRAINT `tagihan_detail_ibfk_2` FOREIGN KEY (`diskon_id`) REFERENCES `diskon` (`id`),
  CONSTRAINT `tagihan_detail_ibfk_3` FOREIGN KEY (`pembiayaan_id`) REFERENCES `pembiayaan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8520 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-12 16:32:58
