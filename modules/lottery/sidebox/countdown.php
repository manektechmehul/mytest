<?php
	global $base_path;
	$next_draw = date( 'Y-m-d H:i:s', strtotime( LOTTERY_DRAW_DAY . ' ' . LOTTERY_DRAW_TIME ) );

	$next_draw_date = date( 'Y/m/d', strtotime( LOTTERY_DRAW_DAY ));

	$currtime  = date( 'Y-m-d H:i:s' );
	$now       = new DateTime( $next_draw );
	$ref       = new DateTime( $currtime );
	$diff      = $now->diff( $ref );
	$smarty->assign( 'days_to_go', $diff->d );
	$smarty->assign( 'hours_to_go', $diff->h );
	$smarty->assign( 'mins_to_go', $diff->i );

	$smarty->assign( 'next_draw_date', $next_draw_date );


	$lotto_countdown_template   = $base_path . '/modules/lottery/templates/countdown.tpl';
	$filters['lotto_countdown'] = array( 'search_string' => '/<!-- CS lotto countdown start *-->.*<!-- CS lotto countdown end *-->/s',
		'replace_string' => '{include file="' . $lotto_countdown_template . '"}' );


	$lotto_countdown_template2   = $base_path . '/modules/lottery/templates/module_countdown.tpl';
	$filters['lotto_module_countdown'] = array( 'search_string' => '/<!-- CS lotto module countdown start *-->.*<!-- CS lotto module countdown end *-->/s',
		'replace_string' => '{include file="' . $lotto_countdown_template2 . '"}' );