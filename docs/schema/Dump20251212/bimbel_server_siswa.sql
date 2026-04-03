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
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_formulir` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guru_id` int DEFAULT NULL,
  `orang_id` int DEFAULT NULL,
  `status` enum('b','a','p','n') COLLATE utf8mb4_general_ci DEFAULT 'b',
  `tanggal_pendaftaran` date DEFAULT (now()),
  `komisi` int DEFAULT NULL,
  `pinyin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dengar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bicara` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `membaca` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menulis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kondisi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggapan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `program` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `paket_belajar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `referal_other` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kursus_id` int DEFAULT NULL,
  `sekolah` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_siswa` enum('Pelajar','Mahasiswa','Pekerja') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guru_id` (`guru_id`),
  KEY `orang_id` (`orang_id`),
  KEY `kursus_id` (`kursus_id`),
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`),
  CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`),
  CONSTRAINT `siswa_ibfk_3` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=603 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-12 16:33:00
