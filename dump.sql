-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: workshop_db
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `buy`
--

DROP TABLE IF EXISTS `buy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_users_id` int DEFAULT NULL,
  `id_shop_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF838277376858A8` (`id_users_id`),
  KEY `IDX_CF838277938B6DAD` (`id_shop_id`),
  CONSTRAINT `FK_CF838277376858A8` FOREIGN KEY (`id_users_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_CF838277938B6DAD` FOREIGN KEY (`id_shop_id`) REFERENCES `shop` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy`
--

LOCK TABLES `buy` WRITE;
/*!40000 ALTER TABLE `buy` DISABLE KEYS */;
INSERT INTO `buy` VALUES (1,1,1),(2,1,2);
/*!40000 ALTER TABLE `buy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenge`
--

DROP TABLE IF EXISTS `challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `challenge` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name_challenge` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descritpion_challenge` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenge`
--

LOCK TABLES `challenge` WRITE;
/*!40000 ALTER TABLE `challenge` DISABLE KEYS */;
INSERT INTO `challenge` VALUES (1,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(2,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(3,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(4,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(5,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(6,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 500 UniTs'),(7,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 500 UniTs'),(8,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(9,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(10,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(11,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(12,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 500 UniTs'),(13,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs'),(14,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 1000 UniTs'),(15,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 500 UniTs'),(16,'D├®fi entre amis','Un d├®fi lanc├® entre amis pour 100 UniTs');
/*!40000 ALTER TABLE `challenge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenged`
--

DROP TABLE IF EXISTS `challenged`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `challenged` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_challenge_id` int DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `challenger_id` int DEFAULT NULL,
  `opponent_id` int DEFAULT NULL,
  `selected_winner_challenger` int DEFAULT NULL,
  `selected_winner_opponent` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_84662227BB636FB4` (`id_challenge_id`),
  KEY `IDX_846622272D521FDF` (`challenger_id`),
  KEY `IDX_846622277F656CDC` (`opponent_id`),
  CONSTRAINT `FK_846622272D521FDF` FOREIGN KEY (`challenger_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_846622277F656CDC` FOREIGN KEY (`opponent_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_84662227BB636FB4` FOREIGN KEY (`id_challenge_id`) REFERENCES `challenge` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenged`
--

LOCK TABLES `challenged` WRITE;
/*!40000 ALTER TABLE `challenged` DISABLE KEYS */;
INSERT INTO `challenged` VALUES (5,5,100,'done',2,1,1,1),(6,6,500,'done',2,1,2,2),(7,7,500,'done',1,2,1,1),(8,8,100,'done',2,1,2,2),(10,10,100,'done',1,2,1,1),(12,12,500,'done',1,2,2,2),(13,13,100,'done',1,2,1,1),(15,15,500,'done',2,1,2,2),(16,16,100,'accepted',1,2,1,NULL);
/*!40000 ALTER TABLE `challenged` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20240924072004','2024-09-24 07:21:31',435),('DoctrineMigrations\\Version20240924095317','2024-09-24 09:53:26',17),('DoctrineMigrations\\Version20240924173513','2024-09-24 17:35:18',130),('DoctrineMigrations\\Version20240924195718','2024-09-24 19:57:23',64),('DoctrineMigrations\\Version20240924200238','2024-09-24 20:02:40',12),('DoctrineMigrations\\Version20240924205656','2024-09-24 20:56:57',144),('DoctrineMigrations\\Version20240925085858','2024-09-25 08:59:00',106),('DoctrineMigrations\\Version20240925104325','2024-09-25 10:43:30',14),('DoctrineMigrations\\Version20240925123059','2024-09-25 12:31:03',12),('DoctrineMigrations\\Version20240925130212','2024-09-25 13:02:16',10),('DoctrineMigrations\\Version20240925144301','2024-09-25 14:43:03',36);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_users_id` int DEFAULT NULL,
  `name_event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_event` longtext COLLATE utf8mb4_unicode_ci,
  `cash_prise` int DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7376858A8` (`id_users_id`),
  CONSTRAINT `FK_3BAE0AA7376858A8` FOREIGN KEY (`id_users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (1,1,'Tournoi Rocket League','Niveau Diamond/Champion - 25/09/2024',350,'done'),(2,1,'Tournoi League Of Legend','ARAM 5v5 - 27/09/2024',200,'open');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friend`
--

DROP TABLE IF EXISTS `friend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friend` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_users_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_55EEAC61376858A8` (`id_users_id`),
  CONSTRAINT `FK_55EEAC61376858A8` FOREIGN KEY (`id_users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friend`
--

LOCK TABLES `friend` WRITE;
/*!40000 ALTER TABLE `friend` DISABLE KEYS */;
/*!40000 ALTER TABLE `friend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friend_request`
--

DROP TABLE IF EXISTS `friend_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friend_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F284D94F624B39D` (`sender_id`),
  KEY `IDX_F284D94CD53EDB6` (`receiver_id`),
  CONSTRAINT `FK_F284D94CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_F284D94F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friend_request`
--

LOCK TABLES `friend_request` WRITE;
/*!40000 ALTER TABLE `friend_request` DISABLE KEYS */;
INSERT INTO `friend_request` VALUES (1,1,2,'accepted');
/*!40000 ALTER TABLE `friend_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307FF624B39D` (`sender_id`),
  CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` VALUES (1,1,'Salut','2024-09-25 09:12:59'),(2,2,'comment ca va ? ','2024-09-25 09:13:18'),(3,1,'un defi baby-foot ?','2024-09-25 09:13:37'),(4,2,'salut ','2024-09-25 09:16:53'),(5,1,'salut salut ','2024-09-25 11:05:34'),(6,2,'salut','2024-09-25 11:05:42'),(7,3,'Salut les gens','2024-09-25 11:09:58'),(8,1,'Bonjour je suis a la recherche de quelqu\'un pour jouer au baby-foot, une personne dispo ?','2024-09-25 14:23:50'),(9,2,'salut bonsoir','2024-09-25 17:57:20');
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participate`
--

DROP TABLE IF EXISTS `participate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participate` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_users_id` int DEFAULT NULL,
  `id_event_id` int DEFAULT NULL,
  `ranking` int DEFAULT NULL,
  `units_amount` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D02B138376858A8` (`id_users_id`),
  KEY `IDX_D02B138212C041E` (`id_event_id`),
  CONSTRAINT `FK_D02B138212C041E` FOREIGN KEY (`id_event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_D02B138376858A8` FOREIGN KEY (`id_users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participate`
--

LOCK TABLES `participate` WRITE;
/*!40000 ALTER TABLE `participate` DISABLE KEYS */;
INSERT INTO `participate` VALUES (1,1,1,1,300),(4,2,1,2,50);
/*!40000 ALTER TABLE `participate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name_shop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_shop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_shop` double NOT NULL,
  `stock_shop` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop`
--

LOCK TABLES `shop` WRITE;
/*!40000 ALTER TABLE `shop` DISABLE KEYS */;
INSERT INTO `shop` VALUES (1,'vending','Bon Pour une Boisson',500,28),(2,'park','R├®servation D\'une place de parking',800,19),(3,'bde','Soir├®e au choix offerte par le BDE',1200,5);
/*!40000 ALTER TABLE `shop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname_users` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point_users` int DEFAULT NULL,
  `birth_users` date DEFAULT NULL,
  `role_users` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_users` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_users` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discord` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Louis','Nectoux',1324,'2024-09-24','ADMIN','0768649912','louis.nectoux@gmail.com','$2y$13$CcTNNwtliNvgKhJnAKfaPewdGdE.58010gsBZTIrShH0NBmwaNY3K',NULL),(2,'John ','Doe',1786,'2024-09-24','USER','0768649912','john.doe@gmail.com','$2y$13$CcTNNwtliNvgKhJnAKfaPewdGdE.58010gsBZTIrShH0NBmwaNY3K',NULL),(3,'Yves','Dupont',625,'2024-09-25','USER','0768649912','yves.dupont@gmail.Com','$2y$13$CcTNNwtliNvgKhJnAKfaPewdGdE.58010gsBZTIrShH0NBmwaNY3K',NULL),(4,'Mathilde','Deschamps',129,'2024-09-25','USER','0768649912','mathilde.deschamps@gmail.Com','$2y$13$CcTNNwtliNvgKhJnAKfaPewdGdE.58010gsBZTIrShH0NBmwaNY3K',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-26  8:53:53
