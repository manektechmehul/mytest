

/* Create table in target */
CREATE TABLE `onpagelink`(
	`id` int(11) NOT NULL  auto_increment ,
	`link_type` tinyint(4) NULL  COMMENT 'choose the type of link cs,download, link etc' ,
	`module_id` int(5) NULL  COMMENT 'the casestudy id or other system module' ,
	`title` varchar(255) COLLATE latin1_swedish_ci NULL  ,
	`summary` varchar(1000) COLLATE latin1_swedish_ci NULL  ,
	`file` varchar(255) COLLATE latin1_swedish_ci NULL  COMMENT 'file url' ,
	`thumb` varchar(255) COLLATE latin1_swedish_ci NULL  COMMENT 'path to thumbnail image to display' ,
	`link` varchar(255) COLLATE latin1_swedish_ci NULL  ,
	`external_link` tinyint(4) NULL  ,
	`video_type` tinyint(4) NULL  COMMENT 'youtube/vimeo/other' ,
	`video_id` varchar(255) COLLATE latin1_swedish_ci NULL  ,
	`freetext` varchar(1000) COLLATE latin1_swedish_ci NULL  COMMENT 'use for static link' ,
	`content_id` int(11) NULL  ,
	`published` tinyint(4) NULL  DEFAULT '1' ,
	`order_num` int(11) NOT NULL  DEFAULT '0' ,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET='latin1';


/* Create table in target */
CREATE TABLE `onpagelink_type`(
	`id` int(5) NOT NULL  auto_increment ,
	`title` varchar(250) COLLATE latin1_swedish_ci NULL  ,
	`active` tinyint(4) NULL  ,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET='latin1';


/* Create table in target */
CREATE TABLE `onpagelink_video_type`(
	`id` int(5) NOT NULL  auto_increment ,
	`title` varchar(255) COLLATE latin1_swedish_ci NULL  ,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET='latin1';


-- insert link types
insert into `onpagelink_type` (`id`, `title`, `active`) values('1','Case Study','1');
insert into `onpagelink_type` (`id`, `title`, `active`) values('2','Download','1');
insert into `onpagelink_type` (`id`, `title`, `active`) values('3','Link','1');
insert into `onpagelink_type` (`id`, `title`, `active`) values('4','Video','1');
insert into `onpagelink_type` (`id`, `title`, `active`) values('5','Static','1');
-- inert video types
insert into `onpagelink_video_type` (`id`, `title`) values('1','You Tube');
insert into `onpagelink_video_type` (`id`, `title`) values('2','Vimeo');