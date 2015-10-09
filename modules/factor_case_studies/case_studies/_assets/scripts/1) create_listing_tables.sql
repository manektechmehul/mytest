/*
SQLyog Enterprise v9.10 
MySQL - 5.5.16 : Database - dummy
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure based on  `case_study` */

-- TODO: replace the table names then run the script 

-- @main_table = 'video';
-- @category_lookup_table = 'video_category_lookup'
-- @category = 'video_category'

DROP TABLE IF EXISTS video;

CREATE TABLE video (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `body` TEXT,
  `thumb` VARCHAR(255) DEFAULT NULL,
  `page_image` VARCHAR(255) DEFAULT NULL,
  `published` TINYINT(1) DEFAULT '1',
  `order_num` INT(11) DEFAULT NULL,
  `page_name` VARCHAR(255) DEFAULT NULL,
  `featured` TINYINT(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `video_category_lookup`;

CREATE TABLE `video_category_lookup` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` INT(11) NOT NULL DEFAULT '0',
  `category_id` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_combos` (`item_id`,`category_id`)
) ENGINE=MYISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `video_category`;

CREATE TABLE `video_category` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `order_num` INT(11) NOT NULL DEFAULT '0',
  `page_name` VARCHAR(255) DEFAULT '',
  `special` TINYINT(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

