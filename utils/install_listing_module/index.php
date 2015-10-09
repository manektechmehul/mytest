<?php
ini_set('display_errors', '1');
include '../../php/databaseconnection.php';
include '../../php/functions_inc.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// var_dump($_GET);
if(isset($_GET)){
    if($_GET['module_name'] != ''){
    
    echo '<h2> Create a new listing module called ' . $_GET['module_name'] . ' </h2>';
    
    
    $module_name = ucfirst($_GET['module_name']);
    $module_const = 'SITE_HAS_' . mb_strtoupper($module_name);
    $module_prefix = mb_strtoupper($module_name) . '_';
    
    /*
    include "_assets/scripts/1_create_tables.php";  
 //   $result = mysql_query($create_tables);
    var_dump($result);
    echo '<h2> Create Tables </h2>';
    echo nl2br($create_tables);
    */
    
    /* --- */
    
        
    echo '<h2>-- Attaching module and Writing config setting </h2>';
    include "_assets/scripts/2_attach_and_config.php";  
    echo nl2br($attach_sql);
 //   $result = mysql_query($attach_sql);
    
    
    /*
    
    echo '<h2>-- Populate with dummy data </h2>';
    include "_assets/scripts/3_populate.php"; 
    echo $populate_sql;
 //   $result = mysql_query($populate_sql);
  */
             
     //Utils::copyDirectory('C:/xampp/htdocs/codebase/modules/xbase', 'C:/xampp/htdocs/codebase/modules/' . $module_name);
     Utils::copyDirectory('C:/wamp/www/modules/xbase', 'C:/wamp/www/modules/' . $module_name);
         
    }
}
?>
<h1> Installing a new listings module</h1>
<form>
    <label>Module Name
    </label>
    <input type="text" name="module_name" id="module_name" ></input>
    <input type="submit" value="Create a new module" />
    
</form>
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


















class Utils
{
  public static function deleteDirectory($dir)
  {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
      if ($item == '.' || $item == '..') continue;
      if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return rmdir($dir);
  }
 
  public static function copyDirectory($sourceDir, $targetDir)
  {
    if (!file_exists($sourceDir)) return false;
    if (!is_dir($sourceDir)) return copy($sourceDir, $targetDir);
    if (!mkdir($targetDir)) return false;
    foreach (scandir($sourceDir) as $item) {
      if ($item == '.' || $item == '..') continue;
      if (!self::copyDirectory($sourceDir.DIRECTORY_SEPARATOR.$item, $targetDir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return true;
  }
}

?>