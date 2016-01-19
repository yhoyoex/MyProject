-- MySQL dump 10.13  Distrib 5.5.29, for osx10.6 (i386)
--
-- Host: localhost    Database: blank_page
-- ------------------------------------------------------
-- Server version	5.5.29

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
-- Table structure for table `abc`
--

DROP TABLE IF EXISTS `abc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abc` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `maxi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abc`
--

LOCK TABLES `abc` WRITE;
/*!40000 ALTER TABLE `abc` DISABLE KEYS */;
/*!40000 ALTER TABLE `abc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login`
--

DROP TABLE IF EXISTS `user_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `initial` varchar(10) NOT NULL,
  `privilege` text NOT NULL,
  `user_login_level_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login`
--

LOCK TABLES `user_login` WRITE;
/*!40000 ALTER TABLE `user_login` DISABLE KEYS */;
INSERT INTO `user_login` VALUES (1,'2015-08-16 15:18:23','Amanda Lee','amandalee','81dc9bdb52d04dc20036dbd8313ed055','','AL','',2),(2,'2015-08-20 00:02:42','Janet Wijaya','janetw','202cb962ac59075b964b07152d234b70','This is Janet','JW','',2),(8,'2015-08-20 02:08:43','Amanda leejaya','amandaleejaya','202cb962ac59075b964b07152d234b70','asdf','AM','',3),(9,'2015-08-20 02:13:40','Amanda Leea','adsf','3c59dc048e8850243be8079a5c74d079','adsf','adf','',0),(10,'2015-08-20 05:13:38','Samantha Yuli','sayuli','202cb962ac59075b964b07152d234b70','','SYL','',1),(11,'2015-08-20 05:25:09','Ian','iansamasama','202cb962ac59075b964b07152d234b70','','IA','',6),(12,'2015-08-20 05:28:49','Steven','stevent','202cb962ac59075b964b07152d234b70','','ST','',4),(13,'2015-09-02 16:02:46','Smart Men','smartmen','7518ddc1f6fdf53ad7ce9549f71deb8b','This is so Smart','SM','',5);
/*!40000 ALTER TABLE `user_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login_level`
--

DROP TABLE IF EXISTS `user_login_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(50) NOT NULL,
  `privilege` text NOT NULL,
  `sort` int(11) NOT NULL,
  `menu` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login_level`
--

LOCK TABLES `user_login_level` WRITE;
/*!40000 ALTER TABLE `user_login_level` DISABLE KEYS */;
INSERT INTO `user_login_level` VALUES (1,'2015-08-16 15:17:35','system_administrator','*/0=true',1,'*=true'),(2,'2015-08-16 15:17:35','administrator','index/*=true|login/*=true|partner/LIST PARTNER=true',2,'*=true'),(3,'2015-08-17 01:59:35','supervisor','partner/VIEW PARTNER=true|partner/LIST PARTNER=true|index/*=true|login/(ajax) SAVE NEW USER=true|login/(ajax) UPDATE USER PASSWORD=true|login/(ajax) UPDATE USER LEVEL=true|login/(ajax) VIEW LOGIN DETAIL=true|login/VIEW LOGIN LIST=true',3,'2/3=true|5/*=true'),(4,'2015-08-20 05:19:32','Invoicer','partner/*=true|index/*=true',4,'2/3=true|5/*=true|1/*=true'),(5,'2015-08-20 08:58:20','superman','index/*=true',5,'2/*=true'),(6,'2015-08-20 12:14:40','Madmen','index/*=true|partner/*=true|login/(ajax) UPDATE LOGIN LEVEL PRIVILEGE=true|login/(ajax) VIEW LOGIN LEVEL DETAIL=true|login/(ajax) VIEW LOGIN DETAIL=true|login/VIEW LOGIN LIST=true|login/(ajax) UPDATE USER LEVEL=true|login/(ajax) READ USER LOGIN ROW=true|login/(ajax) SAVE NEW USER=true|login/(ajax) UPDATE USER PASSWORD=true',6,'2/3=true|5/*=true');
/*!40000 ALTER TABLE `user_login_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login_menu`
--

DROP TABLE IF EXISTS `user_login_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(100) NOT NULL,
  `creator_user_id` int(11) NOT NULL,
  `link` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login_menu`
--

LOCK TABLES `user_login_menu` WRITE;
/*!40000 ALTER TABLE `user_login_menu` DISABLE KEYS */;
INSERT INTO `user_login_menu` VALUES (1,'2015-08-20 16:08:42','Dashboard',1,'[ROOT]/dashboard/',1,1),(2,'2015-08-20 16:11:26','Partner',1,'',2,3),(3,'2015-08-20 16:11:26','List Partner',1,'[ROOT]partner/',2,5),(4,'2015-08-20 16:13:21','Edit Partner',1,'[ROOT]partner/view',2,4),(5,'2015-08-20 16:13:21','Login Manager',1,'[ROOT]login/viewer',5,5);
/*!40000 ALTER TABLE `user_login_menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-03 17:16:02
