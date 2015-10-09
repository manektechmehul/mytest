<?php
$shop_search_sidebox_template_file = "$base_path/modules/shop/templates/search_sidebox.tpl";
$filters['shop_search'] = array('search_string'  => '/<!-- CS shop_search start -->(.*)<!-- CS shop_search end -->/s',
     'replace_string' => '{include file="file:'.$shop_search_sidebox_template_file.'"}');
?>
