<?php
	include_once $base_path . '/modules/shop/classes/basket.php';

	if ( ! isset( $basket ) ) {
		$basket = new basket( true );
	}
	$smarty->assign( 'basket', $basket );
	if ( isset( $basket->total_qty ) ) {
		$total_qty = $basket->total_qty;
		$pounds    = '50';
		$pence     = '99';
	} else {
		$total_qty = 0;
		$pounds    = '0';
		$pence     = '00';
	}
 
	if ( isset( $_POST['submit_basket_add'] ) ) {
		$show_message = "showFadeInmessage(' Items Added to Basket ');";
		$smarty->assign( 'show_message', $show_message );
	}

	$smarty->assign( 'total_qty', $total_qty );
	$trade = false;
	$smarty->assign( 'trade', $trade );
	$basket_sidebox_template_file = "$base_path/modules/shop/templates/basket_sidebox.tpl";
	$filters['basket_list']       = array( 'search_string' => '/<!-- CS shop_basket list start -->(.*)<!-- CS shop_basket list end -->/s',
		'replace_string' => '{include file="file:' . $basket_sidebox_template_file . '"}' );
?>
