-- MySQL dump 10.16  Distrib 10.1.28-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: sistema_bustamante
-- ------------------------------------------------------
-- Server version	10.1.28-MariaDB

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
-- Table structure for table `acciones_usuarios`
--

DROP TABLE IF EXISTS `acciones_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acciones_usuarios` (
  `id_modificacion_usuario` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `accion` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_accion` datetime NOT NULL,
  PRIMARY KEY (`id_modificacion_usuario`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `acciones_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acciones_usuarios`
--

LOCK TABLES `acciones_usuarios` WRITE;
/*!40000 ALTER TABLE `acciones_usuarios` DISABLE KEYS */;
INSERT INTO `acciones_usuarios` VALUES (7,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :jose1232','2020-10-29 22:55:07'),(8,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :jose1232','2020-10-29 22:55:46'),(9,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose1232','2020-10-29 23:35:01'),(10,1,'El Usuario :jose123: (rol actual :0:), :REGISTRO: al usuario :marmes: con el rol de: 0','2020-10-29 23:35:56'),(11,8,'El Usuario :jos12: (rol actual :2:), se actualizo','2020-10-29 23:41:04'),(12,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :sotto18','2020-10-31 13:59:28'),(13,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :sotto18','2020-10-31 18:33:52'),(14,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :marmes','2020-11-03 15:51:05'),(15,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :jos12','2020-11-03 15:51:13'),(16,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :marmes','2020-11-04 10:01:59'),(17,2,'El Usuario :jose1232: (rol actual :1:), :ACTIVO: al usuario :jos12','2020-11-05 15:25:28'),(18,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :freeman20','2020-11-06 21:04:03'),(19,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :pinchevida','2020-11-08 21:02:07'),(20,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :freeman20','2020-11-09 20:42:26'),(21,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :freeman20','2020-11-09 20:43:31'),(22,5,'El Usuario :freeman20: (rol actual :1:), :REGISTRO: al usuario :pruebadir: con el rol de: Secretari@','2020-11-09 22:53:52'),(23,1,'El Usuario :jose123: (rol actual :0:), :REGISTRO: al usuario :pruebaadm: con el rol de: 0','2020-11-09 22:56:12'),(24,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :pruebaadm','2020-11-09 22:57:33'),(25,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :jose1232','2020-11-10 14:23:15'),(26,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :jose1232','2020-11-10 14:24:24'),(27,2,'El Usuario :jose1232: (rol actual :1:), :REGISTRO: al usuario :victor123: con el rol de: Secretari@','2020-11-10 14:26:31'),(28,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose1232','2020-11-10 22:54:07'),(29,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose12324','2020-11-10 22:54:12'),(30,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose123','2020-11-10 23:23:51'),(31,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose123','2020-11-10 23:24:04'),(32,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose123','2020-11-10 23:26:39'),(33,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose123','2020-11-10 23:26:45'),(34,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose1232','2020-11-10 23:27:40'),(35,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :jose123','2020-11-10 23:27:49'),(36,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose123','2020-11-10 23:28:03'),(37,1,'El Usuario :jose123: (rol actual :0:), :ACTUALIZO: al usuario :jose1232','2020-11-11 20:12:27'),(38,2,'El Usuario :jose1232: (rol actual :1:), :ACTUALIZO: al usuario :jose1232','2020-11-11 20:12:44'),(39,2,'El Usuario :jose1232: (rol actual :1:), :ACTUALIZO: al usuario :jose1232','2020-11-11 20:13:12'),(40,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :jose1232','2020-11-11 22:14:00'),(41,1,'El Usuario :jose123: (rol actual :0:), :ACTIVO: al usuario :jose1232','2020-11-12 12:45:16'),(42,1,'El Usuario :jose123: (rol actual :0:), :ELIMINO: al usuario :jose1232','2020-11-18 14:00:35');
/*!40000 ALTER TABLE `acciones_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `busqueda_x_cedula_md_busqueda`
--

DROP TABLE IF EXISTS `busqueda_x_cedula_md_busqueda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `busqueda_x_cedula_md_busqueda` (
  `id_busqueda_x_c` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `num_ced` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_busqueda_x_c`),
  KEY `id_usuario` (`id_usuario`),
  KEY `busqueda_x_cedula_md_busqueda_ibfk_2` (`num_ced`),
  CONSTRAINT `busqueda_x_cedula_md_busqueda_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `busqueda_x_cedula_md_busqueda`
--

LOCK TABLES `busqueda_x_cedula_md_busqueda` WRITE;
/*!40000 ALTER TABLE `busqueda_x_cedula_md_busqueda` DISABLE KEYS */;
INSERT INTO `busqueda_x_cedula_md_busqueda` VALUES (4,1,28422154,'2021-10-27 23:04:18'),(6,1,28422154,'2021-10-27 23:05:01'),(7,1,2341312,'2021-10-27 23:11:36'),(8,1,323123,'2021-10-28 22:33:03');
/*!40000 ALTER TABLE `busqueda_x_cedula_md_busqueda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `busqueda_x_fecha_md_busqueda`
--

