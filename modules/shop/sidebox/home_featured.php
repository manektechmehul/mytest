<?
/*
$featured_items = db_get_rows('SELECT sp.* , (
SELECT rgb FROM colour_colour_details ccd WHERE id = spc.`colour_id` LIMIT 1

)AS rgb
FROM shop_product sp
LEFT  JOIN  shop_product_colour spc ON spc.product_id = sp.id
WHERE featured = 1 
GROUP BY id
ORDER BY sp.order_num');
*/

// reworked to get the first selected colour to be the default background
$featured_items = db_get_rows("SELECT sp.* , (
SELECT rgb
FROM shop_product_colour spc1 
INNER JOIN colour_colour_details ccd ON ccd.`id` = spc1.`colour_id`
WHERE spc1.`product_id` = spc2.`product_id`
ORDER BY spc1.order_no LIMIT 1
)AS rgb
FROM shop_product sp
LEFT  JOIN  shop_product_colour spc2 ON spc2.product_id = sp.id
WHERE featured = 1 and published = 1
GROUP BY id
ORDER BY sp.order_num");

$smarty->assign('featured_items', $featured_items);
$home_featured_products_sidebox_template_file = "$base_path/modules/shop/templates/home_featured_products_sidebox.tpl";
$filters['home_featured_products'] = array('search_string' => '/<!-- CS home featured products start -->(.*)<!-- CS home featured products end -->/s',
    'replace_string' => '{include file="file:' . $home_featured_products_sidebox_template_file . '"}');

?>