SET @module_name = 'lottery';
 
# this is the one we are coping from
SET @source_table_prefix = 'xbase';

START TRANSACTION;

SET @b = CONCAT('DROP TABLE IF EXISTS `',  @module_name , '` ; ');
SET @st = @b;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @c = CONCAT('CREATE TABLE `',  @module_name , '` LIKE `', @source_table_prefix , '`; ');
SET @st = @c;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @d = CONCAT('insert into `',  @module_name , '`  select * from `',  @source_table_prefix ,'`; ');
SET @st = @d;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
	

-- category table

SET @b = CONCAT('DROP TABLE IF EXISTS `',  @module_name , '_category` ; ');
SET @st = @b;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @c = CONCAT('CREATE TABLE `',  @module_name , '_category` LIKE `', @source_table_prefix , '_category`; ');
SET @st = @c;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @d = CONCAT('insert into `',  @module_name , '_category`  select * from `',  @source_table_prefix ,'_category`; ');
SET @st = @d;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

	
-- category lookup table

SET @b = CONCAT('DROP TABLE IF EXISTS `',  @module_name , '_category_lookup` ; ');
SET @st = @b;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @c = CONCAT('CREATE TABLE `',  @module_name , '_category_lookup` LIKE `', @source_table_prefix , '_category_lookup`; ');
SET @st = @c;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @d = CONCAT('insert into `',  @module_name , '_category_lookup`  select * from `',  @source_table_prefix ,'_category_lookup`; ');
SET @st = @d;
PREPARE stmt FROM @st;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


COMMIT;