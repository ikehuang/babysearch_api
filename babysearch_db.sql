-- MySQL dump 10.13  Distrib 5.6.16, for Win32 (x86)
--
-- Host: localhost    Database: babysearch_db
-- ------------------------------------------------------
-- Server version	5.6.16

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
-- Table structure for table `bulletin`
--

DROP TABLE IF EXISTS `bulletin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulletin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletin`
--

LOCK TABLES `bulletin` WRITE;
/*!40000 ALTER TABLE `bulletin` DISABLE KEYS */;
INSERT INTO `bulletin` VALUES (1,'The english version of Baby Search will be coming at the end of 2015.');
/*!40000 ALTER TABLE `bulletin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` varchar(255) DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (13,'human','0'),(15,'pet','0'),(16,'valuable','0'),(17,'dogs','15'),(18,'cats','15'),(19,'cell phone','16'),(20,'laptop','16'),(21,'purse','16'),(22,'others','16'),(23,'children','13'),(24,'elder','13'),(25,'reptiles','15'),(26,'rodents','15'),(27,'birds','15'),(28,'large','17'),(29,'medium','17'),(30,'small','17'),(31,'labrador retriever','28'),(32,'dalmatian','28'),(33,'irish water spaniel','28'),(34,'bull terrier','28'),(35,'borzoi','28'),(36,'foxhound','28'),(37,'dobermann','28'),(38,'eskimo dog','28'),(39,'hovawart','28'),(40,'rottweiler','28'),(41,'afghan hound','28'),(42,'pharaoh hound','28'),(43,'giany schnauzer','28'),(44,'foxhound','28'),(45,'chow chow','28'),(46,'puli','28'),(47,'leonberger','28'),(48,'deerhound','28'),(49,'otter hound','28'),(50,'st.bernard','28'),(51,'bloodhound','28'),(52,'curly-coated','28'),(53,'old english','28'),(54,'bouvier des','28'),(55,'briard','28'),(56,'great dane','28'),(57,'bedlington terrier','29'),(58,'sussex speniel','29'),(59,'jura hound','29'),(60,'english springer spaniel','29'),(61,'harrier','29'),(62,'finnish hound','29'),(63,'cirneco dell etna','29'),(64,'basset hound','29'),(65,'samoyed','29'),(66,'finnish spitz','29'),(67,'basenji','29'),(68,'irish terrier','29'),(69,'whippet','29'),(70,'american cocker spaniel','29'),(71,'posavac hound','29'),(72,'french bulldog','29'),(73,'brittany spaniel','29'),(74,'scottish terrier','29'),(75,'boxer','29'),(76,'pomeranian','30'),(77,'shiba inu','30'),(78,'spaniel: papillon','30'),(79,'standard poodle','30'),(80,'chihuahua s','30'),(81,'yorkshire terrier','30'),(82,'miniature duchshund l','30'),(83,'west highland white','30'),(84,'schipperke','30'),(85,'japanese chin','30'),(86,'dandie dinmont terrier','30'),(87,'english bulldog','30'),(88,'boston terrier','30'),(89,'norwich terrier','30'),(90,'lhasa apso','30'),(91,'maltese','30'),(92,'miniature duchshund w','30'),(93,'miniature duchshund s','30'),(94,'king charles spaniel','30'),(95,'chihuahua l','30'),(96,'shih tzu','30'),(97,'pekingese','30'),(98,'pug','30'),(99,'staffordshire bull terrier','30'),(100,'artesian basset','30'),(101,'beagle','30'),(102,'manchester terrier','30'),(103,'long-haired','18'),(104,'short-haired','18'),(105,'himalayan','103'),(106,'somali','103'),(107,'cymric','103'),(108,'oriental longhair','103'),(109,'japanese bobtail longhair','103'),(110,'scottish fold longhair','103'),(111,'maine coon','103'),(112,'norwegian forest cat','103'),(113,'american curl longhair','103'),(114,'balinese','103'),(115,'ragdoll','103'),(116,'persian longhair','103'),(117,'birman','103'),(118,'brown spotted','103'),(119,'angora','103'),(120,'scottish fold','104'),(121,'european shorthair','104'),(122,'ocicat','104'),(123,'devon','104'),(124,'munchkin','104'),(125,'chartreux','104'),(126,'british shorthair','104'),(127,'american bobtail','104'),(128,'american curl shorthair','104'),(129,'bombay','104'),(130,'bengal','104'),(131,'sphynx','104'),(132,'siamese','104'),(133,'burmese','104'),(134,'singapura','104'),(135,'manx','104'),(136,'egyptian mau','104'),(137,'american shorthair','104'),(138,'american curl shorthair','104'),(139,'korat','104'),(140,'cornigh rex','104'),(141,'russian blue','104'),(142,'abyssinian','104'),(143,'tonkinese','104'),(144,'oriental shorthair','104'),(145,'sphynx','104'),(146,'japanese bobtail','104'),(147,'havana','104');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `message` text,
  `expiry_date` date DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `battery_status` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `open` char(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (6,'KB2A56XFKMX','',NULL,NULL,NULL,NULL,'2015-12-31',120.574,211.32,'low',NULL,NULL,'franky@ink.net.tw',NULL),(15,'BD1B46XFKMX','','pet','birdie',NULL,NULL,'2016-05-31',NULL,NULL,NULL,'dogs',NULL,'franky@ink.net.tw',NULL),(17,'AB4B46XFKMX','lost','human','little george','http://babysearchapi.franky.com/upload/85fd689f093e8282bffe45533d804060-201162813412527.jpg','please contact me if you happen to pick this up at 999132094. Thanks!','2016-05-31',NULL,NULL,NULL,NULL,'Y','franky@ink.net.tw',NULL),(18,'DB4D46XFKMX','normal','valuable','iphone','asdfda123374.png','Please contact my owner at 62387654','2016-05-31',NULL,NULL,NULL,'cell phone','N','terry@ink.net.tw',NULL),(21,'INK46XFKMX','normal','pet',NULL,NULL,NULL,'2016-05-31',NULL,NULL,NULL,'cats','N','ike@ink.net.tw',NULL);
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guestbook`
--

DROP TABLE IF EXISTS `guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guestbook` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `message` text,
  `email` varchar(255) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guestbook`
--

LOCK TABLES `guestbook` WRITE;
/*!40000 ALTER TABLE `guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `human_info`
--

DROP TABLE IF EXISTS `human_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `human_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `height` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `bloodtype` varchar(255) DEFAULT NULL,
  `disease` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `medications` text,
  `hospital_name` varchar(255) DEFAULT NULL,
  `hospital_phone` int(11) DEFAULT NULL,
  `hospital_address` varchar(255) DEFAULT NULL,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `human_info`
--

LOCK TABLES `human_info` WRITE;
/*!40000 ALTER TABLE `human_info` DISABLE KEYS */;
INSERT INTO `human_info` VALUES (1,'david',NULL,'db7','male','1981-10-10',180,75,'O','flu','','sleeping pills','UK general hospital',1177777777,'big ben tower, united kingdom.',16),(2,'george',NULL,'gwbaby','male','2001-10-10',20,35,'O','','','','US general hospital',72017777,'statue of freedom, america.',19);
/*!40000 ALTER TABLE `human_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lost_contacts`
--

DROP TABLE IF EXISTS `lost_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lost_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lost_contacts`
--

LOCK TABLES `lost_contacts` WRITE;
/*!40000 ALTER TABLE `lost_contacts` DISABLE KEYS */;
INSERT INTO `lost_contacts` VALUES (25,'anita','mui',456948123,'franky@ink.net.tw'),(26,'peter','pan',21348123,'franky@ink.net.tw'),(27,'ales','wong',987948123,'franky@ink.net.tw');
/*!40000 ALTER TABLE `lost_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mobiles`
--

DROP TABLE IF EXISTS `mobiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobiles` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `sso_id` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `imei` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mobiles`
--

LOCK TABLES `mobiles` WRITE;
/*!40000 ALTER TABLE `mobiles` DISABLE KEYS */;
INSERT INTO `mobiles` VALUES (6,'','211842940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',353328054557408,'franky@ink.net.tw'),(7,'','288942940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',394857392038576,'franky@ink.net.tw'),(8,'','288942940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',288857392038576,'franky@ink.net.tw'),(13,'123457392038576','123442940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',NULL,'watson@ink.net.tw'),(14,'432157392038576','432142940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',NULL,'ike@ink.net.tw'),(15,'678957392038576','678942940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM',NULL,'terry@ink.net.tw');
/*!40000 ALTER TABLE `mobiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pet_info`
--

DROP TABLE IF EXISTS `pet_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pet_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `height` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `temperament` varchar(255) DEFAULT NULL,
  `talents` varchar(255) DEFAULT NULL,
  `description` text,
  `chip_number` varchar(255) DEFAULT NULL,
  `desex` varchar(255) DEFAULT NULL,
  `vaccine_type` varchar(255) DEFAULT NULL,
  `bloodtype` varchar(255) DEFAULT NULL,
  `bloodbank` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `insurance` varchar(255) DEFAULT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `hospital_phone` int(11) DEFAULT NULL,
  `hospital_address` varchar(255) DEFAULT NULL,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pet_info`
--

LOCK TABLES `pet_info` WRITE;
/*!40000 ALTER TABLE `pet_info` DISABLE KEYS */;
INSERT INTO `pet_info` VALUES (1,'birdie','male','2014-02-14',80,25,'friendly','handshake','gold color fur with white collar around his neck','2134567','yes','canine vaccine','A','taipei','','','taipei vet clinic',1298347,'big on road, taipei',15),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,21);
/*!40000 ALTER TABLE `pet_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` varchar(255) DEFAULT NULL,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (19,'franky','franky@ink.net.tw',98345866,'1302 shenzen',NULL,NULL,NULL,NULL),(22,'watson','watson@ink.net.tw',39833456,'sky tower office, taichung.','wt','1990-03-03','M','clamdowndklf.png'),(23,'ike','ike@ink.net.tw',612333456,'101 bdlg., taipei.','keyman','1990-02-02','M','whatthedklf.png'),(24,'terry','terry@ink.net.tw',91233456,'the peak, hong kong.','sausage','1990-01-01','M','ajsdklf.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valuable_info`
--

DROP TABLE IF EXISTS `valuable_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valuable_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valuable_info`
--

LOCK TABLES `valuable_info` WRITE;
/*!40000 ALTER TABLE `valuable_info` DISABLE KEYS */;
INSERT INTO `valuable_info` VALUES (1,'iphone 6','moms birthday present',17),(2,NULL,NULL,18);
/*!40000 ALTER TABLE `valuable_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-25 19:46:50
