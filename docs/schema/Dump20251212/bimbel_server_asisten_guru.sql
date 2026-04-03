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
-- Table structure for table `asisten_guru`
--

DROP TABLE IF EXISTS `asisten_guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asisten_guru` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `orang_id` int DEFAULT NULL,
  `status` enum('a','n','d') COLLATE utf8mb3_unicode_ci DEFAULT 'a',
  `pp_id` int DEFAULT NULL,
  `berhenti` text COLLATE utf8mb3_unicode_ci,
  `memilih` text COLLATE utf8mb3_unicode_ci,
  `kelebihan` text COLLATE utf8mb3_unicode_ci,
  `kekurangan` text COLLATE utf8mb3_unicode_ci,
  `kesehatan` text COLLATE utf8mb3_unicode_ci,
  `lingkungan` text COLLATE utf8mb3_unicode_ci,
  `aturan` text COLLATE utf8mb3_unicode_ci,
  `pelatihan` text COLLATE utf8mb3_unicode_ci,
  `kapan` text COLLATE utf8mb3_unicode_ci,
  `gaji_sebelumnya` int DEFAULT NULL,
  `gaji_diminta` int DEFAULT NULL,
  `gaji_tetap` int DEFAULT NULL,
  `rekaman_id` int DEFAULT NULL,
  `ideal` text COLLATE utf8mb3_unicode_ci,
  `nama_bank` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `no_rek` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asisten_guru_orang_id_foreign` (`orang_id`),
  KEY `asisten_guru_pp_id_foreign` (`pp_id`),
  KEY `asisten_guru_rekaman_id_foreign` (`rekaman_id`),
  CONSTRAINT `asisten_guru_orang_id_foreign` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`),
  CONSTRAINT `asisten_guru_pp_id_foreign` FOREIGN KEY (`pp_id`) REFERENCES `file` (`id`),
  CONSTRAINT `asisten_guru_rekaman_id_foreign` FOREIGN KEY (`rekaman_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
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
