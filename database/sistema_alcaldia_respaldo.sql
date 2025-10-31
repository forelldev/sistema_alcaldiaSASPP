-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sistema_alcaldia
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `casos`
--

DROP TABLE IF EXISTS `casos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casos` (
  `id_caso` int(11) NOT NULL AUTO_INCREMENT,
  `ci` varchar(100) NOT NULL,
  `estado` varchar(50) DEFAULT 'Sin Atender',
  `direccion` varchar(75) NOT NULL,
  `oficina` varchar(100) NOT NULL,
  PRIMARY KEY (`id_caso`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casos`
--

LOCK TABLES `casos` WRITE;
/*!40000 ALTER TABLE `casos` DISABLE KEYS */;
INSERT INTO `casos` VALUES (23,'3215','En Proceso','Desarrollo Social','Aún no ha llegado a una oficina'),(24,'3215','En Proceso','Desarrollo Social','Aún no ha llegado a una oficina'),(25,'3215','En Proceso','Desarrollo Social','Despacho'),(26,'13264184','Sin Atender','Desarrollo Social','Desarrollo Social'),(27,'5059','Sin Atender','Desarrollo Social','Aún no ha llegado a una oficina'),(28,'3215','En Proceso','Desarrollo Social','Despacho'),(30,'3030','En Proceso','Desarrollo Social','Despacho');
/*!40000 ALTER TABLE `casos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casos_archivos`
--

DROP TABLE IF EXISTS `casos_archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casos_archivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caso` int(11) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `tipo_mime` varchar(100) DEFAULT NULL,
  `peso_kb` int(11) DEFAULT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_caso` (`id_caso`),
  CONSTRAINT `casos_archivos_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos` (`id_caso`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casos_archivos`
--

LOCK TABLES `casos_archivos` WRITE;
/*!40000 ALTER TABLE `casos_archivos` DISABLE KEYS */;
INSERT INTO `casos_archivos` VALUES (7,23,'cartas/carta_6903eab12bc61_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-30 18:46:09'),(8,24,'cartas/carta_6903ec87c212b_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-30 18:53:59'),(9,25,'cartas/carta_6903ed2cbd670_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-30 18:56:44'),(10,26,'cartas/carta_6905117a323d8_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-31 15:43:54'),(11,27,'cartas/carta_690515113f2fa_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-31 15:59:13'),(12,28,'cartas/carta_6905183495ffa_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-31 16:12:36'),(14,30,'cartas/carta_69053576c42d5_logo #3 ###.png','logo #3 ###.png','image/png',75,'2025-10-31 18:17:26');
/*!40000 ALTER TABLE `casos_archivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casos_categoria`
--

DROP TABLE IF EXISTS `casos_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casos_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caso` int(11) NOT NULL,
  `tipo_ayuda` varchar(100) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_caso` (`id_caso`),
  CONSTRAINT `casos_categoria_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos` (`id_caso`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casos_categoria`
--

LOCK TABLES `casos_categoria` WRITE;
/*!40000 ALTER TABLE `casos_categoria` DISABLE KEYS */;
INSERT INTO `casos_categoria` VALUES (18,23,'Sin Registrar','Medicamentos'),(19,24,'Sin Registrar','Medicamentos'),(20,25,'Sin Registrar','Medicamentos'),(21,26,'Sin Registrar','Sin Registrar'),(22,27,'Sin Registrar','Sin Registrar'),(23,28,'Sin Registrar','Medicamentos'),(25,30,'Sin Registrar','Medicamentos');
/*!40000 ALTER TABLE `casos_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casos_fecha`
--

DROP TABLE IF EXISTS `casos_fecha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casos_fecha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caso` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_caso` (`id_caso`),
  CONSTRAINT `casos_fecha_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos` (`id_caso`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casos_fecha`
--

LOCK TABLES `casos_fecha` WRITE;
/*!40000 ALTER TABLE `casos_fecha` DISABLE KEYS */;
INSERT INTO `casos_fecha` VALUES (23,23,'2025-10-30 18:46:09',1,'0000-00-00 00:00:00'),(24,24,'2025-10-30 18:53:59',1,'0000-00-00 00:00:00'),(25,25,'2025-10-30 18:56:44',1,'0000-00-00 00:00:00'),(26,26,'2025-10-31 15:43:54',1,'0000-00-00 00:00:00'),(27,27,'2025-10-31 15:59:13',1,'0000-00-00 00:00:00'),(28,28,'2025-10-31 16:12:36',1,'2025-10-31 17:42:10'),(30,30,'2025-10-31 18:17:26',1,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `casos_fecha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casos_info`
--

DROP TABLE IF EXISTS `casos_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casos_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caso` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `creador` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_caso` (`id_caso`),
  CONSTRAINT `casos_info_ibfk_1` FOREIGN KEY (`id_caso`) REFERENCES `casos` (`id_caso`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casos_info`
--

LOCK TABLES `casos_info` WRITE;
/*!40000 ALTER TABLE `casos_info` DISABLE KEYS */;
INSERT INTO `casos_info` VALUES (23,23,'sisas','atencion alciudadano'),(24,24,'sdfsdf','atencion alciudadano'),(25,25,'qweqwe','atencion alciudadano'),(26,26,'quiero una casa wey','atencion alciudadano'),(27,27,'pues no se xd','atencion alciudadano'),(28,28,'quiero casita en maincra','atencion alciudadano'),(30,30,'pues quiero comida','atencion alciudadano');
/*!40000 ALTER TABLE `casos_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunidades`
--

DROP TABLE IF EXISTS `comunidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comunidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunidades`
--

LOCK TABLES `comunidades` WRITE;
/*!40000 ALTER TABLE `comunidades` DISABLE KEYS */;
INSERT INTO `comunidades` VALUES (1,'PALMICHAL'),(2,'LA ENSENADA'),(3,'CUJISAL'),(4,'EL CARDON'),(5,'AGUA AZUL'),(6,'ESPARRAMADERO'),(7,'CAJA DE AGUA'),(8,'PRODUCTORES DE CAMPO ALEGRE'),(9,'VILLAS DE YARA'),(10,'RENACER DE UN PUEBLO'),(11,'EL PARAISO'),(12,'DON ANTONIO'),(13,'MOTOCROSS'),(14,'ANA SUAREZ CENTRO'),(15,'LA MAPORITA'),(16,'EL JAGUEY'),(17,'SABANA DE TIQUIRE'),(18,'CERRO GRANDE'),(19,'TACARIGUITA'),(20,'REVOLUCION 106'),(21,'SIEMPRE ADELANTE 107 SAN JOSE'),(22,'MAIZANTA'),(23,'CREANDO CONCIENCIA'),(24,'UNIDAD Y ACCION'),(25,'MONTAÑITA I'),(26,'DANIEL CARIAS Y BANCO OBREROS'),(27,'MONTAÑITA III'),(28,'BARRIO BOLIVAR'),(29,'LA REALIDAD'),(30,'TEREPAIMA'),(31,'COLINAS DE TEREPAIMA (VOLUNTAD Y ACCION)'),(32,'BRISAS DE TEREPAIMA'),(33,'CASERIO DE CAÑAVERAL'),(34,'SOL BOLIVARIANO'),(35,'EL SALTO'),(36,'SABANA DE GUREMAL'),(37,'QUEBRADA GRANDE'),(38,'EL PLAYON'),(39,'BRISAS DEL PEGON'),(40,'ARENALES VIA EL SALTO'),(41,'CAMBURITO SECTOR LA CRISPINERA'),(42,'LA FLORIDA'),(43,'MONTANITA II BICENTENARIO'),(44,'II DE SEPTIEMBRE'),(45,'MONTAÑITA INDIO COY ( LIRIOS DEL VALLE)'),(46,'LA VICTORIA'),(47,'YACURAL'),(48,'TORBELLAN'),(49,'ANIMAS'),(50,'UVEDAL'),(51,'DON NICOLA'),(52,'EL SARURO'),(53,'PUEBLO UNIDO'),(54,'OVIDIO MARCHAN'),(55,'AGUA VIVA'),(56,'SAN ANTONIO LA TAPA'),(57,'BRISAS DE LA TAPA'),(58,'TAPA LA LUCHA'),(59,'EL POR VENIR'),(60,'FRANCISCA HERNANDEZ'),(61,'FABRICIO SEQUERA/ LA MORA'),(62,'RIVERA SANTA LUCIA'),(63,'ALDEA LA PAZ'),(64,'LA FUENTE'),(65,'CANAAN CELESTIAL TIERRA DE DIOS'),(66,'TOTUMILLO'),(67,'SAN ROQUE'),(68,'AMINTA ABREU'),(69,'LA VAQUERA BARRIO AJURO'),(70,'PIEDRA ARRIBA'),(71,'PIEDRA CENTRO'),(72,'SAN ANTONIO - LA PIEDRA'),(73,'PUEBLO NUEVO'),(74,'DON TEODORO'),(75,'TEOLINDA PAEZ'),(76,'SANTA EDUVIGE LOS RANCHOS'),(77,'PAZ BOLIVARIANA'),(78,'SOMOS TODOS'),(79,'URBANIZACION ARAGUANEY'),(80,'NUEVA ESPERANZA-CRISTO REY'),(81,'LOS REVOLUCIONARIOS'),(82,'VILLA OLIMPICA'),(83,'RAFAEL RANGEL'),(84,'SUEÑOS BOLIVARIANOS SABANITA 1'),(85,'SECTOR LA VIRGEN'),(86,'LA ROCA DE LA SALVACIÓN'),(87,'URIBEQUE'),(88,'URBANIZACION SIMON RODRIGUEZ III'),(89,'URBANIZACION SIMON RODRIGUEZ I'),(90,'SANTA INES'),(91,'ALI PRIMERA PLATANALES'),(92,'JUAN BERNARDO NAHACA'),(93,'LA ORQUIDEA'),(94,'SABANITA 4/ ALI PRIMERA'),(95,'VILLA JARDIN'),(96,'UNION BOLIVARIANA /BOLIVARIANA 1'),(97,'TRICENTENARIA POPULAR'),(98,'EL PINAL'),(99,'EL POZON'),(100,'LIMONCITO'),(101,'EL CARMELERO'),(102,'AGUA NEGRA'),(103,'AGUA LINDA'),(104,'ALBARICAL'),(105,'LA PERDOMERA'),(106,'LA HILERA'),(107,'PEGON PASTOR GARCIA'),(108,'TRICENTENARIA 1'),(109,'TERMO YARACUY'),(110,'ENCRUCIJADA'),(111,'VALLES DE PEÑA'),(112,'HATO VIEJO'),(113,'CAMINO NUEVO'),(114,'SAN RAFAEL'),(115,'LOS TUBOS'),(116,'LOS PATIECITOS'),(117,'POTRERITO'),(118,'CAÑADA TEMA'),(119,'EL MILAGRO DE BARRIO AJURO I'),(120,'BARRIO AJURO LAS 4R'),(121,'SAN ANTONIO (LA REVOLUCION DE SAN ANTONIO )'),(122,'EL VAPOR'),(123,'ARENALES( VIA LAS VELAS)'),(124,'AMIGO TRES CALLEJONES'),(125,'GRANVEL'),(126,'LAS VELAS CENTRO'),(127,'5 Y 7 CASAS'),(128,'EL PALMAR'),(129,'YUMARITO'),(130,'SANTA BARBARA'),(131,'SANTA LUCIA'),(132,'LA CONCEPCION'),(133,'PILCO MAYO'),(134,'VILLAS SANTA LUCIA'),(135,'TIAMA'),(136,'LA BANDERA'),(137,'JOSE GREGORIO AMAYA'),(138,'LA TRILLA'),(139,'TIERRA AMARILLA'),(140,'EL CHIMBORAZO'),(141,'LA RURAL SECTOR 102'),(142,'EL JOBITO');
/*!40000 ALTER TABLE `comunidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `constancias`
--

DROP TABLE IF EXISTS `constancias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constancias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_manual` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `ci` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `constancias`
--

LOCK TABLES `constancias` WRITE;
/*!40000 ALTER TABLE `constancias` DISABLE KEYS */;
/*!40000 ALTER TABLE `constancias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho`
--

DROP TABLE IF EXISTS `despacho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despacho` (
  `id_despacho` int(11) NOT NULL AUTO_INCREMENT,
  `id_manual` int(11) NOT NULL,
  `ci` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `invalido` int(11) NOT NULL,
  PRIMARY KEY (`id_despacho`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho`
--

LOCK TABLES `despacho` WRITE;
/*!40000 ALTER TABLE `despacho` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho_categoria`
--

DROP TABLE IF EXISTS `despacho_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despacho_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_despacho` int(11) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `tipo_ayuda` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_despacho` (`id_despacho`),
  CONSTRAINT `despacho_categoria_ibfk_1` FOREIGN KEY (`id_despacho`) REFERENCES `despacho` (`id_despacho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho_categoria`
--

LOCK TABLES `despacho_categoria` WRITE;
/*!40000 ALTER TABLE `despacho_categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho_fecha`
--

DROP TABLE IF EXISTS `despacho_fecha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despacho_fecha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_despacho` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_renovacion` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fecha_despacho` (`id_despacho`),
  CONSTRAINT `fk_fecha_despacho` FOREIGN KEY (`id_despacho`) REFERENCES `despacho` (`id_despacho`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho_fecha`
--

LOCK TABLES `despacho_fecha` WRITE;
/*!40000 ALTER TABLE `despacho_fecha` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho_fecha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho_info`
--

DROP TABLE IF EXISTS `despacho_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despacho_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_despacho` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `creador` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_descripcion_despacho` (`id_despacho`),
  CONSTRAINT `fk_descripcion_despacho` FOREIGN KEY (`id_despacho`) REFERENCES `despacho` (`id_despacho`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho_info`
--

LOCK TABLES `despacho_info` WRITE;
/*!40000 ALTER TABLE `despacho_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho_invalido`
--

DROP TABLE IF EXISTS `despacho_invalido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `despacho_invalido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_despacho` int(11) NOT NULL,
  `razon` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invalido_despacho` (`id_despacho`),
  CONSTRAINT `fk_invalido_despacho` FOREIGN KEY (`id_despacho`) REFERENCES `despacho` (`id_despacho`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho_invalido`
--

LOCK TABLES `despacho_invalido` WRITE;
/*!40000 ALTER TABLE `despacho_invalido` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho_invalido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes_acciones`
--

DROP TABLE IF EXISTS `reportes_acciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reportes_acciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `accion` varchar(255) NOT NULL,
  `ci` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_doc` (`id_doc`),
  KEY `ci` (`ci`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes_acciones`
--

LOCK TABLES `reportes_acciones` WRITE;
/*!40000 ALTER TABLE `reportes_acciones` DISABLE KEYS */;
INSERT INTO `reportes_acciones` VALUES (1,2,'2025-08-05 09:03:52','Envió la solicitud a administración por lo tanto, finalizó los 3 procesos, entregado!',3434),(2,2,'2025-08-05 09:04:56','Reinició el proceso de la solicitud',3434),(3,2,'2025-08-05 09:05:08','Recibió documento físico, y aprobó para su procedimiento',3434),(4,2,'2025-08-05 09:34:19','Envió la solicitud a despacho.',3434),(5,2,'2025-08-05 09:36:40','Envió la solicitud a administración.',3434),(6,2,'2025-08-05 09:38:14','Confirmó que se aceptó la ayuda.',3434),(7,2,'2025-08-05 09:38:26','Reinició el proceso de la solicitud.',3434),(8,1,'2025-08-05 20:27:04','Envió la solicitud a Administración. (Despacho)',123),(9,1,'2025-08-05 20:28:26','Confirmó que se entregó la ayuda. (Despacho)',123),(10,1,'2025-08-05 20:28:36','Reinició la solicitud. (Despacho)',123),(11,1,'2025-08-11 10:45:08','Inhabilitó la solicitud razón: porque siii',3434),(12,1,'2025-08-11 10:48:01','Inhabilitó la solicitud razón: pqsi',3434),(13,1,'2025-08-11 10:50:42','Inhabilitó la solicitud razón: pq si',123),(14,1,'2025-08-11 10:51:04','Habilitó la solicitud',123),(15,1,'2025-08-11 10:52:04','Habilitó la solicitud',123),(16,2,'2025-08-11 21:08:20','Recibió documento físico, y aprobó para su procedimiento.',34),(17,2,'2025-08-11 21:08:31','Envió la solicitud a despacho.',34),(18,2,'2025-09-12 08:35:18','Envió la solicitud a administración.',3434),(19,2,'2025-09-12 08:35:36','Confirmó que se aceptó la ayuda.',3434),(20,2,'2025-09-12 08:35:47','Reinició el proceso de la solicitud.',3434),(21,2,'2025-09-16 15:29:05','Recibió documento físico, y aprobó para su procedimiento.',34),(22,2,'2025-09-16 15:29:16','Envió la solicitud a despacho.',34),(23,2,'2025-09-16 15:31:32','Envió la solicitud a administración.',123),(24,2,'2025-09-16 15:33:19','Confirmó que se aceptó la ayuda.',321),(25,1,'2025-09-16 15:45:11','Envió la solicitud a Administración. (Despacho)',123),(26,1,'2025-09-16 15:46:02','Confirmó que se entregó la ayuda. (Despacho)',123),(27,1,'2025-09-16 15:46:12','Reinició la solicitud. (Despacho)',123),(28,2,'2025-09-17 20:54:52','Reinició el proceso de la solicitud.',3434),(29,2,'2025-09-17 20:55:11','Recibió documento físico, y aprobó para su procedimiento.',3434),(30,2,'2025-09-17 21:07:00','Envió la solicitud a despacho.',3434),(31,2,'2025-09-17 21:07:11','Envió la solicitud a administración.',3434),(32,2,'2025-09-17 21:07:22','Confirmó que se entregó la ayuda.',3434),(33,2,'2025-09-20 14:25:40','Reinició el proceso de la solicitud.',3434),(34,2,'2025-09-20 14:27:06','Recibió documento físico, y aprobó para su procedimiento.',34),(35,2,'2025-09-20 14:27:25','Envió la solicitud a despacho.',34),(36,2,'2025-09-20 14:28:34','Envió la solicitud a administración.',123),(37,2,'2025-09-20 14:29:55','Confirmó que se entregó la ayuda.',321),(38,2,'2025-09-20 16:03:23','Reinició el proceso de la solicitud.',3434),(39,2,'2025-09-22 11:43:20','Recibió documento físico, y aprobó para su procedimiento.',3434),(40,2,'2025-09-22 11:43:28','Envió la solicitud a despacho.',3434),(41,1,'2025-09-22 11:45:11','Envió la solicitud a Administración. (Despacho)',123),(42,2,'2025-09-23 15:54:55','Envió la solicitud a administración.',3434),(43,2,'2025-09-23 15:56:16','Confirmó que se entregó la ayuda.',3434),(44,2,'2025-09-23 15:56:53','Reinició el proceso de la solicitud.',3434),(45,2,'2025-09-25 12:43:35','Recibió documento físico, y aprobó para su procedimiento.',3434),(46,4,'2025-10-03 18:12:16','Creó una nueva constancia.',3434),(47,13,'2025-10-06 09:35:21','Creó una nueva solicitud de ayuda.',3434),(48,3,'2025-10-06 10:18:42','Recibió documento físico, y aprobó para su procedimiento.',3434),(49,3,'2025-10-06 10:18:55','Envió la solicitud a despacho.',3434),(50,3,'2025-10-06 10:19:07','Envió la solicitud a administración.',3434),(51,3,'2025-10-06 10:19:19','Confirmó que se entregó la ayuda.',3434),(52,3,'2025-10-06 10:19:32','Reinició el proceso de la solicitud.',3434),(53,13,'2025-10-12 16:32:03','Registró solicitud en Desarrollo Social',3434),(54,13,'2025-10-12 16:39:18','Aprobó la solicitud para su procedimiento (Desarrollo Social)',3434),(55,13,'2025-10-12 16:40:23','Envió la solicitud a Administración. (Desarrollo Social)',3434),(56,13,'2025-10-12 16:40:35','Confirmó que se entregó la ayuda. (Desarrollo Social)',3434),(57,13,'2025-10-12 16:40:47','Reinició la solicitud. (Desarrollo Social)',3434),(58,14,'2025-10-12 17:02:00','Registró solicitud en Desarrollo Social',3434),(59,13,'2025-10-12 18:46:47','Inhabilitó la solicitud razón: siempre estoy brishando',3434),(60,13,'2025-10-12 18:51:35','Habilitó la solicitud',3434),(61,13,'2025-10-12 18:53:29','Inhabilitó la solicitud razón: para probar algo',3434),(62,13,'2025-10-12 19:23:03','Editó la solicitud de Desarrollo Social',3434),(63,6,'2025-10-14 18:44:45','Creó una nueva solicitud de ayuda.',3434),(64,4,'2025-10-14 20:31:19','Inhabilitó la solicitud razón: porque quiero inhabilitarla y soy arrecho',3434),(65,4,'2025-10-14 20:34:37','Inhabilitó la solicitud razón: porque quiero ',3434),(66,4,'2025-10-14 20:38:49','Habilitó la solicitud',3434),(67,4,'2025-10-14 20:39:06','Inhabilitó la solicitud razón: de prueba 1',3434),(68,4,'2025-10-14 20:39:17','Habilitó la solicitud',3434),(69,7,'2025-10-14 21:01:54','Creó una nueva solicitud de ayuda.',3434),(70,15,'2025-10-14 21:22:29','Creó una nueva solicitud de ayuda.',3434),(71,6,'2025-10-15 09:17:28','Editó la solicitud',3434),(72,6,'2025-10-15 09:20:57','Inhabilitó la solicitud razón: razoncita',3434),(73,6,'2025-10-15 09:23:47','Editó la solicitud',3434),(74,6,'2025-10-15 09:24:08','Habilitó la solicitud',3434),(75,6,'2025-10-15 09:24:26','Inhabilitó la solicitud razón: pq si',3434),(76,6,'2025-10-15 09:24:38','Habilitó la solicitud',3434),(77,1,'2025-10-17 16:57:13','Creó un nuevo caso.',3434),(78,1,'2025-10-17 17:08:20','Creó un nuevo caso.',3434),(79,2,'2025-10-17 18:16:03','Creó un nuevo caso.',4343),(80,3,'2025-10-17 18:50:04','Creó un nuevo caso.',4343),(81,4,'2025-10-29 16:25:47','Creó un nuevo caso.',3434),(82,5,'2025-10-29 16:39:23','Creó un nuevo caso.',4343),(83,6,'2025-10-29 16:40:19','Creó un nuevo caso.',4343),(84,17,'2025-10-29 16:51:50','Registró solicitud en Desarrollo Social',34),(85,7,'2025-10-29 17:16:33','Creó un nuevo caso.',4343),(86,9,'2025-10-29 18:01:24','Creó un nuevo caso.',4343),(87,10,'2025-10-29 18:03:05','Creó un nuevo caso.',4343),(88,11,'2025-10-29 18:11:47','Creó un nuevo caso.',4343),(89,12,'2025-10-30 15:46:18','Creó un nuevo caso.',4343),(90,13,'2025-10-30 15:50:33','Creó un nuevo caso.',4343),(91,14,'2025-10-30 17:26:54','Creó un nuevo caso.',4343),(92,15,'2025-10-30 17:43:33','Creó un nuevo caso.',4343),(93,16,'2025-10-30 17:47:07','Creó un nuevo caso.',4343),(94,17,'2025-10-30 17:49:57','Creó un nuevo caso.',4343),(95,18,'2025-10-30 18:23:29','Creó un nuevo caso.',4343),(96,19,'2025-10-30 18:40:38','Creó un nuevo caso.',4343),(97,20,'2025-10-30 18:43:29','Creó un nuevo caso.',4343),(98,21,'2025-10-30 18:44:05','Creó un nuevo caso.',4343),(99,22,'2025-10-30 18:45:00','Creó un nuevo caso.',4343),(100,23,'2025-10-30 18:46:09','Creó un nuevo caso.',4343),(101,24,'2025-10-30 18:53:59','Creó un nuevo caso.',4343),(102,25,'2025-10-30 18:56:44','Creó un nuevo caso.',4343),(103,26,'2025-10-31 15:43:54','Creó un nuevo caso.',4343),(104,27,'2025-10-31 15:59:13','Creó un nuevo caso.',4343),(105,28,'2025-10-31 16:12:36','Creó un nuevo caso.',4343),(106,28,'2025-10-31 17:24:23','Editó información del caso',4343),(107,28,'2025-10-31 17:24:31','Editó información del caso',4343),(108,28,'2025-10-31 17:37:22','Editó información del caso',4343),(109,28,'2025-10-31 17:38:28','Editó información del caso',4343),(110,28,'2025-10-31 17:39:32','Editó información del caso',4343),(111,28,'2025-10-31 17:42:10','Editó información del caso',4343),(112,29,'2025-10-31 18:10:51','Creó un nuevo caso.',4343),(113,30,'2025-10-31 18:17:26','Creó un nuevo caso.',4343);
/*!40000 ALTER TABLE `reportes_acciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes_entradas`
--

DROP TABLE IF EXISTS `reportes_entradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reportes_entradas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ci` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes_entradas`
--

LOCK TABLES `reportes_entradas` WRITE;
/*!40000 ALTER TABLE `reportes_entradas` DISABLE KEYS */;
INSERT INTO `reportes_entradas` VALUES (1,3434,'2025-09-04 12:26:12','2025-09-05 12:55:59'),(2,3434,'2025-09-05 12:56:05','2025-09-12 08:24:41'),(3,3434,'2025-09-12 08:25:59','2025-09-13 17:37:11'),(4,3434,'2025-09-13 17:37:17','2025-09-14 08:45:06'),(5,3434,'2025-09-14 08:45:13','2025-09-15 14:02:45'),(6,3434,'2025-09-15 14:02:54','2025-09-16 14:48:19'),(7,3434,'2025-09-16 14:48:25','2025-09-16 15:19:41'),(8,34,'2025-09-16 15:20:03','2025-09-16 15:29:29'),(9,123,'2025-09-16 15:29:40','2025-09-16 15:31:59'),(10,321,'2025-09-16 15:32:12','2025-09-16 15:36:54'),(11,3434,'2025-09-16 15:39:29','2025-09-16 15:40:27'),(12,123,'2025-09-16 15:40:38','2025-09-16 15:43:06'),(13,3434,'2025-09-16 15:43:37','2025-09-16 15:44:33'),(14,123,'2025-09-16 15:44:48','2025-09-16 15:47:03'),(15,3434,'2025-09-17 14:46:06','2025-09-17 14:57:31'),(16,3434,'2025-09-17 14:58:17','2025-09-17 14:58:23'),(17,3434,'2025-09-17 14:58:50','2025-09-17 14:59:06'),(18,3434,'2025-09-17 15:05:28','2025-09-17 15:05:32'),(19,3434,'2025-09-17 15:06:17','2025-09-17 15:06:22'),(20,3434,'2025-09-17 15:07:03','2025-09-17 15:07:08'),(21,3434,'2025-09-17 15:07:37','2025-09-17 15:10:03'),(22,3434,'2025-09-17 15:10:27','2025-09-17 15:10:32'),(23,3434,'2025-09-17 15:12:15','2025-09-17 15:12:30'),(24,3434,'2025-09-17 15:13:25','2025-09-17 15:13:29'),(25,3434,'2025-09-17 15:20:39','2025-09-18 15:44:49'),(26,3434,'2025-09-18 15:44:59','2025-09-20 14:20:35'),(27,3434,'2025-09-20 14:20:43','2025-09-20 14:24:00'),(28,34,'2025-09-20 14:24:19','2025-09-20 14:25:07'),(29,3434,'2025-09-20 14:25:19','2025-09-20 14:26:08'),(30,34,'2025-09-20 14:26:29','2025-09-20 14:27:37'),(31,123,'2025-09-20 14:27:55','2025-09-20 14:28:45'),(32,321,'2025-09-20 14:28:55','2025-09-20 14:30:10'),(33,3434,'2025-09-20 14:30:25','2025-09-20 16:07:06'),(34,123,'2025-09-20 16:09:04','2025-09-22 11:23:30'),(35,3434,'2025-09-22 11:22:27','2025-09-22 11:23:04'),(36,123,'2025-09-22 11:23:37','2025-09-22 11:42:34'),(37,3434,'2025-09-22 11:42:46','2025-09-22 11:43:42'),(38,123,'2025-09-22 11:43:54','2025-09-22 12:01:05'),(39,3434,'2025-09-23 10:02:58','2025-09-24 14:41:32'),(40,3434,'2025-09-24 14:41:39','2025-09-24 17:22:43'),(41,3434,'2025-09-25 12:04:13','2025-09-25 12:26:45'),(42,3434,'2025-09-25 12:40:21','2025-10-03 08:09:01'),(43,3434,'2025-10-03 08:09:08','2025-10-03 12:03:16'),(44,3434,'2025-10-03 12:03:22','2025-10-03 15:50:09'),(45,3434,'2025-10-03 15:50:16','2025-10-04 09:20:06'),(46,3434,'2025-10-04 09:20:14','2025-10-05 10:53:54'),(47,3434,'2025-10-05 10:54:01','2025-10-05 15:38:56'),(48,123,'2025-10-05 15:39:29','2025-10-06 10:38:56'),(49,3434,'2025-10-06 07:51:39','2025-10-06 10:38:11'),(50,123,'2025-10-06 10:39:04','2025-10-06 10:57:21'),(51,123,'2025-10-06 11:03:07','2025-10-06 11:03:13'),(52,123,'2025-10-06 11:03:25','2025-10-06 11:03:32'),(53,3434,'2025-10-07 07:30:03','2025-10-07 07:30:12'),(54,3434,'2025-10-07 07:30:19','2025-10-07 07:30:50'),(55,3434,'2025-10-08 11:29:02','2025-10-08 11:50:51'),(56,123,'2025-10-08 11:42:04','2025-10-08 11:56:37'),(57,123,'2025-10-08 11:54:59','2025-10-08 11:55:08'),(58,123,'2025-10-08 11:55:19','2025-10-08 11:55:28'),(59,3434,'2025-10-08 11:56:18','2025-10-08 11:59:47'),(60,3434,'2025-10-08 12:06:51','2025-10-08 12:31:01'),(61,3434,'2025-10-08 12:31:09','2025-10-08 12:31:19'),(62,3434,'2025-10-08 12:41:09','2025-10-08 14:06:08'),(63,3434,'2025-10-08 14:06:16','2025-10-08 14:06:23'),(64,3434,'2025-10-08 14:07:25','2025-10-08 14:07:33'),(65,3434,'2025-10-09 15:25:54','2025-10-09 15:26:01'),(66,3434,'2025-10-09 15:31:41','2025-10-09 15:31:51'),(67,3434,'2025-10-09 15:35:50','2025-10-09 15:38:49'),(68,123,'2025-10-09 15:39:01','2025-10-09 15:39:08'),(69,3434,'2025-10-09 15:39:19','2025-10-10 15:33:19'),(70,3434,'2025-10-10 15:33:27','2025-10-12 08:35:27'),(71,3434,'2025-10-12 08:35:36','2025-10-12 12:09:25'),(72,3434,'2025-10-12 12:09:37','2025-10-13 09:54:00'),(73,3434,'2025-10-13 09:54:06','2025-10-14 18:14:03'),(74,3434,'2025-10-14 18:21:47','2025-10-14 21:23:53'),(75,3434,'2025-10-15 08:18:45','2025-10-17 12:49:35'),(76,123,'2025-10-17 12:37:08','2025-10-17 12:40:15'),(77,3434,'2025-10-17 12:49:38','2025-10-17 17:30:48'),(78,3434,'2025-10-17 17:30:51','2025-10-17 17:52:25'),(79,4343,'2025-10-17 17:52:28','2025-10-17 19:21:42'),(80,34,'2025-10-17 19:21:59','2025-10-29 16:30:27'),(81,3434,'2025-10-29 16:24:32','2025-10-29 16:29:35'),(82,34,'2025-10-29 16:30:35','2025-10-29 17:34:39'),(83,4343,'2025-10-29 16:30:45','2025-10-30 15:45:22'),(84,123,'2025-10-29 16:39:48','2025-10-29 16:51:34'),(85,34,'2025-10-29 17:34:55','2025-10-30 15:45:57'),(86,4343,'2025-10-30 15:45:25','2025-10-30 18:59:47'),(87,34,'2025-10-30 15:45:59','2025-10-30 18:59:20'),(88,123,'2025-10-30 16:55:38','2025-10-30 17:32:36'),(89,123,'2025-10-30 18:24:02','2025-10-30 18:24:59'),(90,123,'2025-10-30 18:31:56','2025-10-31 15:15:13'),(91,3434,'2025-10-31 14:59:46','2025-10-31 15:12:15'),(92,3434,'2025-10-31 15:13:47','2025-10-31 15:13:56'),(93,34,'2025-10-31 15:14:48','0000-00-00 00:00:00'),(94,123,'2025-10-31 15:15:15','2025-10-31 15:34:04'),(95,4343,'2025-10-31 15:16:00','0000-00-00 00:00:00'),(96,123,'2025-10-31 15:34:06','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `reportes_entradas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `limite` int(11) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (0,'Atencion',1),(1,'Desarrollo Social',1),(2,'Despacho',1),(3,'Administración',1),(4,'Administrador Principal',1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes`
--

DROP TABLE IF EXISTS `solicitantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes` (
  `id_solicitante` int(11) NOT NULL AUTO_INCREMENT,
  `ci` varchar(100) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id_solicitante`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes`
--

LOCK TABLES `solicitantes` WRITE;
/*!40000 ALTER TABLE `solicitantes` DISABLE KEYS */;
INSERT INTO `solicitantes` VALUES (7,'3215','Jose','Gonzalez','forell.music@gmail.com','2025-10-14 18:44:43'),(8,'30420669','Calucho','Gonzalez','carlossoteldo11@gmail.com','2025-10-14 21:01:52'),(11,'2323','morfeo','perseo','perseo@gmail.com','2025-10-29 16:51:50'),(12,'13264184','peperoni','porpoern','iqwejiqwe@gmail.com','2025-10-31 15:43:54'),(13,'5059','otra','cedula','qweqweqweqwe@gmail.com','2025-10-31 15:59:13'),(15,'3030','perroni','parroni','asdasd@gmail.com','2025-10-31 18:17:26');
/*!40000 ALTER TABLE `solicitantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_comunidad`
--

DROP TABLE IF EXISTS `solicitantes_comunidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_comunidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `comunidad` varchar(255) DEFAULT NULL,
  `direc_habita` varchar(255) DEFAULT NULL,
  `estruc_base` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_comunidad_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_comunidad`
--

LOCK TABLES `solicitantes_comunidad` WRITE;
/*!40000 ALTER TABLE `solicitantes_comunidad` DISABLE KEYS */;
INSERT INTO `solicitantes_comunidad` VALUES (6,7,'PALMICHAL','Carrera centro','Dominio'),(7,8,'JOSE GREGORIO AMAYA','32-15','Pues por ahi'),(8,13,'VILLAS DE YARA',NULL,NULL),(10,15,'PIEDRA ARRIBA',NULL,NULL);
/*!40000 ALTER TABLE `solicitantes_comunidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_conocimiento`
--

DROP TABLE IF EXISTS `solicitantes_conocimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_conocimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `profesion` varchar(255) DEFAULT NULL,
  `nivel_instruc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_conocimiento_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_conocimiento`
--

LOCK TABLES `solicitantes_conocimiento` WRITE;
/*!40000 ALTER TABLE `solicitantes_conocimiento` DISABLE KEYS */;
INSERT INTO `solicitantes_conocimiento` VALUES (6,7,'Ingeniero','Primaria'),(7,8,'Ingeniero en informática','Universidad');
/*!40000 ALTER TABLE `solicitantes_conocimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_extra`
--

DROP TABLE IF EXISTS `solicitantes_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `codigo_patria` varchar(255) DEFAULT NULL,
  `serial_patria` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_extra_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_extra`
--

LOCK TABLES `solicitantes_extra` WRITE;
/*!40000 ALTER TABLE `solicitantes_extra` DISABLE KEYS */;
INSERT INTO `solicitantes_extra` VALUES (6,7,'321423','3213123'),(7,8,'32131231','232342235');
/*!40000 ALTER TABLE `solicitantes_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_info`
--

DROP TABLE IF EXISTS `solicitantes_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `lugar_nacimiento` varchar(255) DEFAULT NULL,
  `estado_civil` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_info_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_info`
--

LOCK TABLES `solicitantes_info` WRITE;
/*!40000 ALTER TABLE `solicitantes_info` DISABLE KEYS */;
INSERT INTO `solicitantes_info` VALUES (7,7,'2025-10-01','Hospital rafael rangel','Soltero/a','042323'),(8,8,'2003-04-11','San juan de los morros','Soltero/a','04245587628'),(9,12,NULL,NULL,NULL,'0424213123'),(10,13,NULL,NULL,NULL,'20302130213'),(12,15,NULL,NULL,NULL,'02423232323');
/*!40000 ALTER TABLE `solicitantes_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_ingresos`
--

DROP TABLE IF EXISTS `solicitantes_ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_ingresos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `nivel_ingreso` int(30) DEFAULT NULL,
  `pension` varchar(255) DEFAULT NULL,
  `bono` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_ingresos_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_ingresos`
--

LOCK TABLES `solicitantes_ingresos` WRITE;
/*!40000 ALTER TABLE `solicitantes_ingresos` DISABLE KEYS */;
INSERT INTO `solicitantes_ingresos` VALUES (6,7,300,'Si','Si'),(7,8,344,'No','No');
/*!40000 ALTER TABLE `solicitantes_ingresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_patologia`
--

DROP TABLE IF EXISTS `solicitantes_patologia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_patologia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `tip_patologia` varchar(255) DEFAULT NULL,
  `nom_patologia` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_patologia_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_patologia`
--

LOCK TABLES `solicitantes_patologia` WRITE;
/*!40000 ALTER TABLE `solicitantes_patologia` DISABLE KEYS */;
INSERT INTO `solicitantes_patologia` VALUES (14,7,'Hereditarias','hipertension'),(15,7,'Congénitas','miopia');
/*!40000 ALTER TABLE `solicitantes_patologia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_propiedad`
--

DROP TABLE IF EXISTS `solicitantes_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_propiedad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `propiedad` varchar(255) DEFAULT NULL,
  `propiedad_est` varchar(255) DEFAULT NULL,
  `observaciones_propiedad` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_propiedad_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_propiedad`
--

LOCK TABLES `solicitantes_propiedad` WRITE;
/*!40000 ALTER TABLE `solicitantes_propiedad` DISABLE KEYS */;
INSERT INTO `solicitantes_propiedad` VALUES (6,7,'Casa','Propia','Sin observaciones'),(7,8,'Apartamento','Prestada','Sin observaciones');
/*!40000 ALTER TABLE `solicitantes_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitantes_trabajo`
--

DROP TABLE IF EXISTS `solicitantes_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitantes_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitante` int(11) DEFAULT NULL,
  `trabajo` varchar(255) DEFAULT NULL,
  `direccion_trabajo` varchar(255) DEFAULT NULL,
  `trabaja_public` varchar(100) DEFAULT NULL,
  `nombre_insti` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitante` (`id_solicitante`),
  CONSTRAINT `solicitantes_trabajo_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitantes` (`id_solicitante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitantes_trabajo`
--

LOCK TABLES `solicitantes_trabajo` WRITE;
/*!40000 ALTER TABLE `solicitantes_trabajo` DISABLE KEYS */;
INSERT INTO `solicitantes_trabajo` VALUES (6,7,'No tiene','No','No','No'),(7,8,'No tiene','No','No','No');
/*!40000 ALTER TABLE `solicitantes_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_ayuda`
--

DROP TABLE IF EXISTS `solicitud_ayuda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_ayuda` (
  `id_doc` int(11) NOT NULL AUTO_INCREMENT,
  `id_manual` varchar(50) DEFAULT NULL,
  `ci` varchar(20) DEFAULT NULL,
  `estado` varchar(255) NOT NULL,
  `invalido` int(11) NOT NULL,
  PRIMARY KEY (`id_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_ayuda`
--

LOCK TABLES `solicitud_ayuda` WRITE;
/*!40000 ALTER TABLE `solicitud_ayuda` DISABLE KEYS */;
INSERT INTO `solicitud_ayuda` VALUES (4,'123123','3215','En espera del documento físico para ser procesado 0/3',0),(5,'32123','30420669','En espera del documento físico para ser procesado 0/3',0),(6,'23231','3215','En espera del documento físico para ser procesado 0/3',0);
/*!40000 ALTER TABLE `solicitud_ayuda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_ayuda_correo`
--

DROP TABLE IF EXISTS `solicitud_ayuda_correo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_ayuda_correo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `correo_enviado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_correo_doc` (`id_doc`),
  CONSTRAINT `fk_correo_doc` FOREIGN KEY (`id_doc`) REFERENCES `solicitud_ayuda` (`id_doc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_ayuda_correo`
--

LOCK TABLES `solicitud_ayuda_correo` WRITE;
/*!40000 ALTER TABLE `solicitud_ayuda_correo` DISABLE KEYS */;
INSERT INTO `solicitud_ayuda_correo` VALUES (4,4,0),(5,5,0),(6,6,0);
/*!40000 ALTER TABLE `solicitud_ayuda_correo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_ayuda_fecha`
--

DROP TABLE IF EXISTS `solicitud_ayuda_fecha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_ayuda_fecha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_renovacion` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fecha_doc` (`id_doc`),
  CONSTRAINT `fk_fecha_doc` FOREIGN KEY (`id_doc`) REFERENCES `solicitud_ayuda` (`id_doc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_ayuda_fecha`
--

LOCK TABLES `solicitud_ayuda_fecha` WRITE;
/*!40000 ALTER TABLE `solicitud_ayuda_fecha` DISABLE KEYS */;
INSERT INTO `solicitud_ayuda_fecha` VALUES (3,4,'2025-10-14 18:44:43','2025-10-14 18:44:43','0000-00-00 00:00:00',1),(4,5,'2025-10-14 21:01:52','2025-10-14 21:01:52','0000-00-00 00:00:00',1),(5,6,'2025-10-14 21:22:27','2025-10-14 21:22:27','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `solicitud_ayuda_fecha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_ayuda_invalido`
--

DROP TABLE IF EXISTS `solicitud_ayuda_invalido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_ayuda_invalido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `razon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invalido_doc` (`id_doc`),
  CONSTRAINT `fk_invalido_doc` FOREIGN KEY (`id_doc`) REFERENCES `solicitud_ayuda` (`id_doc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_ayuda_invalido`
--

LOCK TABLES `solicitud_ayuda_invalido` WRITE;
/*!40000 ALTER TABLE `solicitud_ayuda_invalido` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud_ayuda_invalido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_categoria`
--

DROP TABLE IF EXISTS `solicitud_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `tipo_ayuda` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoria_doc` (`id_doc`),
  CONSTRAINT `fk_categoria_doc` FOREIGN KEY (`id_doc`) REFERENCES `solicitud_ayuda` (`id_doc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_categoria`
--

LOCK TABLES `solicitud_categoria` WRITE;
/*!40000 ALTER TABLE `solicitud_categoria` DISABLE KEYS */;
INSERT INTO `solicitud_categoria` VALUES (4,4,'Otros','Otros'),(5,5,'Otros','Economica'),(6,6,'Otros','Ayudas técnicas');
/*!40000 ALTER TABLE `solicitud_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo`
--

DROP TABLE IF EXISTS `solicitud_desarrollo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo` (
  `id_des` int(11) NOT NULL AUTO_INCREMENT,
  `id_manual` varchar(50) DEFAULT NULL,
  `ci` int(50) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `invalido` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_des`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo`
--

LOCK TABLES `solicitud_desarrollo` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo` VALUES (17,'231231355',2323,'En espera del documento físico para ser procesado 0/2',0);
/*!40000 ALTER TABLE `solicitud_desarrollo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_correo`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_correo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_correo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) NOT NULL,
  `correo_enviado` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_correo_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_correo`
--

LOCK TABLES `solicitud_desarrollo_correo` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_correo` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo_correo` VALUES (7,17,0);
/*!40000 ALTER TABLE `solicitud_desarrollo_correo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_fecha`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_fecha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_fecha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_renovacion` datetime NOT NULL,
  `visto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_fecha_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_fecha`
--

LOCK TABLES `solicitud_desarrollo_fecha` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_fecha` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo_fecha` VALUES (13,17,'2025-10-29 16:51:50','2025-10-29 16:51:50','2025-10-29 16:51:50',0);
/*!40000 ALTER TABLE `solicitud_desarrollo_fecha` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_info`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `creador` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_info_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_info`
--

LOCK TABLES `solicitud_desarrollo_info` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_info` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo_info` VALUES (16,17,'aohrasi we','promotor socio');
/*!40000 ALTER TABLE `solicitud_desarrollo_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_invalido`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_invalido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_invalido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) DEFAULT NULL,
  `razon` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_invalido_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_invalido`
--

LOCK TABLES `solicitud_desarrollo_invalido` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_invalido` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud_desarrollo_invalido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_laboratorio`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_laboratorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_laboratorio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) DEFAULT NULL,
  `examen` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_laboratorio_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_laboratorio`
--

LOCK TABLES `solicitud_desarrollo_laboratorio` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_laboratorio` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo_laboratorio` VALUES (25,17,'Hematología Completa'),(26,17,'Glicemia'),(27,17,'Orina');
/*!40000 ALTER TABLE `solicitud_desarrollo_laboratorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_desarrollo_tipo`
--

DROP TABLE IF EXISTS `solicitud_desarrollo_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_desarrollo_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_des` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_des` (`id_des`),
  CONSTRAINT `solicitud_desarrollo_tipo_ibfk_1` FOREIGN KEY (`id_des`) REFERENCES `solicitud_desarrollo` (`id_des`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_desarrollo_tipo`
--

LOCK TABLES `solicitud_desarrollo_tipo` WRITE;
/*!40000 ALTER TABLE `solicitud_desarrollo_tipo` DISABLE KEYS */;
INSERT INTO `solicitud_desarrollo_tipo` VALUES (16,17,'Laboratorio');
/*!40000 ALTER TABLE `solicitud_desarrollo_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_descripcion`
--

DROP TABLE IF EXISTS `solicitud_descripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_descripcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `promotor` varchar(255) NOT NULL,
  `observaciones` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_descripcion_doc` (`id_doc`),
  CONSTRAINT `fk_descripcion_doc` FOREIGN KEY (`id_doc`) REFERENCES `solicitud_ayuda` (`id_doc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_descripcion`
--

LOCK TABLES `solicitud_descripcion` WRITE;
/*!40000 ALTER TABLE `solicitud_descripcion` DISABLE KEYS */;
INSERT INTO `solicitud_descripcion` VALUES (3,4,'Ayuda para economia','Admin Supremo','Sin observaciones'),(4,5,'Ayuda para mi','Admin Supremo','Sin observaciones'),(5,6,'Ayuda para sobrevolar','Admin Supremo','Sin observaciónn');
/*!40000 ALTER TABLE `solicitud_descripcion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `ci` int(11) NOT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `sesion` varchar(20) NOT NULL,
  PRIMARY KEY (`ci`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (34,'$2y$10$B3B3.eLTtqT.iJcPnh/m4.uSJ7M7j3tKvcLZii.D3B9BI5lgp2CwW',1,'True'),(123,'$2y$10$EUbg2UC5PG3DD2IUBrCf7OrQE.8AYST9kKAPP5MqmTU.9feSrr6Cm',2,'True'),(321,'$2y$10$b7GW4RMYoXkT7w35iXmYWuL3faGW5px.ZEi7bk4sMZZPzEwQcnjKK',3,'False'),(3434,'$2y$10$aaqOa8LN3ZdV7hviTWx7eufhAGPgvqZDWxgXmuUElh6iWfbKqTRXm',4,'False'),(4343,'$2y$10$a5bHaRGPIeE58e1chr9EBOxtFHqwKdhqBlsT.EasiWq/nooj3lfs6',0,'True');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_info`
--

DROP TABLE IF EXISTS `usuarios_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ci` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `correo` varchar(75) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci` (`ci`),
  CONSTRAINT `usuarios_info_ibfk_1` FOREIGN KEY (`ci`) REFERENCES `usuarios` (`ci`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_info`
--

LOCK TABLES `usuarios_info` WRITE;
/*!40000 ALTER TABLE `usuarios_info` DISABLE KEYS */;
INSERT INTO `usuarios_info` VALUES (2,3434,'Alex','Reyes','forell.music@gmail.com'),(5,123,'pepe','gonzalez',''),(6,34,'promotor','socio',''),(7,321,'administracion','gonzalez',''),(8,4343,'atencion','alciudadano','');
/*!40000 ALTER TABLE `usuarios_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_recuperacion`
--

DROP TABLE IF EXISTS `usuarios_recuperacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios_recuperacion` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `ci` int(30) NOT NULL,
  `codigo` int(40) NOT NULL,
  `intentos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_recuperacion`
--

LOCK TABLES `usuarios_recuperacion` WRITE;
/*!40000 ALTER TABLE `usuarios_recuperacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios_recuperacion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 18:38:07
