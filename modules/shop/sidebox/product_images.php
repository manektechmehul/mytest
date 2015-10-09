<?php
global $name_parts;
$page_name =  $name_parts[2];

if ($page_name != '') {
    $result = mysql_query("SELECT * FROM shop_product WHERE page_name = '{$page_name}'");
	$i = 0;
    $thumbs = array();
    
    $image_dir_prefix = "/UserFiles/Image/";
    while ($row = mysql_fetch_array($result)) {
        
        if($row['thumb_preserve_toggle'] == '1'){
            array_push($thumbs,array($row['thumb_preserve_toggle'], $image_dir_prefix . $row['thumb_preserve_large'], $image_dir_prefix. $row['thumb_preserve_small']));
        }else{
            array_push($thumbs,array($row['thumb_preserve_toggle'], $row['thumb']));
        }
        
        if($row['thumb2_preserve_toggle'] == '1'){
            array_push($thumbs,array($row['thumb2_preserve_toggle'], $image_dir_prefix . $row['thumb2_preserve_large'],$image_dir_prefix . $row['thumb2_preserve_small']));
        }else{
            array_push($thumbs,array($row['thumb2_preserve_toggle'], $row['thumb2']));
        }
        
        if($row['thumb3_preserve_toggle'] == '1'){
            array_push($thumbs,array($row['thumb3_preserve_toggle'], $image_dir_prefix . $row['thumb3preserve_large'], $image_dir_prefix .  $row['thumb3_preserve_small']));
        }else{
            array_push($thumbs,array($row['thumb3_preserve_toggle'], $row['thumb3']));
        }
        
       
        if ($row['thumb4'] != '') {
            array_push($thumbs,array('0', $row['thumb4']));
        }
    }
    $smarty->assign('thumbs', $thumbs);
    $product_images_sidebox_template_file = "$base_path/modules/shop/templates/product_images.tpl";
    $filters['products_images'] = array('search_string' => '/<!-- CS_product_images_start -->(.*)<!-- CS_product_images_end -->/s',
        'replace_string' => '{include file="file:' . $product_images_sidebox_template_file . '"}');
}
?>