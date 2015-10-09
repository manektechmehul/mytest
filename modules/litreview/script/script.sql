
/***
for some special reason the configuration_group doesn't have an auto incr key !!

****/

INSERT INTO module (`name`,`path`, `description`,`constant`) VALUES ('lit_review','lit_review','literature review','SITE_HAS_LIT_REVIEW'); 
`configuration`

INSERT INTO configuration_group (id,`name`, order_num, sm_admin_only) VALUES (13,'lit_review',130,1);

SET @groupid = LAST_INSERT_ID(); 

INSERT INTO configuration 
(`group`, `name`,`value`,`title`, `type`, description, sm_admin_only)
VALUES  
(13,'LIT_REVIEW_SOMETHING','','',0,'', 1),
(1,'SITE_HAS_LIT_REVIEW',1,'Switch on Lit Review',1,'turn on lit review module', 1);