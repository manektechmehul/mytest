-- assuming id 20 is not taken in config tables
-- GL 08/11/2012
-- TODO: lets switch on auto incr on the tables - while we run the script ?

INSERT INTO module (`name`,`path`, `description`,`constant`) VALUES ('maps','maps','map the members to the map','SITE_HAS_MAPS'); 

INSERT INTO configuration_group (id,`name`, order_num, sm_admin_only) VALUES (20,'map',200,1);

-- SET @groupid = LAST_INSERT_ID(); 

INSERT INTO configuration 
(`group`, `name`,`value`,`title`, `type`, description, sm_admin_only)
VALUES  
(1,'SITE_HAS_MAPS',1,'Switch on maps',1,'turn on simple maps module', 1);

/*
INSERT INTO `configuration` ( `group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) 
VALUES
('20','PROTX_EMAIL_MESSAGE','email message','email message','0','email message',NULL,'simple_payment','1'),
('20','BEFORE_CHECKOUT_MESSAGE','Please enter your details below','Message before checkout','0','Message before checkout',NULL,'simple_payment','1'),
('20','EMAIL_FROM_NAME','Pavillion Properties','From name in email','0','From name in the email',NULL,'simple_payment','1'),
('20','PAYMENT_LOG_LEVEL','1','Payment Log Level','0','Payment Logging level',NULL,'simple_payment','1'),
('20','PAYMENT_PROVIDER','PROTX','Use Paypal or protx','2','use paypal or protx','PAYPAL,PROTX','simple_payment','1'),
('20','PROTX_ENCRYPTION_PASSWORD','m7CimIcBz4Aj0iua','Encryption Password','0','Set this value to the XOR Encryption password assigned to you by Protx',NULL,'simple_payment','1'),
('20','PROTX_SHOP_DESCRIPTION','Pavillion Properties','Protx Shop Description','0','Description (or stap line) of the Shop',NULL,'simple_payment','1'),
('20','PROTX_STATUS','SIMULATOR','Protx Account Status','2','Protx Account Status - Set to SIMULATOR for the VSP Simulator expert system, TEST for the Test Server and LIVE in the live environment','SIMULATOR,TEST,LIVE','simple_payment','1'),
('20','PROTX_TRANSACTION_TYPE','PAYMENT','Protx Transaction type','2','Usually PAYMENT. This can be DEFERRED or AUTHENTICATE if your Protx account supports those payment types ','PAYMENT,DEFERRED,AUTHENTICATE','simple_payment','1'),
('20','PROTX_VENDOR_EMAIL','glenlockhart@gmail.com','Vendor Email Address','0','Set this to the mail address which will receive order confirmations and failures',NULL,'simple_payment','1'),
('20','PROTX_VENDOR_NAME','creativestream','Protx Vendor Name','0','Vendor name required by Protx. \"Set this value to the VSPVendorName. Set this value to the VSPVendorName assigned to you by protx or chosen when you applied.\"',NULL,'simple_payment','1');*/

-- might need to choose a different template file
INSERT INTO `page_type` (`id`, `name`, `module_page`, `module_or_type`, `template`, `maint_title`, `maint_page`, `has_maintence`, `has_subsections`, `has_body`, `has_articles`, `can_edit`, `can_delete`, `config_flag`) VALUES
('20','maps','1','maps','maps.htm',NULL,NULL,'0','0','1','0','1','0','SITE_HAS_MAPS');


-- edit content_type table so the page_type is 20


/* clear out module
DELETE FROM configuration WHERE `group` = 20  OR `name` = 'SITE_HAS_MAPS';
DELETE FROM module WHERE `name` =  'simple_payment'; 
DELETE FROM  configuration_group WHERE `name` =  'simple_payment'; 
*/