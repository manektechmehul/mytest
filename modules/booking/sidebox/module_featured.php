<?php
    // only show featured bits on module hope page
    global $name_parts;
    if(sizeof($name_parts) == 1) {
        $mfeature = db_get_rows( 'SELECT * FROM booking t WHERE published = 1 AND featured= 1 and end_date > NOW() - INTERVAL 1 DAY ORDER BY `order_num`   ' );
        $smarty->assign( 'mfeature', $mfeature );
//  $smarty->assign('docs_module_url', $module_url);
        $template_booking_module_featured = "$base_path/modules/booking/templates/module_featured.tpl";
        $filters[__FILE__]                = array(
            'search_string' => '/<!-- CS module_featured start -->(.*)<!-- CS module_featured end -->/s',
            'replace_string' => '{include file="file:' . $template_booking_module_featured . '"}'
        );
    }
?>