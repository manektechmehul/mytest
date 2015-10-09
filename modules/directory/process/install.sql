# run this after adding a new top level section called factor

SET @module_name = 'Directory';
SET @module_const = 'SITE_HAS_DIRECTORY';
SET @module_prefix = 'DIRECTORY_';
SET @module_lowercase_name = 'directory';


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
-- INSERT INTO `configuration` (`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only` ) VALUES
-- ('1', @new_cg_id , '1', @module_name, '1', @module_name, NULL, '', '1');

-- add a new page type - defaults to its own template
SELECT @new_pt_id := id + 1 FROM page_type ORDER BY 1 DESC LIMIT 1;
SELECT @new_pt_id;

INSERT INTO `page_type`(id,`name`,`module_page`,`module_or_type`,`template`,`maint_title`,`maint_page`,`has_maintence`,`has_subsections`,`has_body`,`has_articles`,`can_edit`,`can_delete`,`config_flag`)
VALUES (@new_pt_id, @module_name,'1',@module_name,CONCAT(@module_lowercase_name,'.htm'),1,NULL,'0','1','1','0','1','1',@module_const);

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
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_INLINE_GALLERIES'),'1','Inline Galleries ','1','','','','1');


INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_CATEGORIES'),'1','Use Categories ','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_LATEST_SIDEBOX'),'1','Has Latest sidebox','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'LATEST_SIDEBOX_NUMBER_OF_ITEMS'),'2','Number of items in latest list sidebox','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'HAS_FEATURED_SIDEBOX'),'1','Has Featured sidebox','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'FEATURED_SIDEBOX_NUMBER_OF_ITEMS'),'2','Number of items in features list sidebox','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'PAGINATE'),'1','Use Pagination ','1','','','','1');
INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`)VALUES(@new_cg_id,CONCAT(@module_prefix, 'PAGINATE_ITEMS_PER_PAGE'),'5','Items Per Page ','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');



COMMIT;