<?php

$base_path = $_SERVER['DOCUMENT_ROOT'];
include_once $base_path . '/php/nice_urls.php';

function categories_sidebox() {
    $base_path = '';
    $cur_root_cat = false;
    $topcat = false;
    $shop_name = 'shop';
    $page_name = strip_tags($_SERVER['REQUEST_URI']);
    $name_parts = explode('/', $page_name);
    // remove any get strings on url
    $t = explode('?', $name_parts[3]);
    $this_page_name = $t[0];
    $cats_list = array();
    $close_group = false;
    $doclose = false;
    // get details on the current page 
    // get parent info - incase its a sub cat
    if ($name_parts[2] == 'category') {
        $sql = "SELECT  sc1.* , (
    	SELECT sc2.page_name  AS thing
    	FROM shop_category sc2
    	WHERE sc2.id = sc1.parent_id
    	) AS cat_parent_pagename
    	FROM shop_category sc1 WHERE  online = 1  AND page_name = '" . $this_page_name . "'  ORDER BY parent_id, order_num";
    }
    if ($name_parts[2] == 'product') {
        $sql = "SELECT  sc1.* , (
	    SELECT sc2.page_name  AS thing
	    FROM shop_category sc2
	    WHERE sc2.id = sc1.parent_id
	    ) AS cat_parent_pagename
	    FROM shop_category sc1
	    INNER JOIN shop_product sp ON sp.`primary_category_id` = sc1.`id`
	    WHERE  sc1.online = 1  AND sp.page_name ='" . $this_page_name . "'  ORDER BY parent_id, order_num";
		 
    }
    // clean part 2
    $t = explode('?', $name_parts[2]);
    $another_page_name = $t[0];
    if ($name_parts[3] == '' || $another_page_name == 'failed' || $another_page_name == 'success') {
        // do something 
        $page_type = 'shop';
        $level = 1;
        $cat_parent_pagename = '';
        $isRoot = 1;
        $page_name = 'shop';
        $category_items = 0;
    }
    if ($page_type != 'shop') {
        $cat_result = mysql_query($sql);
        while ($row = mysql_fetch_array($cat_result)) {
            if (empty($row['cat_parent_pagename'])) {
                $cat_parent_pagename = $row['page_name'];
            } else {
                $cat_parent_pagename = $row['cat_parent_pagename'];
            }
            $level = $row['level'];
            $isRoot = $row['isRoot'];
            if ($isRoot == 1) {
                $page_type = 'category';
            } else {
                $page_type = 'subcategory';
            }
            $page_name = $row['page_name'];
            $parent_id = $row['parent_id'];
            //var_dump($row);
            //echo '<h2>' . $row['parent_id'] . '</h2>';
            // now lets see if it has any subcategories    	
            $category_items = db_get_single_value('SELECT  COUNT(parent_id) AS items_count
    	FROM shop_category cs
    	WHERE parent_id = ' . $parent_id . '
    	GROUP BY cs.parent_id ORDER BY order_num', 'items_count');
            // We know that the current page we are on has the following attributes
            /*
              $level - 1 or 2
              $cat_parent_pagename
              $page_type - category/subcategory
              $isRoot = 0/1
              $page_name = drapes or capes etc
              $category_items = no of items in the category (parentis 1, more than 1 has sub-cats
             */
        }
    }
    $sql = "SELECT sc1.name, sc1.page_name, sc1.id, sc1.isRoot, sc2.page_name AS cat_parent_pagename,  sc1.level
FROM shop_category sc1
LEFT JOIN shop_category sc2 ON sc2.id = sc1.parent_id
WHERE sc1.online = 1  AND sc1.isRoot = 1 AND COALESCE(sc2.online,1) = 1 
ORDER BY 
CASE WHEN sc1.isroot = 1 
THEN sc1.order_num 
ELSE sc2.order_num 
END, sc1.isRoot DESC, 
sc1.order_num";
    $result = mysql_query($sql);
    $currentRootOpen = false;
    $this_cat_count = 0;
    while ($row = mysql_fetch_array($result)) {
        // datafix //
        $loop_cat_parent_pagename = $row['cat_parent_pagename'];
        if ($loop_cat_parent_pagename == '') {
            $loop_cat_parent_pagename = $row['page_name'];
        }
        if (strtolower($loop_cat_parent_pagename) == strtolower($cat_parent_pagename)) {
            //$currentRootOpen = true;
            $cur_root_cat = true;
        } else {
            $cur_root_cat = false;
        }
        if ($row['page_name'] == $this_page_name) {
            $current_category = true;
        } else {
            $current_category = false;
        }
        $cats_list[] = array(
            'page_type' => $page_type,
            'name' => $row['name'],
            'link' => '/' . $shop_name . '/category/' . $row['page_name'],
            'level' => $row['level'],
            'current_cat' => $current_category,
            'cur_root_cat' => $cur_root_cat,
            'doclose' => $doclose,
            'topcat' => $topcat,
            'cat_items' => $category_items
        );
        //  var_dump($cats_list);
        // need tp soomehow close the loop
    }
    // now get the sub cats
    return array($cats_list, $parent_id, $page_name);
}

function subcategories_sidebox($parent_id, $this_page_name) {
    if ($parent_id != '') {
        $sql = "SELECT sc1.parent_id, sc1.name, sc1.page_name, sc1.id, sc1.isRoot, sc2.page_name AS cat_parent_pagename,  sc1.level
FROM shop_category sc1
LEFT JOIN shop_category sc2 ON sc2.id = sc1.parent_id
WHERE sc1.online = 1  AND sc1.parent_id =  $parent_id  AND sc1.level = 2  AND COALESCE(sc2.online,1) = 1 
ORDER BY 
CASE WHEN sc1.isroot = 1 
THEN sc1.order_num 
ELSE sc2.order_num 
END, sc1.isRoot DESC, 
sc1.order_num";
        //echo $sql;
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            if ($row['page_name'] == $this_page_name) {
                // echo 'bingo !!!';
                $current_category = true;
            } else {
                $current_category = false;
            }
            $subcats_list[] = array(
                'page_type' => 'subcategory',
                'name' => $row['name'],
                'link' => '/shop/category/' . $row['page_name'],
                'level' => $row['level'],
                'current_cat' => $current_category,
                'cur_root_cat' => $cur_root_cat,
            );
        }
    }
    // var_dump($subcats_list);
    return $subcats_list;
}

list($cats_list, $parent_id, $page_name) = categories_sidebox();
$subcats_list = subcategories_sidebox($parent_id, $page_name);
//if (count($cats_list) > 1)
    $smarty->assign('cats_list', $cats_list);
$smarty->assign('subcats_list', $subcats_list);
$cats_sidebox_template_file = "$base_path/modules/shop/templates/category_sidebox.tpl";
$filters['cats_list'] = array('search_string' => '/<!-- CS shop_categories list start -->(.*)<!-- CS shop_categories list end -->/s',
    'replace_string' => '{include file="file:' . $cats_sidebox_template_file . '"}');
?>
