Add a new section with the name of the module - should it have a top level menu.

go to http://www.local-base.com/utils/install_listing_module/index.php 
Enter a module name and run the page. Then cut and paste script into database.

For some reason the db scripts don't run in the php. Need to fix 

> add the module tab in the admin menu
> remove the template file name from the page_type table if there isn't a specific new template
(perhaps we should duplicate and rename the content page for the module ?


> add <!-- CS <modulename> searchbox start -->
      <!-- CS <modulename> searchbox end -->
 to the appropriate template
 
> will need to update output.php with the filter adder loop if the search box is not appearing

> Many code changes to be made. follow through module and rename as you go.

Bug:

Will need to check a page name of an item doesn't clash with a section title, else things will get very confusing











 DROP TABLE IF EXISTS Profiles;
CREATE TABLE Profiles (
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
DROP TABLE IF EXISTS `Profiles_category_lookup`;
CREATE TABLE `Profiles_category_lookup` (
`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`item_id` INT(11) NOT NULL DEFAULT '0',
`category_id` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE KEY `unique_combos` (`item_id`,`category_id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `Profiles_category`;
CREATE TABLE `Profiles_category` (
`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`title` VARCHAR(255) NOT NULL DEFAULT '',
`order_num` INT(11) NOT NULL DEFAULT '0',
`page_name` VARCHAR(255) DEFAULT '',
`special` TINYINT(4) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
-- Attaching module and Writing config setting


SET @module_name = 'Profiles';
SET @module_const = 'SITE_HAS_PROFILES';
SET @module_prefix = 'PROFILES_';


SELECT @content_id := id, @content_type_id := content_type_id FROM content ORDER BY 1 DESC LIMIT 1;

START TRANSACTION;
-- insert into module table
INSERT INTO `module` (`name`, `path`, `description`, `constant`) VALUES (@module_name,@module_name,CONCAT('Site has ', @module_name),@module_const);
SET @module_id = LAST_INSERT_ID();

-- insert into configuration group
SELECT @new_cg_id := id + 1 FROM configuration_group ORDER BY 1 DESC LIMIT 1;
SELECT @new_cg_id;

INSERT INTO `configuration_group` (`id`,`name`,`order_num`,`module_id`,`sm_admin_only` ) VALUES (@new_cg_id, @module_name,@new_cg_id * 10, @module_id, '1');
SET @last_id_in_cg = LAST_INSERT_ID();

-- insert into site configuration (group 1) - site has this module
INSERT INTO `configuration` (`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only` ) VALUES
('1', @new_cg_id , '1', @module_name, '1', @module_name, NULL, '', '1');

-- add a new page type - defaults to its own template
SELECT @new_pt_id := id + 1 FROM page_type ORDER BY 1 DESC LIMIT 1;
SELECT @new_pt_id;

INSERT INTO `page_type`(id,`name`,`module_page`,`module_or_type`,`template`,`maint_title`,`maint_page`,`has_maintence`,`has_subsections`,`has_body`,`has_articles`,`can_edit`,`can_delete`,`config_flag`)
VALUES (@new_pt_id, @module_name,'1',@module_name,CONCAT(@module_name,'.htm'),1,NULL,'0','1','1','0','1','1',@module_const);

-- point content type to page type you just created
UPDATE content_type SET page_type = @new_pt_id WHERE id = @content_type_id;
-- add config items

-- add items to the config table for the new module

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('1',@module_const,'1', CONCAT('Site has ', @module_name),'1',CONCAT('Site has ', @module_name),NULL,'','1');

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)
VALUES(@new_cg_id,CONCAT(@module_prefix, 'CLASSFILE'),'0' ,'Dev::Name of the main module class - not sure if this is there yet','0','','','','1');

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)
VALUES(@new_cg_id,CONCAT(@module_prefix, 'CLASSNAME'),'0' ,'Dev::CaseStudiesModule init() - not sure if this is there yet','0','','','','1');

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)
VALUES(@new_cg_id,CONCAT(@module_prefix, 'ON_LEVEL'),'0','The module is on level 0 (root) or level 1(section sub page)','2','Fired in the submenu to set page sub menu to categories or module specific cats','0,1','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_SEARCH'),'1','Use Search ','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_CATEGORIES'),'1','Use Categories ','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_LATEST_SIDEBOX'),'1','Has Latest sidebox','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'LATEST_SIDEBOX_NUMBER_OF_ITEMS'),'2','Number of items in latest list sidebox','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_FEATURED_SIDEBOX'),'1','Has Featured sidebox','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'FEATURED_SIDEBOX_NUMBER_OF_ITEMS'),'2','Number of items in features list sidebox','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'PAGINATE'),'1','Use Pagination ','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'PAGINATE_ITEMS_PER_PAGE'),'5','Items Per Page ','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');



COMMIT;
-- Populate with dummy data
insert into `Profiles_category` (`title`, `order_num`, `page_name`, `special`) values('Profiles_category_1','40','Profiles_category_1','0'); insert into `Profiles_category` ( `title`, `order_num`, `page_name`, `special`) values('Profiles_category_2','10','Profiles_category_2','0'); insert into `Profiles_category` (`title`, `order_num`, `page_name`, `special`) values('Profiles_category_3','30','Profiles_category_3','0'); insert into `Profiles_category` ( `title`, `order_num`, `page_name`, `special`) values('Profiles_category_4','50','Profiles_category_4','0'); insert into `Profiles` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('Profiles item 1 title','Profiles item 1 description ','

This is the description of Profiles item 1, it is just dummy data for now.
','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','20','Profiles-item-1','1'); insert into `Profiles` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('Profiles item 2 title','Profiles item 2 description ','

This is the description of Profiles item 2, it is just dummy data for now.
','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','30','Profiles-item-2','1'); insert into `Profiles` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('Profiles item 3 title','Profiles item 3 description ','

This is the description of Profiles item 3, it is just dummy data for now.
','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','40','Profiles-item-3','1'); insert into `Profiles` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('Profiles item 4 title','Profiles item 4 description ','

This is the description of Profiles item 4, it is just dummy data for now.
','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','50','Profiles-item-4','1'); 
