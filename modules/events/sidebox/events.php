<?php
	// include_once "php/events_events_list_function.php";
	$num_events_displayed  = EVENTS_SIDEBOX_LIST_COUNT;
	$startOfYesterday      = mktime( 0, 0, 0, date( "m" ), date( "d" ) - 1, date( "Y" ) );
	$date                  = date( 'y-m-d', $startOfYesterday );
	$events_sidebox_sql    = "select id, title, summary, body as description, unix_timestamp(startdate) startdate, unix_timestamp(enddate) enddate, thumbnail, page_name from events " .
	                         "where enddate > '$date' and published = 1 order by startdate limit $num_events_displayed";
	$events_sidebox_result = mysql_query( $events_sidebox_sql );
	$sidebox_events_items  = array();

	while ( $events_sidebox_row = mysql_fetch_array( $events_sidebox_result ) ) {
		$sidebox_events_items[] = array(
			'startdate' => $events_sidebox_row['startdate'],
			'enddate' => $events_sidebox_row['enddate'],
			'title' => $events_sidebox_row['title'],
			'description' => $events_sidebox_row['description'],
			'summary' => $events_sidebox_row['summary'],
			'link' => '/events/' . $events_sidebox_row['page_name'],
			'thumbnail' => $events_sidebox_row['thumbnail']
		);
	}
	$smarty->assign( 'sidebox_events', $sidebox_events_items );
	$smarty->assign( 'no_events', events_NO_events_MESSAGE );
	$smarty->assign( 'EVENTS_HAS_THUMBNAIL', EVENTS_HAS_THUMBNAIL );

	$events_sidebox_template_file = "$base_path/modules/events/templates/sidebox.tpl";

	$filters['events'] = array( 'search_string' => '/<!-- CS events start *-->.*<!-- CS events end *-->/s',
		'replace_string' => '{include file="' . $events_sidebox_template_file . '"}' );



?>
