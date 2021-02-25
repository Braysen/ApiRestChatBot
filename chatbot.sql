/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.11-MariaDB : Database - chatbot
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`chatbot` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci */;

USE `chatbot`;

/*Table structure for table `keywords` */

DROP TABLE IF EXISTS `keywords`;

CREATE TABLE `keywords` (
  `cod_keyword` int(11) NOT NULL AUTO_INCREMENT,
  `desc_keyword` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `type_keyword` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `stat_keyword` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `status_keyword` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cod_keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `keywords` */

insert  into `keywords`(`cod_keyword`,`desc_keyword`,`type_keyword`,`stat_keyword`,`status_keyword`) values 
(1,'Plumbing','Interior','active','active'),
(2,'Electric','Interior','active','active'),
(3,'Heating/Cooling','Interior','active','active'),
(4,'Pest','Interior','active','active'),
(5,'Structural','Interior','active','active'),
(6,'Somethig Else','Interior','inactive','active'),
(7,'Appliances','Interior','inactive','active'),
(8,'keyword','Interno','active','active'),
(9,'test update 5','ncskncsk','active','active'),
(10,'keyword test 1','typetest','active','eliminado'),
(11,'keyword test 1','typetest','active','eliminado'),
(12,'keyword test 1','typetest','active','eliminado'),
(13,'test update 2','ncskncsk','active','eliminado'),
(14,'keyword update test','ncskncsk','inactive','eliminado'),
(15,'keyword post','type post','inactive','eliminado'),
(16,'keyword update test','ncskncsk','active','eliminado'),
(17,'keyword update test 100','ncskncsk','active','eliminado'),
(18,'keyword update test','ncskncsk','inactive','eliminado'),
(19,'keyword update test 102','ncskncsk','active','eliminado'),
(20,'keyword postman update','malsmlsa','active','active'),
(21,'keyword update test 104','ncskncsk','active','active'),
(22,'keyword test postman','type keyword','active','eliminado'),
(23,'keyword test postman','type keyword','active','active'),
(24,'nckncaka','knckank','active','active'),
(25,'keyword postman update','keyword type postman','active','active'),
(26,'keyword postman update','keyword type postman','active','eliminado'),
(27,'Keyword postman','knckank','active','eliminado');

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `code_message` int(11) NOT NULL AUTO_INCREMENT,
  `desc_message` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `stat_message` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `code_user` int(11) DEFAULT NULL,
  `code_keyword` int(11) DEFAULT NULL,
  PRIMARY KEY (`code_message`),
  KEY `fk1` (`code_user`),
  KEY `fk2` (`code_keyword`),
  CONSTRAINT `fk1` FOREIGN KEY (`code_user`) REFERENCES `users` (`cod_user`),
  CONSTRAINT `fk2` FOREIGN KEY (`code_keyword`) REFERENCES `keywords` (`cod_keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `messages` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `cod_user` int(11) NOT NULL AUTO_INCREMENT,
  `name_user` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `email_user` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `password_user` varchar(120) COLLATE utf8_spanish2_ci NOT NULL,
  `type` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `token` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `stat_token` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`cod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `users` */

insert  into `users`(`cod_user`,`name_user`,`email_user`,`password_user`,`type`,`status`,`token`,`stat_token`,`fecha`) values 
(1,'Braysen','btorrejon@gmail.com','hola2','Administrator','active','81688b6d29778f3f3c5c4feced0690d97518130e2c13ad4c7e6442677cdb42cccc981d3cc1d8ce18a03333a71b2fa0e00f34','active','2021-02-25 17:02:00'),
(2,'Brahimi','brahimidiaz3@gmail.com','brahimi','Normal','active','b41f0b206c151ed20cb0ab478fd1696bf7addd72483f972039e3da7615ad072323688e3c02816741799e3a2702c894bb7041','active','2021-02-24 00:38:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
