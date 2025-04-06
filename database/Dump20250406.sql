-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: localhost    Database: web_flower_shop
-- ------------------------------------------------------
-- Server version	8.0.33

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
-- Table structure for table `orderitem`
--

DROP TABLE IF EXISTS `orderitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderitem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Orders_OrderID` int NOT NULL,
  `Products_ProductID` int NOT NULL,
  `Quantity` int unsigned NOT NULL DEFAULT '1',
  `Price` decimal(10,2) NOT NULL,
  `Subtotal` decimal(10,2) GENERATED ALWAYS AS ((`Quantity` * `Price`)) VIRTUAL,
  PRIMARY KEY (`id`),
  KEY `Orders_OrderID` (`Orders_OrderID`),
  KEY `Products_ProductID` (`Products_ProductID`),
  CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`Orders_OrderID`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`Products_ProductID`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderitem`
--

LOCK TABLES `orderitem` WRITE;
/*!40000 ALTER TABLE `orderitem` DISABLE KEYS */;
INSERT INTO `orderitem` (`id`, `Orders_OrderID`, `Products_ProductID`, `Quantity`, `Price`) VALUES (9,3,2,3,34.99),(10,4,2,2,29.99),(12,4,3,5,29.99),(13,4,2,3,29.99),(14,4,2,3,29.99),(16,4,2,3,29.99);
/*!40000 ALTER TABLE `orderitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Users_UserID` int NOT NULL,
  `OrderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TotalAmount` decimal(10,2) DEFAULT '0.00',
  `OrderStatus` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Users_UserID` (`Users_UserID`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`Users_UserID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,2,'2025-03-29 16:33:34',150.75,'completed','2025-03-29 15:33:34','2025-03-31 16:15:35'),(4,2,'2025-03-31 18:41:25',119.96,'pending','2025-03-31 16:41:25','2025-03-31 18:02:26'),(5,6,'2025-04-01 09:56:57',0.00,'pending','2025-04-01 07:56:57','2025-04-01 07:56:57'),(7,6,'2025-04-01 13:10:56',120.00,'completed','2025-04-01 11:10:56','2025-04-01 11:14:17'),(8,7,'2025-04-05 15:40:20',125.00,'completed','2025-04-05 13:40:20','2025-04-05 13:42:02');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Orders_OrderID` int NOT NULL,
  `PaymentDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `AmountPaid` decimal(10,2) NOT NULL,
  `PaymentStatus` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `Orders_OrderID` (`Orders_OrderID`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`Orders_OrderID`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,4,'2025-04-01 07:37:01',120.00,'completed'),(5,4,'2025-04-01 10:51:13',100.50,'pending'),(7,3,'2025-04-01 10:53:38',200.00,'completed');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(100) NOT NULL,
  `ProductPrice` decimal(10,2) unsigned NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `ProductDescription` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,'Updated Rose Bouquet',34.99,'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg','An updated beautiful bouquet of roses.'),(3,'Rose1 Bouquet',29.99,'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg','A beautiful bouquet of roses for any occasion.'),(4,'Rose2 Bouquet',29.99,'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg','A beautiful bouquet of roses for any occasion.'),(5,'Tulips Bouquet',21.99,'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg','A beautiful bouquet of tulips for loved ones.');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `UserType` enum('admin','customer') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email` (`email`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Amina','Crncevic','amina.crncevic@stu.ibu.edu.ba','$2y$10$uOql75KCY2MYh4.z9TMptuAnWU11cG4qmdgoixok5XcJtYVLmxOXW','customer'),(6,'Amina1','Crncevic','amina1.crncevic@stu.ibu.edu.ba','$2y$10$3qRWQRvWffUZhXhmRKIsWefNPOqglKp.DLMWgj93rChSm4ibHM4SK','customer'),(7,'Johnathan','Doe','johnathan@example.com','$2y$10$xp9UccVYHxNoyGAYt/1NeO2o70/x2HWEnJSn.33OSwAS1pYqrv8Ry','customer');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Users_UserID` int NOT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_wishlist` (`Users_UserID`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`Users_UserID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (6,2,'2025-03-30 16:00:16'),(15,7,'2025-04-05 13:09:57');
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlistitem`
--

DROP TABLE IF EXISTS `wishlistitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlistitem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Wishlist_WishlistID` int NOT NULL,
  `Products_ProductID` int NOT NULL,
  `AddedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Wishlist_WishlistID` (`Wishlist_WishlistID`),
  KEY `Products_ProductID` (`Products_ProductID`),
  CONSTRAINT `wishlistitem_ibfk_1` FOREIGN KEY (`Wishlist_WishlistID`) REFERENCES `wishlist` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlistitem_ibfk_2` FOREIGN KEY (`Products_ProductID`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlistitem`
--

LOCK TABLES `wishlistitem` WRITE;
/*!40000 ALTER TABLE `wishlistitem` DISABLE KEYS */;
INSERT INTO `wishlistitem` VALUES (5,6,2,'2025-03-31 13:47:48'),(7,6,5,'2025-04-01 12:19:01');
/*!40000 ALTER TABLE `wishlistitem` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-06 13:47:54
