-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: logilotto
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Bets`
--

DROP TABLE IF EXISTS `Bets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placed_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) DEFAULT NULL,
  `stake_amount` float NOT NULL,
  `win_amount` float DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `placed_numbers` varchar(100) DEFAULT NULL,
  `draw_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Bets_UN` (`id`),
  KEY `Bets_Bets_FK` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Bets`
--

LOCK TABLES `Bets` WRITE;
/*!40000 ALTER TABLE `Bets` DISABLE KEYS */;
INSERT INTO `Bets` VALUES (1,'2019-04-15 04:02:48','Lost',230.4,0,1,'8,58,59,43,34,15,42',2),(2,'2019-04-15 04:03:00','Won',9.6,19.2,1,'25,47,40,42,59,55,14',2),(3,'2019-04-15 04:03:13','Lost',50,0,2,'30,42,14,15,44,28,10',2),(4,'2019-04-15 04:03:34','Lost',760,0,1,'7,59,31,60,46,24,21',2),(6,'2019-04-15 04:13:57','Lost',32.54,0,1,'17,22,31,41,21,37,40',8),(7,'2019-04-15 04:14:08','Won',67,134,1,'13,24,7,56,18,50,34',8),(8,'2019-04-15 04:14:19','Won',30,60,2,'13,24,7,56,18,50,34',8),(9,'2019-04-15 04:16:51','Lost',34.5,0,1,'30,42,14,15,44,28,10',9),(10,'2019-04-15 04:17:13','Won',5.5,22,1,'2,49,1,8,13,17,34',10),(11,'2019-04-15 04:17:13','Won',5.5,22,1,'2,49,1,8,13,17,34',10),(12,'2019-04-15 04:21:06','Won',0.96,2.208,1,'2,49,1,8,13,17,34',12);
/*!40000 ALTER TABLE `Bets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Clients`
--

DROP TABLE IF EXISTS `Clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `balance` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Clients`
--

LOCK TABLES `Clients` WRITE;
/*!40000 ALTER TABLE `Clients` DISABLE KEYS */;
INSERT INTO `Clients` VALUES (1,1034.21),(2,230);
/*!40000 ALTER TABLE `Clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Draws`
--

DROP TABLE IF EXISTS `Draws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Draws` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `draw_numbers` varchar(100) NOT NULL,
  `draw_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Draws`
--

LOCK TABLES `Draws` WRITE;
/*!40000 ALTER TABLE `Draws` DISABLE KEYS */;
INSERT INTO `Draws` VALUES (1,'26,16,35,24,54,5,40','2019-04-15 04:02:19'),(2,'52,55,1,12,57,16,18','2019-04-15 04:05:19'),(3,'21,55,40,28,18,26,31','2019-04-15 04:08:19'),(4,'17,22,31,41,21,37,40','2019-04-15 04:11:19'),(5,'13,24,7,56,18,50,34','2019-04-15 04:12:30'),(6,'13,9,48,42,55,7,21','2019-04-15 04:13:03'),(7,'16,39,36,12,34,4,17','2019-04-15 04:13:09'),(8,'55,25,29,3,12,32,56','2019-04-15 04:14:45'),(9,'2,49,1,8,13,17,34','2019-04-15 04:16:57'),(10,'16,37,34,13,26,40,15','2019-04-15 04:17:15'),(11,'52,53,45,32,7,18,39','2019-04-15 04:20:15'),(12,'7,38,8,52,10,17,55','2019-04-15 04:21:10');
/*!40000 ALTER TABLE `Draws` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Transactions`
--

DROP TABLE IF EXISTS `Transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Transactions`
--

LOCK TABLES `Transactions` WRITE;
/*!40000 ALTER TABLE `Transactions` DISABLE KEYS */;
INSERT INTO `Transactions` VALUES (1,1,'payment','230.4'),(2,1,'payment','9.6'),(3,2,'payment','50'),(4,1,'payment','760'),(5,1,'payout','0'),(6,1,'payout','19.2'),(7,2,'payout','0'),(8,1,'payout','0'),(9,1,'payment','19.20'),(10,1,'payment','32.54'),(11,1,'payment','67'),(12,2,'payment','30'),(13,1,'payout','134'),(14,2,'payout','60'),(15,1,'payment','34.5'),(16,1,'payment','5.5'),(17,1,'payment','5.5'),(18,1,'payout','22'),(19,1,'payout','22'),(20,1,'payment','0.96'),(21,1,'payout','2.208');
/*!40000 ALTER TABLE `Transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'logilotto'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-15  4:23:42
