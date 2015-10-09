<?php

    function GetStaticPromoLinks() {
        global $smarty;
        
        $sql = 'select * from staticpromo order by order_num';
        $links = db_get_rows($sql);
        return $links;
    }
    
    $staticPromoLinks = GetStaticPromoLinks();
    $smarty->assign('staticPromoLinks', $staticPromoLinks);

    $staticPromoLinksTemplateFile = "$base_path/modules/links/templates/staticpromolinks.tpl";
    
	$filters['staticPromoLinks'] = array('search_string'  => '/<!-- CS static promo links start *-->(.*)<!-- CS static promo links end *-->/s',
       'replace_string' => '{include file="'.$staticPromoLinksTemplateFile.'"}');