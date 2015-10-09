<?php
include ('./php/classes/metatags.php');
$tags = new tags();
// get the blog id
$product_name = $name_parts[2];
$product_id = db_get_single_value("select id from shop_product where page_name = '$product_name'");
// set module id
$tags->module_id = db_get_single_value("SELECT id FROM module WHERE constant = 'SITE_HAS_SHOP'");
$tags->tags_sql = "select 'this' as level, 1 as ordernum, m1.* from metatag m1 where ext_id = '{$product_id}' and module_id = '{$tags->module_id}'
            union select 'top', 3, m3.* from metatag m3 where ext_id = 0 order by 2";
$tags->get_database_values($link, $product_id);