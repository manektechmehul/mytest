/*********************************************************
 ATTACH A NEW MODULE TO THE CMS
 Author: GL
 Date: 18/02/2013
 Version: 1.0
 Notes: First Cut


 NB: The system will add a link inthe page_type table to module.htm in the template field.
 If that templates doesn't exsist it will white screen

**********************************************************
 SET VARS: */

SET @module_name = 'video';
SET @module_const = 'SITE_HAS_VIDEO';

/* Set content block to attach to
-- find a content_id of the section you just created (75)
-- set @content_id = 75
*/
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
     ('1', @module_const , '1',  @module_name, '1',  @module_name, NULL, '', '1');

    -- add a new page type - defaults to its own template
    SELECT @new_pt_id := id + 1 FROM page_type ORDER BY 1 DESC LIMIT 1;
    SELECT @new_pt_id;

    INSERT INTO `page_type`(id,`name`,`module_page`,`module_or_type`,`template`,`maint_title`,`maint_page`,`has_maintence`,`has_subsections`,`has_body`,`has_articles`,`can_edit`,`can_delete`,`config_flag`)
    VALUES (@new_pt_id, @module_name,'1',@module_name,CONCAT(@module_name,'.htm'),1,NULL,'0','1','1','0','0','1',@module_const);

    -- point content type to page type you just created
    UPDATE content_type SET page_type = @new_pt_id WHERE id = @content_type_id;
    -- add config items

COMMIT;