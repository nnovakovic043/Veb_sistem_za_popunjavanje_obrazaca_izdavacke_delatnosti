-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: izdavacka_delatnost
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `autori`
--

DROP TABLE IF EXISTS `autori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autori` (
  `id_autora` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(20) NOT NULL,
  `prezime` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `biografija` text DEFAULT NULL,
  PRIMARY KEY (`id_autora`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autori`
--

LOCK TABLES `autori` WRITE;
/*!40000 ALTER TABLE `autori` DISABLE KEYS */;
INSERT INTO `autori` VALUES (3,'Milan','Čabarkapa','mcabar@eunet.yu','Profesor je računarstva i informatike u Matematičkoj gimnaziji u Beogradu i autor brojnih udžbenika i zbirki zadataka iz oblasti programiranja. Njegova literatura se koristi u mnogim srednjim školama u Srbiji.'),(4,'Stanka','Matković','mstanka@eunet.yu',NULL),(8,'Perica','Štrbac','pericas@viser.edu.rs','Profesor je strukovnih studija na odseku VISER. Ima 16 godina radnog iskustva u IT sektoru. Učestvovao je u brojnim međunarodnim i domaćim projektima, autor je preko 60 naučnih i stručnih radova, 4 udžbenika i 3 priručnika.'),(9,'Aleksandar','Ivanović','aleksandar@viser.edu.rs',NULL),(15,'Slobodanka','Đenić','slobodanka.djenic@viser.edu.rs','Profesorka je strukovnih studija na odseku VISER. \r\nOsnovne i magistarske studije je zavšila na Elektrotehničkom fakultetu u Beogradu, \r\na doktorske na Tehničkom fakultetu u Čačku. Kao autor je objavila 18 naučnih radova.'),(16,'Jelena','Mitić','jelenam@viser.edu.rs',NULL),(17,'Svetlana','Štrbac- Savić','svetlana.strbac@viser.edu.rs','Profesorka je strukovnih studija i trenutni rukovodilac odseka VISER, na kome radi od 2002. godine. Autor je 4 rada publikovana u naučnim časopisima od međunarodnog značaja.\r\n'),(19,'Valerij','Sinjeljnjikov','drValerij@gmail.com','Poznati je ruski lekar, homeopata, psihoterapeut i pisac. Njegova učenja kombinuju savremenu medicinu sa alternativnim pristupima, a najpoznatiji je po stavu da su bolesti zapravo signali naše podsvesti koji nas upozoravaju na pogrešne stavove i ponašanja.'),(20,'Peter','Handke','peter@gmail.com','Peter Handke je jedan od najznačajnijih pisaca nemačke i savremene svetske književnosti. Pisac je stotinak knjiga, romana, pripovesti, drama, eseja, poezije i filmskih scenarija.');
/*!40000 ALTER TABLE `autori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autori_publikacije`
--

DROP TABLE IF EXISTS `autori_publikacije`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autori_publikacije` (
  `id_autora` int(11) NOT NULL,
  `id_publikacije` int(11) NOT NULL,
  PRIMARY KEY (`id_autora`,`id_publikacije`),
  KEY `id_publikacije` (`id_publikacije`),
  CONSTRAINT `autori_publikacije_ibfk_1` FOREIGN KEY (`id_autora`) REFERENCES `autori` (`id_autora`) ON DELETE CASCADE,
  CONSTRAINT `autori_publikacije_ibfk_2` FOREIGN KEY (`id_publikacije`) REFERENCES `publikacije` (`id_publikacije`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autori_publikacije`
--

LOCK TABLES `autori_publikacije` WRITE;
/*!40000 ALTER TABLE `autori_publikacije` DISABLE KEYS */;
INSERT INTO `autori_publikacije` VALUES (3,3),(4,3),(8,7),(9,7),(15,11),(16,11),(17,11),(19,13),(20,14);
/*!40000 ALTER TABLE `autori_publikacije` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `korisnici` (
  `id_korisnika` int(11) NOT NULL AUTO_INCREMENT,
  `kor_ime` varchar(30) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `tip_korisnika` enum('administrator','izdavac') NOT NULL DEFAULT 'izdavac',
  `datum_kreiranja` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_korisnika`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `korisnici`
--

LOCK TABLES `korisnici` WRITE;
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT INTO `korisnici` VALUES (1,'Admin1','b18e4608d4542005c09ce6b00f68d9e15d43fcf1321690751c365af00a04988d79bf0ee30863fbdfef59b1b079c064c1772a422ae313e5f143101a0de0480dd6','administrator','2026-03-05 13:39:02'),(4,'Milan_Cabarkapa','172b70eec5c12f544ab7efdf344de85ff4ca79afe1685bd5b76fbeb549e76dac909cbecf59d1e3523f3362406f85663fbbd04fbce97853dc73628f272d3c1ca5','izdavac','2026-03-06 15:32:49'),(8,'Ognjen','28bffd06f33dd452d2c3904c627cc3f3d508b4ee67b028a708ae46189323966464e862ebdc5cd8273d839c71999ce87d778b1c34cbfd1d0f6f3faff03cc4e70b','izdavac','2026-03-06 19:01:49'),(10,'Nikola','1ae15da741d3b0ba77b5d58c54fa081b09c34500e3a79a5197c379bc5735db18ec98422c3d56fd4bfd130e8446dd57c1f1ff82141fe257f72b7493e0d9afea06','administrator','2026-06-23 17:27:03'),(13,'Dr_Valerij','0f48b5f48941d4d27f9f17d5205e89c3d8f2b40845f70c80d6aa8c4421d3f0211b92a5708d808e04e420be91656194df842c9d6a2438b24f48a36bb18583e54e','izdavac','2026-06-24 17:43:16');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publikacije`
--

DROP TABLE IF EXISTS `publikacije`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publikacije` (
  `id_publikacije` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) NOT NULL,
  `vrsta_publikacije` enum('knjiga','udzbenik','casopis','ostalo') NOT NULL DEFAULT 'ostalo',
  `tiraz` int(11) NOT NULL,
  `ISBN_ISSN` varchar(17) NOT NULL,
  `datum_kreiranja` datetime NOT NULL DEFAULT current_timestamp(),
  `id_korisnika` int(11) NOT NULL,
  PRIMARY KEY (`id_publikacije`),
  KEY `id_korisnika` (`id_korisnika`),
  CONSTRAINT `publikacije_ibfk_1` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnici` (`id_korisnika`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publikacije`
--

LOCK TABLES `publikacije` WRITE;
/*!40000 ALTER TABLE `publikacije` DISABLE KEYS */;
INSERT INTO `publikacije` VALUES (3,'C/C++ zbirka zadataka','udzbenik',200,'86-7136-104-7','2026-06-20 17:43:56',4),(7,'Standardni korisnički interfejsi- priručnik','udzbenik',100,'978-86-6090-136-3','2026-06-23 14:44:04',8),(11,'Osnovi programiranja na jeziku C','udzbenik',150,'978-86-6090-104-2','2026-06-23 20:02:13',8),(13,'Zavoli bolest svoju','knjiga',250,'978-86-88492-10-9','2026-06-24 17:58:31',13),(14,'Veliki pad','knjiga',200,'978-86-521-3381-9','2026-06-25 10:12:35',10);
/*!40000 ALTER TABLE `publikacije` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-25 15:58:02
