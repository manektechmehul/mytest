<?php

 
include_once 'classes/blogs.php';
include ('./php/classes/metatags.php');

$tags = new tags();
// get the blog id
$blog_page_name = $name_parts[1];
$blog_id = db_get_single_value("select id from blogs where page_name = '$blog_page_name'");

if($name_parts[2] == 'archive'){
	$post = get_single_post($blog_id, $name_parts[3]);
}else{
	$post = get_single_post($blog_id, $name_parts[2]);
}

 
$post_id = $post['id'];
// set module id
$tags->module_id = db_get_single_value("SELECT id FROM module WHERE constant = 'SITE_HAS_BLOG'");



$tags->tags_sql = "select 'this' as level, 1 as ordernum, m1.* from metatag m1 where ext_id = '{$post_id}' and module_id = '8'
            union 
            select 'top', 3, m3.* from metatag m3 where ext_id = 0 
            order by 2";
		 
			
$tags->get_database_values($link, $blog_id);

?>
