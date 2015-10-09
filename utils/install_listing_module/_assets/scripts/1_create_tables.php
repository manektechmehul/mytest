<?
$create_tables = " DROP TABLE IF EXISTS " . $module_name . ";
CREATE TABLE " . $module_name . " (
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
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `" . $module_name . "_category_lookup`;
CREATE TABLE `" . $module_name . "_category_lookup` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` INT(11) NOT NULL DEFAULT '0',
  `category_id` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_combos` (`item_id`,`category_id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `" . $module_name . "_category`;
CREATE TABLE `" . $module_name . "_category` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `order_num` INT(11) NOT NULL DEFAULT '0',
  `page_name` VARCHAR(255) DEFAULT '',
  `special` TINYINT(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
?>