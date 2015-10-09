
INSERT INTO module (`name`,`path`, `description`,`constant`) VALUES ('twitter','twitter','Latest tweet displayed om page (php version, with cache)','SITE_HAS_TWITTER_FEED'); 
`configuration`

INSERT INTO configuration_group (id,`name`, order_num, sm_admin_only) VALUES (12,'twitter',120,1);

INSERT INTO configuration 
(`group`, `name`,`value`,`title`, `type`, description, sm_admin_only)
VALUES  
(12,'TWITTER_FREINDLY_NAME','Creative Stream','Friendly Name',0,'Follow <Freindly name>, for link text', 1),
(12,'TWITTER_USERNAME','antclifford','Twitter Username',0,'Username to follow', 1),
(12,'TWITTER_FEED_ON',1,'Twitter feed On',1,'Display Last Tweet ', 1),
(1,'SITE_HAS_TWITTER_FEED',0,'Site has Twitter Feed',1,'turn on twitter module', 1);


INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) VALUES ( '12','TWIITER_CACHE_TIME','600','Twitter User Timeline Cache Time ','0','Twitter User Timeline Cache time ',NULL,'','1'); 

INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) 
VALUES ( '12','TWIITER_CONSUMER_KEY','zCVaDqqvCObRQUI8GIFmfA','Twitter Oauth Consumer Key','0','Twitter Oauth Consumer Key',NULL,'','1'); 

INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) 
VALUES ( '12','TWIITER_CONSUMER_SECRET','bJSUgca89cswWQZsC9jfCXycSbhedLBzb1vhg6V3ojk','Twitter Oauth Consumer Secret','0','Twitter Oauth Consumer Secret',NULL,'','1'); 

INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) 
VALUES ( '12','TWIITER_OAUTH_TOKEN','68679793-YjJAfsuN10YOfygUfHSmjb11hzZrElhAgLQLfqxc','Twitter Oauth Token','0','Twitter Oauth Token',NULL,'','1'); 

INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) 
VALUES ( '12','TWIITER_OAUTH_TOKEN_SECRET','eKcLN3gNc7NoyHKNrL0YfmI8c60xqhiroyURNgCE0','Twitter Oauth Token Secret','0','Twitter Oauth Token Secret',NULL,'','1'); 

INSERT INTO `configuration`(`group`,`name`,`value`,`title`,`type`,`description`,`options`,`module`,`sm_admin_only`) 
VALUES ( '12','TWIITER_OAUTH_CALLBACK','','Twitter Oauth Callback','0','Twitter Oauth Callback',NULL,'','1'); 

insert into `configuration` (`group`, `name`, `value`, `title`, `type`, `description`, `options`, `module`, `sm_admin_only`) values('12','TWITTER_ITEMS','1','number of tweets to show in simple tweet module','2','','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','','1');