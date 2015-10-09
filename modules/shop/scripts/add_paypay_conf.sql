

-- this assumes your shop module is 9 and the id's in the database are where the current paypal entries are.

SELECT * FROM configuration WHERE NAME LIKE '%paypal%';

DELETE FROM configuration  WHERE NAME LIKE '%paypal%';

INSERT INTO `configuration` ( `id`,`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('949','9','PAYPAL_IPN_USE_SANDBOX','1','Paypal IPN Sandbox','1','Paypal URL','','shop','1');
INSERT INTO `configuration` ( `id`,`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('950','9','PAYPAL_IPN_DEBUG','1','Paypal IPN Debug','1','PAYPAL IPN DEBUG','','shop','1');
INSERT INTO `configuration` ( `id`,`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('947','9','PAYPAL_ENV','sandbox','</td></tr><tr><td col span=2><br><hr></td></tr><tr><td>Paypal Mode','2','Paypal Mode','SANDBOX,LIVE','shop','1');
INSERT INTO `configuration` ( `id`,`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) VALUES('948','9','PAYPAL_EMAIL_ADDRESS','glenlockhart-facilitator@gmail.com','Paypal email','0','Paypal email','','shop','1');
