
/***
for some special reason the configuration_group doesn't have an auto incr key !!

****/

CREATE TABLE `twitter_block` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '',
  `banner_image` varchar(500) DEFAULT '',
  `tags` varchar(500) DEFAULT '',
  `description` varchar(2000) DEFAULT NULL,
  `no_of_items_to_display` smallint(6) DEFAULT '5',
  `featured` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `twitter_block` */

insert  into `twitter_block`(`id`,`name`,`banner_image`,`tags`,`description`,`no_of_items_to_display`,`featured`) values (1,'Kilimanjaro','../../UserFiles/Image///twitter_blocks/huruPeakKilimanjaro5895metres.JPG','@helenstrust #Kilimanjaro','Climb Kilimanjaro with Action Challenge. We organise group trips, charity climbs, and corporate challenges up Kilimanjaro. ',15,0),(2,'Chirstmas','twitter_blocks/harity_Parachute_Jump.jpg','@helenstrust #texttogive','King of twitter, Stephen Fry',5,1);
