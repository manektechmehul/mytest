<?php

	include_once 'classes/main.php';
	include ('./php/classes/metatags.php');
	//-----------------------------------
	// just update these 2 items module specific - will need to update the module admin with new functions
	$module_const = 'SITE_HAS_BLOG';
	$module_table = 'blog_posts';
	//-----------------------------------
	$tags = new tags();
	$tags->module_id = db_get_single_value("SELECT id FROM module WHERE constant = '{$module_const}'");
	if($name_parts[2] == "archive") {
		$page_name = $name_parts[3];
	}else{
		$page_name = $name_parts[2];
	}
	$item_id = db_get_single_value("select id from {$module_table} where page_name = '$page_name'");
	$tags->tags_sql = "select 'this' as level, 1 as ordernum, m1.* from metatag m1 where ext_id = '{$item_id}' and module_id = '{$tags->module_id}'
            union
            select 'top', 3, m3.* from metatag m3 where ext_id = 0
            order by 2";




	// echo "<!--" . $tags->tags_sql . " -->";


	$tags->get_database_values($link, $item_id);





?>
