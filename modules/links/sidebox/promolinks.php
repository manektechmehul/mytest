<?php

    function GetPromoLinks() {
        global $smarty;        
        $sql = 'select * from link where published = 1 order by order_num';
        $links = db_get_rows($sql);
        return $links;
    }
    
    $promoLinks = GetPromoLinks();
    $smarty->assign('promoLinks', $promoLinks);

    $promoLinksTemplateFile = "$base_path/modules/links/templates/promolinks.tpl";
    
	$filters['promoLinks'] = array('search_string'  => '/<!-- CS promo links start *-->(.*)<!-- CS promo links end *-->/s',
       'replace_string' => '{include file="'.$promoLinksTemplateFile.'"}');
