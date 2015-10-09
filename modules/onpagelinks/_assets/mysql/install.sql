/* Install module */

INSERT INTO `module` (`name`, `path`, `description`, `constant`) VALUES('onpagelinks','onpagelinks','This module lists many types of items any where in the cms','SITE_HAS_ONPAGELINKS');

SELECT @new_cg_id := id + 1 FROM configuration_group ORDER BY 1 DESC LIMIT 1;
SELECT @new_cg_id;

INSERT INTO `configuration_group` (id, `name`, `order_num`, `module`, `sm_admin_only`) VALUES(@new_cg_id,'Onpagelinks','2020','','1');
SET @last_id_in_cg = LAST_INSERT_ID();

INSERT INTO `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('1','SITE_HAS_ONPAGELINKS','1','On Page Links','1','This module lists many types of items any where in the cms',NULL,'','1');