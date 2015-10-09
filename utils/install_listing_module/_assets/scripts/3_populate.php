<?php
// insert into `" . $module_name . "_category` (`id`, `title`, `order_num`, `page_name`, `special`) values('1','featured','0','featured','1');
$populate_sql = "
insert into `" . $module_name . "_category` (`title`, `order_num`, `page_name`, `special`) values('" . $module_name . "_category_1','40','" . $module_name . "_category_1','0');
insert into `" . $module_name . "_category` ( `title`, `order_num`, `page_name`, `special`) values('" . $module_name . "_category_2','10','" . $module_name . "_category_2','0');
insert into `" . $module_name . "_category` (`title`, `order_num`, `page_name`, `special`) values('" . $module_name . "_category_3','30','" . $module_name . "_category_3','0');
insert into `" . $module_name . "_category` ( `title`, `order_num`, `page_name`, `special`) values('" . $module_name . "_category_4','50','" . $module_name . "_category_4','0');
";

$populate_sql .= "
insert into `" . $module_name . "` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('" . $module_name . " item 1 title','" . $module_name . " item 1 description ','<p>This is the description of " . $module_name . " item 1,  it is just dummy data for now.</p>\r\n','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','20','" . $module_name . "-item-1','1');
insert into `" . $module_name . "` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('" . $module_name . " item 2 title','" . $module_name . " item 2 description ','<p>This is the description of " . $module_name . " item 2,  it is just dummy data for now.</p>\r\n','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','30','" . $module_name . "-item-2','1');
insert into `" . $module_name . "` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('" . $module_name . " item 3 title','" . $module_name . " item 3 description ','<p>This is the description of " . $module_name . " item 3,  it is just dummy data for now.</p>\r\n','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','40','" . $module_name . "-item-3','1');
insert into `" . $module_name . "` ( `title`, `description`, `body`, `thumb`, `page_image`, `published`, `order_num`, `page_name`, `featured`) values('" . $module_name . " item 4 title','" . $module_name . " item 4 description ','<p>This is the description of " . $module_name . " item 4,  it is just dummy data for now.</p>\r\n','Test_Images/gallery/nature3.jpg','Test_Images/gallery/nature10.jpg','1','50','" . $module_name . "-item-4','1');
            ";



?>