DROP TABLE IF EXISTS `busqueda_x_fecha_md_busqueda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `busqueda_x_fecha_md_busqueda` (
  `id_busqueda_x_f` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(5) NOT NULL,
  `fecha_i` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_f` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_busqueda_x_f`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `busqueda_x_fecha_md_busqueda_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `busqueda_x_fecha_md_busqueda`
--

LOCK TABLES `busqueda_x_fecha_md_busqueda` WRITE;
/*!40000 ALTER TABLE `busqueda_x_fecha_md_busqueda` DISABLE KEYS */;
INSERT INTO `busqueda_x_fecha_md_busqueda` VALUES (1,1,'2021-10-04','2021-10-27','2021-10-27 23:16:59');
/*!40000 ALTER TABLE `busqueda_x_fecha_md_busqueda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crear_doc_cert_final_ejem`
--

DROP TABLE IF EXISTS `crear_doc_cert_final_ejem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crear_doc_cert_final_ejem` (
  `id_num_example_cer_final` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_num_example_cer_final`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `crear_doc_cert_final_ejem_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crear_doc_cert_final_ejem`
--

LOCK TABLES `crear_doc_cert_final_ejem` WRITE;
/*!40000 ALTER TABLE `crear_doc_cert_final_ejem` DISABLE KEYS */;
/*!40000 ALTER TABLE `crear_doc_cert_final_ejem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crear_doc_const_conducta_ejem`
--

DROP TABLE IF EXISTS `crear_doc_const_conducta_ejem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crear_doc_const_conducta_ejem` (
  `id_num_example_const_conducta` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_num_example_const_conducta`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `crear_doc_const_conducta_ejem_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crear_doc_const_conducta_ejem`
--

LOCK TABLES `crear_doc_const_conducta_ejem` WRITE;
/*!40000 ALTER TABLE `crear_doc_const_conducta_ejem` DISABLE KEYS */;
/*!40000 ALTER TABLE `crear_doc_const_conducta_ejem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crear_doc_const_prose_ejem`
--

DROP TABLE IF EXISTS `crear_doc_const_prose_ejem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crear_doc_const_prose_ejem` (
  `id_num_example_const_prose` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_num_example_const_prose`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `crear_doc_const_prose_ejem_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crear_doc_const_prose_ejem`
--

LOCK TABLES `crear_doc_const_prose_ejem` WRITE;
/*!40000 ALTER TABLE `crear_doc_const_prose_ejem` DISABLE KEYS */;
/*!40000 ALTER TABLE `crear_doc_const_prose_ejem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estudiantes` (
  `id_estudiante` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `nacionalidad` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `cedula` int(10) NOT NULL,
  `fecha_nacimiento_estudiante` date NOT NULL,
  `lugar_nacimiento` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `literal` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `periodo_escolar` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_estudiante`),
  KEY `id_usuario` (`id_usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (4,1,'JosÃ© Manuel','GonzÃ¡lez Arciniegas','venezolana',28422154,'2009-10-12','San Cristobal, Estado Tachira','A','2019-2020','2020-10-16 14:10:13',1),(5,1,'Pedro','Soto','venezolana',12345678,'2009-02-10','San Josecito, Torbes','A','2020-2021','2020-10-19 14:27:04',1),(6,1,'Pedro','Soto','venezolana',20000003,'2000-02-10','J','A','','2020-10-17 14:27:33',1),(7,1,'Pedro','Rex','venezolana',20000001,'2000-02-10','J','A','2019-2020','2020-10-18 14:28:12',1),(8,1,'Jose','Joder','venezolana',124412,'1312-02-21','El coño de la madre','F','','2020-10-19 14:29:41',1),(10,1,'Mercedes','Arciniegas','venezolana',11504716,'1974-11-14','Barinas, Barinas','A','','2020-10-19 18:33:30',1),(11,1,'Gordon','Freeman','extranjera',10000000,'1990-10-18','San C, Tachira','A','','2020-10-19 22:46:04',1),(12,1,'Gordon','Freeman','extranjera',1000000,'1990-10-18','San C, Tachira','A','','2020-10-19 22:46:33',1),(13,1,'Luis','Lopez NiÃ±o','venezolana',29434123,'2001-02-12','San J tachira','A','2019-2020','2020-10-22 21:43:09',1),(14,2,'Enyerbert','Arciniegas','venezolana',30234123,'2010-04-13','Rubio, Tachira','A','2020-2021','2020-10-26 23:51:54',1),(15,1,'Victor','CarreÃ±o','venezolana',23123232,'2010-10-10','San Cristobal, Tachira','A','2020-2021','2020-11-10 14:07:14',1);
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes_modificados`
--

DROP TABLE IF EXISTS `estudiantes_modificados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estudiantes_modificados` (
  `id_estudiante_modificado` int(6) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_estudiante_modificado`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_estudiante` (`id_estudiante`),
  CONSTRAINT `estudiantes_modificados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `estudiantes_modificados_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes_modificados`
--

LOCK TABLES `estudiantes_modificados` WRITE;
/*!40000 ALTER TABLE `estudiantes_modificados` DISABLE KEYS */;
INSERT INTO `estudiantes_modificados` VALUES (1,1,4,'2020-10-28 22:21:30'),(2,1,5,'2020-10-28 22:54:46'),(3,2,7,'2020-10-28 22:55:56'),(4,2,7,'2020-10-28 22:58:32'),(5,1,5,'2020-10-29 19:41:58');
/*!40000 ALTER TABLE `estudiantes_modificados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `export_db`
--

DROP TABLE IF EXISTS `export_db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `export_db` (
  `id_export` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_export`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `export_db_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `export_db`
--

LOCK TABLES `export_db` WRITE;
/*!40000 ALTER TABLE `export_db` DISABLE KEYS */;
INSERT INTO `export_db` VALUES (1,1,'2021-10-27 23:23:31'),(2,1,'2021-11-04 17:07:35');
/*!40000 ALTER TABLE `export_db` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inicio_sesiones`
--

DROP TABLE IF EXISTS `inicio_sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inicio_sesiones` (
  `id_sesion` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `sesion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sesion`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_usuario_2` (`id_usuario`),
  CONSTRAINT `inicio_sesiones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inicio_sesiones`
--

LOCK TABLES `inicio_sesiones` WRITE;
/*!40000 ALTER TABLE `inicio_sesiones` DISABLE KEYS */;
INSERT INTO `inicio_sesiones` VALUES (1,1,'2021-11-04 17:06:45'),(2,2,'2020-11-12 12:45:41'),(3,6,'2020-10-31 18:35:34'),(5,5,'2020-11-09 20:43:40'),(6,9,'2020-11-05 21:12:59'),(7,8,'2020-11-12 12:49:08'),(8,21,'2020-11-10 14:27:18');
/*!40000 ALTER TABLE `inicio_sesiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdfs_usuarios`
--

DROP TABLE IF EXISTS `pdfs_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdfs_usuarios` (
  `id_pdf` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `tipo_pdf` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `codigo_pdf` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pdf`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pdfs_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdfs_usuarios`
--

LOCK TABLES `pdfs_usuarios` WRITE;
/*!40000 ALTER TABLE `pdfs_usuarios` DISABLE KEYS */;
INSERT INTO `pdfs_usuarios` VALUES (1,1,'Listado de Usuarios PDF','500773b7bb686632e7e355a707501542','2020-11-10 14:22:33'),(2,9,'Listado de Usuarios PDF','2ce3dd8528a58d68d0ee23622b5c6ffe','2020-11-05 21:14:08'),(3,1,'Acciones del Usuario PDF','bc29c32c5d8cc47f212945d7d4827ef9','2021-10-26 10:59:40'),(4,5,'Acciones del Usuario PDF','2206dd77811c9041150d21b7db98d387','2020-11-09 22:28:15'),(5,1,'Busqueda por Cedula PDF','e4f3e32e76a222178ca4fa5f0342e2fa','2021-10-30 22:50:14'),(6,1,'Busqueda por Fecha PDF','10b7b1b6d1a46f1617dbd9ac3e97333b','2021-10-31 10:07:47');
/*!40000 ALTER TABLE `pdfs_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_documentos`
--

DROP TABLE IF EXISTS `plantilla_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_documentos` (
  `id_documentos` int(3) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(3) NOT NULL,
  `nombre_director` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `apellido_director` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nacionalidad` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cedula_director` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `genero` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_documentos`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `plantilla_documentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_documentos`
--

LOCK TABLES `plantilla_documentos` WRITE;
/*!40000 ALTER TABLE `plantilla_documentos` DISABLE KEYS */;
INSERT INTO `plantilla_documentos` VALUES (1,1,'JosÃ© Manuel','GonzÃ¡lez Arciniegas','venezolana','22.543.545','hombre');
/*!40000 ALTER TABLE `plantilla_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_documentos`
--

DROP TABLE IF EXISTS `registro_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registro_documentos` (
  `id_registro` int(3) NOT NULL AUTO_INCREMENT,
  `codigo_documento` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de PDF',
  `id_usuario` int(3) NOT NULL,
  `id_estudiantes` int(5) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_registro`),
  KEY `id_usuario` (`id_usuario`,`id_estudiantes`),
  KEY `registro_certificaciones_ibfk_2` (`id_estudiantes`),
  CONSTRAINT `registro_documentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `registro_documentos_ibfk_2` FOREIGN KEY (`id_estudiantes`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_documentos`
--

LOCK TABLES `registro_documentos` WRITE;
/*!40000 ALTER TABLE `registro_documentos` DISABLE KEYS */;
INSERT INTO `registro_documentos` VALUES (7,'aebb96e7dd866fd52f3b3299b65ed756','CertificaciÃ³n Final',1,4,'2021-10-26 10:27:36'),(8,'a548694dea382b775cb6f6e7b8c7998f','CertificaciÃ³n Final',1,8,'2020-10-29 19:13:24'),(23,'ba82acb09660b539b3c45f29b449b452','Constancia de Buena Conducta',1,4,'2020-10-05 15:21:58'),(26,'576a435e02694c6977cf9aee40c178d7','Constancia de ProsecuciÃ³n',1,4,'2020-10-31 18:31:39'),(27,'31f6b6bb8093fd9c924c1e37de0979ed','CertificaciÃ³n Final',9,14,'2020-11-05 23:02:09'),(28,'3edbaee19b93bfb1b3044713ed811aaf','Constancia de ProsecuciÃ³n',1,11,'2020-11-09 20:37:32'),(29,'606b3d57e98133795a3cd1d3456ec3ad','Constancia de Buena Conducta',1,12,'2020-11-09 20:37:44'),(30,'945869d0fc85c714d220be85fd8638c9','CertificaciÃ³n Final',1,12,'2020-11-09 20:37:53'),(31,'eead9cf587a3a27ce924ae2e7b42da27','Constancia de Buena Conducta',1,11,'2020-11-09 20:38:10'),(32,'baba870e6c617dc58648cec6d79e89ff','Constancia de ProsecuciÃ³n',1,13,'2020-11-09 20:38:20'),(33,'98fcc0557f81a62376873c78b5be28d0','CertificaciÃ³n Final',1,13,'2020-11-09 20:38:32'),(34,'473af099a5926f534ef6580747210ef1','Constancia de Buena Conducta',1,4,'2020-11-11 22:45:18'),(35,'7a4cf543a04a170781d120f08800b31a','Constancia de ProsecuciÃ³n',1,4,'2020-11-11 23:06:53'),(36,'9cef7c230f15c3eba00b231fd7baf94e','Constancia de ProsecuciÃ³n',1,5,'2020-11-11 23:36:13'),(37,'c704d2e0a59169eaf3cf18ebfc5b0451','Constancia de Buena Conducta',1,5,'2020-11-11 23:36:25'),(38,'e3450ab666e875c82248ab6fb815b268','CertificaciÃ³n Final',1,5,'2020-11-11 23:36:36');
/*!40000 ALTER TABLE `registro_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_usuario`
--

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
INSERT INTO `tipo_usuario` VALUES (0,'admin'),(1,'director'),(2,'secretario');
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(3) NOT NULL AUTO_INCREMENT,
  `nick_usuario` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `contra_usuario` varchar(16) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `apellido_usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_usuario` tinyint(3) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tema` int(2) NOT NULL,
  `status_usuario` tinyint(3) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `usuarios_ibfk_1` (`tipo_usuario`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'jose123','123','JosÃ©','Gonzalez A',0,'2020-07-12 23:50:00',1,1),(2,'jose1232','123','JosÃ©','GonzÃ¡lez',1,'2020-08-27 01:37:27',0,0),(5,'freeman20','12','Jose','Gonzalez',1,'2020-10-11 19:05:34',0,1),(6,'meche','12','Mercedes','Arciniegas',1,'2020-10-11 19:09:48',1,1),(7,'freeman16','12','Nice','Great',0,'2020-10-19 13:35:08',0,1),(8,'jos12','1','Marcelo','Rodriguez',2,'2020-10-19 13:53:01',0,1),(9,'luis123','12','Jose Luis','Lopez',1,'2020-10-19 13:55:23',1,1),(17,'yeison2020','12','Yeison','Ramirez',2,'2020-10-29 21:56:35',0,1),(21,'victor123','12','Victor','CarreÃ±o',2,'2020-11-10 14:26:31',1,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-04 17:08:36
